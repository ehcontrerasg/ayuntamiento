<?php
require_once '../../clases/class.conexion.php';

/**
 * Clase para gestionar los reportes.
 */
class Reports extends ConexionClass
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDepartment($user = '')
    {
        /*$sql = "SELECT M.ID_AREA, M.DESC_AREA
                    FROM SGC_TP_AREAS M
                    WHERE M.VISIBLE = 'S'
                    ORDER BY M.ID_AREA";*/

        if($user != ''){
            $sql  = "SELECT M.ID_AREA, M.DESC_AREA
                  FROM SGC_TP_AREAS M,SGC_TT_USUARIOS U,SGC_TP_CARGOS C
                  WHERE M.VISIBLE = 'S'
                  AND   C.ID_CARGO = U.ID_CARGO
                  AND   M.ID_AREA = C.ID_AREA
                  AND   U.ID_USUARIO = '$user'
                  ORDER BY DESC_AREA";
        }else{
            /*$sql  = "SELECT M.ID_AREA, M.DESC_AREA
                  FROM SGC_TP_AREAS M
                  WHERE M.VISIBLE = 'S'
                  ORDER BY M.ID_AREA
                  UNION
                  SELECT '0','DEPARTAMENTO' FROM dual
                  ";   */
            $sql  = "SELECT M.ID_AREA, M.DESC_AREA
                  FROM SGC_TP_AREAS M
                  UNION
                  SELECT 0 ID_AREA,'DEPARTAMENTO' DESC_AREA FROM dual
                   ORDER BY DESC_AREA
                  ";

        }




        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            $data = array();

            while (oci_fetch($result)) {
                $id   = oci_result($result, 'ID_AREA');
                $desc = oci_result($result, 'DESC_AREA');

                $data[$id] = $desc;
            }
            oci_close($this->_db);

            return $data;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDataReport($dep, $fec_ini, $fec_fin,$estado = '')
    {
        $where = "";
        if($estado !=  ''){
            $where = " AND S.ESTADO = '$estado'";
        }
        if($estado == "FIN"){
            $where = " AND S.ESTADO = '$estado'
                       AND S.FECHA_VALIDA_SOLICITANTE IS NOT NULL";
        }    
        $sql = "SELECT S.ID_SCMS,
                       U.NOM_USR || ' ' || U.APE_USR SOLICITADOR,
                       A.DESC_AREA DEPARTAMENTO,
                       TS.DESC_REQUERIMIENTO TIPO,
                       ES.DESCRIPCION ESTADO,
                       DECODE(TS.PRIORIDAD_REQUERI,
                              'A',
                              'Alta',
                              'M',
                              'Media',
                              'B',
                              'Baja',
                              'En espera') PRIORIDAD,
                       TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA,
                       CASE WHEN TO_DATE(S.FECHA_VALIDA_SOLICITANTE, 'DD/MM/YYYY') - 
                                 TO_DATE(S.FECHA_CONCLUCION, 'DD/MM/YYYY') < 7 THEN 
                            'Sí' 
                            ELSE 
                            'No' 
                            END VALIDA_SOLICITANTE
                  FROM SGC_TT_USUARIOS  U,
                       SGC_TT_SCMS      S,
                       SGC_TP_AREAS     A,
                       SGC_TP_TIPO_SCMS TS,
                       sgc_tp_estados_scms es
                 WHERE S.FECHA_SOLICITUD BETWEEN TO_DATE('$fec_ini', 'YYYY-MM-DD') AND
                       TO_DATE('$fec_fin' || ' 23:59', 'YYYY-MM-DD HH24:MI')
                       --AND S.DESARROLLADOR IS NOT NULL
                       AND S.ESTADO = ES.CODIGO"
                       .$where;

        if ($dep != 0) {
            $sql .= " AND S.ID_AREA = $dep";
        }

        $sql .= " AND S.SOLICITADO = U.ID_USUARIO
                   AND S.ID_AREA = A.ID_AREA
                   AND S.ID_TIPO_SCMS = TS.ID_CODIGO
                   AND S.VALIDA_CALIDAD = 'S'
                 ORDER BY S.FECHA_SOLICITUD DESC";

        //ECHO $sql;
        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDataReportPdf($id){

        $sql = "SELECT S.ID_SCMS ID,
                 TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:HH AM') FECHA_SOLICITUD,
                 TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY HH:MI:HH AM') FECHA_COMPROMISO,
                 TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:HH AM') FECHA_CONCLUCION,
                 TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:HH AM') FECHA_CALIDAD,
                 (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                  FROM SGC_TT_USUARIOS U
                  WHERE U.ID_USUARIO = S.USR_CALIDAD) USR_CALIDAD,
                 S.VALIDA_SOLICITANTE,
                 INITCAP(LOWER(M.DESC_MENU)) MODULO,
                 INITCAP(LOWER(A.DESC_AREA)) DEPARTAMENTO,
                 PS.ID_CODIGO PRIORIDAD,
                 TS.ID_CODIGO REQUERIMIENTO,
                 S.DESCRIPCION,
                 (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                  FROM SGC_TT_USUARIOS U
                  WHERE U.ID_USUARIO = S.SOLICITADO) SOLICITADOR,
                 (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                  FROM SGC_TT_USUARIOS U
                  WHERE U.ID_USUARIO = S.DESARROLLADOR) DESARROLLADOR,
                 (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                  FROM SGC_TT_USUARIOS U
                  WHERE U.ID_USUARIO = S.USR_REVISION) USR_REVISION,
                 (SELECT CE.COMENTARIO  FROM (SELECT U.LOGIN, C.COMENTARIO,CM.ID_SCMS/*, C.FECHA_CREACION*/
                                              FROM SGC_TT_USUARIOS U, SGC_TT_COMENT_MODIF CM,SGC_TT_COMENTARIO_SCMS C
                                              WHERE C.ID_COMENTARIO = CM.ID_COMENTARIO
                                                AND   U.ID_USUARIO    = C.USR_CREACION
                                                AND   U.ID_CARGO IN (111, 112)
                                                AND   U.FEC_FIN IS NULL
                                              ORDER BY C.FECHA_CREACION DESC            ) CE
                  WHERE ROWNUM = 1
                    AND   CE.ID_SCMS = S.ID_SCMS
                 ) COMENTARIO_ENCARGADO_TI,
                 CASE VALIDA_SOLICITANTE WHEN 'S' THEN
                     (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                      FROM SGC_TT_USUARIOS U
                      WHERE U.ID_USUARIO = S.SOLICITADO)
                     WHEN  'N' THEN
                          NULL
                     END USR_APRUEBA
          FROM SGC_TT_SCMS           S,
               SGC_TP_MENUS          M,
               SGC_TP_AREAS          A,
               SGC_TP_PRIORIDAD_SCMS PS,
               SGC_TP_TIPO_SCMS      TS
          WHERE S.ID_MODULO = M.ID_MENU
            AND S.ID_AREA = A.ID_AREA
            AND S.ID_TIPO_SCMS = TS.ID_CODIGO
            AND TS.PRIORIDAD_REQUERI = PS.CODIGO_PRIORIDAD
                  AND S.ID_SCMS = $id";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return oci_fetch_array($result);
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function compEdition($id, $fecha)
    {
        $sql = "SELECT VERFECSOLIC($id, '$fecha') STATUS FROM DUAL";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return oci_fetch_array($result)['STATUS'];
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDataReportDetPdf($dep, $fecha_ini, $fecha_fin)
    {
        $sql = "SELECT count(s.id_scms) totales,
                       count(decode(s.estado, 'FIN', 'SI')) terminadas,
                       count(case
                               when s.fecha_compromiso > s.fecha_conclucion then
                                1
                             end) a_tiempo
                  FROM SGC_TT_SCMS S
                 WHERE S.FECHA_COMPROMISO BETWEEN TO_DATE('$fecha_ini', 'YYYY-MM-DD') AND
                       TO_DATE('$fecha_fin' || ' 23:59', 'YYYY-MM-DD HH24:MI')
                   AND S.ESTADO NOT IN ('ANU', 'REC','PAU')
                   AND S.VALIDA_CALIDAD = 'S'";

        if ($dep != 0) {
            $sql .= " AND S.ID_AREA = $dep";
        }

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return oci_fetch_array($result);
        } else {
            oci_close($this->_db);
            return false;
        }
    }
    function getFormDates($nombre_formulario){

         $sql = "SELECT F.CODIGO,F.DESCRIPCION,TO_CHAR(FE.FECHA_EMISION, 'DD/MM/YYYY') FECHA_EMISION,FE.EDICION,FE.IMAGEN
                FROM SGC_TP_FORMULARIO F,SGC_TP_FORMULARIO_EDICION FE
                WHERE F.CODIGO = FE.CODIGO_FORMULARIO
                AND   F.CODIGO = '$nombre_formulario'
                ORDER BY FE.FECHA_EMISION";

        $resultado = oci_parse($this->_db,$sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

   /* public function getEstadosScms(){

        $sql = "SELECT ESCMS.CODIGO,ESCMS.DESCRIPCION 
                FROM SGC_TP_ESTADOS_SCMS ESCMS";

        $result   = oci_parse($this->_db, $sql);
        $bandera  = oci_execute($result);

        if($bandera){
            // oci_close($this->_db);
            return $result;
        }else{
            //oci_close($this->_db);
            return false;
        }


    }*/
}
