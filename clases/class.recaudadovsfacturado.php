<?php

    include_once 'class.conexion.php';

    class RecaudadoVsFacturado extends ConexionClass {
        public function __construct()
        {
            parent::__construct();
        }

        function gatCantidadPagos($proyecto="",$periodo){

            $where="";
            if($proyecto!=""){
                $where .= " AND P.ACUEDUCTO = '$proyecto'";
            }

            echo $sql="select  SUM(CANTIDAD)CANTIDAD, SUM(IMPORTE)IMPORTE
                        from (SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE
                        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                        WHERE C.ID_CAJA = P.ID_CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO='A'
                        AND I.CODIGO_INM = P.INM_CODIGO
                        AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')=$periodo
                        $where
                        UNION   all
                        SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE
                        FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                        WHERE C.ID_CAJA = P.CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO IN ('T','A')
                        AND I.CODIGO_INM = P.INMUEBLE
                        AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')=$periodo
                        $where";

            $resultado = oci_parse($this->_db, $sql);

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

        function getValorFacturado($periodo,$ruta,$sector){
            $sql="SELECT SUM(F.TOTAL_ORI)
                  FROM SGC_TT_FACTURA F,SGC_TT_INMUEBLES I
                  WHERE

                        F.INMUEBLE = I.CODIGO_INM AND
                        F.FEC_EXPEDICION IS NOT NULL AND
                        I.ID_RUTA = $ruta AND
                        I.ID_SECTOR =$sector AND
                    F.PERIODO = '$periodo'";

            $resultado = oci_parse($this->_db, $sql);

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

        function getCantidadFacturas($periodo,$ruta,$sector){

            $sql="SELECT COUNT(F.CONSEC_FACTURA) CANTIDAD_FACTURAS
                  FROM SGC_TT_FACTURA F,SGC_TT_INMUEBLES I
                  WHERE
                      f.INMUEBLE = i.CODIGO_INM AND
                      F.FEC_EXPEDICION IS NOT NULL AND
                      I.ID_SECTOR= $sector AND
                      I.ID_RUTA = $ruta AND
                      F.PERIODO = $periodo";
           // echo $sql;
            $resultado = oci_parse($this->_db, $sql);

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

        function getvalorRecaudos($periodo,$ruta,$sector){

            $sql="SELECT DISTINCT ((SELECT SUM(P.IMPORTE)CANTIDAD
                  FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                  WHERE C.ID_CAJA = P.ID_CAJA
                    AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                    AND R.ENTIDAD_COD=E.COD_ENTIDAD
                    AND E.VALIDA_REPORTES='S'
                    AND P.ESTADO='A'
                    AND I.CODIGO_INM = P.INM_CODIGO
                    AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')=F.PERIODO
                    AND I.ID_RUTA =  I1.ID_RUTA
                    AND I.ID_SECTOR = I1.ID_SECTOR
                    )
                    +
                (SELECT SUM(P.IMPORTE)CANTIDAD
                    FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                    WHERE C.ID_CAJA = P.CAJA
                    AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                    AND R.ENTIDAD_COD=E.COD_ENTIDAD
                    AND E.VALIDA_REPORTES='S'
                    AND P.ESTADO IN ('T','A')
                    AND I.CODIGO_INM = P.INMUEBLE
                    AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')=F.PERIODO
                    AND I.ID_RUTA =  I1.ID_RUTA
                    AND I.ID_SECTOR = I1.ID_SECTOR)) RECAUDOS
                FROM SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1
                WHERE
                    f.INMUEBLE = i1.CODIGO_INM AND
                    F.FEC_EXPEDICION IS NOT NULL AND
                    I1.ID_SECTOR= $sector AND
                    I1.ID_RUTA = $ruta AND
                    F.PERIODO = $periodo";
            $resultado = oci_parse($this->_db, $sql);

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

        function getPeriodos($periodoInicial,$periodoFinal){
             $sql="SELECT F.PERIODO
                  FROM SGC_TT_FACTURA F
                  WHERE F.PERIODO BETWEEN  $periodoInicial AND $periodoFinal
                  GROUP BY   F.PERIODO
                  ORDER BY  F.PERIODO";
            $resultado = oci_parse($this->_db, $sql);

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



}