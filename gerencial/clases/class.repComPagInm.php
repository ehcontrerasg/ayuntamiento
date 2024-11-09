<?php
include_once 'class.conexion.php';
class ReportesComPagInm extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function PagosInmueblesUno($proyecto, $anouno, $mesuno, $diauno)
    {
        $sql = "SELECT P.ID_PAGO, P.INM_CODIGO, CO.ALIAS, I.ID_SECTOR, I.ID_RUTA, I.ID_PROCESO, I.CATASTRO, A.ID_USO, T.CATEGORIA,
 DECODE(SI.COD_SERVICIO,'1','Agua','3','Pozo')SUMINISTRO, DECODE(I.FACTURAR,'D','SI','P','NO')MEDIDO,
 ME.SERIAL, P.IMPORTE,
  (SELECT COUNT(PF.ID_PAGO) FROM SGC_TT_PAGO_FACTURAS PF WHERE PF.ID_PAGO = P.ID_PAGO) NUM_FACTURAS
          FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_MEDIDOR_INMUEBLE ME,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF,
          SGC_TT_CONTRATOS CO, SGC_TP_ACTIVIDADES A
          WHERE I.CODIGO_INM = P.INM_CODIGO
          AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
          AND ME.COD_INMUEBLE(+) = I.CODIGO_INM
          AND ME.FECHA_BAJA(+) IS NULL
          AND CO.CODIGO_INM = I.CODIGO_INM
          AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
          AND T.CONSEC_TARIFA(+) = SI.CONSEC_TARIFA
          AND SI.COD_SERVICIO (+) IN (1,3)
          AND SF.CODIGO_PAG(+) = P.ID_PAGO
          AND P.FECHA_PAGO BETWEEN TO_DATE('$anouno-$mesuno-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$anouno-$mesuno-$diauno 23:59:59','YYYY-MM-DD HH24:MI:SS')
          AND P.ESTADO='A'
          AND CO.FECHA_FIN (+)IS NULL
          AND P.ACUEDUCTO = '$proyecto'
          GROUP BY P.ID_PAGO, P.INM_CODIGO, CO.ALIAS, I.ID_SECTOR, I.ID_RUTA, I.ID_PROCESO, I.CATASTRO, A.ID_USO, T.CATEGORIA, SI.COD_SERVICIO, I.FACTURAR, ME.SERIAL, P.IMPORTE  ";
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

    public function PagosInmueblesDos($proyecto, $anodos, $mesdos, $diados)
    {
        $sql = "SELECT P.ID_PAGO, P.INM_CODIGO, CO.ALIAS, I.ID_SECTOR, I.ID_RUTA, I.ID_PROCESO, I.CATASTRO, A.ID_USO, T.CATEGORIA,
 DECODE(SI.COD_SERVICIO,'1','Agua','3','Pozo')SUMINISTRO, DECODE(I.FACTURAR,'D','SI','P','NO')MEDIDO,
 ME.SERIAL, P.IMPORTE,
  (SELECT COUNT(PF.ID_PAGO) FROM SGC_TT_PAGO_FACTURAS PF WHERE PF.ID_PAGO = P.ID_PAGO) NUM_FACTURAS
          FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_MEDIDOR_INMUEBLE ME,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF,
          SGC_TT_CONTRATOS CO, SGC_TP_ACTIVIDADES A
          WHERE I.CODIGO_INM = P.INM_CODIGO
          AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
          AND ME.COD_INMUEBLE(+) = I.CODIGO_INM
          AND ME.FECHA_BAJA(+) IS NULL
          AND CO.CODIGO_INM = I.CODIGO_INM
          AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
          AND T.CONSEC_TARIFA(+) = SI.CONSEC_TARIFA
          AND SI.COD_SERVICIO (+) IN (1,3)
          AND SF.CODIGO_PAG(+) = P.ID_PAGO
          AND P.FECHA_PAGO BETWEEN TO_DATE('$anodos-$mesdos-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$anodos-$mesdos-$diados 23:59:59','YYYY-MM-DD HH24:MI:SS')
          AND P.ESTADO='A'
          AND CO.FECHA_FIN (+)IS NULL
          AND P.ACUEDUCTO = '$proyecto'
          GROUP BY P.ID_PAGO, P.INM_CODIGO, CO.ALIAS, I.ID_SECTOR, I.ID_RUTA, I.ID_PROCESO, I.CATASTRO, A.ID_USO, T.CATEGORIA, SI.COD_SERVICIO, I.FACTURAR, ME.SERIAL, P.IMPORTE ";
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

    public function DiferenciasPagosInm($proyecto, $anouno, $mesuno, $diauno, $anodos, $mesdos, $diados)
    {
        $sql = "SELECT P.ID_PAGO,
       P.INM_CODIGO,
       CO.ALIAS,
       I.ID_SECTOR,
       I.ID_RUTA,
       I.ID_PROCESO,
       I.CATASTRO,
       A.ID_USO,
       T.CATEGORIA,
       DECODE(SI.COD_SERVICIO, '1', 'Agua', '3', 'Pozo') SUMINISTRO,
       DECODE(I.FACTURAR, 'D', 'SI', 'P', 'NO') MEDIDO,
       ME.SERIAL,
       P.IMPORTE,
       (SELECT COUNT(PF.ID_PAGO)
          FROM SGC_TT_PAGO_FACTURAS PF
         WHERE PF.ID_PAGO = P.ID_PAGO) NUM_FACTURAS
  FROM SGC_TT_INMUEBLES           I,
       SGC_TT_PAGOS               P,
       SGC_TT_SERVICIOS_INMUEBLES SI,
       SGC_TT_MEDIDOR_INMUEBLE    ME,
       SGC_TP_TARIFAS             T,
       SGC_TT_SALDO_FAVOR         SF,
       SGC_TT_CONTRATOS           CO,
       SGC_TP_ACTIVIDADES         A
 WHERE I.CODIGO_INM = P.INM_CODIGO
   AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
   AND ME.COD_INMUEBLE(+) = I.CODIGO_INM
   AND ME.FECHA_BAJA(+) IS NULL
   AND CO.CODIGO_INM = I.CODIGO_INM
   AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
   AND T.CONSEC_TARIFA(+) = SI.CONSEC_TARIFA
   AND SI.COD_SERVICIO(+) IN (1, 3)
   AND SF.CODIGO_PAG(+) = P.ID_PAGO
   AND P.FECHA_PAGO BETWEEN
       TO_DATE('$anouno-$mesuno-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
       TO_DATE('$anouno-$mesuno-$diauno 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
   AND P.ESTADO = 'A'
   AND CO.FECHA_FIN(+) IS NULL
   AND P.ACUEDUCTO = '$proyecto'
 GROUP BY P.ID_PAGO,
          P.INM_CODIGO,
          CO.ALIAS,
          I.ID_SECTOR,
          I.ID_RUTA,
          I.ID_PROCESO,
          I.CATASTRO,
          A.ID_USO,
          T.CATEGORIA,
          SI.COD_SERVICIO,
          I.FACTURAR,
          ME.SERIAL,
          P.IMPORTE
MINUS
SELECT P.ID_PAGO,
       P.INM_CODIGO,
       CO.ALIAS,
       I.ID_SECTOR,
       I.ID_RUTA,
       I.ID_PROCESO,
       I.CATASTRO,
       A.ID_USO,
       T.CATEGORIA,
       DECODE(SI.COD_SERVICIO, '1', 'Agua', '3', 'Pozo') SUMINISTRO,
       DECODE(I.FACTURAR, 'D', 'SI', 'P', 'NO') MEDIDO,
       ME.SERIAL,
       P.IMPORTE,
       (SELECT COUNT(PF.ID_PAGO)
          FROM SGC_TT_PAGO_FACTURAS PF
         WHERE PF.ID_PAGO = P.ID_PAGO) NUM_FACTURAS
  FROM SGC_TT_INMUEBLES           I,
       SGC_TT_PAGOS               P,
       SGC_TT_SERVICIOS_INMUEBLES SI,
       SGC_TT_MEDIDOR_INMUEBLE    ME,
       SGC_TP_TARIFAS             T,
       SGC_TT_SALDO_FAVOR         SF,
       SGC_TT_CONTRATOS           CO,
       SGC_TP_ACTIVIDADES         A
 WHERE I.CODIGO_INM = P.INM_CODIGO
   AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
   AND ME.COD_INMUEBLE(+) = I.CODIGO_INM
   AND ME.FECHA_BAJA(+) IS NULL
   AND CO.CODIGO_INM = I.CODIGO_INM
   AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
   AND T.CONSEC_TARIFA(+) = SI.CONSEC_TARIFA
   AND SI.COD_SERVICIO(+) IN (1, 3)
   AND SF.CODIGO_PAG(+) = P.ID_PAGO
   AND P.FECHA_PAGO BETWEEN
       TO_DATE('$anodos-$mesdos-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
       TO_DATE('$anodos-$mesdos-$diados 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
   AND P.ESTADO = 'A'
   AND CO.FECHA_FIN(+) IS NULL
   AND P.ACUEDUCTO = '$proyecto'
 GROUP BY P.ID_PAGO,
          P.INM_CODIGO,
          CO.ALIAS,
          I.ID_SECTOR,
          I.ID_RUTA,
          I.ID_PROCESO,
          I.CATASTRO,
          A.ID_USO,
          T.CATEGORIA,
          SI.COD_SERVICIO,
          I.FACTURAR,
          ME.SERIAL,
          P.IMPORTE
";
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
}
