<?php
include_once "class.conexion.php";

class Inmueble extends ConexionClass
{
    private $mesrror;
    private $coderror;
    private $ordenesGeneradas = 0;

    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getOrdenesGeneradas(){
        return $this->ordenesGeneradas;
    }

    public function getDatInmConsGen($where, $sort, $start, $end, $from)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT TAR.CATEGORIA, SI.CUPO_BASICO, SI.COD_SERVICIO, I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_TIPO_CLIENTE, I.ID_ESTADO, I.CATASTRO, I.ID_PROCESO, C.ID_CONTRATO, C.CODIGO_CLI,
                        C.ALIAS, L.NOMBRE_CLI, L.DOCUMENTO, L.TELEFONO, M.SERIAL, M.COD_CALIBRE, M.COD_EMPLAZAMIENTO, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA_INSTALACION,
                        M.METODO_SUMINISTRO, A.ID_USO, I.ID_PROYECTO, TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA, D.DESC_CALIBRE,
                        i.ID_TIPO_CLIENTE TIPO_CLIENTE
                        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_ESTADOS_INMUEBLES E,
                        SGC_TP_GRUPOS G, SGC_TP_PROYECTOS P, SGC_TP_TIPO_CLIENTE T, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES D,
                        SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS TAR  $from
                        WHERE U.CONSEC_URB(+) = I.CONSEC_URB
                        AND C.CODIGO_INM(+) = I.CODIGO_INM
                        AND SI.COD_INMUEBLE=I.CODIGO_INM
                        AND SI.COD_SERVICIO IN (1,3)
                        AND TAR.CONSEC_TARIFA    = SI.CONSEC_TARIFA
                        AND L.CODIGO_CLI(+) = C.CODIGO_CLI
                        AND M.COD_INMUEBLE(+) = I.CODIGO_INM
                        AND E.ID_ESTADO(+) = I.ID_ESTADO
                        AND P.ID_PROYECTO(+) = I.ID_PROYECTO
                        and C.FECHA_FIN (+) IS NULL
                        AND T.ID_TIPO_CLIENTE(+) = I.ID_TIPO_CLIENTE
                        AND G.COD_GRUPO(+) = L.COD_GRUPO
                        AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
                        AND M.FECHA_BAJA(+) IS NULL
                        AND D.COD_CALIBRE(+) = M.COD_CALIBRE
                        $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
//        echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }
    public function getDatInmDatByProy($proyecto)
    {
         $sql = "
            SELECT I.CODIGO_INM,
           CL.NOMBRE_CLI,
           I.ID_SECTOR,
           I.ID_RUTA,
           CON.ALIAS,
           I.TELEFONO ||'  '||CL.TELEFONO TELEFONO,
           CL.DOCUMENTO,
           I.DIRECCION,
           CON.EMAIL||'  '||CL.EMAIL EMAIL,  
           CON.ID_CONTRATO,
           COUNT(1) FACTURAS,
           SUM(TOTAL_ORI-TOTAL_PAGADO+TOTAL_DEBITO-TOTAL_CREDITO) DEUDA,
           CASE WHEN RA.TIP_DOCUMENTOS IS NULL
                     THEN
               'N'
               ELSE
                'S'
           END
           CONTRATO_FISICO FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA FAC,
                              SGC_TT_CONTRATOS CON, SGC_TT_CLIENTES CL, SGC_TT_REGISTRO RA
WHERE FAC.INMUEBLE=I.CODIGO_INM AND
      FAC.FECHA_PAGO IS NULL AND
      FAC.FEC_EXPEDICION IS NOT NULL AND
      FAC.TOTAL_ORI>0 AND
      I.ESTADO_CREDITO=1 AND
      I.ID_PROYECTO='$proyecto' AND
      RA.CODIGO_INM(+)=INMUEBLE AND
      RA.TIP_DOCUMENTOS(+)=4 AND
      CON.CODIGO_INM=I.CODIGO_INM AND
      CON.FECHA_FIN IS NULL AND
      CL.CODIGO_CLI=CON.CODIGO_CLI AND
      CL.DOCUMENTO_VALIDO='S'
GROUP BY RA.TIP_DOCUMENTOS, CL.NOMBRE_CLI,I.ID_SECTOR, I.ID_RUTA,  CON.ALIAS, I.CODIGO_INM, I.TELEFONO, CON.EMAIL,CL.EMAIL,CL.TELEFONO,CL.DOCUMENTO,I.DIRECCION,CON.ID_CONTRATO      ";
//        echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function cantidadDatInmConsGen($where, $from)
    {
        $sql = "SELECT COUNT(*) CANTIDAD
            FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_ESTADOS_INMUEBLES E,
                        SGC_TP_GRUPOS G, SGC_TP_PROYECTOS P, SGC_TP_TIPO_CLIENTE T, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES D,
                        SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS TAR  $from
                        WHERE U.CONSEC_URB(+) = I.CONSEC_URB
                        AND C.CODIGO_INM(+) = I.CODIGO_INM
                        AND SI.COD_INMUEBLE=I.CODIGO_INM
                        AND SI.COD_SERVICIO IN (1,3)
                        AND TAR.CONSEC_TARIFA    = SI.CONSEC_TARIFA
                        AND L.CODIGO_CLI(+) = C.CODIGO_CLI
                        AND M.COD_INMUEBLE(+) = I.CODIGO_INM
                        AND E.ID_ESTADO(+) = I.ID_ESTADO
                        AND P.ID_PROYECTO(+) = I.ID_PROYECTO
                        and C.FECHA_FIN (+) IS NULL
                        AND T.ID_TIPO_CLIENTE(+) = I.ID_TIPO_CLIENTE
                        AND G.COD_GRUPO(+) = L.COD_GRUPO
                        AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
                        AND M.FECHA_BAJA(+) IS NULL
                        AND D.COD_CALIBRE(+) = M.COD_CALIBRE
                        $where
            $where";
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getFotTotByInm($inmueble)
    {
        $sql = "
                SELECT FC.ID_INMUEBLE,FC.NOMBRE_FOTO,FC.URL_FOTO,'CORTE' AS AREA,ID_PERIODO  FROM SGC_TT_FOTOS_CORTE FC
                WHERE FC.ID_INMUEBLE=$inmueble
                UNION ALL
                SELECT
                FF.ID_INMUEBLE,FF.NOMBRE_FOTO,FF.URL_FOTO,'FACTURA' AS AREA  ,ID_PERIODO
                FROM
                SGC_TT_FOTOS_FACTURA FF
                WHERE
                FF.ID_INMUEBLE=$inmueble
                UNION ALL
                SELECT
                FI.ID_INMUEBLE,FI.NOMBRE_FOTO,FI.URL_FOTO,'INSPECCION' AS AREA ,ID_PERIODO
                FROM
                SGC_TT_FOTOS_INSPECCION FI
                WHERE
                FI.ID_INMUEBLE=$inmueble
                UNION ALL
                SELECT
                FL.ID_INMUEBLE,FL.NOMBRE_FOTO,FL.URL_FOTO,'LECTURA' AS AREA,ID_PERIODO
                FROM
                SGC_TT_FOTOS_LECTURA FL
                WHERE
                FL.ID_INMUEBLE=$inmueble
                UNION ALL
                SELECT
                FM.ID_INMUEBLE,FM.NOMBRE_FOTO,FM.URL_FOTO,'MANTENIMIENTO' AS AREA,ID_PERIODO
                FROM
                SGC_TT_FOTOS_MANTENIMIENTO FM
                WHERE
                FM.ID_INMUEBLE=$inmueble
                UNION ALL
                SELECT
                FR.ID_INMUEBLE,FR.NOMBRE_FOTO,FR.URL_FOTO,'RECONEXION' AS AREA,ID_PERIODO
                FROM
                SGC_TT_FOTOS_RECONEXION FR
                WHERE
                FR.ID_INMUEBLE=$inmueble
                ORDER BY 3 DESC ,4 DESC
            ";
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getDatInmConByCod($codSist){
        $codSist = addslashes($codSist);
        $sql     = "SELECT I.ID_PROYECTO, I.ID_SECTOR, I.ID_RUTA, I.ID_PROCESO, I.CATASTRO, I.DIRECCION, U.DESC_URBANIZACION, A.ID_USO,
       DECODE(SI.COD_SERVICIO, 1, 'RED', 3, 'POZO') TIPO, SI.UNIDADES_HAB, I.ID_ESTADO, SUBSTR (ID_PROCESO,10,2) DIGITO_PROCESO,
       (SELECT COUNT(*) FROM SGC_TT_CONTRATOS C WHERE C.CODIGO_INM = I.CODIGO_INM AND FECHA_FIN IS NULL) CANT_CONTRATO,
        I.ID_PROYECTO||'-'||(SQ_CONTRATOS.NEXTVAL)||'-'||CONCAT(I.ID_SECTOR, I.ID_RUTA) SECUENCIA,
       (SELECT COUNT(*) FROM SGC_TT_MEDIDOR_INMUEBLE MI WHERE MI.COD_INMUEBLE = I.CODIGO_INM AND MI.FECHA_BAJA IS NULL)CANT_MEDIDOR
        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_SERVICIOS_INMUEBLES SI
        WHERE U.CONSEC_URB = I.CONSEC_URB AND
        A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD AND
        SI.COD_INMUEBLE = I.CODIGO_INM AND
        I.CODIGO_INM = '$codSist' AND
        SI.COD_SERVICIO IN (1,3)";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDatTarConByCod($codSist,$urbaniza,$medidor, $acueducto){
        $codSist = addslashes($codSist);
        if($acueducto == 'SD') {
            if ($urbaniza == 'RESD. DUMAS VII' || $urbaniza == 'RESD MATISSE' || $urbaniza == 'REYOLI' || $urbaniza == 'ISLAS CANARIAS' ||
                $urbaniza == 'S GABRIELA' || $urbaniza == 'V BELLA' || $urbaniza == 'V CARMELA' || $urbaniza == 'BAMBU III' ||
                $urbaniza == 'PASEO DEL LLANO' || $urbaniza == 'METROPOLI DEL NORTE' || $urbaniza == 'J DEL ARROYO' || $urbaniza == 'VILLA LIBERACION') {
                $where = " AND VC.URBANIZACION = '$urbaniza'";
            }
        }
        if($acueducto == 'BC'){
            if($medidor == 1){
                $where = " AND VC.MEDIDO = 'S'";
            }
            else{
                $where = " AND VC.MEDIDO = 'N'";
            }
        }
        //echo $where;
        $sql    = "SELECT (VC.DERECHO * SI.UNIDADES_HAB) DI, (VC.FIANZA * SI.UNIDADES_HAB) FIANZA, T.CODIGO_TARIFA TARIFA, VC.CUPO_BASICO CUPO,
       (VC.DERECHO + VC.FIANZA) * SI.UNIDADES_HAB TOTAL, CASE WHEN SI.COD_SERVICIO = 1 THEN ROUND(((VC.CUPO_BASICO * SI.UNIDADES_HAB)* RT.VALOR_METRO))
        ELSE ((VC.CUPO_BASICO * SI.UNIDADES_HAB)* 1) END AS TARIFA_AGUA, T.CONSEC_TARIFA
        FROM SGC_TP_VALOR_CONTRATOS VC, SGC_TP_TARIFAS T, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_RANGOS_TARIFAS RT
        WHERE I.CODIGO_INM = SI.COD_INMUEBLE AND
        T.CONSEC_TARIFA = RT.CONSEC_TARIFA AND
        RT.RANGO = 0 AND
        RT.PERIODO = (SELECT MAX(RT1.PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT1 WHERE RT.CONSEC_TARIFA = RT1.CONSEC_TARIFA) AND
        I.ID_SECTOR = VC.ID_SECTOR AND
        I.ID_RUTA = VC.ID_RUTA AND
        T.CONSEC_TARIFA = VC.TARIFA AND
        I.CODIGO_INM = '$codSist' AND
        SI.COD_SERVICIO IN (1,3) $where";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }


    public function getInfoInmInsByCod($codSist)
    {
        $codSist = addslashes($codSist);
        $sql     = "select
                  I.ID_PROYECTO PROYECTO_COD,
                  P.SIGLA_PROYECTO PROYECTO_DESC,
                  USU.NOM_USR||' '||USU.APE_USR USU_INSMED,
                  INS.FECH_GEN FECH_GENINSMED,
                  MOTR.DESC_MOTIVO_REC MOTRECL,
                  INS.ORDEN ODEN_INSMED,
                  INS.DESCRIPCION DESCINS,
                  CLI.CODIGO_CLI CODIGO_CLI,
                  NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE_CLI,
                  I.DIRECCION DIRECCION,
                  I.ID_ZONA ZONA,
                  NVL(MI.COD_MEDIDOR,'N\A') MEDIDOR_COD,
                  NVL(ME.DESC_MED,'N\A') MEDIDOR_DESC,
                  CAL.DESC_CALIBRE CALIBRE_DESC,
                  EMP.DESC_EMPLAZAMIENTO EMPLAZAMIENTO_DESC,
                  MI.SERIAL,
                  INS.ID_PQR PQR,
                  URB.DESC_URBANIZACION
              from
                  sgc_tt_inmuebles I,
                  SGC_TP_PROYECTOS P,
                  SGC_TT_INSPECCIONES_MED INS,
                  SGC_TT_USUARIOS USU,
                  SGC_TP_MOTIVO_RECLAMOS MOTR,
                  SGC_TT_CONTRATOS CON,
                  SGC_TT_CLIENTES CLI,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_MEDIDORES ME,
                  SGC_TP_CALIBRES CAL,
                  SGC_TP_EMPLAZAMIENTO EMP,
                  SGC_TP_URBANIZACIONES URB
              WHERE
                  P.ID_PROYECTO=I.ID_PROYECTO AND
                  MOTR.ID_MOTIVO_REC=INS.MOTIVO AND
                  INS.GERENCIA=MOTR.GERENCIA AND
                  INS.INMUEBLE=I.CODIGO_INM AND
                  INS.ANULADO='N' AND
                  INS.FECHA_EJE IS NULL AND
                  USU.ID_USUARIO=INS.USUARIO_GEN AND
                  CON.CODIGO_INM(+)=I.CODIGO_INM AND
                  CON.FECHA_FIN(+) IS NULL AND
                  CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                  MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                  ME.CODIGO_MED(+)=MI.COD_MEDIDOR AND
                  CAL.COD_CALIBRE(+)=MI.COD_CALIBRE AND
                  EMP.COD_EMPLAZAMIENTO(+)=MI.COD_EMPLAZAMIENTO AND
                  URB.CONSEC_URB=I.CONSEC_URB AND
                  I.CODIGO_INM='$codSist'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }



    public function getServInmByInm($codinm){
        $sql = "SELECT C.DESC_SERVICIO, U.DESC_USO, T.CODIGO_TARIFA, S.UNIDADES_TOT, T.CONSEC_TARIFA, u.OPERA_CORTE
		FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U
		WHERE C.COD_SERVICIO  = S.COD_SERVICIO 
		AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
		AND T.COD_USO = U.ID_USO
		AND S.COD_INMUEBLE = '$codinm'
		AND S.ACTIVO = 'S'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function GetDatInmByCod($cod)
    {
        $cod = addslashes($cod);
        $sql = "SELECT
                CSER.DESCRIPCION ESTADO_SERVICIO,
                SI1.COD_SERVICIO SERVAGUA,
                SI2.COD_SERVICIO SERVALCANT,
                si1.CUPO_BASICO,
                I.ID_ZONA,
                U.DESC_URBANIZACION,
                I.DIRECCION,
                TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA,
                I.ID_ESTADO,
                I.ID_PROYECTO,
                I.CATASTRO,
                I.ID_PROCESO,
                EC.DESCRIPCION ESTADO_CREDITO,
                C.CODIGO_CLI,
                C.ID_CONTRATO,
                C.ALIAS,
                L.DOCUMENTO,
                I.TELEFONO,
                M.SERIAL,
                NVL(L.EMAIL,C.EMAIL) EMAIL,
                l.EMAIL EMAIL2,
                A.ID_USO USO,
                A.DESC_ACTIVIDAD ACTIVIDAD,
                L.COD_GRUPO,
                SI1.UNIDADES_HAB,
                SI1.UNIDADES_TOT,
                TAR1.DESC_TARIFA,
                TAR2.DESC_TARIFA TAR_ALC,
                NVL(C.ALIAS,l.NOMBRE_CLI) NOMBRE,
                L.TELEFONO TEL2,
                (SELECT
                    COUNT(1)
                 FROM
                    SGC_TT_FACTURA F
                 WHERE
                    F.INMUEBLE=I.CODIGO_INM  AND
                    F.FECHA_PAGO IS NULL AND
                    F.FACTURA_PAGADA='N' AND
                    F.FEC_EXPEDICION IS NOT NULL
                ) FACTURAS,
                NVL((SELECT
                    SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO )
                 FROM
                    SGC_TT_FACTURA F
                 WHERE
                    F.INMUEBLE=I.CODIGO_INM  AND
                    F.FECHA_PAGO IS NULL AND
                    F.FACTURA_PAGADA='N' AND
                    F.FEC_EXPEDICION IS NOT NULL
                ),0) DEUDA
            FROM
                SGC_TT_INMUEBLES I,
                SGC_TP_URBANIZACIONES U,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES L,
                SGC_TT_MEDIDOR_INMUEBLE M,
                SGC_TP_ACTIVIDADES A,
                SGC_TT_SERVICIOS_INMUEBLES SI1,
                SGC_TP_TARIFAS TAR1,
                SGC_TT_SERVICIOS_INMUEBLES SI2,
                SGC_TP_TARIFAS TAR2,
                SGC_TP_CONDICION_SERV CSER,
                SGC_TP_ESTADO_CREDITO EC 
                 
            WHERE
                EC.ID=I.ESTADO_CREDITO AND 
                CSER.ID=I.CONDICION_SERV AND  
                A.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                SI1.COD_INMUEBLE(+)=I.CODIGO_INM AND 
                SI1.COD_SERVICIO (+) IN (1,3) AND     
                SI2.COD_INMUEBLE(+)=I.CODIGO_INM AND 
                SI2.COD_SERVICIO (+) IN (2,4) AND  
                U.CONSEC_URB(+) = I.CONSEC_URB AND
                M.COD_INMUEBLE(+) = I.CODIGO_INM AND
                C.CODIGO_INM(+) = I.CODIGO_INM AND
                L.CODIGO_CLI(+) = C.CODIGO_CLI AND
                TAR1.CONSEC_TARIFA(+)=SI1.CONSEC_TARIFA AND
                TAR2.CONSEC_TARIFA(+)=SI2.CONSEC_TARIFA AND
                C.FECHA_FIN(+) IS NULL AND
                I.CODIGO_INM = '$cod'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function GetDatInmByCodAseo($cod)
    {
        $cod = addslashes($cod);
        $sql = "SELECT
                CSER.DESCRIPCION ESTADO_SERVICIO,
                SI1.COD_SERVICIO SERVAGUA,
                SI2.COD_SERVICIO SERVALCANT,
                si1.CUPO_BASICO,
                I.ID_ZONA,
                U.DESC_URBANIZACION,
                I.DIRECCION,
                TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA,
                I.ID_ESTADO,
                I.ID_PROYECTO,
                I.CATASTRO,
                I.ID_PROCESO,
                EC.DESCRIPCION ESTADO_CREDITO,
                C.CODIGO_CLI,
                C.ID_CONTRATO,
                C.ALIAS,
                L.DOCUMENTO,
                I.TELEFONO,
                M.SERIAL,
                NVL(L.EMAIL,C.EMAIL) EMAIL,
                l.EMAIL EMAIL2,
                A.ID_USO USO,
                A.DESC_ACTIVIDAD ACTIVIDAD,
                L.COD_GRUPO,
                SI1.UNIDADES_HAB,
                SI1.UNIDADES_TOT,
                TAR1.DESC_TARIFA,
                TAR2.DESC_TARIFA TAR_ALC,
                NVL(C.ALIAS,l.NOMBRE_CLI) NOMBRE,
                L.TELEFONO TEL2,
                (SELECT
                    COUNT(1)
                 FROM
                    SGC_TT_FACTURA_ASEO F
                 WHERE
                    F.INMUEBLE=I.CODIGO_INM  AND
                    F.FECHA_PAGO IS NULL AND
                    F.FACTURA_PAGADA='N' AND
                    F.FEC_EXPEDICION IS NOT NULL
                ) FACTURAS,
                NVL((SELECT
                    SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO )
                 FROM
                    SGC_TT_FACTURA_ASEO F
                 WHERE
                    F.INMUEBLE=I.CODIGO_INM  AND
                    F.FECHA_PAGO IS NULL AND
                    F.FACTURA_PAGADA='N' AND
                    F.FEC_EXPEDICION IS NOT NULL
                ),0) DEUDA
            FROM
                SGC_TT_INMUEBLES I,
                SGC_TP_URBANIZACIONES U,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES L,
                SGC_TT_MEDIDOR_INMUEBLE M,
                SGC_TP_ACTIVIDADES A,
                SGC_TT_SERVICIOS_INMUEBLES SI1,
                SGC_TP_TARIFAS TAR1,
                SGC_TT_SERVICIOS_INMUEBLES SI2,
                SGC_TP_TARIFAS TAR2,
                SGC_TP_CONDICION_SERV CSER,
                SGC_TP_ESTADO_CREDITO EC 
                 
            WHERE
                EC.ID=I.ESTADO_CREDITO AND 
                CSER.ID=I.CONDICION_SERV AND  
                A.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                SI1.COD_INMUEBLE(+)=I.CODIGO_INM AND 
                SI1.COD_SERVICIO (+) IN (1,3) AND     
                SI2.COD_INMUEBLE(+)=I.CODIGO_INM AND 
                SI2.COD_SERVICIO (+) IN (2,4) AND  
                U.CONSEC_URB(+) = I.CONSEC_URB AND
                M.COD_INMUEBLE(+) = I.CODIGO_INM AND
                C.CODIGO_INM(+) = I.CODIGO_INM AND
                L.CODIGO_CLI(+) = C.CODIGO_CLI AND
                TAR1.CONSEC_TARIFA(+)=SI1.CONSEC_TARIFA AND
                TAR2.CONSEC_TARIFA(+)=SI2.CONSEC_TARIFA AND
                C.FECHA_FIN(+) IS NULL AND
                I.CODIGO_INM = '$cod'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function GetDatInmMantByCodPro($cod,$per)
    {
        $cod = addslashes($cod);
        $per = addslashes($per);
        $sql = "
            SELECT M.DIRECCION,M.ID_USO,M.ID_ACTIVIDAD,
              M.UNIDADES_H UNIDADES_HAB,M.UNIDADES UNIDADES_TOT,
              M.ID_ESTADO,
              M.TELEFONO_CLI,
              M.OBSERVACIONES,
              M.CONDICION_SERV  
              FROM SGC_TT_MANTENIMIENTO M
              WHERE M.ID_INMUEBLE='$cod' and 
              M.ID_PERIODO=$per";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getDiferidosByInm($inm)
    {
        $inm = addslashes($inm);
        $sql = "
            SELECT
              NVL(SUM(D.VALOR_DIFERIDO-D.VALOR_PAGADO),0) DIFERIDO
            FROM
              SGC_TT_DIFERIDOS D
            WHERE
              D.INMUEBLE= $inm AND
              D.ACTIVO='S'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getLecByInmFlexy($where, $sort, $start, $end, $codinmueble)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT R.PERIODO, CASE R.LECTURA_ACTUAL WHEN -1 THEN NULL ELSE R.LECTURA_ACTUAL END LECTURA_ACTUAL, TO_CHAR(R.FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC, R.OBSERVACION_ACTUAL, (U.NOM_USR||' '||U.APE_USR)LECTOR, R.CONSUMO_ACT CONSUMO
                        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TT_USUARIOS U
                        WHERE R.COD_LECTOR = U.ID_USUARIO AND R.COD_INMUEBLE = '$codinmueble' $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getSaldoFavorByInm($inm)
    {

        $inm = addslashes($inm);
        $sql = "SELECT
                NVL(SUM(SALDO),0) SALDO
              FROM(
                SELECT
                  SUM(IMPORTE-VALOR_APLICADO)SALDO
                FROM
                  SGC_TT_SALDO_FAVOR
                WHERE
                  INM_CODIGO=$inm
                AND ESTADO = 'A'
              UNION ALL
                SELECT
                  SUM(ORE.IMPORTE -ORE.APLICADO)SALDO
                FROM
                  SGC_TT_OTROS_RECAUDOS ORE
                WHERE
                  ORE.INMUEBLE=$inm
                AND ESTADO = 'A')";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getInfoInmInsTot($usr)
    {
        $usr = addslashes($usr);
        $sql = "SELECT
                INM.CODIGO_INM,
                IM.DESCRIPCION,
                IM.FECH_GEN,
                MR.DESC_MOTIVO_REC
              FROM
                SGC_TT_INSPECCIONES_MED IM,
                SGC_TT_INMUEBLES INM,
                SGC_TP_MOTIVO_RECLAMOS MR,
                SGC_TT_PERMISOS_USUARIO PU
              WHERE
                IM.INMUEBLE=INM.CODIGO_INM AND
                IM.FECHA_EJE IS NULl AND
                IM.ANULADO='N' AND
                IM.MOTIVO=MR.ID_MOTIVO_REC AND
                IM.GERENCIA=MR.GERENCIA AND
                PU.ID_PROYECTO=INM.ID_PROYECTO AND
                PU.ID_USUARIO='$usr'
                order BY 3
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function setInsMedInm($usr, $orden, $resGnral, $obsGnral, $lect, $pqr, $area)
    {
        $usr      = addslashes($usr);
        $orden    = addslashes($orden);
        $resGnral = addslashes($resGnral);
        $obsGnral = addslashes($obsGnral);
        $lect     = addslashes($lect);
        $pqr      = addslashes($pqr);
        $area     = addslashes($area);

        $sql       = "BEGIN SGC_P_INGRESA_RES_INS($orden,'$usr','', '$resGnral','$obsGnral',$lect,'$pqr','$area',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

 public function actMantInm($inmueble, $direccion, $uso, $actividad, $unidadesh, $unidadest, $estado,$tarAgua,$telefono,
                                $obs, $tarifaAlc,$periodo,$cupo,$usuario,$estSer)
    {
        $inmueble   = addslashes($inmueble);
        $direccion  = addslashes($direccion);
        $uso        = addslashes($uso);
        $actividad  = addslashes($actividad);
        $unidadesh  = addslashes($unidadesh);
        $unidadest  = addslashes($unidadest);
        $estado     = addslashes($estado);
        $tarAgua    = addslashes($tarAgua);
        $telefono   = addslashes($telefono);
        $obs        = addslashes($obs);
        $tarifaAlc  = addslashes($tarifaAlc);
        $periodo    = addslashes($periodo);
        $cupo       = addslashes($cupo);
        $estSer       = addslashes($estSer);

         $sql       = "BEGIN SGC_P_ACT_MAN_CAT($inmueble,'$direccion','$uso','$actividad','$unidadesh','$unidadest','$estado','$tarAgua','$telefono','$obs','$tarifaAlc','$periodo','$cupo','$usuario','$estSer',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function aperMantCorrMedInm($proyecto, $usr, $zona, $desc, $proceso_inicial, $proceso_final, $codigo_inmueble, $manzana_inicial, $manzana_final, $usuario_asignado)
    {
        $usr       = addslashes($usr);
        $zona      = addslashes($zona);
        $desc      = addslashes($desc);
        $sql       = "BEGIN SGC_P_ABRE_MANTCORR('$proyecto','$usr','$zona','$desc','$proceso_inicial','$proceso_final','$codigo_inmueble','$manzana_inicial','$manzana_final','$usuario_asignado',:ORDENES_GENERADAS_OUT,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 1000);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        oci_bind_by_name($resultado, ":ORDENES_GENERADAS_OUT", $this->ordenesGeneradas,6);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            print_r(oci_error($resultado));
            return false;
        }
    }

    public function getMantMedCorrByInm($codSist)
    {
        $codSist = addslashes($codSist);
        $sql     = "SELECT
                INM.ID_PROYECTO ,
                PR.SIGLA_PROYECTO,
                (US.NOM_USR||' '||US.APE_USR) NOMBRE_OPER ,
                MC.DESCRIPCION,
                MC.ID_ORDEN,
                MC.FECHA_GENORDEN,
                CON.CODIGO_CLI,
                NVL(CON.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                INM.DIRECCION,
                INM.ID_ZONA,
                MI.COD_MEDIDOR,
                MED.DESC_MED,
                CAL.DESC_CALIBRE,
                EMP.DESC_EMPLAZAMIENTO,
                MI.SERIAL
              FROM
                SGC_TT_INMUEBLES INM,
                SGC_TP_PROYECTOS PR,
                SGC_TT_USUARIOS US ,
                SGC_TT_MANT_CORRMED MC ,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TP_MEDIDORES MED,
                SGC_TP_CALIBRES CAL,
                SGC_TP_EMPLAZAMIENTO EMP
              WHERE
                INM.ID_PROYECTO=PR.ID_PROYECTO AND
                MC.CODIGO_INM=INM.CODIGO_INM AND
                US.ID_USUARIO=MC.USUARIO_ASIGNADO AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                MC.FECHA_REEALIZACION IS NULL AND
                MC.ESTADO='A' AND
                MI.COD_INMUEBLE=INM.CODIGO_INM AND
                MI.FECHA_BAJA IS NULL AND
                MED.CODIGO_MED=MI.COD_MEDIDOR AND
                CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                EMP.COD_EMPLAZAMIENTO=MI.COD_EMPLAZAMIENTO AND
                INM.CODIGO_INM='$codSist'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getInfoInmMedByInm($codSist)
    {
        $codSist = addslashes($codSist);
        $sql     = "SELECT
                I.ID_PROYECTO,
                P.SIGLA_PROYECTO,
                CLI.CODIGO_CLI,
                NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                I.DIRECCION,
                I.ID_ZONA,
                MI.COD_MEDIDOR,
                MED.DESC_MED,
                CAL.DESC_CALIBRE,
                EMP.DESC_EMPLAZAMIENTO,
                MI.SERIAL
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TP_MEDIDORES MED,
                SGC_TP_CALIBRES CAL,
                SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_PROYECTOS P
              WHERE
                MI.COD_INMUEBLE=I.CODIGO_INM AND
                CON.CODIGO_INM(+)=I.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                MED.CODIGO_MED=MI.COD_MEDIDOR AND
                CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                MI.FECHA_BAJA IS NULL AND
                EMP.COD_EMPLAZAMIENTO=MI.COD_EMPLAZAMIENTO AND
                P.ID_PROYECTO=I.ID_PROYECTO AND
                I.CODIGO_INM='$codSist'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getInmPdcDeudaByProceProy($procesoIni, $proyecto, $procesoFin)
    {
        $procesoIni = addslashes($procesoIni);
        $procesoFin = addslashes($procesoFin);
        $proyecto   = addslashes($proyecto);
        $sql        = "SELECT
                INM.CODIGO_INM,
                SUBSTR(INM.ID_PROCESO,1,2) SECTOR,
                SUBSTR(INM.ID_PROCESO,3,2) RUTA,
                INM.ID_PROCESO,
                DC.ACTIVA DEUDACERO,
                INM.CATASTRO,
                NVL(ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                INM.DIRECCION||' '||URB.DESC_URBANIZACION DIRECCION,
                INM.ID_ESTADO,
                AC.ID_USO,
                MI.SERIAL,
                (SELECT
                    COUNT(1)
                FROM
                    SGC_TT_FACTURA F
                WHERE F.INMUEBLE=INM.CODIGO_INM AND
                    F.FEC_EXPEDICION IS NOT NULL AND
                    F.FECHA_PAGO IS NULL AND
                    F.FACTURA_PAGADA='N'
                ) FACTURAS,

                (SELECT
                    SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO )
                FROM
                    SGC_TT_FACTURA F
                WHERE
                    F.INMUEBLE=INM.CODIGO_INM AND
                    F.FEC_EXPEDICION IS NOT NULL AND
                    F.FECHA_PAGO IS NULL AND
                    F.FACTURA_PAGADA='N'
                ) DEUDA
            FROM
                SGC_TT_INMUEBLES INM,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TP_URBANIZACIONES URB,
                SGC_TP_ACTIVIDADES AC,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TP_ESTADOS_INMUEBLES EI,
                SGC_TT_DEUDA_CERO DC
            WHERE
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                URB.CONSEC_URB(+)=INM.CONSEC_URB AND
                AC.SEC_ACTIVIDAD(+)=INM.SEC_ACTIVIDAD AND
                MI.COD_INMUEBLE(+)=INM.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS  NULL AND
                EI.ID_ESTADO=INM.ID_ESTADO AND
                INM.ID_PROCESO>='$procesoIni' AND
                INM.ID_PROYECTO='$proyecto' AND
                INM.ID_PROCESO<='$procesoFin' AND
                INM.CODIGO_INM=DC.COD_INMUEBLE(+)
            ORDER BY
                INM.ID_PROCESO";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getInmCortByProSecZonOper($proy, $sec, $zon, $oper, $inm)
    {
        $sql = "SELECT R.ORDEN, to_char(SYSDATE,'DD/MM/YYYY') FECHACT, U.ID_USUARIO, CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) OPERARIO, TO_CHAR(R.FECHA_ACUERDO,'DD/MM/YYYY') FECACUERDO,I.CODIGO_INM,I.DIRECCION,URB.DESC_URBANIZACION,
        NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRE,S.DESC_SERVICIO, NVL(CLI.TELEFONO,I.TELEFONO) TELEFONO,I.ID_PROCESO, I.CATASTRO, R.TIPO_CORTE, ME.DESC_MED, CA.DESC_CALIBRE, MI.SERIAL,DESC_USO,
        (SELECT DC.DESC_CALIBRE FROM SGC_TP_CALIBRES DC WHERE DC.COD_CALIBRE=I.COD_DIAMETRO) DIAMETRO
        FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI, SGC_TP_ACTIVIDADES AC,
        SGC_TP_USOS USO, SGC_TP_URBANIZACIONES URB, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_MEDIDORES ME,SGC_TP_CALIBRES CA,
        sgc_tt_servicios_inmuebles si, SGC_TP_SERVICIOS S
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND C.CODIGO_INM=I.CODIGO_INM
              AND CLI.CODIGO_CLI=C.CODIGO_CLI
              AND SI.COD_INMUEBLE=I.CODIGO_INM
              AND SI.COD_SERVICIO IN (1,3)
              AND SI.COD_SERVICIO=S.COD_SERVICIO
              AND AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD
              AND MI.COD_INMUEBLE(+)=I.CODIGO_INM
              AND MI.FECHA_BAJA (+) IS NULL
              AND ME.CODIGO_MED(+)=MI.COD_MEDIDOR
              AND CA.COD_CALIBRE(+)=MI.COD_CALIBRE
              AND USO.ID_USO=AC.ID_USO
              AND URB.CONSEC_URB=I.CONSEC_URB
              AND C.FECHA_FIN (+) IS NULL
              AND R.USR_EJE=U.ID_USUARIO
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
            and R.PERVENC='N'
            AND R.FECHA_ACUERDO IS NULL

              AND R.REVERSADO='N'
              and R.FECHA_PLANIFICACION is not null
              ";

        if (trim($proy)) {
            $sql = $sql . " AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($sec) != "") {
            $sql = $sql . " AND I.ID_SECTOR=$sec ";
        }
        if (trim($zon) != "") {
            $sql = $sql . " AND I.ID_zona='$zon' ";
        }

        if (trim($oper) != "") {
            $sql = $sql . " AND R.USR_EJE='$oper' ";
        }

        if (trim($inm) != "") {
            $sql = $sql . " AND I.CODIGO_INM='$inm' ";
        }

        $sql = $sql . " ORDER BY R.USR_EJE, ID_PROCESO ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getCantInmueblesACorByProOperRuta($proy, $oper, $ruta)
    {
        $proy = addslashes($proy);
        $oper = addslashes($oper);
        $ruta = addslashes($ruta);
        $sql  = "SELECT
                COUNT(1) NUMERO
              FROM
                SGC_TT_REGISTRO_CORTES R,
                SGC_TT_INMUEBLES I
              WHERE
                I.CODIGO_INM=R.ID_INMUEBLE AND
                R.FECHA_EJE IS NULL AND
                R.REVERSADO='N'and
                R.PERVENC='N' AND
                R.FECHA_ACUERDO IS NULL AND
                I.ID_PROYECTO='$proy' AND
                SUBSTR(I.ID_PROCESO,0,4)='$ruta' AND
                R.USR_EJE='$oper'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getSerByInmFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT S.COD_SERVICIO, O.DESC_SERVICIO, T.DESC_TARIFA, S.UNIDADES_TOT, S.UNIDADES_HAB, S.UNIDADES_DESH, S.CUPO_BASICO, S.PROMEDIO, S.CONSUMO_MINIMO,
                        C.DESC_CALCULO, CAL.DESC_CALIBRE DIAMETRO
                        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS O, SGC_TP_TARIFAS T, SGC_TP_CALCULO C, SGC_TP_CALIBRES CAL , SGC_TT_INMUEBLES I
                        WHERE S.COD_SERVICIO = O.COD_SERVICIO
                        AND T.CONSEC_TARIFA(+) = S.CONSEC_TARIFA
                        AND I.CODIGO_INM=S.COD_INMUEBLE 
                        AND I.COD_DIAMETRO=CAL.COD_CALIBRE
                        AND S.CALCULO = C.COD_CALCULO
                        AND O.VISIBLE = 'S'
                        $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getFacByInmFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                SELECT F.CONSEC_FACTURA, F.TOTAL, F.PERIODO, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY HH24:MI') FEC_EXPEDICION,
                concat(NU.ID_NCF,F.NCF_CONSEC) ncf,F.TOTAL_PAGADO,
                (SELECT TO_CHAR(RL.FECHA_LECTURA_ORI,'DD/MM/YYYY HH24:MI') FROM SGC_TT_REGISTRO_LECTURAS RL WHERE F.PERIODO = RL.PERIODO AND F.INMUEBLE = RL.COD_INMUEBLE)FECHA_LECTURA,
                (SELECT SUM (DF1.UNIDADES) FROM SGC_TT_DETALLE_FACTURA DF1 WHERE DF1.CONCEPTO (+) IN (1,3) AND DF1.FACTURA=DF.FACTURA) LECTURA
                , TRUNC(NVL(F.FECHA_PAGO,SYSDATE)-F.FEC_EXPEDICION) DIAS ,TO_CHAR(F.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO
                 FROM

                SGC_TT_FACTURA F,sgc_tp_ncf_usos nu, SGC_TT_DETALLE_FACTURA DF WHERE
                   F.NCF_ID=NU.ID_NCF_USO(+)

                   AND DF.FACTURA(+)=F.CONSEC_FACTURA
                   AND DF.RANGO(+)=0
                   AND DF.CONCEPTO (+) IN (1,3)
                   $where
                   $sort
                   )where  rownum<1000
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getDifByInmFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                         select codigo, S.DESC_SERVICIO, dif.valor_diferido,numero_cuotas,round(valor_cuota)valor_cuota,cuotas_pagadas,valor_pagado,(dif.valor_diferido-valor_pagado) pendiente,
                         DIF.FECHA_APERTURA,DIF.ACTIVO,DIF.PER_INI
                          from sgc_tt_diferidos dif, sgc_tp_servicios s
                          where DIF.CONCEPTO = S.COD_SERVICIO
                          --AND  dif.activo='S'
                          $where
                          $sort
                         )
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getDeudCerByInmFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                          select DC.TOTAL_DIFERIDO ,DC.TOTAL_CUOTAS,DC.TOTAL_CUOTAS_PAG,ROUND(DC.TOTAL_PAGADO,2) TOTAL_PAGADO, ROUND((DC.TOTAL_DIFERIDO-DC.TOTAL_PAGADO),2) PENDIENTE
                          from sgc_tt_deuda_cero dc
                          where
                          DC.ACTIVA IN ('S','N')
                          $where
                          $sort
                         )
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getMaestroInmMedByProy($proy,$fecini,$fecfin)
    {
        $proy = addslashes($proy);

        $sql = "SELECT I.CODIGO_INM CODIGO,
       I.ID_SECTOR SECTOR,
       I.ID_RUTA RUTA,
       I.CATASTRO ID_INMUEBLE,
       I.ID_PROCESO,
       NVL(C.ALIAS, CLI.NOMBRE_CLI) NOMBRE_CLIENTE,
       I.DIRECCION,
       URB.DESC_URBANIZACION URBANIZACION,
       I.ID_ESTADO ESTADO,
       MI.COD_MEDIDOR,
       ACT.ID_USO USO,
       I.ID_ZONA,
       (select sim.cupo_basico
          from sgc_tp_tarifas tr, sgc_tt_servicios_inmuebles sim
         where sim.cod_inmueble = I.CODIGO_INM
           and sim.consec_tarifa = tr.consec_tarifa
           and sim.cod_servicio in (1, 3)) Unidades,
        EM.DESC_EMPLAZAMIENTO,
       NVL(TO_CHAR(MI.FECHA_INSTALACION, 'DD/MM/YYYY'), 'N/A') FECHA_INSTALACION,
       NVL(MI.SERIAL, 'N/A') SERIAL,
       (SELECT RL.LECTURA_ACTUAL
          FROM SGC_TT_REGISTRO_LECTURAS RL
         WHERE RL.COD_INMUEBLE = I.CODIGO_INM
           AND RL.PERIODO =
               (SELECT MAX(RL2.PERIODO)
                  FROM SGC_TT_REGISTRO_LECTURAS RL2
                 WHERE RL2.COD_INMUEBLE = I.CODIGO_INM
                   AND RL2.FECHA_LECTURA IS NOT NULL)) LECTURA,
       CAL.DESC_CALIBRE CALIBRE,
       (SELECT RL.OBSERVACION_ACTUAL
          FROM SGC_TT_REGISTRO_LECTURAS RL
         WHERE RL.COD_INMUEBLE = I.CODIGO_INM
           AND RL.PERIODO =
               (SELECT MAX(RL2.PERIODO)
                  FROM SGC_TT_REGISTRO_LECTURAS RL2
                 WHERE RL2.COD_INMUEBLE = I.CODIGO_INM
                   AND RL2.FECHA_LECTURA IS NOT NULL)) OBSLECTURA,
       (SELECT COUNT(1)
          FROM SGC_TT_FACTURA F
         WHERE F.INMUEBLE = I.CODIGO_INM
           AND F.FECHA_PAGO IS NULL
           AND F.FEC_EXPEDICION IS NOT NULL
           AND F.FACTURA_PAGADA = 'N') FACT_PEND,
       (SELECT OL.DESCRIPCION
          FROM SGC_TT_REGISTRO_LECTURAS RL, SGC_TP_OBSERVACIONES_LECT OL
         WHERE RL.COD_INMUEBLE = I.CODIGO_INM
           AND RL.PERIODO =
               (SELECT MAX(RL2.PERIODO)
                  FROM SGC_TT_REGISTRO_LECTURAS RL2
                 WHERE RL2.COD_INMUEBLE = I.CODIGO_INM
                   AND RL2.FECHA_LECTURA IS NOT NULL)
           AND OL.CODIGO = RL.OBSERVACION_ACTUAL
           AND OL.MEDIDOR_DAGNADO = 'S') DAGNO_MED
  FROM SGC_TT_INMUEBLES        I,
       SGC_TT_CONTRATOS        C,
       SGC_TT_CLIENTES         CLI,
       SGC_TP_URBANIZACIONES   URB,
       SGC_TP_ACTIVIDADES      ACT,
       SGC_TT_MEDIDOR_INMUEBLE MI,
       SGC_TP_CALIBRES         CAL,
       SGC_TP_EMPLAZAMIENTO    EM
 WHERE I.CODIGO_INM = C.CODIGO_INM(+)
   AND I.ID_PROYECTO = '$proy'
   AND CLI.CODIGO_CLI(+) = C.CODIGO_CLI
   AND C.FECHA_FIN(+) IS NULL
   AND URB.CONSEC_URB(+) = I.CONSEC_URB
   AND ACT.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
   AND MI.COD_INMUEBLE = I.CODIGO_INM
   AND MI.FECHA_BAJA IS NULL
   AND CAL.COD_CALIBRE = MI.COD_CALIBRE
   AND EM.COD_EMPLAZAMIENTO = MI.COD_EMPLAZAMIENTO
   AND MI.FECHA_INSTALACION >= TO_DATE('$fecini 00:00','YYYY-MM-DD HH24:MI')
   AND MI.FECHA_INSTALACION <= TO_DATE('$fecfin 23:59','YYYY-MM-DD HH24:MI')
 ORDER BY I.ID_PROCESO";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getMaestroInmByProy($proy)
    {
        $proy = addslashes($proy);

        $sql = "SELECT /*+ NO_CPU_COSTING */
                              I.CODIGO_INM CODIGO,
                            I.ID_SECTOR SECTOR,
                            I.ID_RUTA RUTA,
                            I.CATASTRO ID_INMUEBLE,
                            I.ID_PROCESO,
                            NVL (C.ALIAS, CLI.NOMBRE_CLI) NOMBRE_CLIENTE,
                            I.DIRECCION,
                            URB.DESC_URBANIZACION URBANIZACION,
                            I.ID_ESTADO ESTADO,
                            'N/A'COD_MEDIDOR,
                            ACT.ID_USO USO,
                            'N/A'DESC_EMPLAZAMIENTO,
                            'N/A' FECHA_INSTALACION,
                            'N/A'SERIAL,
                            'N/A' LECTURA,
                            'N/A' CALIBRE,
                            'N/A' OBSLECTURA,
                            I.ID_ZONA
                            FROM SGC_TT_INMUEBLES I,
                            SGC_TT_CONTRATOS C,
                            SGC_TT_CLIENTES CLI,
                            SGC_TP_URBANIZACIONES URB,
                            SGC_TP_ACTIVIDADES ACT
                            WHERE     I.CODIGO_INM = C.CODIGO_INM(+)
                              AND I.ID_PROYECTO = '$proy'
                              AND  ROWNUM between 1 and 150000  
                              AND CLI.CODIGO_CLI(+) = C.CODIGO_CLI
                              AND C.FECHA_FIN(+) IS NULL
                              AND URB.CONSEC_URB(+) = I.CONSEC_URB
                              AND ACT.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
                              AND I.CODIGO_INM NOT  IN (
                              SELECT MI.COD_INMUEBLE FROM SGC_TT_MEDIDOR_INMUEBLE MI
                              WHERE MI.COD_INMUEBLE=I.CODIGO_INM
                              AND MI.FECHA_BAJA IS NOT NULL
                            )
                            ORDER BY I.ID_PROCESO";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getInmCorConLecByProcProy($proy, $procIni, $procFin,$contratista)
    {
        $proy    = addslashes($proy);
        $procIni = addslashes($procIni);
        $procFin = addslashes($procFin);
$where="";
        if($contratista!='')
            $where." AND (U.CONTRATISTA= $contratista OR U2.CONTRATISTA=$contratista ) ";

        $sql = "SELECT /*+ ORDERED */
                   I.CODIGO_INM,
                   I.ID_PROCESO,
                   RC.LECTURA,
                   RC.FECHA_EJE,
                   U.LOGIN,
                   NVL(U2.LOGIN,'N/A') LOGININSPECCION,
                   RC.FECHA_EJE_ACT FECHA_INSP,
                   RL.LECTURA_ORIGINAL LECTURA_PERIODO_ANTERIOR,
                   RL.PERIODO PERIODO_ANTERIOR,
                   RL.OBSERVACION OBS_PERIODO_ANTERIOR,
                   RL2.LECTURA_ORIGINAL LECTURA_PERIODO_ACTUAL,
                   RL2.PERIODO PERIODO_ACTUAL,
                   RL2.OBSERVACION OBS_PERIODO_ACTUAL,
                   RL2.LECTURA_ORIGINAL - RL.LECTURA_ORIGINAL DIFERENCIA,
                   MI.SERIAL,
                   I.CATASTRO,
                   MI.FECHA_INSTALACION FECHA_INS,
                   I.ID_SECTOR||I.ID_RUTA RUTA,
                   RL.OBSERVACION IMP_LECT
              FROM SGC_TT_MEDIDOR_INMUEBLE MI,
                   SGC_TT_INMUEBLES I,
                   SGC_TT_REGISTRO_CORTES RC,
                   SGC_TT_REGISTRO_LECTURAS RL2,
                   SGC_TT_REGISTRO_LECTURAS RL,
                   SGC_TT_USUARIOS U,
                   SGC_TT_USUARIOS U2
             WHERE I.ID_ESTADO = 'SS'
               AND RL.COD_INMUEBLE = I.CODIGO_INM + 0
               AND RL2.COD_INMUEBLE = I.CODIGO_INM + 0
               AND RL.PERIODO = ANY (SELECT MAX(TO_CHAR(ADD_MONTHS(TO_DATE(P1.PERIODO, 'YYYYMM'), -1), 'YYYYMM'))
                                       FROM SGC_TP_PERIODO_ZONA P1
                                      WHERE P1.ID_ZONA = I.ID_ZONA
                                        AND P1.FEC_CIERRE IS NOT NULL)
               AND RL2.PERIODO = (SELECT MAX(P2.PERIODO)
                                    FROM SGC_TP_PERIODO_ZONA P2
                                   WHERE P2.ID_ZONA = I.ID_ZONA
                                     AND P2.FEC_CIERRE IS NOT NULL)
               AND U.ID_USUARIO = RC.USR_EJE || ''
               AND U2.ID_USUARIO(+) = RC.USR_ULT_CORTE || ''
               AND RL.FECHA_LECTURA_ORI > RC.FECHA_EJE
               AND I.CODIGO_INM = MI.COD_INMUEBLE + 0
               AND MI.FECHA_BAJA IS NULL
               AND RC.ID_INMUEBLE = I.CODIGO_INM + 0
               AND RC.ORDEN = (SELECT MAX(RC2.ORDEN)
                                 FROM SGC_TT_REGISTRO_CORTES RC2
                                WHERE RC2.ID_INMUEBLE = I.CODIGO_INM)
               AND RL.LECTURA_ORIGINAL <> RL2.LECTURA_ORIGINAL
               AND I.ID_PROCESO || '' >= $procIni
               AND I.ID_PROCESO || '' <= $procFin
               AND I.ID_PROYECTO='$proy'
               $where
             ORDER BY 2 asc";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getEstadosInmuebles($proyecto,$gerencia,$indicador_estado,$id_uso,$periodo){

        $sql="SELECT count(I1.CODIGO_INM) CANTIDAD
              FROM SGC_TT_INMUEBLES I1, SGC_TP_ESTADOS_INMUEBLES EI1,
              SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S1, SGC_TT_HISTORICO_ESTADO HE
              WHERE I1.ID_SECTOR = S1.ID_SECTOR AND
                    I1.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND
                    I1.CODIGO_INM = HE.COD_INMUEBLE AND
                    A.ID_USO = '$id_uso' AND
                    i1.ID_ESTADO = ei1.ID_ESTADO and
                    I1.ID_PROYECTO = '$proyecto' AND
                    S1.ID_GERENCIA = '$gerencia' AND
                    EI1.INDICADOR_ESTADO = '$indicador_estado' AND 
                    HE.PERIODO = $periodo";

       // echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getInmuByContrato($contrato){
        $sql="SELECT  C.CODIGO_INM 
            FROM SGC_TT_CONTRATOS C
           WHERE C.ID_CONTRATO='$contrato'
            AND C.FECHA_FIN IS NULL 
        ";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }



    public function getUnidadesPorUso($proyecto,$indicador_estado,$id_uso,$gerencia,$periodo){
        $sql="SELECT NVL(SUM(SI.UNIDADES_TOT),0) UNIDADES
              from SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S,
                  SGC_TP_ESTADOS_INMUEBLES EI, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_HISTORICO_ESTADO HE
              WHERE I.ID_SECTOR=S.ID_SECTOR
                AND I.SEC_ACTIVIDAD=A.SEC_ACTIVIDAD
                AND I.ID_ESTADO =  EI.ID_ESTADO
                AND I.CODIGO_INM= SI. COD_INMUEBLE
                AND I.CODIGO_INM = HE.COD_INMUEBLE
                AND A.ID_USO='$id_uso'
                AND I.ID_PROYECTO= '$proyecto'
                AND S.ID_GERENCIA = '$gerencia'
                AND EI.INDICADOR_ESTADO='$indicador_estado'
                AND HE.PERIODO = $periodo";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }


    public function getProyectoByInmueble($codigo_inmueble){
        $sql = "SELECT P.ID_PROYECTO, P.SIGLA_PROYECTO
                FROM ACEASOFT.SGC_TT_INMUEBLES INM, ACEASOFT.SGC_TP_PROYECTOS P  
                WHERE P.ID_PROYECTO = INM.ID_PROYECTO 
                AND INM.CODIGO_INM = '$codigo_inmueble'";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }


    public function getDatInmAmnByCod($codSist){
        $codSist = addslashes($codSist);
        $sql     = "SELECT I.ID_PROYECTO, I.DIRECCION, U.DESC_URBANIZACION, A.ID_USO, A.SEC_ACTIVIDAD, T.CATEGORIA, SI.CONSEC_TARIFA, I.ID_ESTADO, C.ALIAS, CL.DOCUMENTO, CL.TIPO_DOC, CL.TELEFONO, CL.EMAIL, S.ID_GERENCIA,
       --(SELECT SUM (F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO)
        --FROM SGC_TT_FACTURA F WHERE F.INMUEBLE = I.CODIGO_INM AND F.FACTURA_PAGADA = 'N' AND F.FEC_EXPEDICION IS NOT NULL)DEUDA,
       (SELECT SUM (F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO)
        FROM SGC_TT_FACTURA F WHERE F.INMUEBLE = I.CODIGO_INM AND F.FACTURA_PAGADA = 'N' AND F.FEC_EXPEDICION IS NOT NULL
        AND F.PERIODO <= 202111)DEUDA_A_NOV,
       (SELECT SUM (F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO)
        FROM SGC_TT_FACTURA F WHERE F.INMUEBLE = I.CODIGO_INM AND F.FACTURA_PAGADA = 'N' AND F.FEC_EXPEDICION IS NOT NULL
        AND F.PERIODO >= 202112)DEUDA_RESTA,
       NVL((SELECT SUM (DF.VALOR - DF.VALOR_PAGADO - DF.TOTAL_CREDITO + DF.TOTAL_DEBITO)
            FROM  SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF WHERE F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = DF.FACTURA
            AND F.FACTURA_PAGADA = 'N' AND DF.CONCEPTO IN (21,52,91,95,421,452) AND F.PERIODO <= 202111),0)PENALIDAD_A_NOV,
       NVL((SELECT SUM (DF.VALOR - DF.VALOR_PAGADO - DF.TOTAL_CREDITO + DF.TOTAL_DEBITO)
                FROM  SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF WHERE F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = DF.FACTURA
                AND F.FACTURA_PAGADA = 'N' AND DF.CONCEPTO IN (21,52,91,95,421,452) AND F.PERIODO >= 202112),0)PENALIDAD_RESTA
       --NVL((SELECT SUM (DF.VALOR - DF.VALOR_PAGADO - DF.TOTAL_CREDITO + DF.TOTAL_DEBITO)
        --FROM  SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF WHERE F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = DF.FACTURA AND F.FACTURA_PAGADA = 'N' AND DF.CONCEPTO IN (21,52,91,95,421,452)),0)PENALIDAD
FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_SECTORES S
WHERE U.CONSEC_URB = I.CONSEC_URB AND
        A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD AND
        SI.COD_INMUEBLE = I.CODIGO_INM AND
        C.CODIGO_INM = I.CODIGO_INM AND
        C.CODIGO_CLI = CL.CODIGO_CLI AND
        I.ID_SECTOR = S.ID_SECTOR AND
        I.CODIGO_INM = '$codSist' AND
        SI.CONSEC_TARIFA = T.CONSEC_TARIFA AND
        SI.COD_SERVICIO IN (1,3) AND
        C.FECHA_FIN IS NULL";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }


    public function getDatInmAcuByCod($codSist){
        $codSist = addslashes($codSist);
        $sql = "SELECT I.ID_PROYECTO, I.DIRECCION, U.DESC_URBANIZACION, A.ID_USO, T.CATEGORIA, I.ID_ESTADO, C.ALIAS, CL.DOCUMENTO, CL.TIPO_DOC, CL.TELEFONO, CL.EMAIL, I.ID_SECTOR, S.ID_GERENCIA,
       (SELECT SUM(DF2.VALOR-DF2.VALOR_PAGADO+DF2.TOTAL_DEBITO-DF2.TOTAL_CREDITO)
            FROM SGC_TT_DETALLE_FACTURA DF2, SGC_TT_FACTURA F, SGC_TP_SERVICIOS S
            WHERE F.CONSEC_FACTURA = DF2.FACTURA AND
            S.COD_SERVICIO = DF2.CONCEPTO AND
            DF2.COD_INMUEBLE = '$codSist' AND
            F.FEC_EXPEDICION IS NOT NULL AND
            F.FECHA_PAGO IS NULL AND
            F.FACTURA_PAGADA = 'N')DEUDA,
       (SELECT SUM (DF.VALOR - DF.VALOR_PAGADO - DF.TOTAL_CREDITO + DF.TOTAL_DEBITO)
        FROM  SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF WHERE F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = DF.FACTURA AND F.FACTURA_PAGADA = 'N' AND DF.CONCEPTO = 10)MORA
FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_SECTORES S
WHERE U.CONSEC_URB = I.CONSEC_URB AND
        A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD AND
        SI.COD_INMUEBLE = I.CODIGO_INM AND
        C.CODIGO_INM = I.CODIGO_INM AND
        C.CODIGO_CLI = CL.CODIGO_CLI AND
        I.ID_SECTOR = S.ID_SECTOR AND
        I.CODIGO_INM = '$codSist' AND
        SI.CONSEC_TARIFA = T.CONSEC_TARIFA AND
        SI.COD_SERVICIO IN (1,3) AND
        C.FECHA_FIN IS NULL";
        /*$sql     = "SELECT I.ID_PROYECTO, I.DIRECCION, U.DESC_URBANIZACION, A.ID_USO, T.CATEGORIA, I.ID_ESTADO, C.ALIAS, CL.DOCUMENTO, CL.TIPO_DOC, CL.TELEFONO, CL.EMAIL, I.ID_SECTOR, S.ID_GERENCIA,
       (SELECT SUM (F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO)
        FROM SGC_TT_FACTURA F WHERE F.INMUEBLE = I.CODIGO_INM AND F.FACTURA_PAGADA = 'N' AND F.FEC_EXPEDICION IS NOT NULL)DEUDA,
       (SELECT SUM (DF.VALOR - DF.VALOR_PAGADO - DF.TOTAL_CREDITO + DF.TOTAL_DEBITO)
        FROM  SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF WHERE F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = DF.FACTURA AND F.FACTURA_PAGADA = 'N' AND DF.CONCEPTO = 10)MORA
FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_SECTORES S
WHERE U.CONSEC_URB = I.CONSEC_URB AND
        A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD AND
        SI.COD_INMUEBLE = I.CODIGO_INM AND
        C.CODIGO_INM = I.CODIGO_INM AND
        C.CODIGO_CLI = CL.CODIGO_CLI AND
        I.ID_SECTOR = S.ID_SECTOR AND
        I.CODIGO_INM = '$codSist' AND
        SI.CONSEC_TARIFA = T.CONSEC_TARIFA AND
        SI.COD_SERVICIO IN (1,3) AND
        C.FECHA_FIN IS NULL";*/

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDatAseoInmAcuByCod($codSist){
        $codSist = addslashes($codSist);
        $sql = "SELECT I.ID_PROYECTO, I.DIRECCION, U.DESC_URBANIZACION, A.ID_USO, T.CATEGORIA, I.ID_ESTADO, C.ALIAS, CL.DOCUMENTO, CL.TIPO_DOC, CL.TELEFONO, CL.EMAIL, I.ID_SECTOR, S.ID_GERENCIA,
       (SELECT SUM(DF2.VALOR-DF2.VALOR_PAGADO+DF2.TOTAL_DEBITO-DF2.TOTAL_CREDITO)
            FROM SGC_TT_DETALLE_FACTURA_ASEO DF2, SGC_TT_FACTURA_ASEO F, SGC_TP_SERVICIOS S
            WHERE F.CONSEC_FACTURA = DF2.FACTURA AND
            S.COD_SERVICIO = DF2.CONCEPTO AND
            DF2.COD_INMUEBLE = '$codSist' AND
            F.FEC_EXPEDICION IS NOT NULL AND
            F.FECHA_PAGO IS NULL AND
            F.FACTURA_PAGADA = 'N')DEUDA,
       NVL((SELECT SUM (DF.VALOR - DF.VALOR_PAGADO - DF.TOTAL_CREDITO + DF.TOTAL_DEBITO)
        FROM  SGC_TT_FACTURA_ASEO F, SGC_TT_DETALLE_FACTURA_ASEO DF WHERE F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = DF.FACTURA AND F.FACTURA_PAGADA = 'N' AND DF.CONCEPTO = 6),0)MORA
FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_SECTORES S
WHERE U.CONSEC_URB = I.CONSEC_URB AND
        A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD AND
        SI.COD_INMUEBLE = I.CODIGO_INM AND
        C.CODIGO_INM = I.CODIGO_INM AND
        C.CODIGO_CLI = CL.CODIGO_CLI AND
        I.ID_SECTOR = S.ID_SECTOR AND
        I.CODIGO_INM = '$codSist' AND
        SI.CONSEC_TARIFA = T.CONSEC_TARIFA AND
        SI.COD_SERVICIO IN (5) AND
        C.FECHA_FIN IS NULL";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }


    public function getDatAcuConByCod($cuotas,$categoria,$acueducto){
        //$codSist = addslashes($codSist);
        $sql    = "SELECT RA.PORCENTAJE_INICIAL, RA.PORCEN_MORA_EXONERA
                    FROM SGC_TP_RANGOS_ACUERDOS RA
                    WHERE RA.TIPO_CLIENTE = '$categoria' AND
                          RA.CUOTA_MIN <= $cuotas AND
                          RA.CUOTA_MAX >= $cuotas AND
                          RA.ID_PROYECTO = '$acueducto'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDatAmnConByCod($cuotas,$categoria,$acueducto,$tarifa,$actividad){
        //$codSist = addslashes($codSist);
        if ($tarifa == 43 || $actividad == 18){
            $sql    = "SELECT RA.PORCENTAJE_INICIAL
                    FROM SGC_TP_RANGOS_AMNISTIA RA
                    WHERE RA.TIPO_CLIENTE = 'Materia Prima Agua' AND
                            RA.CUOTA_MIN <= $cuotas AND
                        RA.CUOTA_MAX >= $cuotas AND
                        RA.ID_PROYECTO = '$acueducto'";
        }
        else{
            $sql    = "SELECT RA.PORCENTAJE_INICIAL
                    FROM SGC_TP_RANGOS_AMNISTIA RA
                    WHERE RA.TIPO_CLIENTE = '$categoria' AND
                            RA.CUOTA_MIN <= $cuotas AND
                        RA.CUOTA_MAX >= $cuotas AND
                        RA.ID_PROYECTO = '$acueducto'";
        }

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getSerByInmFlexyAseo($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT S.COD_SERVICIO, O.DESC_SERVICIO, T.DESC_TARIFA, S.UNIDADES_TOT, S.UNIDADES_HAB, S.UNIDADES_DESH, S.CUPO_BASICO, S.PROMEDIO, S.CONSUMO_MINIMO,
                        C.DESC_CALCULO, CAL.DESC_CALIBRE DIAMETRO
                        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS O, SGC_TP_TARIFAS T, SGC_TP_CALCULO C, SGC_TP_CALIBRES CAL , SGC_TT_INMUEBLES I
                        WHERE S.COD_SERVICIO = O.COD_SERVICIO
                        AND T.CONSEC_TARIFA(+) = S.CONSEC_TARIFA
                        AND I.CODIGO_INM=S.COD_INMUEBLE 
                        AND I.COD_DIAMETRO=CAL.COD_CALIBRE
                        AND S.CALCULO = C.COD_CALCULO
                        $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function getFacByInmFlexyAseo($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                SELECT F.CONSEC_FACTURA, F.TOTAL, F.PERIODO, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY HH24:MI') FEC_EXPEDICION,
                concat(NU.ID_NCF,F.NCF_CONSEC) ncf,F.TOTAL_PAGADO,
                (SELECT TO_CHAR(RL.FECHA_LECTURA_ORI,'DD/MM/YYYY HH24:MI') FROM SGC_TT_REGISTRO_LECTURAS RL WHERE F.PERIODO = RL.PERIODO AND F.INMUEBLE = RL.COD_INMUEBLE)FECHA_LECTURA,
                (SELECT SUM (DF1.UNIDADES) FROM SGC_TT_DETALLE_FACTURA DF1 WHERE DF1.CONCEPTO (+) IN (1,3) AND DF1.FACTURA=DF.FACTURA) LECTURA
                , TRUNC(NVL(F.FECHA_PAGO,SYSDATE)-F.FEC_EXPEDICION) DIAS ,TO_CHAR(F.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO
                 FROM
                SGC_TT_FACTURA_ASEO F,SGC_TP_NCF_USOS_ASEO NU, SGC_TT_DETALLE_FACTURA_ASEO DF WHERE
                   F.NCF_ID=NU.ID_NCF_USO(+)

                   AND DF.FACTURA(+)=F.CONSEC_FACTURA
                   AND DF.CONCEPTO (+) IN (5)
                   $where
                   $sort
                   )where  rownum<1000
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }


}
