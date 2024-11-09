<?php

include_once "class.conexion.php";
Class Solicitudes extends ConexionClass
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSolicitudes($dep,$fecha_ini, $fecha_fin, $estado)
    {
        $where_area = '';
        $where = '';
        if($dep != 0){
            $where_area=" AND S.ID_AREA = $dep";
        }
        if($estado !=  ''){
            $where = " AND S.ESTADO = '$estado'";
        }
        if($estado == "FIN"){
            $where = " AND S.ESTADO = '$estado'
                       AND S.FECHA_VALIDA_SOLICITANTE IS NOT NULL";
        } 
         $sql = "SELECT S.ID_SCMS,
  A.DESC_AREA DEPARTAMENTO,
  TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA_SOLICITUD,
  TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM') FECHA_APROBACION,
  TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA_RECEPCION,
  TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY HH:MI:SS AM') FECHA_COMPROMISO,
  TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:SS AM') FECHA_CONCLUSION,
  ES.DESCRIPCION as Estado,
  CASE
         WHEN TO_DATE(S.FECHA_VALIDA_SOLICITANTE, 'DD/MM/YYYY') -
              TO_DATE(S.FECHA_CONCLUCION, 'DD/MM/YYYY') < 7 THEN
          'Sí'
         ELSE
          'No'
       END VALIDA_SOLICITANTE,
  NVL(CS.COMENTARIO,'SIN OBSERVACION') AS DESCRIPCION
  

FROM
  SGC_TT_SCMS      S,
  SGC_TP_AREAS     A,
  SGC_TP_TIPO_SCMS TS,
  sgc_tp_estados_scms es,
  SGC_TT_COMENTARIO_SCMS CS
WHERE S.FECHA_SOLICITUD BETWEEN TO_DATE('$fecha_ini', 'YYYY-MM-DD') AND
      TO_DATE('$fecha_fin' || ' 23:59', 'YYYY-MM-DD HH24:MI')
      --AND S.DESARROLLADOR IS NOT NULL
      AND S.ESTADO = ES.CODIGO
      AND S.ID_AREA = A.ID_AREA
      AND S.ID_TIPO_SCMS = TS.ID_CODIGO
      AND S.VALIDA_CALIDAD = 'S'
      AND CS.ID_SCMS(+) = S.ID_SCMS
      ".$where_area."
      ".$where."
      ORDER BY S.FECHA_SOLICITUD DESC";

        $resultado = oci_parse($this->_db, $sql);
        $banderas = oci_execute($resultado);
        if ($banderas == TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";


        }


    }

    public function getUltimaActualizacion()
    {
        $sql = "SELECT * FROM (select TO_CHAR(S.FECHA_SOLICITUD,'DD/MM/YYYY') as ULTIMA_ACTUALIZACION 
                FROM  SGC_TT_SCMS S  ORDER BY S.FECHA_SOLICITUD DESC) WHERE ROWNUM =1";

        $resultado = oci_parse($this->_db, $sql);
        $banderas = oci_execute($resultado);
        if ($banderas == TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";


        }

    }

    public function getTipoSolCat()
    {
        $sql = "SELECT DISTINCT ID_MOTIVO_REC CODIGO, DESC_MOTIVO_REC DESCRIPCION
                 FROM SGC_TP_MOTIVO_RECLAMOS
                 WHERE ID_MOTIVO_REC IN  (64,101) AND GERENCIA = 'E'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas = oci_execute($resultado);
        if ($banderas == TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";


        }

    }

    public function resumenSolicitud($idSolicitud){
        $sql = "SELECT S.ID_SCMS CODIGO_SOLICITUD,
                INITCAP((U1.NOM_USR || ' ' || U1.APE_USR)) SOLICITANTE,
                PS.DESC_PRIORIDAD PRIORIDAD,
                NVL(MO.DESC_MODULO, '-') || '/' || ME.DESC_MENU PANTALLA_MODULO,
                NVL(ES1.DESCRIPCION,ES2.DESCRIPCION) ESTADO,
                TS.DESC_REQUERIMIENTO TIPO_REQUERIMIENTO,
                TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH24:MI PM') FECHA_SOLICITUD,
                S.DESCRIPCION,
                INITCAP((U2.NOM_USR || ' ' || U2.APE_USR)) DESARROLLADOR,
                TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY HH24:MI PM') FECHA_COMPROMISO,
                TO_CHAR(S.FECHA_INICIO, 'DD/MM/YYYY HH24:MI PM') FECHA_INICIO,
                TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH24:MI PM') FECHA_CONCLUSION
        FROM SGC_TT_SCMS S,
                SGC_TT_USUARIOS U1,
                SGC_TP_PRIORIDAD_SCMS PS,
                SGC_TP_TIPO_SCMS TS,
                SGC_TP_MODULOS MO,
                SGC_TP_MENUS ME,
                (SELECT MAX(MS.ID_MOVIMIENTO), MS.ID_SCMS, MS.ESTADO_SOLICITUD
                FROM SGC_TT_MOVIMIENTO_SCMS MS
                WHERE MS.ID_MOVIMIENTO =
                        (SELECT MAX(MS1.ID_MOVIMIENTO)
                        FROM SGC_TT_MOVIMIENTO_SCMS MS1
                        WHERE MS.ID_SCMS = MS1.ID_SCMS)
                GROUP BY MS.ID_SCMS, MS.ESTADO_SOLICITUD) MS,
                SGC_TP_ESTADOS_SCMS ES1,
                SGC_TP_ESTADOS_SCMS ES2,
                SGC_TT_USUARIOS U2
        WHERE S.ID_SCMS = $idSolicitud
            AND U1.ID_USUARIO = S.SOLICITADO
            AND TS.ID_CODIGO(+) = S.ID_TIPO_SCMS
            AND PS.CODIGO_PRIORIDAD(+) = TS.PRIORIDAD_REQUERI
            AND MO.ID_MODULO(+) = S.ID_MODULO
            AND ME.ID_MENU(+) = S.ID_PANTALLA
            AND S.ID_SCMS = MS.ID_SCMS(+)
            AND ES1.CODIGO(+) = MS.ESTADO_SOLICITUD
            AND U2.ID_USUARIO(+) = S.DESARROLLADOR
            AND ES2.CODIGO = S.ESTADO";
        
        $statement = oci_parse($this->_db, $sql);
        oci_execute($statement);
        oci_close($this->_db);

        return $statement;
    }

    public function getHistoricoSolicitud($idSolicitud){
        $sql = "SELECT S.ID_SCMS,
                MST.ID_MOVIMIENTO,
                NVL(NVL(TO_CHAR(MST.FECHA, 'DD/MM/YYYY HH24:MI PM'),
                        TO_CHAR(CS.FECHA_CREACION, 'DD/MM/YYYY HH24:MI PM')),
                    '-') FECHA,
                ES.DESCRIPCION ESTADO_SOLICITUD,
                NVL(MS.DESCRIPCION, '-') DESCRIPCION,
                DECODE(INITCAP((U.NOM_USR || ' ' || U.APE_USR)),
                    ' ',
                    INITCAP((U1.NOM_USR || ' ' || U1.APE_USR))) USUARIO,
                CS.COMENTARIO
        FROM SGC_TP_MOVIMIENTO_SCMS MS,
                SGC_TT_MOVIMIENTO_SCMS MST,
                SGC_TT_USUARIOS        U,
                SGC_TT_COMENTARIO_SCMS CS,
                SGC_TP_ESTADOS_SCMS    ES,
                SGC_TT_SCMS            S,
                SGC_TT_USUARIOS        U1
        WHERE MS.ID_MOVIMIENTO(+) = MST.TIPO_MOVIMIENTO
            AND U.ID_USUARIO(+) = MST.USUARIO
            AND S.ID_SCMS = CS.ID_SCMS(+)
            AND ES.CODIGO = S.ESTADO
            AND S.ID_SCMS = MST.ID_SCMS(+)
            AND S.ID_SCMS = $idSolicitud
            AND U1.ID_USUARIO = CS.USR_CREACION
        ORDER BY MST.ID_SCMS ASC, MST.FECHA ASC";

        $statement = oci_parse($this->_db, $sql);
        oci_execute($statement);
        oci_close($this->_db);

        return $statement;
    }
}