<?php

    include_once "class.conexion.php";
    class ListaPagos extends ConexionClass
    {

        public function __construct(){
            parent::__construct();
        }

        public function getListaPagos($fechaDesde,$fechaHasta)
        {
            /*$sql="SELECT FD.ID_FACTURA,FD.MONTO,TO_CHAR(FD.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO,
                         FD.INMUEBLE, PRO.SIGLA_PROYECTO
                  FROM   TBL_FACTURA_DIGITAL@LK_CAASDENLINEA FD, SGC_TP_PROYECTOS PRO
                  WHERE  PRO.ID_PROYECTO = FD.PROYECTO
                  AND    FD.FECHA_PAGO BETWEEN TO_DATE('$fechaDesde 00:00:00','YYYY-MM-DD HH24:MI:SS')
                  AND    TO_DATE('$fechaHasta 23:59:59','YYYY-MM-DD HH24:MI:SS')";*/

            $sql="SELECT * FROM (
                                (SELECT PC.ID FACTURA, PC.MONTO,TO_CHAR(PC.FECHA_PAGO,'DD/MM/YYYY HH24:MI')FECHA_PAGO, PC.INM_CODIGO INMUEBLE,P.DESC_PROYECTO PROYECTO, 'PAGO_RECURRENTE_CARDNET' ORIGEN
                                FROM SGC_TT_PAGOS_CARDNET PC, SGC_TT_INMUEBLES INM,SGC_TP_PROYECTOS P
                                WHERE INM.CODIGO_INM = PC.INM_CODIGO
                                  AND   P.ID_PROYECTO = INM.ID_PROYECTO)
                                UNION
                                (SELECT FD.ID_FACTURA FACTURA,FD.MONTO,TO_CHAR(FD.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO,
                                        FD.INMUEBLE, PRO.SIGLA_PROYECTO PROYECTO, 'WEB' ORIGEN
                                  FROM   TBL_FACTURA_DIGITAL@LK_CAASDENLINEA FD, SGC_TP_PROYECTOS PRO
                                  WHERE  PRO.ID_PROYECTO = FD.PROYECTO
                                    AND    FD.FECHA_PAGO BETWEEN TO_DATE('$fechaDesde 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND    TO_DATE('$fechaHasta 23:59:59','YYYY-MM-DD HH24:MI:SS')))";

            $result = oci_parse($this->_db, $sql);

            if (oci_execute($result)) {
                oci_close($this->_db);
                return $result;
            } else {
                oci_close($this->_db);
                return false;
            }
        }
    }