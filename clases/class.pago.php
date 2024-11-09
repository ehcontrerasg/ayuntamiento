<?php
date_default_timezone_set('America/Santo_Domingo');
include_once "class.conexion.php";

class Pago extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }



    public function GetPagDiarioByProyFechEntPuntCaj($proyecto, $fechaIni,$fechaFin,$entidad,$punto,$caja)
    {

        $from  ="";
        $where ="";

        if($entidad!=""){
            $where .= " AND EP.COD_ENTIDAD=$entidad ";
        }
        if($punto!=""){
            $where .= " AND PP.ID_PUNTO_PAGO=$punto ";
        }
        if($caja!=""){
            $where .= " AND CP.NUM_CAJA=$caja ";
        }


         $sql = "
        SELECT * FROM(
            (SELECT
            P.INM_CODIGO INMUEBLE,
            TO_CHAR( P.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO,
            TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY HH24:MI') FECHA_INGRESO,
            P.IMPORTE IMPORTE,
            P.IMPORTE APLICADO,
            CP.NUM_CAJA CAJA,
            PP.DESCRIPCION PUNTO,
            EP.DESC_ENTIDAD ENTIDAD,
            'PAGO' TIPO,
            S.DESC_SERVICIO SUMINISTRO
            FROM SGC_TT_PAGOS P,
                 SGC_TP_CAJAS_PAGO CP,
                 SGC_TP_PUNTO_PAGO PP,
                 SGC_TP_ENTIDAD_PAGO EP,
                 SGC_TT_INMUEBLES inm,
                 SGC_TT_SERVICIOS_INMUEBLES SI,
                 SGC_TP_SERVICIOS S
            
            WHERE
                P.ID_CAJA=CP.ID_CAJA AND
                CP.ID_PUNTO_PAGO=PP.ID_PUNTO_PAGO AND
                PP.ENTIDAD_COD=EP.COD_ENTIDAD and
                inm.CODIGO_INM=p.INM_CODIGO   and
                inm.ID_PROYECTO='$proyecto' AND
                P.FECHA_REGISTRO BETWEEN
                TO_DATE('$fechaIni 00:00:00', 'yyyy-mm-dd HH24:MI:SS')
                AND TO_DATE('$fechaFin 23:59:59', 'yyyy-mm-dd HH24:MI:SS')
                AND INM.CODIGO_INM=SI.COD_INMUEBLE(+) AND
                SI.COD_SERVICIO (+) IN  (1,3) AND
                SI.COD_SERVICIO=S.COD_SERVICIO(+)
                $where
                )
            
            UNION (
            
                SELECT
                    P.INMUEBLE INMUEBLE,
                    TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO,
                    TO_CHAR(P.FECHA,'DD/MM/YYYY HH24:MI') FECHA_INGRESO,
                    P.IMPORTE IMPORTE,
                    P.APLICADO APLICADO,
                    CP.NUM_CAJA CAJA,
                    PP.DESCRIPCION PUNTO,
                    EP.DESC_ENTIDAD ENTIDAD,
                    'OTRO RECAUDO' PUNTO,
                    S.DESC_SERVICIO SUMINISTRO
                FROM SGC_TT_OTROS_RECAUDOS P,
                     SGC_TP_CAJAS_PAGO CP,
                     SGC_TP_PUNTO_PAGO PP,
                     SGC_TP_ENTIDAD_PAGO EP,
                     SGC_TT_INMUEBLES inm,
                     SGC_TT_SERVICIOS_INMUEBLES SI,
                     SGC_TP_SERVICIOS S
            
                WHERE
                        P.CAJA=CP.ID_CAJA AND
                        CP.ID_PUNTO_PAGO=PP.ID_PUNTO_PAGO AND
                        PP.ENTIDAD_COD=EP.COD_ENTIDAD and
                        inm.CODIGO_INM=p.INMUEBLE   and
                        inm.ID_PROYECTO='$proyecto' AND
                    P.FECHA BETWEEN
                        TO_DATE('$fechaIni 00:00:00', 'yyyy-mm-dd HH24:MI:SS')
                        AND TO_DATE('$fechaFin 23:59:59', 'yyyy-mm-dd HH24:MI:SS')
                  AND INM.CODIGO_INM=SI.COD_INMUEBLE(+) AND
                        SI.COD_SERVICIO (+) IN  (1,3) AND
                        SI.COD_SERVICIO=S.COD_SERVICIO(+)
                        $where
            
            ))
            ORDER BY FECHA_INGRESO
        
        
        
        ";

        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            return false;
        }

    }



    public function getPagosByInmAsc($codinm)
    {
        $sql = "SELECT TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY')FECHA_PAGO,to_char(P.FECHA_PAGO,'YYYYMMDD')  FECHCOMP , to_char(P.FECHA_PAGO,'DD-MM-YYYY') FECHCOMP2  , 'pago '||P.ID_PAGO||' '||FP.DESCRIPCION||' '||EP.DESC_ENTIDAD ||' '|| PP.DESCRIPCION ||' '||CP.DESCRIPCION DESCRIPCION,P.IMPORTE  FROM SGC_TT_PAGOS P,SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,SGC_TP_CAJAS_PAGO CP,
                SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP
                WHERE P.INM_CODIGO=$codinm
                AND MP.ID_PAGO(+)=P.ID_PAGO
                AND FP.CODIGO=MP.ID_FORM_PAGO
                AND CP.ID_CAJA(+)=P.ID_CAJA
                AND PP.ID_PUNTO_PAGO(+)=CP.ID_PUNTO_PAGO
                AND EP.COD_ENTIDAD(+)=PP.ENTIDAD_COD
                --AND P.FECHA_PAGO > TO_DATE('31/12/2010','DD/MM/YYYY')
                ORDER BY P.FECHA_PAGO ASC";
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

    public function getDetPagByFactFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                          SELECT PF.ID_PAGO PAGO,PA.FECHA_PAGO FECHA,PF.IMPORTE APLICADO,PA.IMPORTE VALPAGO FROM SGC_TT_PAGO_FACTURAS PF, SGC_TT_PAGOS PA
                          WHERE  PA.ID_PAGO=PF.ID_PAGO
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
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getPagByInmFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT P.ID_PAGO CODIGO, P.FECHA_PAGO , REPLACE(REPLACE(REPLACE(P.REFERENCIA,CHR(10),' ') ,CHR(13),' ') ,'  ',' ')  REFERENCIA , P.IMPORTE,
                         REPLACE(REPLACE(R.MOTIVO_REV,CHR(10),' '),CHR(13),' ') MOTIVO_REV, R.FECHA_REV, R.USR_REV
                        FROM SGC_TT_PAGOS P, SGC_TT_REV_PAGO R
                        WHERE P.ID_PAGO = R.ID_PAGO(+)
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

    public function getPagByInmFlexyAseo($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT P.ID_PAGO CODIGO, P.FECHA_PAGO , REPLACE(REPLACE(REPLACE(P.REFERENCIA,CHR(10),' ') ,CHR(13),' ') ,'  ',' ')  REFERENCIA , P.IMPORTE,
                         REPLACE(REPLACE(R.MOTIVO_REV,CHR(10),' '),CHR(13),' ') MOTIVO_REV, R.FECHA_REV, R.USR_REV
                        FROM SGC_TT_PAGOS_ASEO P, SGC_TT_REV_PAGO R
                        WHERE P.ID_PAGO = R.ID_PAGO(+)
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

    public function obtenerRepDetxCon($proyecto,$concepto,$fechaIn,$fechaFn,$periodo)
    {
        $sql = "SELECT R.CODIGO, TO_CHAR(R.FECHA_PAGO,'DD/MM/YYYY')FECPAGO, TO_CHAR(R.FECHA,'DD/MM/YYYY')fereg, 
       NVL(DECODE(R.ESTADO,'A','Pend Aplic'),$periodo)ESTADO,
       NVL(TO_CHAR(RC.FECHA_EJE,'DD/MM/YYYY'),TO_CHAR(R.FECHA_PAGO,'DD/MM/YYYY'))FECHA_CORTE, RC.TIPO_CORTE,
  R.IMPORTE, I.CODIGO_INM, CL.NOMBRE_CLI, REGEXP_REPLACE(c.alias, '[^A-Za-z0-9ÁÉÍÓÚáéíóú ]', '') AS alias, I.DIRECCION
FROM SGC_TT_INMUEBLES I, SGC_TT_OTROS_RECAUDOS R, SGC_TT_CONTRATOS C, SGC_TT_REGISTRO_CORTES RC, SGC_TT_CLIENTES CL
WHERE I.CODIGO_INM = R.INMUEBLE
      AND C.CODIGO_CLI = CL.CODIGO_CLI(+)
      AND I.CODIGO_INM = C.CODIGO_INM(+)
      AND RC.ID_OTRO_RECAUDO(+) = R.CODIGO
      AND R.ESTADO NOT IN ('I')
      AND R.CAJA NOT IN (463,391)
      AND R.CONCEPTO = '$concepto'
      AND R.ACUEDUCTO = '$proyecto'
      AND C.FECHA_FIN (+)IS NULL
      AND R.FECHA_PAGO BETWEEN TO_DATE('$fechaIn 00:00:00','YYYY/MM/DD HH24:MI:SS')
      AND TO_DATE('$fechaFn 23:59:59','YYYY/MM/DD HH24:MI:SS')";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);

            return false;
        }

    }

    public function getForPagByPag($codpago)
    {
        $sql = "SELECT FP.DESCRIPCION  FROM  SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP
              WHERE MP.ID_PAGO=$codpago
              AND  FP.CODIGO=MP.ID_FORM_PAGO";
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

    public function getUbPagByPag($codpago)
    {
        $sql = "SELECT CP.DESCRIPCION DESC_CAJA,PP.DESCRIPCION DESC_PUNTO , EP.DESC_ENTIDAD FROM  sgc_tt_pagos p, sgc_tp_cajas_pago cp, sgc_tp_punto_pago pp, sgc_tp_entidad_pago ep
                WHERE  P.ID_CAJA=cp.id_caja
                and PP.ID_PUNTO_PAGO=CP.ID_PUNTO_PAGO
                and EP.COD_ENTIDAD=PP.ENTIDAD_COD
                and P.ID_PAGO=$codpago";
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

    public function getDifAplFacByPagFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT  DD.CONCEPTO_DIF,DD.CODIGO,P.FECHA_PAGO, DF.PAGADO
                            FROM SGC_TT_PAGO_DETALLEFAC DF, SGC_TT_PAGOS P ,SGC_TT_DIFERIDOS DD

                            WHERE
                            P.ID_PAGO=DF.PAGO
                            AND DD.INMUEBLE=P.INM_CODIGO
                            AND DF.CONCEPTO=DD.CONCEPTO


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

    public function getPagAplFacByPagFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT F.PERIODO,F.CONSEC_FACTURA,F.TOTAL,PF.IMPORTE FROM SGC_TT_PAGO_FACTURAS PF, SGC_TT_FACTURA F
                        WHERE
                        F.CONSEC_FACTURA=PF.FACTURA

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

    public function getPagAplFacByPagFlexyAseo($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT F.PERIODO,F.CONSEC_FACTURA,F.TOTAL,PF.IMPORTE 
                        FROM SGC_TT_PAGO_FACTURAS_ASEO PF, SGC_TT_FACTURA_ASEO F
                        WHERE
                        F.CONSEC_FACTURA=PF.FACTURA

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

    public function getUltPagoByInm($inm)
    {
        $sql = "SELECT P.IMPORTE, to_char(P.FECHA_PAGO,'DD/MM/YYYY') FECHA_PAGO  FROM SGC_tT_PAGOS P
            WHERE P.INM_CODIGO=$inm
            AND P.ESTADO='A'
            AND P.FECHA_PAGO=(SELECT MAX(P2.FECHA_PAGO) FROM SGC_tT_PAGOS P2 WHERE
            P2.INM_CODIGO=$inm
            )
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

    public function getPagoGroupSecPerBySecPer($periodo, $sec)
    {
        $sql = " SELECT COUNT(1) CANTIDAD
                   FROM SGC_TT_PAGOS        P,
                        SGC_TP_CAJAS_PAGO   C,
                        SGC_TP_ENTIDAD_PAGO E,
                        SGC_TP_PUNTO_PAGO   R,
                        SGC_TT_INMUEBLES    I
                  WHERE I.CODIGO_INM = P.INM_CODIGO
                    AND C.ID_CAJA = P.ID_CAJA
                    AND C.ID_PUNTO_PAGO = R.ID_PUNTO_PAGO
                    AND R.ENTIDAD_COD = E.COD_ENTIDAD
                    AND E.VALIDA_REPORTES = 'S'
                    AND P.ESTADO = 'A'
                    AND I.ID_SECTOR = $sec
                    AND P.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                        LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))"; // <= +1
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getPagoGroupSecPerBySecPerSoloCorte($periodo, $sec)
    {
        $sql = " SELECT COUNT(1) CANTIDAD
                   FROM SGC_TT_PAGOS        P,
                        SGC_TP_CAJAS_PAGO   C,
                        SGC_TP_ENTIDAD_PAGO E,
                        SGC_TP_PUNTO_PAGO   R,
                        SGC_TT_INMUEBLES    I
                  WHERE I.CODIGO_INM = P.INM_CODIGO
                    AND C.ID_CAJA = P.ID_CAJA
                    AND C.ID_PUNTO_PAGO = R.ID_PUNTO_PAGO
                    AND R.ENTIDAD_COD = E.COD_ENTIDAD
                    AND E.VALIDA_REPORTES = 'S'
                    AND P.ESTADO = 'A'
                    AND I.ID_SECTOR = $sec
                    AND FECHA_ULTIMA_INS IS NULL
                    AND P.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                        LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))"; // <= +1
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function GerPagCorGroupSecPerBySecPer($periodo, $sec,$contratista)
    {

        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS U";
            $where = " AND U.ID_USUARIO = RC2.USR_EJE 
                       AND U.CONTRATISTA = $contratista";
        }

        $sql = "SELECT COUNT(1)CANTIDAD
                        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                        WHERE C.ID_CAJA = P.ID_CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO='A'
                        AND I.CODIGO_INM = P.INM_CODIGO
                        AND I.ID_SECTOR = $sec
                       AND P.FECHA_PAGO BETWEEN TO_DATE($periodo || '01', 'YYYYMM' || 'DD')
                     AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                     and P.INM_CODIGO IN (
                        SELECT RC2.ID_INMUEBLE
                          FROM
                               SGC_TT_REGISTRO_CORTES RC2".$from."
                         WHERE I.CODIGO_INM = RC2.ID_INMUEBLE


                           AND RC2.FECHA_EJE IS NOT NULL
                           AND TRIM(RC2.IMPO_CORTE) IS NULL
                           AND RC2.REVERSADO = 'N' AND
                           RC2.ID_PERIODO='$periodo'
                           ".$where."
                     )";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function GerPagCorGroupSecPerBySecPerSoloCorte($periodo, $sec,$contratista)
    {

        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS U";
            $where = " AND U.ID_USUARIO = RC2.USR_EJE 
                       AND U.CONTRATISTA = $contratista";
        }

        $sql = "SELECT COUNT(1)CANTIDAD
                        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                        WHERE C.ID_CAJA = P.ID_CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO='A'
                        AND I.CODIGO_INM = P.INM_CODIGO
                        AND I.ID_SECTOR = $sec  
                       AND P.FECHA_PAGO BETWEEN TO_DATE($periodo || '01', 'YYYYMM' || 'DD')
                     AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                     and P.INM_CODIGO IN (
                        SELECT RC2.ID_INMUEBLE
                          FROM
                               SGC_TT_REGISTRO_CORTES RC2".$from."
                         WHERE I.CODIGO_INM = RC2.ID_INMUEBLE


                           AND RC2.FECHA_EJE IS NOT NULL
                           AND TRIM(RC2.IMPO_CORTE) IS NULL
                           AND RC2.REVERSADO = 'N' AND
                           RC2.ID_PERIODO='$periodo'
                           AND RC2.FECHA_ULTIMA_INS IS NULL
                           ".$where."
                     )";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getPagRecByPerSec($periodo, $sec,$contratista)
    {
        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS US";
            $where = " AND RC.USR_EJE = US.ID_USUARIO(+)
                       AND US.CONTRATISTA = $contratista";
        }

        $sql = " SELECT COUNT(IMPORTE) CANTIDAD
                FROM SGC_TT_OTROS_RECAUDOS   ORE,
                     SGC_TT_INMUEBLES        INM,
                     sgc_tp_sectores         s,
                     sgc_tt_medidor_inmueble mi,
                     SGC_TT_REGISTRO_CORTES RC,
                     SGC_TP_MEDIDORES        ME
                     ".$from."
                    WHERE MI.COD_INMUEBLE(+) = INM.CODIGO_INM
                    AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                    AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
                    --AND INM.ID_PROYECTO = 'SD'
                    AND INM.ID_SECTOR = $sec
                    AND S.ID_GERENCIA IN ('E','N')
                    AND INM.ID_SECTOR = S.ID_SECTOR
                    AND NVL(ME.ESTADO_MED, 'N') IN ('S','N')
                    AND MI.FECHA_BAJA(+) is null
                    AND INM.CODIGO_INM = ORE.INMUEBLE
                    AND ORE.ESTADO <> 'I'
                    AND ORE.CONCEPTO = 20
                    AND ORE.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                    AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                    ".$where
        ;
        /*$sql = "SELECT count(1) CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS      ORE,
                       SGC_TT_CONTRATOS           CON,
                       SGC_TT_CLIENTES            CLI,
                       SGC_TT_INMUEBLES           INM,
                       SGC_TT_REGISTRO_RECONEXION REC,
                       sgc_tt_registro_cortes     rc
                       ".$from."
                 WHERE CON.CODIGO_INM(+) = INM.CODIGO_INM
                   AND CON.FECHA_FIN(+) IS NULL
                   AND CLI.CODIGO_CLI(+) = CON.CODIGO_CLI
                   AND RC.ORDEN = REC.ORDEN(+)
                   and INM.CODIGO_INM = ORE.INMUEBLE
                   AND INM.ID_SECTOR = '$sec'
                   AND RC.ID_OTRO_RECAUDO(+) = ORE.CODIGO
                   AND ORE.ESTADO <> 'I'
                   AND ORE.CONCEPTO = 20
                   AND ORE.FECHA_PAGO(+) BETWEEN
                       TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                       LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                       ".$where."
                --ORDER BY 4 ASC";*/

       // echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getPagRecByPerSecSoloCorte($periodo, $sec,$contratista)
    {
        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS US";
            $where = " AND RC.USR_EJE = US.ID_USUARIO(+)
                       AND US.CONTRATISTA = $contratista";
        }

        $sql = " SELECT COUNT(IMPORTE) CANTIDAD
                FROM SGC_TT_OTROS_RECAUDOS   ORE,
                     SGC_TT_INMUEBLES        INM,
                     sgc_tp_sectores         s,
                     sgc_tt_medidor_inmueble mi,
                     SGC_TT_REGISTRO_CORTES RC,
                     SGC_TP_MEDIDORES        ME
                     ".$from."
                    WHERE MI.COD_INMUEBLE(+) = INM.CODIGO_INM
                    AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                    AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
                    --AND INM.ID_PROYECTO = 'SD'
                    AND INM.ID_SECTOR = $sec
                    AND S.ID_GERENCIA IN ('E','N')
                    AND INM.ID_SECTOR = S.ID_SECTOR
                    AND NVL(ME.ESTADO_MED, 'N') IN ('S','N')
                    AND MI.FECHA_BAJA(+) is null
                    AND INM.CODIGO_INM = ORE.INMUEBLE
                    AND ORE.ESTADO <> 'I'
                    AND ORE.CONCEPTO = 20
                    AND RC.FECHA_ULTIMA_INS IS NULL
                    AND ORE.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                    AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                    ".$where
        ;
        /*$sql = "SELECT count(1) CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS      ORE,
                       SGC_TT_CONTRATOS           CON,
                       SGC_TT_CLIENTES            CLI,
                       SGC_TT_INMUEBLES           INM,
                       SGC_TT_REGISTRO_RECONEXION REC,
                       sgc_tt_registro_cortes     rc
                       ".$from."
                 WHERE CON.CODIGO_INM(+) = INM.CODIGO_INM
                   AND CON.FECHA_FIN(+) IS NULL
                   AND CLI.CODIGO_CLI(+) = CON.CODIGO_CLI
                   AND RC.ORDEN = REC.ORDEN(+)
                   and INM.CODIGO_INM = ORE.INMUEBLE
                   AND INM.ID_SECTOR = '$sec'
                   AND RC.ID_OTRO_RECAUDO(+) = ORE.CODIGO
                   AND ORE.ESTADO <> 'I'
                   AND ORE.CONCEPTO = 20
                   AND ORE.FECHA_PAGO(+) BETWEEN
                       TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                       LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                       ".$where."
                --ORDER BY 4 ASC";*/

       // echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getTotValRecBySecPer($periodo, $sec,$contratista)
    {

        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS US";
            $where = " AND RC.USR_EJE = US.ID_USUARIO(+)
                       AND US.CONTRATISTA = $contratista";
        }

       $sql = " SELECT SUM(IMPORTE) CANTIDAD
                FROM SGC_TT_OTROS_RECAUDOS   ORE,
                     SGC_TT_INMUEBLES        INM,
                     sgc_tp_sectores         s,
                     sgc_tt_medidor_inmueble mi,
                     SGC_TT_REGISTRO_CORTES RC,
                     SGC_TP_MEDIDORES        ME
                     ".$from."
                    WHERE MI.COD_INMUEBLE(+) = INM.CODIGO_INM
                    AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                    AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
                    --AND INM.ID_PROYECTO = 'SD'
                    AND INM.ID_SECTOR = $sec
                    AND S.ID_GERENCIA IN ('E','N')
                    AND INM.ID_SECTOR = S.ID_SECTOR
                    AND NVL(ME.ESTADO_MED, 'N') IN ('S','N')
                    AND MI.FECHA_BAJA(+) is null
                    AND INM.CODIGO_INM = ORE.INMUEBLE
                    AND ORE.ESTADO <> 'I'
                    AND ORE.CONCEPTO = 20
                    AND ORE.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                    AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                    ".$where
    ;
       /* $sql = "SELECT SUM(ORE.IMPORTE) CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS      ORE,
                       SGC_TT_CONTRATOS           CON,
                       SGC_TT_CLIENTES            CLI,
                       SGC_TT_INMUEBLES           INM,
                       SGC_TT_REGISTRO_RECONEXION REC,
                       sgc_tt_registro_cortes     rc
                       ".$from."
                 WHERE CON.CODIGO_INM(+) = INM.CODIGO_INM
                   AND CON.FECHA_FIN(+) IS NULL
                   AND CLI.CODIGO_CLI(+) = CON.CODIGO_CLI
                   AND RC.ORDEN = REC.ORDEN(+)
                   and INM.CODIGO_INM = ORE.INMUEBLE
                   AND INM.ID_SECTOR = '$sec'
                   AND RC.ID_OTRO_RECAUDO(+) = ORE.CODIGO
                   AND ORE.ESTADO <> 'I'
                   AND ORE.CONCEPTO = 20
                   AND ORE.FECHA_PAGO(+) BETWEEN
                       TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                       LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                  --AND REC.ID_INMUEBLE = INM.CODIGO_INM
                       ".$where;*/

       // echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getTotValRecBySecPerSoloCorte($periodo, $sec,$contratista)
    {

        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS US";
            $where = " AND RC.USR_EJE = US.ID_USUARIO(+)
                       AND US.CONTRATISTA = $contratista";
        }

       $sql = " SELECT SUM(IMPORTE) CANTIDAD
                FROM SGC_TT_OTROS_RECAUDOS   ORE,
                     SGC_TT_INMUEBLES        INM,
                     sgc_tp_sectores         s,
                     sgc_tt_medidor_inmueble mi,
                     SGC_TT_REGISTRO_CORTES RC,
                     SGC_TP_MEDIDORES        ME
                     ".$from."
                    WHERE MI.COD_INMUEBLE(+) = INM.CODIGO_INM
                    AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                    AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
                    --AND INM.ID_PROYECTO = 'SD'
                    AND INM.ID_SECTOR = $sec
                    AND S.ID_GERENCIA IN ('E','N')
                    AND INM.ID_SECTOR = S.ID_SECTOR
                    AND NVL(ME.ESTADO_MED, 'N') IN ('S','N')
                    AND MI.FECHA_BAJA(+) is null
                    AND INM.CODIGO_INM = ORE.INMUEBLE
                    AND ORE.ESTADO <> 'I'
                    AND ORE.CONCEPTO = 20
                    AND RC.FECHA_ULTIMA_INS IS NULL
                    AND ORE.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                    AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                    ".$where
    ;
       /* $sql = "SELECT SUM(ORE.IMPORTE) CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS      ORE,
                       SGC_TT_CONTRATOS           CON,
                       SGC_TT_CLIENTES            CLI,
                       SGC_TT_INMUEBLES           INM,
                       SGC_TT_REGISTRO_RECONEXION REC,
                       sgc_tt_registro_cortes     rc
                       ".$from."
                 WHERE CON.CODIGO_INM(+) = INM.CODIGO_INM
                   AND CON.FECHA_FIN(+) IS NULL
                   AND CLI.CODIGO_CLI(+) = CON.CODIGO_CLI
                   AND RC.ORDEN = REC.ORDEN(+)
                   and INM.CODIGO_INM = ORE.INMUEBLE
                   AND INM.ID_SECTOR = '$sec'
                   AND RC.ID_OTRO_RECAUDO(+) = ORE.CODIGO
                   AND ORE.ESTADO <> 'I'
                   AND ORE.CONCEPTO = 20
                   AND ORE.FECHA_PAGO(+) BETWEEN
                       TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                       LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                  --AND REC.ID_INMUEBLE = INM.CODIGO_INM
                       ".$where;*/

       // echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getCantRecPagByPerTipGerPro($periodo, $tipo, $gerencia, $proyecto,$contratista)
    {

        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS U";
            $where = " AND U.ID_USUARIO = RC.USR_EJE 
                       AND U.CONTRATISTA = $contratista";
        }
        $sql = "SELECT COUNT(1) CANTIDAD
              FROM SGC_TT_OTROS_RECAUDOS   ORE,
                   SGC_TT_INMUEBLES        INM,
                   sgc_tp_sectores         s,
                   sgc_tt_medidor_inmueble mi,
                   SGC_TT_REGISTRO_CORTES RC,
                  /* SGC_TT_USUARIOS U,*/
                   SGC_TP_MEDIDORES        ME
                   ".$from."
                   
             where MI.COD_INMUEBLE(+) = INM.CODIGO_INM
               AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
         /*      AND RC.USR_EJE=U.ID_USUARIO(+)*/
               AND INM.ID_PROYECTO = '$proyecto'
               AND S.ID_GERENCIA = '$gerencia'
               and ME.CODIGO_MED(+) = MI.COD_MEDIDOR
               AND NVL(ME.ESTADO_MED, 'N') = '$tipo'
               and INM.ID_SECTOR = S.ID_SECTOR
               and MI.FECHA_BAJA(+) is null
               AND INM.CODIGO_INM = ORE.INMUEBLE
               AND ORE.ESTADO <> 'I'
               AND ORE.CONCEPTO = 20
               AND ORE.FECHA_PAGO  BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                                  AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
               ".$where."
             ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getCantRecPagByPerTipGerProSoloCorte($periodo, $tipo, $gerencia, $proyecto,$contratista)
    {

        $from  ="";
        $where ="";

        if($contratista!=""){
            $from  = " ,SGC_TT_USUARIOS U";
            $where = " AND U.ID_USUARIO = RC.USR_EJE 
                       AND U.CONTRATISTA = $contratista";
        }
        $sql = "SELECT COUNT(1) CANTIDAD
              FROM SGC_TT_OTROS_RECAUDOS   ORE,
                   SGC_TT_INMUEBLES        INM,
                   sgc_tp_sectores         s,
                   sgc_tt_medidor_inmueble mi,
                   SGC_TT_REGISTRO_CORTES RC,
                  /* SGC_TT_USUARIOS U,*/
                   SGC_TP_MEDIDORES        ME
                   ".$from."
                   
             where MI.COD_INMUEBLE(+) = INM.CODIGO_INM
               AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
         /*      AND RC.USR_EJE=U.ID_USUARIO(+)*/
               AND INM.ID_PROYECTO = '$proyecto'
               AND S.ID_GERENCIA = '$gerencia'
               and ME.CODIGO_MED(+) = MI.COD_MEDIDOR
               AND NVL(ME.ESTADO_MED, 'N') = '$tipo'
               and INM.ID_SECTOR = S.ID_SECTOR
               and MI.FECHA_BAJA(+) is null
               AND INM.CODIGO_INM = ORE.INMUEBLE
               AND ORE.ESTADO <> 'I'
               AND ORE.CONCEPTO = 20
               AND RC.FECHA_ULTIMA_INS IS NULL
               AND ORE.FECHA_PAGO  BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                                  AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
               ".$where."
             ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getValRecByPerTipGerPro($periodo, $tipo, $gerencia, $proyecto,$contratista)
    {
        $from  ="";
        $where ="";

        if($contratista!="") {
            $from = " ,SGC_TT_USUARIOS U";
            $where = " AND U.ID_USUARIO = RC.USR_EJE 
                       AND U.CONTRATISTA = $contratista";
        }
        $sql = "SELECT SUM(IMPORTE) CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS   ORE,
                       SGC_TT_INMUEBLES        INM,
                       sgc_tp_sectores         s,
                       sgc_tt_medidor_inmueble mi,
                       SGC_TT_REGISTRO_CORTES RC,
                  /*     SGC_TT_USUARIOS U,*/
                       SGC_TP_MEDIDORES        ME
                       ".$from."
                 WHERE MI.COD_INMUEBLE(+) = INM.CODIGO_INM
                   AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                   AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
                   AND INM.ID_PROYECTO = '$proyecto'
                   AND S.ID_GERENCIA = '$gerencia'
                   AND INM.ID_SECTOR = S.ID_SECTOR
                   AND NVL(ME.ESTADO_MED, 'N') = '$tipo'
                   AND MI.FECHA_BAJA(+) is null
                   AND INM.CODIGO_INM = ORE.INMUEBLE
                   AND ORE.ESTADO <> 'I'
                   AND ORE.CONCEPTO = 20
                   AND ORE.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                                      AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                  ".$where."
               ";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }
public function getValRecByPerTipGerProSoloCorte($periodo, $tipo, $gerencia, $proyecto,$contratista)
    {
        $from  ="";
        $where ="";

        if($contratista!="") {
            $from = " ,SGC_TT_USUARIOS U";
            $where = " AND U.ID_USUARIO = RC.USR_EJE 
                       AND U.CONTRATISTA = $contratista";
        }
        $sql = "SELECT SUM(IMPORTE) CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS   ORE,
                       SGC_TT_INMUEBLES        INM,
                       sgc_tp_sectores         s,
                       sgc_tt_medidor_inmueble mi,
                       SGC_TT_REGISTRO_CORTES RC,
                  /*     SGC_TT_USUARIOS U,*/
                       SGC_TP_MEDIDORES        ME
                       ".$from."
                 WHERE MI.COD_INMUEBLE(+) = INM.CODIGO_INM
                   AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                   AND ORE.CODIGO=RC.ID_OTRO_RECAUDO
                   AND INM.ID_PROYECTO = '$proyecto'
                   AND S.ID_GERENCIA = '$gerencia'
                   AND INM.ID_SECTOR = S.ID_SECTOR
                   AND NVL(ME.ESTADO_MED, 'N') = '$tipo'
                   AND MI.FECHA_BAJA(+) is null
                   AND INM.CODIGO_INM = ORE.INMUEBLE
                   AND ORE.ESTADO <> 'I'
                   AND ORE.CONCEPTO = 20
                   AND RC.FECHA_ULTIMA_INS IS NULL
                   AND ORE.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
                                      AND LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                  ".$where."
               ";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getMedioPagoByPago($id_pago,$rec_o_pago)
    {
        if($rec_o_pago=='pago')
        {
            $sql = "SELECT M.ID_FORM_PAGO, F.DESCRIPCION
        FROM SGC_TT_MEDIOS_PAGO M, SGC_TP_FORMA_PAGO F
        WHERE M.ID_FORM_PAGO = F.CODIGO AND ID_PAGO = '$id_pago'";
        }
        else if($rec_o_pago=='recaudo')
        {
            $sql = "SELECT MR.ID_FORM_PAGO, F.DESCRIPCION
                FROM SGC_TT_MEDIOS_RECAUDO MR, SGC_TP_FORMA_PAGO F
                WHERE MR.ID_FORM_PAGO = F.CODIGO AND MR.ID_OTRREC = '$id_pago'";
        }

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }
    public function getMedioPagoByPagoOtrosRecaudos($id_pago)
    {
        $sql = "SELECT MR.ID_FORM_PAGO, F.DESCRIPCION
                FROM SGC_TT_MEDIOS_RECAUDO MR, SGC_TP_FORMA_PAGO F
                WHERE MR.ID_FORM_PAGO = F.CODIGO AND MR.ID_OTRREC = '$id_pago'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function getTiposPagos($formaPago)
    {
        $sql = "SELECT FP.CODIGO, FP.DESCRIPCION
                FROM SGC_TP_FORMA_PAGO FP
                WHERE
                FP.DESCRIPCION!='$formaPago' AND
                FP.ESTADO = 'A'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getParametros($idMedPago)
    {
        $sql = "SELECT DMP.CODIGO, DMP.DESCRICPCION DESCRIPCION,DMP.TIPO_CAMPO,DMP.TIPO_CONTROL FROM SGC_TP_DETALLE_MED_PAGO DMP WHERE DMP.ID_MED_PAGO = $idMedPago";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getBancos(){

        $sql = "SELECT B.CODIGO,B.DESCRIPCION FROM SGC_TP_BANCOS B WHERE B.ESTADO='A' ORDER BY B.DESCRIPCION";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getTarjetas()
    {
        $sql = "SELECT TC.CODIGO,TC.DESCRIPCION FROM SGC_TP_TARJETA_CREDITO TC WHERE TC.ESTADO='A'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
    public function EditarFormaPago($idPago,$idFormaPago,$listaCod,$listaVal,$rec)
    {

        $sql = "BEGIN SGC_P_ACTUALIZA_FORMA_PAGO($idPago,$idFormaPago,'$listaCod','$listaVal','$rec',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        /*echo $sql;*/
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':PCODRESULT', $codresult, "1000");
        oci_bind_by_name($resultado, ':PMSGRESULT', $msgresult, "1000");
        $bandera = oci_execute($resultado);
        if ($bandera) {
            if ($codresult == 0) {
                return true;
            } else {

                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }


    }



}
