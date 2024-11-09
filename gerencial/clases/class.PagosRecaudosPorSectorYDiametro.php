<?php

include_once 'class.conexion.php';
    class PagosRecaudosPorSectorYDiametro extends ConexionClass{

        function __construct(){
            parent::__construct();
        }

        public function GetPeriodos($desde, $hasta){
            $sql = "SELECT (TO_CHAR(TO_DATE(P.MES,'MM'),'MON')||'-'||P.AGNO) PERIODO
                    FROM SGC_TP_PERIODOS P
                    WHERE P.ID_PERIODO BETWEEN $desde AND $hasta
                   ";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        //Funciones para el reporte de número de pagos de agua por uso y diametro.
        public function GetPagosAgua($periodoDesde,$periodoHasta,$uso,$calibre,$proyecto){

            if($uso != "ZF"){
                $where = " AND T.COD_USO     = '$uso'
                           AND I.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND I.ZONA_FRANCA = 'S' ";
            }

             $sql = "SELECT ((SELECT COUNT(*)
         FROM SGC_TT_INMUEBLES I,
              SGC_TT_PAGOS P,
              SGC_TP_CAJAS_PAGO C,
              SGC_TP_PUNTO_PAGO PP,
              SGC_TP_ENTIDAD_PAGO E,
              SGC_TT_SERVICIOS_INMUEBLES SI,
              SGC_TP_TARIFAS T,
              SGC_TT_SALDO_FAVOR SF,
              SGC_TT_MEDIOS_PAGO MP,
              SGC_TP_FORMA_PAGO FP,
              SGC_TT_MEDIDOR_INMUEBLE MI,
              SGC_TP_ACTIVIDADES ACT 
         WHERE I.CODIGO_INM = P.INM_CODIGO
           AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
           AND P.ID_CAJA = C.ID_CAJA
           AND MP.ID_PAGO = P.ID_PAGO
           AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
           AND FP.CODIGO = MP.ID_FORM_PAGO
           AND E.COD_ENTIDAD = PP.ENTIDAD_COD
           AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
           AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
           AND SI.COD_SERVICIO (+) IN (1, 3)
           AND SF.CODIGO_PAG (+) = P.ID_PAGO
           AND  TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
           AND E.VALIDA_REPORTES = 'S'
           AND P.ESTADO = 'A'
           AND P.ACUEDUCTO = '$proyecto'
           AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
           AND MI.FECHA_BAJA(+) IS NULL
           AND I.FACTURAR IN ('D') "
                .$where.
           " AND MI.COD_CALIBRE = $calibre
        )
          +
        (SELECT COUNT(*) CANTIDAD
         FROM (SELECT R.CODIGO                                         COD_PAGO,
                      R.INMUEBLE                                       INMUEBLE,
                      MR.VALOR                                         IMPORTE_PAGADO,
                      R.APLICADO                                       IMPORTE_APLICADO,
                      SUM('0')                                         NUM_FACTURAS,
                      R.FECHA_PAGO,
                      SUM(0)                                           NOTA_CREDITO,
                      SUM('0')                                         TOTAL_FACTURADO,
                      (MR.VALOR - R.APLICADO)                          DIFERENCIA,
                      E.COD_ENTIDAD,
                      PP.ID_PUNTO_PAGO,
                      C.NUM_CAJA,
                      'Otro Recaudo'                                   TIPO,
                      DECODE(SI.COD_SERVICIO, '1', 'Agua', '3', 'Pozo')SUMINISTRO,
                      I.UNIDADES_HAB                                   UNIDADES,
                      R.DESCRIPCION                                    DESCRIPCION,
                      DECODE(I.FACTURAR, 'D', 'SI', 'P', 'NO')MEDIDOR,
                      T.CATEGORIA,
                      T.COD_USO                                        USO,
                      MAX(0)PERMAX,
                      MIN(0)PERMIN,
                      SUM('0')                                         SALDOANT,
                      R.CONCEPTO,
                      SI.CUPO_BASICO,
                      I.ID_SECTOR,
                      I.ID_RUTA,
                      I.ID_ZONA,
                      FP.DESCRIPCION                                   DESCRIPCIONM
               FROM SGC_TT_INMUEBLES I,
                    SGC_TT_OTROS_RECAUDOS R,
                    SGC_TP_CAJAS_PAGO C,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TP_ENTIDAD_PAGO E,
                    SGC_TT_FACTURA F,
                    SGC_TT_APLICA_OTROSREC A,
                    SGC_TT_SERVICIOS_INMUEBLES SI,
                    SGC_TP_TARIFAS T,
                    ACEASOFT.SGC_TT_MEDIOS_RECAUDO MR,
                    SGC_TP_FORMA_PAGO FP,
                    SGC_TT_MEDIDOR_INMUEBLE MI,
                    SGC_TP_ACTIVIDADES ACT
               WHERE I.CODIGO_INM = R.INMUEBLE
                 AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
                 AND R.CAJA = C.ID_CAJA
                 AND MR.ID_OTRREC = R.CODIGO
                 AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                 AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                 AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
                 AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
                 AND F.INMUEBLE (+) = R.INMUEBLE
                 AND A.ID_OTROREC (+) = R.CODIGO
                 AND FP.CODIGO = MR.ID_FORM_PAGO
                 AND R.ESTADO IN ('T', 'A')
                 AND E.ACTIVO IN ('S')
                 AND R.CAJA NOT IN (463, 391)
                 AND R.ACUEDUCTO = '$proyecto'
                 AND SI.COD_SERVICIO (+) IN (1, 3)
                   ---
                 AND R.CONCEPTO NOT IN (20)
                 AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                 AND MI.FECHA_BAJA(+) IS NULL
                 AND I.FACTURAR IN ('D') "
                    .$where.
                 " AND MI.COD_CALIBRE = $calibre
               GROUP BY R.CODIGO, R.INMUEBLE, R.FECHA_PAGO, MR.VALOR, R.APLICADO, E.COD_ENTIDAD,
                        PP.ID_PUNTO_PAGO,
                        C.NUM_CAJA, SI.COD_SERVICIO, I.UNIDADES_HAB, R.DESCRIPCION, I.FACTURAR, T.CATEGORIA, R.CONCEPTO,
                        SI.CUPO_BASICO, T.COD_USO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION) dual
         WHERE TO_CHAR(dual.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO)) NUMERO_PAGOS, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
FROM SGC_TP_PERIODOS PE
WHERE PE.ID_PERIODO BETWEEN  $periodoDesde AND $periodoHasta
";
               /* $sql = "SELECT (SELECT COUNT(*) NUMERO_PAGOS
                    FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                         SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                         SGC_TT_MEDIDOR_INMUEBLE MI
                    WHERE I.CODIGO_INM = P.INM_CODIGO
                      AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
                      AND P.ID_CAJA = C.ID_CAJA
                      AND MP.ID_PAGO=P.ID_PAGO
                      AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                      AND FP.CODIGO=MP.ID_FORM_PAGO
                      AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                      AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
                      AND SI.COD_SERVICIO IN (1)
                      AND SF.CODIGO_PAG(+) = P.ID_PAGO
                      AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
                      AND E.VALIDA_REPORTES='S'
                      AND P.ESTADO='A'
                      AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                      AND MI.COD_CALIBRE = $calibre "
                        .$where.
                      " AND MI.FECHA_BAJA(+) IS NULL
                        AND I.FACTURAR = 'D'
                        AND I.ID_PROYECTO = '$proyecto'
                      )NUMERO_PAGOS , TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
                        FROM SGC_TP_PERIODOS PE
                        WHERE PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                    GROUP BY PE.ID_PERIODO";*/

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }
        public function NumeroPagosSinMedidor($periodoDesde,$periodoHasta, $uso,$proyecto){

            if($uso != "ZF"){
                $where = " AND T.COD_USO     = '$uso'
                           AND I.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND I.ZONA_FRANCA = 'S' ";
            }

            $sql = "SELECT ((SELECT COUNT(*)
          FROM SGC_TT_INMUEBLES I,
              SGC_TT_PAGOS P,
              SGC_TP_CAJAS_PAGO C,
              SGC_TP_PUNTO_PAGO PP,
              SGC_TP_ENTIDAD_PAGO E,
              SGC_TT_SERVICIOS_INMUEBLES SI,
              SGC_TP_TARIFAS T,
              SGC_TT_SALDO_FAVOR SF,
              SGC_TT_MEDIOS_PAGO MP,
              SGC_TP_FORMA_PAGO FP,
              SGC_TP_ACTIVIDADES ACT 
         WHERE I.CODIGO_INM = P.INM_CODIGO
           AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
           AND P.ID_CAJA = C.ID_CAJA
           AND MP.ID_PAGO = P.ID_PAGO
           AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
           AND FP.CODIGO = MP.ID_FORM_PAGO
           AND E.COD_ENTIDAD = PP.ENTIDAD_COD
           AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
           AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
           AND SI.COD_SERVICIO (+) IN (1, 3)
           AND SF.CODIGO_PAG (+) = P.ID_PAGO
           AND  TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
           AND E.VALIDA_REPORTES = 'S'
           AND P.ESTADO = 'A'
           AND P.ACUEDUCTO = '$proyecto'
           AND I.FACTURAR IN ('P') "
                .$where.
                " )
          +
        (SELECT COUNT(*) CANTIDAD
         FROM (SELECT R.CODIGO                                         COD_PAGO,
                      R.INMUEBLE                                       INMUEBLE,
                      MR.VALOR                                         IMPORTE_PAGADO,
                      R.APLICADO                                       IMPORTE_APLICADO,
                      SUM('0')                                         NUM_FACTURAS,
                      R.FECHA_PAGO,
                      SUM(0)                                           NOTA_CREDITO,
                      SUM('0')                                         TOTAL_FACTURADO,
                      (MR.VALOR - R.APLICADO)                          DIFERENCIA,
                      E.COD_ENTIDAD,
                      PP.ID_PUNTO_PAGO,
                      C.NUM_CAJA,
                      'Otro Recaudo'                                   TIPO,
                      DECODE(SI.COD_SERVICIO, '1', 'Agua', '3', 'Pozo')SUMINISTRO,
                      I.UNIDADES_HAB                                   UNIDADES,
                      R.DESCRIPCION                                    DESCRIPCION,
                      DECODE(I.FACTURAR, 'D', 'SI', 'P', 'NO')MEDIDOR,
                      T.CATEGORIA,
                      T.COD_USO                                        USO,
                      MAX(0)PERMAX,
                      MIN(0)PERMIN,
                      SUM('0')                                         SALDOANT,
                      R.CONCEPTO,
                      SI.CUPO_BASICO,
                      I.ID_SECTOR,
                      I.ID_RUTA,
                      I.ID_ZONA,
                      FP.DESCRIPCION                                   DESCRIPCIONM
               FROM SGC_TT_INMUEBLES I,
                    SGC_TT_OTROS_RECAUDOS R,
                    SGC_TP_CAJAS_PAGO C,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TP_ENTIDAD_PAGO E,
                    SGC_TT_FACTURA F,
                    SGC_TT_APLICA_OTROSREC A,
                    SGC_TT_SERVICIOS_INMUEBLES SI,
                    SGC_TP_TARIFAS T,
                    ACEASOFT.SGC_TT_MEDIOS_RECAUDO MR,
                    SGC_TP_FORMA_PAGO FP,
                    SGC_TP_ACTIVIDADES ACT
               WHERE I.CODIGO_INM = R.INMUEBLE
                 AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
                 AND R.CAJA = C.ID_CAJA
                 AND MR.ID_OTRREC = R.CODIGO
                 AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                 AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                 AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
                 AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
                 AND F.INMUEBLE (+) = R.INMUEBLE
                 AND A.ID_OTROREC (+) = R.CODIGO
                 AND FP.CODIGO = MR.ID_FORM_PAGO
                 AND R.ESTADO IN ('T', 'A')
                 AND E.ACTIVO IN ('S')
                 AND R.CAJA NOT IN (463, 391)
                 AND R.ACUEDUCTO = '$proyecto'
                 AND SI.COD_SERVICIO (+) IN (1, 3)
                   ---
                 AND R.CONCEPTO NOT IN (20)
                 AND I.FACTURAR IN ('P') "
                .$where.
                " GROUP BY R.CODIGO, R.INMUEBLE, R.FECHA_PAGO, MR.VALOR, R.APLICADO, E.COD_ENTIDAD,
                        PP.ID_PUNTO_PAGO,
                        C.NUM_CAJA, SI.COD_SERVICIO, I.UNIDADES_HAB, R.DESCRIPCION, I.FACTURAR, T.CATEGORIA, R.CONCEPTO,
                        SI.CUPO_BASICO, T.COD_USO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION) dual
         WHERE TO_CHAR(dual.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO))NUMERO_PAGOS, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
FROM SGC_TP_PERIODOS PE
WHERE PE.ID_PERIODO BETWEEN  $periodoDesde AND $periodoHasta
";
            /*$sql = "SELECT (SELECT COUNT(*) NUMERO_PAGOS
                      FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                      SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                      SGC_TT_MEDIDOR_INMUEBLE MI
                      WHERE I.CODIGO_INM = P.INM_CODIGO
                      AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
                      AND P.ID_CAJA = C.ID_CAJA
                      AND MP.ID_PAGO=P.ID_PAGO
                      AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                      AND FP.CODIGO=MP.ID_FORM_PAGO
                      AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                      AND T.CONSEC_TARIFA(+) = SI.CONSEC_TARIFA
                      AND SI.COD_SERVICIO  IN (1)
                      AND SF.CODIGO_PAG(+) = P.ID_PAGO
                      AND E.VALIDA_REPORTES='S'
                      AND P.ESTADO='A'
                      AND I.ID_PROYECTO = '$proyecto'
                      AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                      AND I.FACTURAR = 'P'
                      AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO "
                      .$where.
                " )NUMERO_PAGOS, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
                        FROM SGC_TP_PERIODOS PE
                        WHERE PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                    GROUP BY PE.ID_PERIODO";*/

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        //Funciones de la hoja de Recaudo por uso y diámetro.
        public function RecaudosPorUsoYDiametro($periodoDesde,$periodoHasta,$uso,$calibre,$proyecto){

            if($uso != "ZF"){
                $where = " AND ACT.ID_USO      = '$uso'
                           AND INM.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND INM.ZONA_FRANCA = 'S' ";
            }

             $sql = "SELECT
                           NVL((SELECT NVL(SUM(PD.PAGADO),0) PAGADO
                                FROM SGC_TT_PAGO_DETALLEFAC PD,
                                     SGC_TT_INMUEBLES INM,
                                     SGC_TT_PAGOS P,
                                     SGC_TP_ACTIVIDADES ACT,
                                     SGC_TT_MEDIDOR_INMUEBLE MI
                                WHERE INM.CODIGO_INM = P.INM_CODIGO
                                  AND P.ID_PAGO = PD.PAGO
                                  AND ACT.SEC_ACTIVIDAD (+) = INM.SEC_ACTIVIDAD
                                  AND INM.CODIGO_INM = MI.COD_INMUEBLE (+)
                                  AND MI.FECHA_BAJA(+) IS NULL
                                  AND MI.COD_CALIBRE = $calibre
                                  AND INM.ID_PROYECTO = '$proyecto'
                                  AND P.ESTADO NOT IN 'I'
                                  AND INM.FACTURAR = 'D'
                                  AND P.ID_CAJA NOT IN (463,391)
                                  AND TO_CHAR(P.FECHA_PAGO, 'YYYYMM') = PE.ID_PERIODO
                                  AND MI.COD_CALIBRE(+) IS NOT NULL
                                  AND PD.CONCEPTO NOT IN (11,20) ".$where.
                                                        " ),0)
                                                       +
                                               NVL((SELECT NVL(SUM(O1.IMPORTE), 0)PAGOS
                                                    FROM SGC_TT_OTROS_RECAUDOS O1,
                                                         SGC_TT_INMUEBLES INM,
                                                         SGC_TP_ACTIVIDADES ACT,
                                                         SGC_TT_MEDIDOR_INMUEBLE MI
                                                    WHERE INM.CODIGO_INM = O1.INMUEBLE
                                                      AND INM.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD (+)
                                                      AND INM.CODIGO_INM = MI.COD_INMUEBLE (+)
                                                      AND MI.FECHA_BAJA (+) IS NULL
                                                      AND MI.COD_CALIBRE = $calibre
                                                      AND O1.ESTADO NOT IN 'I'
                                                      AND INM.ID_PROYECTO = '$proyecto'
                                                      AND O1.CAJA NOT IN (463, 391)
                                                      AND TO_CHAR(O1.FECHA_PAGO, 'YYYYMM') = PE.ID_PERIODO
                                                      AND INM.FACTURAR = 'D'
                                                      AND MI.COD_CALIBRE(+) IS NOT NULL
                                                      AND O1.CONCEPTO NOT IN (11,20) "
                                                      .$where." ),0) RECAUDO, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO  
                                        FROM SGC_TP_PERIODOS PE
                                        WHERE  PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }
        public function RecaudosSinMedidor($periodoDesde,$periodoHasta, $uso, $proyecto){

            if($uso != "ZF"){
                $where = " AND ACT.ID_USO     = '$uso'
                           AND INM.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND INM.ZONA_FRANCA = 'S' ";
            }

             $sql = "SELECT
                     NVL((SELECT NVL(SUM(PD.PAGADO),0) PAGADO
                          FROM SGC_TT_PAGO_DETALLEFAC PD,
                               SGC_TT_INMUEBLES INM,
                               SGC_TT_PAGOS P,
                               SGC_TP_ACTIVIDADES ACT
                          WHERE INM.CODIGO_INM = P.INM_CODIGO
                            AND P.ID_PAGO = PD.PAGO
                            AND ACT.SEC_ACTIVIDAD(+) = INM.SEC_ACTIVIDAD
                            AND INM.ID_PROYECTO = '$proyecto'
                            AND P.ESTADO NOT IN 'I'
                            AND INM.FACTURAR = 'P'
                            AND P.ID_CAJA NOT IN (463,391)
                            AND TO_CHAR(P.FECHA_PAGO, 'YYYYMM') = PE.ID_PERIODO
                            AND PD.CONCEPTO NOT IN (11,20) ".$where.
                                            " ),0)
                                           +
                                   NVL((SELECT NVL(SUM(O1.IMPORTE), 0)PAGOS
                                        FROM SGC_TT_OTROS_RECAUDOS O1,
                                             SGC_TT_INMUEBLES INM,
                                             SGC_TP_ACTIVIDADES ACT
                                        WHERE INM.CODIGO_INM = O1.INMUEBLE
                                          AND INM.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD (+)
                                          AND O1.ESTADO NOT IN 'I'
                                          AND INM.ID_PROYECTO = '$proyecto'
                                          AND O1.CAJA NOT IN (463, 391)
                                          AND TO_CHAR(O1.FECHA_PAGO, 'YYYYMM') = PE.ID_PERIODO
                                          AND INM.FACTURAR = 'P'
                                          AND O1.CONCEPTO NOT IN (11,20) "
                                            .$where." ),0)RECAUDO, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO 
                            FROM SGC_TP_PERIODOS PE
                            WHERE  PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        //Funciones de la hoja de número de pagos por sector y diámetro
        public function NumeroPagosPorSectorYDiametro($periodoDesde,$periodoHasta,$idSector,$calibre,$proyecto){

              $sql = "select  NVL(SUM(CANTIDAD),0)NUMERO_PAGOS, NVL(SUM(IMPORTE),0)IMPORTE,PE.ID_PERIODO PERIODO--NVL(PERIODO,'-') PERIODO
        from (SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
              FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE C.ID_CAJA = P.ID_CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO='A'
                AND I.CODIGO_INM = P.INM_CODIGO
                AND I.ID_PROYECTO = '$proyecto'
                AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                AND MI.FECHA_BAJA(+) IS NULL
                AND I.FACTURAR IN ('D')
                AND MI.COD_CALIBRE = $calibre
                AND I.ID_SECTOR = $idSector
              GROUP BY  TO_CHAR(P.FECHA_PAGO,'YYYYMM')
              UNION   all
              SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
              FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI
            WHERE C.ID_CAJA = P.CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO IN ('T','A')
                AND I.CODIGO_INM = P.INMUEBLE
                AND I.ID_PROYECTO = '$proyecto'
                AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                AND MI.FECHA_BAJA(+) IS NULL
                AND I.FACTURAR IN ('D')
                AND MI.COD_CALIBRE = $calibre
                AND I.ID_SECTOR = $idSector
               GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM')
             ), SGC_TP_PERIODOS PE
        WHERE PERIODO(+)= PE.ID_PERIODO
          AND PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
        GROUP BY PE.ID_PERIODO";
/*            $sql = "SELECT ((SELECT COUNT(*)
         FROM SGC_TT_INMUEBLES I,
              SGC_TT_PAGOS P,
              SGC_TP_CAJAS_PAGO C,
              SGC_TP_PUNTO_PAGO PP,
              SGC_TP_ENTIDAD_PAGO E,
              SGC_TT_SERVICIOS_INMUEBLES SI,
              SGC_TP_TARIFAS T,
              SGC_TT_SALDO_FAVOR SF,
              SGC_TT_MEDIOS_PAGO MP,
              SGC_TP_FORMA_PAGO FP,
              SGC_TT_MEDIDOR_INMUEBLE MI,
              SGC_TP_ACTIVIDADES ACT 
         WHERE I.CODIGO_INM = P.INM_CODIGO
           AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
           AND P.ID_CAJA = C.ID_CAJA
           AND MP.ID_PAGO = P.ID_PAGO
           AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
           AND FP.CODIGO = MP.ID_FORM_PAGO
           AND E.COD_ENTIDAD = PP.ENTIDAD_COD
           AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
           AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
           AND SI.COD_SERVICIO (+) IN (1, 3)
           AND SF.CODIGO_PAG (+) = P.ID_PAGO
           AND  TO_CHAR(P.FECHA_PAGO,'YYYYMM') = 201912
           AND E.VALIDA_REPORTES = 'S'
           AND P.ESTADO = 'A'
           AND P.ACUEDUCTO = '$proyecto'
           AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
           AND MI.FECHA_BAJA(+) IS NULL
           AND I.FACTURAR IN ('D')  
           AND MI.COD_CALIBRE = $calibre
           AND I.ID_SECTOR = $idSector
        )
          +
        (SELECT COUNT(*) CANTIDAD
         FROM (SELECT R.CODIGO                                         COD_PAGO,
                      R.INMUEBLE                                       INMUEBLE,
                      MR.VALOR                                         IMPORTE_PAGADO,
                      R.APLICADO                                       IMPORTE_APLICADO,
                      SUM('0')                                         NUM_FACTURAS,
                      R.FECHA_PAGO,
                      SUM(0)                                           NOTA_CREDITO,
                      SUM('0')                                         TOTAL_FACTURADO,
                      (MR.VALOR - R.APLICADO)                          DIFERENCIA,
                      E.COD_ENTIDAD,
                      PP.ID_PUNTO_PAGO,
                      C.NUM_CAJA,
                      'Otro Recaudo'                                   TIPO,
                      DECODE(SI.COD_SERVICIO, '1', 'Agua', '3', 'Pozo')SUMINISTRO,
                      I.UNIDADES_HAB                                   UNIDADES,
                      R.DESCRIPCION                                    DESCRIPCION,
                      DECODE(I.FACTURAR, 'D', 'SI', 'P', 'NO')MEDIDOR,
                      T.CATEGORIA,
                      T.COD_USO                                        USO,
                      MAX(0)PERMAX,
                      MIN(0)PERMIN,
                      SUM('0')                                         SALDOANT,
                      R.CONCEPTO,
                      SI.CUPO_BASICO,
                      I.ID_SECTOR,
                      I.ID_RUTA,
                      I.ID_ZONA,
                      FP.DESCRIPCION                                   DESCRIPCIONM
               FROM SGC_TT_INMUEBLES I,
                    SGC_TT_OTROS_RECAUDOS R,
                    SGC_TP_CAJAS_PAGO C,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TP_ENTIDAD_PAGO E,
                    SGC_TT_FACTURA F,
                    SGC_TT_APLICA_OTROSREC A,
                    SGC_TT_SERVICIOS_INMUEBLES SI,
                    SGC_TP_TARIFAS T,
                    ACEASOFT.SGC_TT_MEDIOS_RECAUDO MR,
                    SGC_TP_FORMA_PAGO FP,
                    SGC_TT_MEDIDOR_INMUEBLE MI,
                    SGC_TP_ACTIVIDADES ACT
               WHERE I.CODIGO_INM = R.INMUEBLE
                 AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
                 AND R.CAJA = C.ID_CAJA
                 AND MR.ID_OTRREC = R.CODIGO
                 AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                 AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                 AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
                 AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
                 AND F.INMUEBLE (+) = R.INMUEBLE
                 AND A.ID_OTROREC (+) = R.CODIGO
                 AND FP.CODIGO = MR.ID_FORM_PAGO
                 AND R.ESTADO IN ('T', 'A')
                 AND E.ACTIVO IN ('S')
                 AND R.CAJA NOT IN (463, 391)
                 AND R.ACUEDUCTO = '$proyecto'
                 AND SI.COD_SERVICIO (+) IN (1, 3)
                   ---
                 AND R.CONCEPTO NOT IN (20)
                 AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                 AND MI.FECHA_BAJA(+) IS NULL
                 AND I.FACTURAR IN ('D') 
                AND MI.COD_CALIBRE = $calibre
                AND I.ID_SECTOR = $idSector
               GROUP BY R.CODIGO, R.INMUEBLE, R.FECHA_PAGO, MR.VALOR, R.APLICADO, E.COD_ENTIDAD,
                        PP.ID_PUNTO_PAGO,
                        C.NUM_CAJA, SI.COD_SERVICIO, I.UNIDADES_HAB, R.DESCRIPCION, I.FACTURAR, T.CATEGORIA, R.CONCEPTO,
                        SI.CUPO_BASICO, T.COD_USO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION) dual
         WHERE TO_CHAR(dual.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO)) NUMERO_PAGOS, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
FROM SGC_TP_PERIODOS PE
WHERE PE.ID_PERIODO BETWEEN  $periodoDesde AND $periodoHasta
";*/


            /*if($uso != "ZF"){
                $where = " AND T.COD_USO     = '$uso'
                           AND I.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND I.ZONA_FRANCA = 'S' ";
            }*/

            /*$sql = "SELECT (SELECT COUNT(*) NUMERO_PAGOS
                    FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                         SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                         SGC_TT_MEDIDOR_INMUEBLE MI
                    WHERE I.CODIGO_INM = P.INM_CODIGO
                      AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
                      AND P.ID_CAJA = C.ID_CAJA
                      AND MP.ID_PAGO=P.ID_PAGO
                      AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                      AND FP.CODIGO=MP.ID_FORM_PAGO
                      AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                      AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
                      AND SI.COD_SERVICIO IN (1)
                      AND SF.CODIGO_PAG(+) = P.ID_PAGO
                      AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
                      AND E.VALIDA_REPORTES='S'
                      AND P.ESTADO='A'
                      AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                      AND MI.COD_CALIBRE = $calibre 
                      AND MI.FECHA_BAJA(+) IS NULL
                      AND I.FACTURAR = 'D'
                      AND I.ID_PROYECTO = '$proyecto'
                      AND I.ID_SECTOR = $idSector
                      )NUMERO_PAGOS , TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
                        FROM SGC_TP_PERIODOS PE
                        WHERE PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                    GROUP BY PE.ID_PERIODO";*/

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }
        public function NumeroPagosPorSectorYDiametroSinMedidor($periodoDesde,$periodoHasta, $idSector,$proyecto){

            $sql = "select  NVL(SUM(CANTIDAD),0)NUMERO_PAGOS, NVL(SUM(IMPORTE),0)IMPORTE,PE.ID_PERIODO PERIODO
                    from (SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
                          FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                          WHERE C.ID_CAJA = P.ID_CAJA
                            AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                            AND R.ENTIDAD_COD=E.COD_ENTIDAD
                            AND E.VALIDA_REPORTES='S'
                            AND P.ESTADO='A'
                            AND I.CODIGO_INM = P.INM_CODIGO
                            AND I.ID_PROYECTO = '$proyecto'
                            AND I.FACTURAR IN ('P')
                            AND I.ID_SECTOR = $idSector
                          GROUP BY  TO_CHAR(P.FECHA_PAGO,'YYYYMM')
                          UNION   all
                          SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
                          FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                          WHERE C.ID_CAJA = P.CAJA
                            AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                            AND R.ENTIDAD_COD=E.COD_ENTIDAD
                            AND E.VALIDA_REPORTES='S'
                            AND P.ESTADO IN ('T','A')
                            AND I.CODIGO_INM = P.INMUEBLE
                            AND I.ID_PROYECTO = '$proyecto'
                            AND I.FACTURAR IN ('P')
                            AND I.ID_SECTOR = $idSector
                          GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM')
                         ), SGC_TP_PERIODOS PE
                    WHERE PERIODO(+)= PE.ID_PERIODO
                      AND PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                    GROUP BY PE.ID_PERIODO";

            /*$sql = "SELECT ((SELECT COUNT(*)
          FROM SGC_TT_INMUEBLES I,
              SGC_TT_PAGOS P,
              SGC_TP_CAJAS_PAGO C,
              SGC_TP_PUNTO_PAGO PP,
              SGC_TP_ENTIDAD_PAGO E,
              SGC_TT_SERVICIOS_INMUEBLES SI,
              SGC_TP_TARIFAS T,
              SGC_TT_SALDO_FAVOR SF,
              SGC_TT_MEDIOS_PAGO MP,
              SGC_TP_FORMA_PAGO FP,
              SGC_TP_ACTIVIDADES ACT 
         WHERE I.CODIGO_INM = P.INM_CODIGO
           AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
           AND P.ID_CAJA = C.ID_CAJA
           AND MP.ID_PAGO = P.ID_PAGO
           AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
           AND FP.CODIGO = MP.ID_FORM_PAGO
           AND E.COD_ENTIDAD = PP.ENTIDAD_COD
           AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
           AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
           AND SI.COD_SERVICIO (+) IN (1, 3)
           AND SF.CODIGO_PAG (+) = P.ID_PAGO
           AND  TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
           AND E.VALIDA_REPORTES = 'S'
           AND P.ESTADO = 'A'
           AND P.ACUEDUCTO = '$proyecto'
           AND I.FACTURAR IN ('P')
           AND I.ID_SECTOR = $idSector)
          +
        (SELECT COUNT(*) CANTIDAD
         FROM (SELECT R.CODIGO                                         COD_PAGO,
                      R.INMUEBLE                                       INMUEBLE,
                      MR.VALOR                                         IMPORTE_PAGADO,
                      R.APLICADO                                       IMPORTE_APLICADO,
                      SUM('0')                                         NUM_FACTURAS,
                      R.FECHA_PAGO,
                      SUM(0)                                           NOTA_CREDITO,
                      SUM('0')                                         TOTAL_FACTURADO,
                      (MR.VALOR - R.APLICADO)                          DIFERENCIA,
                      E.COD_ENTIDAD,
                      PP.ID_PUNTO_PAGO,
                      C.NUM_CAJA,
                      'Otro Recaudo'                                   TIPO,
                      DECODE(SI.COD_SERVICIO, '1', 'Agua', '3', 'Pozo')SUMINISTRO,
                      I.UNIDADES_HAB                                   UNIDADES,
                      R.DESCRIPCION                                    DESCRIPCION,
                      DECODE(I.FACTURAR, 'D', 'SI', 'P', 'NO')MEDIDOR,
                      T.CATEGORIA,
                      T.COD_USO                                        USO,
                      MAX(0)PERMAX,
                      MIN(0)PERMIN,
                      SUM('0')                                         SALDOANT,
                      R.CONCEPTO,
                      SI.CUPO_BASICO,
                      I.ID_SECTOR,
                      I.ID_RUTA,
                      I.ID_ZONA,
                      FP.DESCRIPCION                                   DESCRIPCIONM
               FROM SGC_TT_INMUEBLES I,
                    SGC_TT_OTROS_RECAUDOS R,
                    SGC_TP_CAJAS_PAGO C,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TP_ENTIDAD_PAGO E,
                    SGC_TT_FACTURA F,
                    SGC_TT_APLICA_OTROSREC A,
                    SGC_TT_SERVICIOS_INMUEBLES SI,
                    SGC_TP_TARIFAS T,
                    ACEASOFT.SGC_TT_MEDIOS_RECAUDO MR,
                    SGC_TP_FORMA_PAGO FP,
                    SGC_TP_ACTIVIDADES ACT
               WHERE I.CODIGO_INM = R.INMUEBLE
                 AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
                 AND R.CAJA = C.ID_CAJA
                 AND MR.ID_OTRREC = R.CODIGO
                 AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                 AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                 AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD(+)
                 AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
                 AND F.INMUEBLE (+) = R.INMUEBLE
                 AND A.ID_OTROREC (+) = R.CODIGO
                 AND FP.CODIGO = MR.ID_FORM_PAGO
                 AND R.ESTADO IN ('T', 'A')
                 AND E.ACTIVO IN ('S')
                 AND R.CAJA NOT IN (463, 391)
                 AND R.ACUEDUCTO = '$proyecto'
                 AND SI.COD_SERVICIO (+) IN (1, 3)
                   ---
                 AND R.CONCEPTO NOT IN (20)
                 AND I.FACTURAR IN ('P')
                 AND I.ID_SECTOR = $idSector 
                  GROUP BY R.CODIGO, R.INMUEBLE, R.FECHA_PAGO, MR.VALOR, R.APLICADO, E.COD_ENTIDAD,
                        PP.ID_PUNTO_PAGO,
                        C.NUM_CAJA, SI.COD_SERVICIO, I.UNIDADES_HAB, R.DESCRIPCION, I.FACTURAR, T.CATEGORIA, R.CONCEPTO,
                        SI.CUPO_BASICO, T.COD_USO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION) dual
         WHERE TO_CHAR(dual.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO))NUMERO_PAGOS, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
FROM SGC_TP_PERIODOS PE
WHERE PE.ID_PERIODO BETWEEN  $periodoDesde AND $periodoHasta
";*/
             /*$sql = "SELECT (SELECT COUNT(*) NUMERO_PAGOS
                      FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                      SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                      SGC_TT_MEDIDOR_INMUEBLE MI
                      WHERE I.CODIGO_INM = P.INM_CODIGO
                      AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
                      AND P.ID_CAJA = C.ID_CAJA
                      AND MP.ID_PAGO=P.ID_PAGO
                      AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                      AND FP.CODIGO=MP.ID_FORM_PAGO
                      AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                      AND T.CONSEC_TARIFA(+) = SI.CONSEC_TARIFA
                      AND SI.COD_SERVICIO  IN (1)
                      AND SF.CODIGO_PAG(+) = P.ID_PAGO
                      AND E.VALIDA_REPORTES='S'
                      AND P.ESTADO='A'
                      AND I.ID_PROYECTO = '$proyecto'
                      AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                      AND I.FACTURAR = 'P'
                      AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO 
                      AND I.ID_SECTOR = $idSector)NUMERO_PAGOS, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
                        FROM SGC_TP_PERIODOS PE
                        WHERE PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                    GROUP BY PE.ID_PERIODO";*/

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        //Funciones de la hoja de recaudos por sector y diámetro
        public function RecaudosPorSectorYDiametro($periodoDesde,$periodoHasta,$idSector,$calibre,$proyecto){

            $sql = "select  NVL(SUM(IMPORTE),0)RECAUDO,PE.ID_PERIODO PERIODO--NVL(PERIODO,'-') PERIODO
        from (SELECT  SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
              FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE C.ID_CAJA = P.ID_CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO='A'
                AND I.CODIGO_INM = P.INM_CODIGO
                AND I.ID_PROYECTO = '$proyecto'
                AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                AND MI.FECHA_BAJA(+) IS NULL
                AND I.FACTURAR IN ('D')
                AND MI.COD_CALIBRE = $calibre
                AND I.ID_SECTOR = $idSector
              GROUP BY  TO_CHAR(P.FECHA_PAGO,'YYYYMM')
              UNION   all
              SELECT /*COUNT(P.IMPORTE)CANTIDAD,*/ SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
              FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI
            WHERE C.ID_CAJA = P.CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO IN ('T','A')
                AND I.CODIGO_INM = P.INMUEBLE
                AND I.ID_PROYECTO = '$proyecto'
                AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                AND MI.FECHA_BAJA(+) IS NULL
                AND I.FACTURAR IN ('D')
                AND MI.COD_CALIBRE = $calibre
                AND I.ID_SECTOR = $idSector
               GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM')
             ), SGC_TP_PERIODOS PE
        WHERE PERIODO(+)= PE.ID_PERIODO
          AND PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
        GROUP BY PE.ID_PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }
        public function RecaudosPorSectorYDiametroSinMedidor($periodoDesde,$periodoHasta, $idSector,$proyecto){

            $sql = "select NVL(SUM(IMPORTE),0) RECAUDO,PE.ID_PERIODO PERIODO
                    from (SELECT SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
                          FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                          WHERE C.ID_CAJA = P.ID_CAJA
                            AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                            AND R.ENTIDAD_COD=E.COD_ENTIDAD
                            AND E.VALIDA_REPORTES='S'
                            AND P.ESTADO='A'
                            AND I.CODIGO_INM = P.INM_CODIGO
                            AND I.ID_PROYECTO = '$proyecto'
                            AND I.FACTURAR IN ('P')
                            AND I.ID_SECTOR = $idSector
                          GROUP BY  TO_CHAR(P.FECHA_PAGO,'YYYYMM')
                          UNION   all
                          SELECT SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO
                          FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I
                          WHERE C.ID_CAJA = P.CAJA
                            AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                            AND R.ENTIDAD_COD=E.COD_ENTIDAD
                            AND E.VALIDA_REPORTES='S'
                            AND P.ESTADO IN ('T','A')
                            AND I.CODIGO_INM = P.INMUEBLE
                            AND I.ID_PROYECTO = '$proyecto'
                            AND I.FACTURAR IN ('P')
                            AND I.ID_SECTOR = $idSector
                          GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM')
                         ), SGC_TP_PERIODOS PE
                    WHERE PERIODO(+)= PE.ID_PERIODO
                      AND PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                    GROUP BY PE.ID_PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        //Funciones para el reporte de M3 facturado de agua de red.
        public function M3FacturadoAguaRedOLD($periodoDesde,$periodoHasta,$uso,$calibre,$proyecto,$listadoUsos){

            if($uso != "ZF"){
                $where = " AND A.ID_USO      = '$uso'
                           AND I.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND I.ZONA_FRANCA = 'S'
                           AND A.ID_USO      IN $listadoUsos
                         ";
            }

             $sql = "SELECT  (SELECT NVL(SUM(DF.UNIDADES_ORI),0) UNIDADES
                               FROM SGC_TT_INMUEBLES I,
                                    SGC_TT_FACTURA F,
                                    SGC_TT_DETALLE_FACTURA DF,
                                    SGC_TP_ACTIVIDADES A,
                                    --SGC_TP_USOS U,
                                    SGC_TP_SECTORES S,
                                    SGC_TT_MEDIDOR_INMUEBLE MI
                               WHERE I.CODIGO_INM = F.INMUEBLE
                                 AND F.CONSEC_FACTURA = DF.FACTURA
                                 AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                                -- AND A.ID_USO = U.ID_USO
                                 AND S.ID_SECTOR = I.ID_SECTOR
                                 AND DF.CONCEPTO IN (1, 3)
                                 AND DF.PERIODO = PE.ID_PERIODO
                                 AND I.ID_PROYECTO = '$proyecto'
                                 AND S.ID_GERENCIA IN ('E', 'N')
                                 --AND I.FACTURAR IN ('D')
                                 AND I.ID_ESTADO NOT IN ('CC', 'CT', 'CB', 'CK')
                                 AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                                 AND FECHA_BAJA IS NULL
                                 AND MI.COD_CALIBRE = $calibre "
                                    .$where.
                               " ) UNIDADES, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO 
                FROM SGC_TP_PERIODOS PE
                WHERE PE.ID_PERIODO BETWEEN  $periodoDesde AND $periodoHasta";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }
        public function M3FacturadoAguaRedSinMedidorOLD($periodoDesde,$periodoHasta,$uso,$proyecto,$listadoUsos){


            if($uso != "ZF"){
                $where = " AND A.ID_USO      = '$uso'
                           AND I.ZONA_FRANCA = 'N' ";
            }else{
                $where = " AND I.ZONA_FRANCA = 'S'
                           AND A.ID_USO      IN $listadoUsos   
                         ";
            }

             $sql = "SELECT  (SELECT NVL(SUM(DF.UNIDADES_ORI),0) UNIDADES
                               FROM SGC_TT_INMUEBLES I,
                                    SGC_TT_FACTURA F,
                                    SGC_TT_DETALLE_FACTURA DF,
                                    SGC_TP_ACTIVIDADES A,
                                    SGC_TP_USOS U,
                                    SGC_TP_SECTORES S
                               WHERE I.CODIGO_INM = F.INMUEBLE
                                 AND F.CONSEC_FACTURA = DF.FACTURA
                                 AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                                 AND A.ID_USO = U.ID_USO
                                 AND S.ID_SECTOR = I.ID_SECTOR
                                 AND DF.CONCEPTO IN (1, 3)
                                 AND DF.PERIODO = PE.ID_PERIODO
                                 AND I.ID_PROYECTO = '$proyecto'
                                 AND S.ID_GERENCIA IN ('E', 'N')
                                 AND I.FACTURAR IN ('P')
                                 AND I.ID_ESTADO NOT IN ('CC', 'CT', 'CB', 'CK') "
                                    .$where.
                                " ) UNIDADES, TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO 
                                FROM SGC_TP_PERIODOS PE
                                WHERE PE.ID_PERIODO BETWEEN  $periodoDesde AND $periodoHasta";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        //Funciones para el reporte de M3 facturado de agua de red.
        public function M3FacturadoAguaPozoOLD($periodoDesde,$periodoHasta,$idUso,$calibre,$zonaFranca){

            if($zonaFranca == "N"){
                $where = " AND T.COD_USO = '$idUso' ";
            }

            $sql = "SELECT (SELECT NVL(SUM(DF.UNIDADES_ORI),0) FACTURADO
                                    FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                                         SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                                         SGC_TT_MEDIDOR_INMUEBLE MI,SGC_TT_DETALLE_FACTURA DF
                                    WHERE I.CODIGO_INM = P.INM_CODIGO
                                      AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
                                      AND P.ID_CAJA = C.ID_CAJA
                                      AND MP.ID_PAGO=P.ID_PAGO
                                      AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                                      AND FP.CODIGO=MP.ID_FORM_PAGO
                                      AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                                      AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
                                      AND SI.COD_SERVICIO IN (3)
                                      AND SF.CODIGO_PAG(+) = P.ID_PAGO
                                      AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
                                      AND E.VALIDA_REPORTES='S'
                                      AND P.ESTADO='A'
                                      AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                                      AND MI.COD_CALIBRE = $calibre
                                      AND I.ZONA_FRANCA = 'N'
                                      AND MI.FECHA_BAJA(+) IS NULL
                                      AND I.FACTURAR = 'D'
                                      AND T.COD_USO = '$idUso'
                                      AND DF.COD_INMUEBLE = I.CODIGO_INM
                                      AND DF.PERIODO = TO_CHAR(P.FECHA_PAGO,'YYYYMM') ".
                                        $where.
                                   " )FACTURADO , TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
                            FROM SGC_TP_PERIODOS PE
                            WHERE PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                                GROUP BY PE.ID_PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }
        public function M3FacturadoAguaPozoSinMedidorOLD($periodoDesde,$periodoHasta,$idUso,$zonaFranca){

            if($zonaFranca == "N"){
                $where = " AND T.COD_USO = '$idUso' ";
            }
            $sql = "SELECT (SELECT NVL(SUM(DF.UNIDADES_ORI),0) FACTURADO
                                    FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                                         SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                                         SGC_TT_MEDIDOR_INMUEBLE MI,SGC_TT_DETALLE_FACTURA DF
                                    WHERE I.CODIGO_INM = P.INM_CODIGO
                                      AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
                                      AND P.ID_CAJA = C.ID_CAJA
                                      AND MP.ID_PAGO=P.ID_PAGO
                                      AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                                      AND FP.CODIGO=MP.ID_FORM_PAGO
                                      AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                                      AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
                                      AND SI.COD_SERVICIO IN (3)
                                      AND SF.CODIGO_PAG(+) = P.ID_PAGO
                                      AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') = PE.ID_PERIODO
                                      AND E.VALIDA_REPORTES='S'
                                      AND P.ESTADO='A'
                                      AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
                                      AND I.ZONA_FRANCA = 'N'
                                      AND MI.FECHA_BAJA(+) IS NULL
                                      AND I.FACTURAR = 'P'
                                      AND DF.COD_INMUEBLE = I.CODIGO_INM
                                      AND DF.PERIODO = TO_CHAR(P.FECHA_PAGO,'YYYYMM') "
                                      .$where.
                                   " )FACTURADO , TO_CHAR(TO_DATE(PE.ID_PERIODO,'YYYYMM'),'MON-YYYY') PERIODO
                            FROM SGC_TP_PERIODOS PE
                            WHERE PE.ID_PERIODO BETWEEN $periodoDesde AND $periodoHasta
                                GROUP BY PE.ID_PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        /////////////////////////////FUNCIONES OPTIMIZADAS
        function RecaudoYNumeroPagosPorUso($periodoDesde, $periodoHasta,$proyecto){
              $sql = "SELECT SUM(CANTIDAD_PAGOS) NUMERO_PAGOS, SUM(IMPORTE_APLICADO) RECAUDOS,ZONA_FRANCA, DESC_USO,PERIODO, CALIBRE
FROM (SELECT COUNT(*) CANTIDAD_PAGOS, P.IMPORTE IMPORTE_APLICADO, I.ZONA_FRANCA, U.DESC_USO,TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO, MI.COD_CALIBRE CALIBRE
      FROM SGC_TT_INMUEBLES I,
           SGC_TT_PAGOS P,
           SGC_TP_CAJAS_PAGO C,
           SGC_TP_PUNTO_PAGO PP,
           SGC_TP_ENTIDAD_PAGO E,
           SGC_TT_SERVICIOS_INMUEBLES SI,
           SGC_TP_TARIFAS T,
           SGC_TT_SALDO_FAVOR SF,
           SGC_TT_MEDIOS_PAGO MP,
           SGC_TP_FORMA_PAGO FP,
           SGC_TT_MEDIDOR_INMUEBLE MI,
           SGC_TP_ACTIVIDADES ACT,
           SGC_TP_USOS U   
      WHERE I.CODIGO_INM = P.INM_CODIGO
        AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
        AND P.ID_CAJA = C.ID_CAJA
        AND MP.ID_PAGO = P.ID_PAGO
        AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
        AND FP.CODIGO = MP.ID_FORM_PAGO
        AND E.COD_ENTIDAD = PP.ENTIDAD_COD
        AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
        AND SI.COD_SERVICIO (+) IN (1, 3)
        AND SF.CODIGO_PAG (+) = P.ID_PAGO
        AND TO_CHAR(P.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde and $periodoHasta
        AND E.VALIDA_REPORTES = 'S'
        AND P.ESTADO = 'A'
        AND P.ACUEDUCTO = '$proyecto'
        AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
        AND MI.FECHA_BAJA(+) IS NULL
        AND MI.COD_CALIBRE IN (1,2,3,5,6,7,8,9,11)
        AND I.FACTURAR = 'D'
        AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
        AND U.ID_USO = ACT.ID_USO
      GROUP BY P.IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM'),MI.COD_CALIBRE, I.ZONA_FRANCA,U.DESC_USO
      UNION
      ---SIN MEDIDOR
      SELECT COUNT(*) CANTIDAD_PAGOS, P.IMPORTE IMPORTE_APLICADO, I.ZONA_FRANCA, U.DESC_USO,TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO, 0 CALIBRE
      FROM SGC_TT_INMUEBLES I,
           SGC_TT_PAGOS P,
           SGC_TP_CAJAS_PAGO C,
           SGC_TP_PUNTO_PAGO PP,
           SGC_TP_ENTIDAD_PAGO E,
           SGC_TT_SERVICIOS_INMUEBLES SI,
           SGC_TP_TARIFAS T,
           SGC_TT_SALDO_FAVOR SF,
           SGC_TT_MEDIOS_PAGO MP,
           SGC_TP_FORMA_PAGO FP,
           SGC_TP_ACTIVIDADES ACT,
           SGC_TP_USOS U
      WHERE I.CODIGO_INM = P.INM_CODIGO
        AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
        AND P.ID_CAJA = C.ID_CAJA
        AND MP.ID_PAGO = P.ID_PAGO
        AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
        AND FP.CODIGO = MP.ID_FORM_PAGO
        AND E.COD_ENTIDAD = PP.ENTIDAD_COD
        AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
        AND SI.COD_SERVICIO (+) IN (1, 3)
        AND SF.CODIGO_PAG (+) = P.ID_PAGO
        AND TO_CHAR(P.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde and $periodoHasta
        AND E.VALIDA_REPORTES = 'S'
        AND P.ESTADO = 'A'
        AND P.ACUEDUCTO = '$proyecto'
        AND I.FACTURAR = 'P'
        AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
        AND U.ID_USO = ACT.ID_USO
      GROUP BY P.IMPORTE, TO_CHAR(P.FECHA_PAGO,'YYYYMM'),0, I.ZONA_FRANCA, U.DESC_USO
      UNION
      SELECT COUNT(*), SUM(IMPORTE_APLICADO) IMPORTE_APLICADO, ZONA_FRANCA,DESC_USO,FECHA_PAGO PERIODO,CALIBRE FROM (SELECT R.CODIGO COD_PAGO, R.INMUEBLE INMUEBLE, MR.VALOR IMPORTE_PAGADO, R.APLICADO IMPORTE_APLICADO,
                                                                                                                   SUM('0') NUM_FACTURAS, TO_CHAR(R.FECHA_PAGO,'YYYYMM')FECHA_PAGO, SUM(0) NOTA_CREDITO, SUM('0') TOTAL_FACTURADO,
                                                                                                                   (MR.VALOR - R.APLICADO) DIFERENCIA, E.COD_ENTIDAD, PP.ID_PUNTO_PAGO, C.NUM_CAJA, 'Otro Recaudo' TIPO,
                                                                                                                   DECODE(SI.COD_SERVICIO,'1','Agua','3','Pozo')SUMINISTRO, I.UNIDADES_HAB UNIDADES, R.DESCRIPCION DESCRIPCION,
                                                                                                                   DECODE(I.FACTURAR,'D','SI','P','NO')MEDIDOR, T.CATEGORIA, T.COD_USO USO, MAX(0)PERMAX, MIN(0)PERMIN, SUM('0') SALDOANT, R.CONCEPTO,
                                                                                                                   SI.CUPO_BASICO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION DESCRIPCIONM, NVL(MI.COD_CALIBRE,0) CALIBRE, I.ZONA_FRANCA,U.DESC_USO
                                                                                                            FROM SGC_TT_INMUEBLES I,
                                                                                                                 SGC_TT_OTROS_RECAUDOS R,
                                                                                                                 SGC_TP_CAJAS_PAGO C,
                                                                                                                 SGC_TP_PUNTO_PAGO PP,
                                                                                                                 SGC_TP_ENTIDAD_PAGO E,
                                                                                                                 SGC_TT_FACTURA F,
                                                                                                                 SGC_TT_APLICA_OTROSREC A,
                                                                                                                 SGC_TT_SERVICIOS_INMUEBLES SI,
                                                                                                                 SGC_TP_TARIFAS T,
                                                                                                                 ACEASOFT.SGC_TT_MEDIOS_RECAUDO MR,
                                                                                                                 SGC_TP_FORMA_PAGO FP,
                                                                                                                 SGC_TT_MEDIDOR_INMUEBLE MI,
                                                                                                                 SGC_TP_ACTIVIDADES ACT,
                                                                                                                 SGC_TP_USOS U
                                                                                                            WHERE I.CODIGO_INM = R.INMUEBLE
                                                                                                              AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
                                                                                                              AND R.CAJA = C.ID_CAJA
                                                                                                              AND MR.ID_OTRREC = R.CODIGO
                                                                                                              AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                                                                                                              AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                                                                                                              AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
                                                                                                              AND F.INMUEBLE (+) = R.INMUEBLE
                                                                                                              AND A.ID_OTROREC (+) = R.CODIGO
                                                                                                              AND FP.CODIGO = MR.ID_FORM_PAGO
                                                                                                              AND TO_CHAR(R.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde and $periodoHasta
                                                                                                              AND R.ESTADO IN ('T', 'A')
                                                                                                              AND E.ACTIVO IN ('S')
                                                                                                              AND R.CAJA NOT IN (463, 391)
                                                                                                              AND R.ACUEDUCTO = '$proyecto'
                                                                                                              AND SI.COD_SERVICIO (+) IN (1, 3)
                                                                                                              AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                                                                                                              AND MI.FECHA_BAJA(+) IS NULL
                                                                                                              AND MI.COD_CALIBRE IN (1,2,3,5,6,7,8,9,11)
                                                                                                              AND I.FACTURAR = 'D'
                                                                                                              AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
                                                                                                              AND U.ID_USO = ACT.ID_USO
                                                                                                              AND R.CONCEPTO NOT IN (20)
                                                                                                            GROUP BY R.CODIGO, R.INMUEBLE, TO_CHAR(R.FECHA_PAGO, 'YYYYMM'), MR.VALOR, R.APLICADO, E.COD_ENTIDAD,
                                                                                                                     PP.ID_PUNTO_PAGO,
                                                                                                                     C.NUM_CAJA, SI.COD_SERVICIO, I.UNIDADES_HAB, R.DESCRIPCION, I.FACTURAR, T.CATEGORIA, R.CONCEPTO,
                                                                                                                     SI.CUPO_BASICO, T.COD_USO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION, MI.COD_CALIBRE, I.ZONA_FRANCA,U.DESC_USO
                                                                                                           )GROUP BY FECHA_PAGO,CALIBRE,ZONA_FRANCA,DESC_USO


      UNION
      -- SIN MEDIDOR
      SELECT COUNT(*), SUM(IMPORTE_APLICADO) IMPORTE_APLICADO,ZONA_FRANCA, DESC_USO, FECHA_PAGO PERIODO,CALIBRE FROM (SELECT R.CODIGO COD_PAGO, R.INMUEBLE INMUEBLE, MR.VALOR IMPORTE_PAGADO, R.APLICADO IMPORTE_APLICADO,
                                                                                                                   SUM('0') NUM_FACTURAS, TO_CHAR(R.FECHA_PAGO,'YYYYMM')FECHA_PAGO, SUM(0) NOTA_CREDITO, SUM('0') TOTAL_FACTURADO,
                                                                                                                   (MR.VALOR - R.APLICADO) DIFERENCIA, E.COD_ENTIDAD, PP.ID_PUNTO_PAGO, C.NUM_CAJA, 'Otro Recaudo' TIPO,
                                                                                                                   DECODE(SI.COD_SERVICIO,'1','Agua','3','Pozo')SUMINISTRO, I.UNIDADES_HAB UNIDADES, R.DESCRIPCION DESCRIPCION,
                                                                                                                   DECODE(I.FACTURAR,'D','SI','P','NO')MEDIDOR, T.CATEGORIA, T.COD_USO USO, MAX(0)PERMAX, MIN(0)PERMIN, SUM('0') SALDOANT, R.CONCEPTO,
                                                                                                                   SI.CUPO_BASICO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION DESCRIPCIONM, 0 CALIBRE, I.ZONA_FRANCA, U.DESC_USO
                                                                                                            FROM SGC_TT_INMUEBLES I,
                                                                                                                 SGC_TT_OTROS_RECAUDOS R,
                                                                                                                 SGC_TP_CAJAS_PAGO C,
                                                                                                                 SGC_TP_PUNTO_PAGO PP,
                                                                                                                 SGC_TP_ENTIDAD_PAGO E,
                                                                                                                 SGC_TT_FACTURA F,
                                                                                                                 SGC_TT_APLICA_OTROSREC A,
                                                                                                                 SGC_TT_SERVICIOS_INMUEBLES SI,
                                                                                                                 SGC_TP_TARIFAS T,
                                                                                                                 ACEASOFT.SGC_TT_MEDIOS_RECAUDO MR,
                                                                                                                 SGC_TP_FORMA_PAGO FP,
                                                                                                                 SGC_TP_ACTIVIDADES ACT,
                                                                                                                 SGC_TP_USOS U
                                                                                                            WHERE I.CODIGO_INM = R.INMUEBLE
                                                                                                              AND I.CODIGO_INM = SI.COD_INMUEBLE (+)
                                                                                                              AND R.CAJA = C.ID_CAJA
                                                                                                              AND MR.ID_OTRREC = R.CODIGO
                                                                                                              AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
                                                                                                              AND E.COD_ENTIDAD = PP.ENTIDAD_COD
                                                                                                              AND T.CONSEC_TARIFA (+) = SI.CONSEC_TARIFA
                                                                                                              AND F.INMUEBLE (+) = R.INMUEBLE
                                                                                                              AND A.ID_OTROREC (+) = R.CODIGO
                                                                                                              AND FP.CODIGO = MR.ID_FORM_PAGO
                                                                                                              AND TO_CHAR(R.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde and $periodoHasta
                                                                                                              AND R.ESTADO IN ('T', 'A')
                                                                                                              AND E.ACTIVO IN ('S')
                                                                                                              AND R.CAJA NOT IN (463, 391)
                                                                                                              AND R.ACUEDUCTO = '$proyecto'
                                                                                                              AND SI.COD_SERVICIO (+) IN (1, 3)
                                                                                                              AND I.FACTURAR = 'P'
                                                                                                              AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
                                                                                                              AND U.ID_USO = ACT.ID_USO
                                                                                                              AND R.CONCEPTO NOT IN (20)
                                                                                                            GROUP BY R.CODIGO, R.INMUEBLE, TO_CHAR(R.FECHA_PAGO, 'YYYYMM'), MR.VALOR, R.APLICADO, E.COD_ENTIDAD,
                                                                                                                     PP.ID_PUNTO_PAGO,
                                                                                                                     C.NUM_CAJA, SI.COD_SERVICIO, I.UNIDADES_HAB, R.DESCRIPCION, I.FACTURAR, T.CATEGORIA, R.CONCEPTO,
                                                                                                                     SI.CUPO_BASICO, T.COD_USO, I.ID_SECTOR, I.ID_RUTA, I.ID_ZONA, FP.DESCRIPCION, 0, I.ZONA_FRANCA,U.DESC_USO
                                                                                                           )GROUP BY FECHA_PAGO,CALIBRE, ZONA_FRANCA,DESC_USO

     )GROUP BY  PERIODO,CALIBRE,ZONA_FRANCA,DESC_USO ORDER BY PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }

        }

        function RecaudosAguaPorUso($periodoDesde,$periodoHasta, $poyecto){
            $sql = "SELECT SUM(PAGADO)RECAUDOS,PERIODO,DESC_USO,ZONA_FRANCA,COD_CALIBRE CALIBRE FROM (SELECT NVL(SUM(PD.PAGADO), 0) PAGADO, TO_CHAR(P.FECHA_PAGO, 'YYYYMM') PERIODO,U.DESC_USO, INM.ZONA_FRANCA,MI.COD_CALIBRE
             FROM SGC_TT_PAGO_DETALLEFAC PD,
                  SGC_TT_INMUEBLES INM,
                  SGC_TT_PAGOS P,
                  SGC_TP_ACTIVIDADES ACT,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_USOS U
             WHERE INM.CODIGO_INM = P.INM_CODIGO
               AND P.ID_PAGO = PD.PAGO
               AND ACT.SEC_ACTIVIDAD (+) = INM.SEC_ACTIVIDAD
               AND INM.CODIGO_INM = MI.COD_INMUEBLE (+)
               AND MI.FECHA_BAJA (+) IS NULL
               AND INM.ID_PROYECTO = '$poyecto'
               AND P.ESTADO NOT IN 'I'
               AND INM.FACTURAR = 'D'
               AND P.ID_CAJA NOT IN (463, 391)
               AND TO_CHAR(P.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
               AND MI.COD_CALIBRE (+) IS NOT NULL
               AND MI.COD_CALIBRE IN (1,2,3,5,6,7,8,9,11)
               AND PD.CONCEPTO NOT IN (11, 20)
               AND U.ID_USO = ACT.ID_USO
             GROUP BY  TO_CHAR(P.FECHA_PAGO, 'YYYYMM'),U.DESC_USO, INM.ZONA_FRANCA,MI.COD_CALIBRE
            -- SIN MEDIDOR
             UNION
             SELECT NVL(SUM(PD.PAGADO), 0) PAGADO, TO_CHAR(P.FECHA_PAGO, 'YYYYMM') PERIODO,U.DESC_USO, INM.ZONA_FRANCA, 0 COD_CALIBRE
             FROM SGC_TT_PAGO_DETALLEFAC PD,
                  SGC_TT_INMUEBLES INM,
                  SGC_TT_PAGOS P,
                  SGC_TP_ACTIVIDADES ACT,
                  SGC_TP_USOS U
             WHERE INM.CODIGO_INM = P.INM_CODIGO
               AND P.ID_PAGO = PD.PAGO
               AND ACT.SEC_ACTIVIDAD (+) = INM.SEC_ACTIVIDAD
               AND INM.ID_PROYECTO = '$poyecto'
               AND P.ESTADO NOT IN 'I'
               AND INM.FACTURAR = 'P'
               AND P.ID_CAJA NOT IN (463, 391)
               AND TO_CHAR(P.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
               AND PD.CONCEPTO NOT IN (11, 20)
               AND U.ID_USO = ACT.ID_USO
             GROUP BY  TO_CHAR(P.FECHA_PAGO, 'YYYYMM'),U.DESC_USO, INM.ZONA_FRANCA,0
             UNION
             SELECT NVL(SUM(O1.IMPORTE), 0)PAGOS,TO_CHAR(O1.FECHA_PAGO, 'YYYYMM') PERIODO, U.DESC_USO, INM.ZONA_FRANCA,MI.COD_CALIBRE
             FROM SGC_TT_OTROS_RECAUDOS O1,
                  SGC_TT_INMUEBLES INM,
                  SGC_TP_ACTIVIDADES ACT,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_USOS U
             WHERE INM.CODIGO_INM = O1.INMUEBLE
               AND INM.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD (+)
               AND INM.CODIGO_INM = MI.COD_INMUEBLE (+)
               AND MI.FECHA_BAJA (+) IS NULL
               AND O1.ESTADO NOT IN 'I'
               AND INM.ID_PROYECTO = '$poyecto'
               AND O1.CAJA NOT IN (463, 391)
               AND TO_CHAR(O1.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
               AND INM.FACTURAR = 'D'
               AND MI.COD_CALIBRE (+) IS NOT NULL
               AND MI.COD_CALIBRE IN (1,2,3,5,6,7,8,9,11)
               AND O1.CONCEPTO NOT IN (11, 20)
               AND U.ID_USO = ACT.ID_USO
               GROUP BY TO_CHAR(O1.FECHA_PAGO, 'YYYYMM'),U.DESC_USO, INM.ZONA_FRANCA,MI.COD_CALIBRE
               --SIN MEDIDOR
                UNION
                SELECT NVL(SUM(O1.IMPORTE), 0)PAGOS,TO_CHAR(O1.FECHA_PAGO, 'YYYYMM') PERIODO, U.DESC_USO, INM.ZONA_FRANCA,0 COD_CALIBRE
                FROM SGC_TT_OTROS_RECAUDOS O1,
                     SGC_TT_INMUEBLES INM,
                     SGC_TP_ACTIVIDADES ACT,
                     SGC_TP_USOS U
                WHERE INM.CODIGO_INM = O1.INMUEBLE
                  AND INM.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD (+)
                  AND O1.ESTADO NOT IN 'I'
                  AND INM.ID_PROYECTO = '$poyecto'
                  AND O1.CAJA NOT IN (463, 391)
                  AND TO_CHAR(O1.FECHA_PAGO, 'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
                  AND INM.FACTURAR = 'P'
                  AND O1.CONCEPTO NOT IN (11, 20)
                  AND U.ID_USO = ACT.ID_USO
                GROUP BY TO_CHAR(O1.FECHA_PAGO, 'YYYYMM'),U.DESC_USO, INM.ZONA_FRANCA,0
                                      )
GROUP BY PERIODO,DESC_USO,ZONA_FRANCA,COD_CALIBRE ORDER BY PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        function RecaudosYNumeroPagosPorSector($periodoDesde,$periodoHasta,/*$calibresMedidor,*/ $proyecto)
        {

                          $sql = "select  SUM(CANTIDAD)  NUMERO_PAGOS,SUM(IMPORTE) RECAUDOS, PERIODO, SECTOR, CALIBRE
                from (SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO, 'YYYYMM') PERIODO,SEC.ID_SECTOR SECTOR, MI.COD_CALIBRE CALIBRE
                FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_pago mp, SGC_TP_FORMA_PAGO FP, SGC_TP_SECTORES SEC, SGC_TT_MEDIDOR_INMUEBLE MI
                WHERE C.ID_CAJA = P.ID_CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND FP.CODIGO(+)=MP.ID_FORM_PAGO
                AND MP.ID_PAGO(+)=P.ID_PAGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO='A'
                AND I.CODIGO_INM = P.INM_CODIGO
                AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
                AND SEC.ID_SECTOR = I.ID_SECTOR
                AND I.ID_PROYECTO = '$proyecto'
                AND I.CODIGO_INM(+) = MI.COD_INMUEBLE
                AND MI.FECHA_BAJA(+) IS NULL
                AND MI.COD_CALIBRE IN (1,2,3,5,6,7,8,9,11)
                AND I.FACTURAR = 'D'
                GROUP BY  TO_CHAR(P.FECHA_PAGO, 'YYYYMM'),SEC.ID_SECTOR, MI.COD_CALIBRE
                --SIN MEDIDOR
                UNION
                SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(P.IMPORTE)IMPORTE, TO_CHAR(P.FECHA_PAGO, 'YYYYMM') PERIODO,SEC.ID_SECTOR SECTOR, 0 CALIBRE
                FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_pago mp, SGC_TP_FORMA_PAGO FP, SGC_TP_SECTORES SEC--, SGC_TT_MEDIDOR_INMUEBLE MI
                WHERE C.ID_CAJA = P.ID_CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND FP.CODIGO(+)=MP.ID_FORM_PAGO
                AND MP.ID_PAGO(+)=P.ID_PAGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO='A'
                AND I.CODIGO_INM = P.INM_CODIGO
                AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
                AND SEC.ID_SECTOR = I.ID_SECTOR
                AND I.ID_PROYECTO = '$proyecto'
                AND I.FACTURAR = 'P'
                GROUP BY  TO_CHAR(P.FECHA_PAGO, 'YYYYMM'),SEC.ID_SECTOR, 0
            UNION   all
                SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(MP.VALOR)IMPORTE, TO_CHAR(P.FECHA_PAGO, 'YYYYMM') PERIODO,SEC.ID_SECTOR SECTOR, MI.COD_CALIBRE CALIBRE
                FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_RECAUDO mp, SGC_TP_FORMA_PAGO FP, SGC_TP_SECTORES SEC, SGC_TT_MEDIDOR_INMUEBLE MI
                WHERE C.ID_CAJA = P.CAJA
                AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                AND FP.CODIGO(+)=MP.ID_FORM_PAGO
                AND MP.ID_OTRREC(+)=P.CODIGO
                AND R.ENTIDAD_COD=E.COD_ENTIDAD
                AND E.VALIDA_REPORTES='S'
                AND P.ESTADO IN ('T','A')
                AND I.CODIGO_INM = P.INMUEBLE
                AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
                AND SEC.ID_SECTOR = I.ID_SECTOR
                AND I.ID_PROYECTO = '$proyecto'
                AND I.CODIGO_INM(+) = MI.COD_INMUEBLE
                AND MI.FECHA_BAJA(+) IS NULL
                AND MI.COD_CALIBRE IN (1,2,3,5,6,7,8,9,11)
                AND I.FACTURAR = 'D'
                GROUP BY  TO_CHAR(P.FECHA_PAGO, 'YYYYMM'),SEC.ID_SECTOR, MI.COD_CALIBRE
                --SIN MEDIDOR
                UNION
                SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(MP.VALOR)IMPORTE, TO_CHAR(P.FECHA_PAGO, 'YYYYMM') PERIODO,SEC.ID_SECTOR SECTOR, 0 CALIBRE
                FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_RECAUDO mp, SGC_TP_FORMA_PAGO FP, SGC_TP_SECTORES SEC
                WHERE C.ID_CAJA = P.CAJA
                  AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                  AND FP.CODIGO(+)=MP.ID_FORM_PAGO
                  AND MP.ID_OTRREC(+)=P.CODIGO
                  AND R.ENTIDAD_COD=E.COD_ENTIDAD
                  AND E.VALIDA_REPORTES='S'
                  AND P.ESTADO IN ('T','A')
                  AND I.CODIGO_INM = P.INMUEBLE
                  AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
                  AND SEC.ID_SECTOR = I.ID_SECTOR
                  AND I.ID_PROYECTO = '$proyecto'
                  AND I.FACTURAR = 'P'
                GROUP BY  TO_CHAR(P.FECHA_PAGO, 'YYYYMM'),SEC.ID_SECTOR, 0
                )
            GROUP BY PERIODO, SECTOR, CALIBRE";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        function M3FacturadoAguaRed($periodoDesde,$periodoHasta, $proyecto){

            $sql = "SELECT UNIDADES,DESC_USO,ZONA_FRANCA,PERIODO, CALIBRE FROM (SELECT NVL(SUM(DF.UNIDADES_ORI), 0) UNIDADES, U.DESC_USO, I.ZONA_FRANCA, DF.PERIODO, MI.COD_CALIBRE CALIBRE
               FROM SGC_TT_INMUEBLES I,
                    SGC_TT_FACTURA F,
                    SGC_TT_DETALLE_FACTURA DF,
                    SGC_TP_ACTIVIDADES A,
                    SGC_TP_USOS U,
                    SGC_TP_SECTORES S,
                    SGC_TT_MEDIDOR_INMUEBLE MI
               WHERE I.CODIGO_INM = F.INMUEBLE
                 AND F.CONSEC_FACTURA = DF.FACTURA
                 AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                 AND A.ID_USO = U.ID_USO
                 AND S.ID_SECTOR = I.ID_SECTOR
                 AND DF.CONCEPTO IN (1, 3)
                 AND DF.PERIODO BETWEEN $periodoDesde AND $periodoHasta
                 AND I.ID_PROYECTO = '$proyecto'
                 AND S.ID_GERENCIA IN ('E', 'N')
                 AND I.FACTURAR IN ('D')
                 AND I.ID_ESTADO NOT IN ('CC', 'CT', 'CB', 'CK')
                 AND I.CODIGO_INM = MI.COD_INMUEBLE (+)
                 AND FECHA_BAJA IS NULL
                 AND MI.COD_CALIBRE IN (1, 2, 3, 5, 6, 7, 8, 9, 11)
               GROUP BY U.DESC_USO, I.ZONA_FRANCA, DF.PERIODO, MI.COD_CALIBRE
               UNION
               SELECT NVL(SUM(DF.UNIDADES_ORI), 0) UNIDADES, U.DESC_USO, I.ZONA_FRANCA, DF.PERIODO, 0 CALIBRE
               FROM SGC_TT_INMUEBLES I,
                    SGC_TT_FACTURA F,
                    SGC_TT_DETALLE_FACTURA DF,
                    SGC_TP_ACTIVIDADES A,
                    SGC_TP_USOS U,
                    SGC_TP_SECTORES S
               WHERE I.CODIGO_INM = F.INMUEBLE
                 AND F.CONSEC_FACTURA = DF.FACTURA
                 AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                 AND A.ID_USO = U.ID_USO
                 AND S.ID_SECTOR = I.ID_SECTOR
                 AND DF.CONCEPTO IN (1, 3)
                 AND DF.PERIODO BETWEEN $periodoDesde AND $periodoHasta
                 AND I.ID_PROYECTO = '$proyecto'
                 AND S.ID_GERENCIA IN ('E', 'N')
                 AND I.FACTURAR IN ('P')
                 AND I.ID_ESTADO NOT IN ('CC', 'CT', 'CB', 'CK')
               GROUP BY U.DESC_USO, I.ZONA_FRANCA, DF.PERIODO, 0) ORDER BY PERIODO";

            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        function M3FacturadoAguaPozo($periodoDesde,$periodoHasta, $poyecto){
              $sql = "SELECT FACTURADO,PERIODO,DESC_USO,CALIBRE,ZONA_FRANCA FROM (
            SELECT NVL(SUM(DF.UNIDADES_ORI),0) FACTURADO, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO, U.DESC_USO, MI.COD_CALIBRE CALIBRE, I.ZONA_FRANCA 
            FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                 SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,
                 SGC_TT_MEDIDOR_INMUEBLE MI,SGC_TT_DETALLE_FACTURA DF, SGC_TP_USOS U 
            WHERE I.CODIGO_INM = P.INM_CODIGO
              AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
              AND P.ID_CAJA = C.ID_CAJA
              AND MP.ID_PAGO=P.ID_PAGO
              AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
              AND FP.CODIGO=MP.ID_FORM_PAGO
              AND E.COD_ENTIDAD = PP.ENTIDAD_COD
              AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
              AND U.ID_USO = T.COD_USO
              AND SI.COD_SERVICIO IN (3)
              AND SF.CODIGO_PAG(+) = P.ID_PAGO
              AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
              AND E.VALIDA_REPORTES='S'
              AND P.ESTADO='A'
              AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
              AND MI.COD_CALIBRE  IN (1, 2, 3, 5, 6, 7, 8, 9, 11)
              AND I.ZONA_FRANCA = 'N'
              AND MI.FECHA_BAJA(+) IS NULL
              AND I.FACTURAR = 'D'
              AND DF.COD_INMUEBLE = I.CODIGO_INM
              AND DF.PERIODO = TO_CHAR(P.FECHA_PAGO,'YYYYMM')
              AND I.ID_PROYECTO = '$poyecto'
            GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM'), U.DESC_USO, MI.COD_CALIBRE, I.ZONA_FRANCA
            UNION
            SELECT NVL(SUM(DF.UNIDADES_ORI),0) FACTURADO, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO, U.DESC_USO, 0 CALIBRE, I.ZONA_FRANCA
            FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO E,
                 SGC_TT_SERVICIOS_INMUEBLES SI,  SGC_TP_TARIFAS T, SGC_TT_SALDO_FAVOR SF, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,SGC_TT_DETALLE_FACTURA DF, SGC_TP_USOS U
            WHERE I.CODIGO_INM = P.INM_CODIGO
              AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
              AND P.ID_CAJA = C.ID_CAJA
              AND MP.ID_PAGO=P.ID_PAGO
              AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
              AND FP.CODIGO=MP.ID_FORM_PAGO
              AND E.COD_ENTIDAD = PP.ENTIDAD_COD
              AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
              AND U.ID_USO = T.COD_USO
              AND SI.COD_SERVICIO IN (3)
              AND SF.CODIGO_PAG(+) = P.ID_PAGO
              AND TO_CHAR(P.FECHA_PAGO,'YYYYMM') BETWEEN $periodoDesde AND $periodoHasta
              AND E.VALIDA_REPORTES='S'
              AND P.ESTADO='A'
              AND I.ZONA_FRANCA = 'N'
              AND I.FACTURAR = 'P'
              AND DF.COD_INMUEBLE = I.CODIGO_INM
              AND DF.PERIODO = TO_CHAR(P.FECHA_PAGO, 'YYYYMM')
              AND I.ID_PROYECTO = '$poyecto'
                           GROUP BY TO_CHAR(P.FECHA_PAGO, 'YYYYMM'), U.DESC_USO, 0, I.ZONA_FRANCA) ORDER BY PERIODO";
            $result  = oci_parse($this->_db,$sql);
            $bandera = oci_execute($result);

            if($bandera){
                return $result;
            }else{
                return false;
            }
        }

        function __destruct(){
            oci_close($this->_db);
        }
    }