<?php
include_once "../../clases/class.conexion.php";
class Pagos extends ConexionClass
{
    private $coduser;
    private $importe;
    private $referencia;
    private $num_caja;
    private $cod_inmueble;
    private $origen;
    private $monto;
    private $cod_pro;
    private $medio;
    private $id_pago;
    private $codresult;
    private $msgresult;

    public function __construct()
    {
        parent::__construct();
        $this->coduser      = "";
        $this->importe      = "";
        $this->referencia   = "";
        $this->num_caja     = "";
        $this->cod_inmueble = "";
        $this->origen       = "";
        $this->monto        = "";
        $this->cod_pro      = "";
        $this->medio        = "";
        $this->id_pago      = "";
    }

    public function getcodresult()
    {
        return $this->codresult;
    }

    public function getmsgresult()
    {
        return $this->msgresult;
    }

    public function setcodigo($valor)
    {
        $this->id_pago = $valor;
    }

    public function setfecha($valor)
    {
        $this->cod_fecha = $valor;
    }

    public function seleccionaUser($coduser)
    {
        $sql = "SELECT C.ID_CAJA, C.NUM_CAJA, P.COD_VIEJO, P.DESCRIPCION, E.COD_ENTIDAD, E.DESC_ENTIDAD, (U.NOM_USR||' '||U.APE_USR) NOMBRE,
        PR.ID_TIPO_PERFIL PERF, PR.MAX_DIAS, P.ID_PUNTO_PAGO, P.PAGO_X_FAC
        FROM SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO P, SGC_TP_ENTIDAD_PAGO E, SGC_TT_USUARIOS U, SGC_TP_PERFILES_RECAUDO PR
        WHERE C.ID_PUNTO_PAGO = P.ID_PUNTO_PAGO(+)
        AND P.ENTIDAD_COD = E.COD_ENTIDAD (+)
        AND C.ID_USUARIO(+) = U.ID_USUARIO
        AND U.ID_USUARIO  = '$coduser'
        AND PR.ID_USUARIO=U.ID_USUARIO
        AND P.ACTIVO (+)= 'S'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function getPagosByconceptosFecha($acueducto, $concepto, $entIni, $entFin, $punIni, $punFin, $cajaIni, $cajaFin, $fechIni, $fechFin)
    {
        $where1 = "";
        $where2 = "";
        $where3 = "";
        if (trim($concepto) != '') {
            $where1 .= "  AND PDF.CONCEPTO in ($concepto)";
            $where2 .= "  AND ORE.CONCEPTO in ($concepto)";}
        if (trim($entIni) != '') {
            $where1 .= " AND PP.ENTIDAD_COD>=$entIni";
            $where2 .= " AND PP.ENTIDAD_COD>=$entIni";}
        if (trim($entFin) != '') {
            $where1 .= " AND PP.ENTIDAD_COD<=$entFin";
            $where2 .= " AND PP.ENTIDAD_COD<=$entFin";}
        if (trim($punIni) != '') {
            $where1 .= " AND CAJ.ID_PUNTO_PAGO>=$punIni";
            $where2 .= " AND CAJ.ID_PUNTO_PAGO>=$punIni";}
        if (trim($punFin) != '') {
            $where1 .= " AND CAJ.ID_PUNTO_PAGO<=$punFin";
            $where2 .= " AND CAJ.ID_PUNTO_PAGO<=$punFin";}
        if (trim($cajaIni) != '') {
            $where1 .= " AND CAJ.NUM_CAJA>=$cajaIni";
            $where2 .= " AND CAJ.NUM_CAJA>=$cajaIni";}
        if (trim($cajaFin) != '') {
            $where1 .= " AND CAJ.NUM_CAJA<=$cajaFin";
            $where2 .= " AND CAJ.NUM_CAJA<=$cajaFin";}
        //if(trim($concepto)>10){$where3.="   PDF.PAGO > 36448089 AND "; }

        $sql = "SELECT
                  SUM(TOTAL) TOTAL,
                  CONCEPTO,
                  SUM(CANTIDAD) CANTIDAD, TIPO, FECHAPAGO
                FROM
                  (SELECT
                    NVL(SUM(PDF.PAGADO),0) TOTAL,
                    PDF.CONCEPTO ||'-'||SER.DESC_SERVICIO CONCEPTO,
                    COUNT(1) CANTIDAD, 'PAGOS' TIPO, TO_CHAR(PAG.FECHA_PAGO,'DD/MM/YYYY')FECHAPAGO
                  FROM
                    SGC_TT_PAGO_DETALLEFAC PDF,
                    SGC_TT_PAGOS PAG,
                    SGC_TT_INMUEBLES INM,
                    SGC_TP_CAJAS_PAGO CAJ,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TP_SERVICIOS SER
                  WHERE
                    SER.COD_SERVICIO=PDF.CONCEPTO AND
                    PAG.ID_PAGO=PDF.PAGO AND
                    INM.CODIGO_INM=PAG.INM_CODIGO AND
                    PAG.ID_CAJA=CAJ.ID_CAJA AND
                    PAG.ID_CAJA NOT IN (463,391) AND
                    $where3
                    PP.ID_PUNTO_PAGO=CAJ.ID_PUNTO_PAGO AND
                    PAG.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','YYYY-MM-DD hh24:mi:ss' ) AND TO_DATE('$fechFin 23:59:59','YYYY-MM-DD hh24:mi:ss') AND

                    PAG.ESTADO IN ('T','A') AND
                    --INM.ID_PROYECTO='$acueducto'
                    PAG.ACUEDUCTO='$acueducto'
                    $where1
                  GROUP BY
                  PDF.PAGADO,
                  PDF.CONCEPTO||'-'||SER.DESC_SERVICIO, TO_CHAR(PAG.FECHA_PAGO,'DD/MM/YYYY')
              UNION
                  SELECT
                    NVL(SUM(ORE.IMPORTE),0) TOTAL ,
                    ORE.CONCEPTO||'-'||SER.DESC_SERVICIO CONCEPTO,
                    COUNT(1) CANTIDAD, 'OTROS RECAUDOS' TIPO, TO_CHAR(ORE.FECHA_PAGO,'DD/MM/YYYY')FECHAPAGO
                  FROM
                    SGC_TT_OTROS_RECAUDOS ORE,
                    SGC_TT_INMUEBLES INM,
                    SGC_TP_CAJAS_PAGO CAJ,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TP_SERVICIOS SER
                  WHERE
                    SER.COD_SERVICIO=ORE.CONCEPTO AND
                    ORE.INMUEBLE=INM.CODIGO_INM AND
                    CAJ.ID_CAJA=ORE.CAJA AND
                    PP.ID_PUNTO_PAGO=CAJ.ID_PUNTO_PAGO AND
                    ORE.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','YYYY-MM-DD  hh24:mi:ss' ) AND TO_DATE('$fechFin 23:59:59','YYYY-MM-DD  hh24:mi:ss')AND
                    ORE.ESTADO IN ('T','A') AND
                    ORE.CAJA NOT IN (463,391)  AND
                    --INM.ID_PROYECTO='$acueducto'
                    ORE.ACUEDUCTO='$acueducto'
                    $where2
                  GROUP BY ORE.IMPORTE, ORE.CONCEPTO||'-'||SER.DESC_SERVICIO, TO_CHAR(ORE.FECHA_PAGO,'DD/MM/YYYY')
                  )
                 GROUP BY CONCEPTO, TIPO, FECHAPAGO
                ORDER BY CONCEPTO, FECHAPAGO, TIPO ASC";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerDatosCliente($cod_inmueble)
    {
        $sql = "SELECT substr(i.ID_PROCESO,0,4) RUTA,  I.CODIGO_INM, I.DIRECCION, U.DESC_URBANIZACION, C.CODIGO_CLI, C.ALIAS, I.ID_ESTADO, A.DESC_ACTIVIDAD, A.ID_USO, I.ID_PROYECTO, P.DESC_PROYECTO, L.NOMBRE_CLI, I.ID_TIPO_CLIENTE,
                   SI.COD_SERVICIO 
        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TP_ACTIVIDADES A, SGC_TP_PROYECTOS P, SGC_TT_CLIENTES L, SGC_TT_SERVICIOS_INMUEBLES SI  
        WHERE I.CONSEC_URB = U.CONSEC_URB AND C.CODIGO_INM = I.CODIGO_INM AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND L.CODIGO_CLI = C.CODIGO_CLI
        AND C.FECHA_FIN IS NULL AND I.ID_PROYECTO = P.ID_PROYECTO AND I.CODIGO_INM = '$cod_inmueble'
        AND SI.COD_INMUEBLE(+)=I.CODIGO_INM AND 
             SI.COD_SERVICIO IN (1,3) 
        ";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

    public function obtenerDatosFactura($cod_inmueble)
    {
        $sql = "SELECT SUM(VALOR) TOTAL
        FROM SGC_TT_DETALLE_FACTURA F
        WHERE F.COD_INMUEBLE = '$cod_inmueble'
        AND CONCEPTO IN (1,2,3,4)
        AND PERIODO  =( SELECT MAX(F2.PERIODO) FROM SGC_TT_DETALLE_FACTURA F2
        WHERE F2.COD_INMUEBLE = '$cod_inmueble')";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

    /*public function obtenerDatosFacturaTotal($cod_inmueble)
    {
        $sql = "SELECT TOTAL FROM(
        SELECT TOTAL
        FROM SGC_TT_FACTURA F
        WHERE F.INMUEBLE = '$cod_inmueble'
        AND TOTAL>0
        ORDER BY PERIODO DESC)
        WHERE ROWNUM <=1;";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }*/

/*
    public function obtenerDatosSerial($cod_inmueble)
    {
        $sql = "SELECT MI.SERIAL SERIAL
        FROM SGC_TT_MEDIDOR_INMUEBLE MI
        WHERE MI.COD_INMUEBLE = '$cod_inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }
*/

    public function obtenerDescEnt($cod_entidad)
    {
        $sql = "
                select EP.DESC_ENTIDAD from sgc_tp_entidad_pago ep
                where EP.COD_ENTIDAD=$cod_entidad";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerDescPunto($cod_entidad, $id_punto)
    {
        $sql = "
                select EP.DESCRIPCION from sgc_tp_punto_pago ep
                    where EP.ENTIDAD_COD=$cod_entidad
                    and EP.COD_VIEJO=$id_punto
                    AND ACTIVO = 'S'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function seleccionaMedioPago($medpago='')
    {
        $sql = "SELECT CODIGO, DESCRIPCION
        FROM SGC_TP_FORMA_PAGO
        WHERE ESTADO = 'A'
        ";
        if (trim($medpago) != "") {$sql = $sql . " AND CODIGO IN($medpago)";}
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaMedioPagoTeleAgua($medpago='')
    {
        $sql = "SELECT CODIGO, DESCRIPCION
        FROM SGC_TP_FORMA_PAGO
        WHERE ESTADO = 'A'
        AND CODIGO = '3'
        ";
        if (trim($medpago) != "") {$sql = $sql . " AND CODIGO IN($medpago)";}
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaBanco()
    {
        $sql = "SELECT CODIGO, DESCRIPCION
        FROM SGC_TP_BANCOS
        WHERE ESTADO = 'A'
        ORDER BY DESCRIPCION";
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

    public function seleccionaIdCaja($numcaja, $puntopago, $entidad)
    {
        $sql = "SELECT C.ID_CAJA
        FROM SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO P, SGC_TP_CAJAS_PAGO C
        WHERE E.COD_ENTIDAD = P.ENTIDAD_COD
        AND P.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
        AND E.COD_ENTIDAD = '$entidad'
        AND P.COD_VIEJO = '$puntopago'
        AND C.NUM_CAJA = '$numcaja'";
        //echo $sql;
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


    public function seleccionaUltimaCaja($cod_inmueble)
    {
        $sql = "SELECT P.ID_PAGO, PP.ENTIDAD_COD, EP.DESC_ENTIDAD, PP.COD_VIEJO, PP.ID_PUNTO_PAGO, PP.DESCRIPCION, CP.NUM_CAJA, P.ID_CAJA
        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO CP, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP
        WHERE CP.ID_CAJA = P.ID_CAJA
        AND PP.ID_PUNTO_PAGO = CP.ID_PUNTO_PAGO
        AND EP.COD_ENTIDAD = PP.ENTIDAD_COD
        AND P.INM_CODIGO = $cod_inmueble 
        AND P.ID_PAGO = (SELECT MAX(P2.ID_PAGO) FROM SGC_TT_PAGOS P2 WHERE P2.INM_CODIGO= $cod_inmueble )";
        //echo $sql;
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


    public function seleccionaTarjeta()
    {
        $sql = "SELECT CODIGO, DESCRIPCION
        FROM SGC_TP_TARJETA_CREDITO
        WHERE ESTADO = 'A'
        ORDER BY DESCRIPCION";
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

    public function totalfacven($cod_inmueble)
    {
        $sql = "SELECT NVL(SUM(F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO),0) DEUDA, COUNT (F.FACTURA_PAGADA) FACPEND
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I
        WHERE F.FACTURA_PAGADA = 'N'
        AND FEC_VCTO < SYSDATE
        AND F.FEC_EXPEDICION IS NOT NULL
        AND I.CODIGO_INM = F.INMUEBLE
        AND I.CODIGO_INM = '$cod_inmueble'";
        // echo $sql;
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

    public function facpend($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT F.CONSEC_FACTURA, F.TOTAL, (F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO)PENDIENTE, F.PERIODO, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FEC_EXPEDICION,
                        TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VCTO, CONCAT(NU.ID_NCF,F.NCF_CONSEC) NCF
                        FROM SGC_TT_FACTURA F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
                        and F.NCF_ID=NU.ID_NCF_USO
                        AND F.FEC_EXPEDICION IS NOT NULL
                        AND F.FECHA_PAGO IS NULL
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

    public function seleccionaFecpago($cod_inmueble)
    {
        $sql = "SELECT MAX(FECIND)FEC_PAGO FROM SGC_TT_PAGOS WHERE INM_CODIGO = '$cod_inmueble'";
        //echo $sql;
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

    public function seleccionaIdpago($fec_pago, $cod_inmueble)
    {
        $sql       = "SELECT MAX(ID_PAGO)ID_PAGO FROM SGC_TT_PAGOS WHERE FECIND = '$fec_pago' AND INM_CODIGO = '$cod_inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

    public function IngresaMedioPago($medio, $importe, $id_pago)
    {
        $sql = "BEGIN SGC_P_MEDIO_PAGO('$medio','$importe','$id_pago',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "500");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "500");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function seleccionaIdmedpago($id_pago)
    {
        $sql       = "SELECT ID_MEDIO_PAGO FROM SGC_TT_MEDIOS_PAGO WHERE ID_PAGO = '$id_pago'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function IngresaDetMedioPago($id_det_medio, $desc_medio, $id_pago)
    {
        $sql       = "BEGIN SGC_P_DETALLEMEDPAGO('$id_det_medio','$desc_medio','$id_pago',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function seleccionaConcepto()
    {
        $sql = "SELECT COD_SERVICIO, DESC_SERVICIO
        FROM SGC_TP_SERVICIOS
        WHERE OTRO_RECAUDO = 'S'
        AND COD_SERVICIO IN (1,20)
        ORDER BY COD_SERVICIO";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaConceptoTotal()
    {
        $sql = "SELECT COD_SERVICIO, DESC_SERVICIO
        FROM SGC_TP_SERVICIOS
        WHERE OTRO_RECAUDO = 'S'
        ORDER BY COD_SERVICIO";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaConcepto2()
    {
        $sql = "SELECT COD_SERVICIO, DESC_SERVICIO
        FROM SGC_TP_SERVICIOS
        WHERE OTRO_RECAUDO = 'S' AND COD_SERVICIO NOT IN (1,20, 53) ORDER BY COD_SERVICIO";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaTarifa($q, $des_uso, $cod_pro)
    {
        $sql = "SELECT CONSEC_TARIFA, DESC_TARIFA
        FROM SGC_TP_TARIFAS
        WHERE COD_SERVICIO = '$q' AND COD_USO = '$des_uso' AND COD_PROYECTO = '$cod_pro' AND VISIBLE = 'S' ORDER BY CONSEC_TARIFA";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerDatosDiametro($cod_inmueble)
    {
        $sql = "SELECT I.COD_DIAMETRO
        FROM SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = '$cod_inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerDatosCalibre($cod_inmueble)
    {
        $sql = "SELECT M.COD_CALIBRE
        FROM SGC_TT_MEDIDOR_INMUEBLE M
        WHERE M.COD_INMUEBLE = '$cod_inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function obtenerValorReconex($calibre, $diametro, $uso)
    {
        $sql = "SELECT T.VALOR_TARIFA
        FROM SGC_TP_TARIFAS_RECONEXION T
        WHERE T.CODIGO_CALIBRE = '$calibre' AND T.CODIGO_DIAMETRO = '$diametro' AND T.CODIGO_USO = '$uso'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //   echo "false";
            return false;
        }

    }

    public function obtenerDescServicio($a1)
    {
        $sql       = "SELECT S.DESC_SERVICIO FROM SGC_TP_SERVICIOS S WHERE S.COD_SERVICIO = '$a1'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function IngresaPago($importe1, $referencia, $num_caja, $cod_inmueble, $origen, $coduser, $monto, $cod_pro, $deuda, $fecha, $pago_fac, $medio, $importe2, $importe3, $banco, $cheque, $numcard, $tarjeta, $numaproba, $observacion)
    {
        $sql = "BEGIN
        SGC_P_INGRESA_PAGO('$importe1','$referencia','$num_caja','$cod_inmueble','$origen','$coduser','$monto','$cod_pro','$deuda',to_date('$fecha','yyyy-mm-dd'),'$pago_fac','$medio','$importe2','$importe3','$banco','$cheque','$numcard','$tarjeta','$numaproba','$observacion',:PMSGRESULT,:PCODRESULT);
        COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, 10000);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult);
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function IngresaOtroRecaudo($cod_pro, $a1, $importe, $coduser, $referencia, $cod_inmueble, $id_caja, $fecha)
    {
        $sql = "BEGIN
        SGC_P_ING_OTROS_RECAUDOS('$cod_pro','$a1','$importe','$coduser','$referencia','$cod_inmueble','$id_caja',to_date('$fecha','yyyy-mm-dd'),:PMSGRESULT,:PCODRESULT);
        COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "500");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }


    public function getValRecByByInm($cod_inmueble){
        $cod_inmueble=addslashes($cod_inmueble);
        $sql="SELECT
                ROUND(TR.VALOR_TARIFA) VALOR_TARIFA
              FROM
                SGC_TP_TARIFAS_RECONEXION TR,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TP_ACTIVIDADES AC,
                SGC_TP_MEDIDORES M
              WHERE
                TR.CODIGO_DIAMETRO=I.COD_DIAMETRO AND
                I.CODIGO_INM='$cod_inmueble' AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                M.CODIGO_MED(+)=MI.COD_MEDIDOR AND
                AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                MI.FECHA_BAJA(+) IS NULL AND
                TR.CODIGO_CALIBRE=NVL(MI.COD_CALIBRE,0) AND
                TR.CODIGO_USO=AC.ID_USO AND
                TR.MEDIDOR=NVL(M.ESTADO_MED,'N') AND
                TR.PROYECTO=I.ID_PROYECTO
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


    public function seleccionaIdRec($cod_inmueble)
    {
        $sql = "SELECT MAX(CODIGO)ID_REC FROM SGC_TT_OTROS_RECAUDOS WHERE INMUEBLE = '$cod_inmueble'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function IngresaMedioRecaudo($medio, $importe, $id_rec)
    {
        $sql = "BEGIN SGC_P_MEDIO_REC('$medio','$importe','$id_rec',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function seleccionaIdMedRec($id_rec)
    {
        $sql       = "SELECT ID_MEDIO_RECAUDO FROM SGC_TT_MEDIOS_RECAUDO WHERE ID_OTRREC = '$id_rec'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function obtenerDatosCliente2($cod_inmueble)
    {
        $sql = "SELECT I.CODIGO_INM,
                       I.DIRECCION,
                       U.DESC_URBANIZACION,
                       C.CODIGO_CLI,
                       C.ALIAS,
                       I.ID_ESTADO,
                       A.DESC_ACTIVIDAD,
                       A.ID_USO,
                       I.ID_PROYECTO,
                       P.DESC_PROYECTO
                       --NVL(SUM(DF.VALOR - DF.VALOR_PAGADO), 0) DEUDA
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TP_URBANIZACIONES U,
                       SGC_TT_CONTRATOS C,
                       SGC_TP_ACTIVIDADES A,
                       SGC_TP_PROYECTOS P,
                       SGC_TT_FACTURA F,
                       SGC_TT_DETALLE_FACTURA DF
                 WHERE I.CONSEC_URB = U.CONSEC_URB
                   AND C.CODIGO_INM = I.CODIGO_INM
                   AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                   AND I.ID_PROYECTO = P.ID_PROYECTO
                   AND FEC_VCTO < SYSDATE
                   AND F.FEC_EXPEDICION IS NOT NULL
                   AND DF.FACTURA (+) = F.CONSEC_FACTURA
                   AND I.CODIGO_INM = F.INMUEBLE
                   AND I.CODIGO_INM = '$cod_inmueble'
                 GROUP BY I.CODIGO_INM, I.DIRECCION, U.DESC_URBANIZACION, C.CODIGO_CLI, C.ALIAS, I.ID_ESTADO, A.DESC_ACTIVIDAD, A.ID_USO, I.ID_PROYECTO, P.DESC_PROYECTO";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

    public function IngresaDetMedioRecaudo($id_det_medio, $desc_medio, $id_pago)
    {
        $sql       = "BEGIN SGC_P_DETALLEMEDREC('$id_det_medio','$desc_medio','$id_pago',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function obtenerFechaPago($id_pago)
    {
        $sql = "SELECT TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY HH24:MI:SS')FECHA_PAGO
    FROM SGC_TT_PAGOS P
    WHERE P.ID_PAGO = $id_pago";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerFechaPagoPromotora($id_pago)
    {
        $sql = "SELECT TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY HH24:MI:SS')FECHA_PAGO,
    E.DESC_ENTIDAD, PP.DESCRIPCION, C.NUM_CAJA
    FROM SGC_TT_PAGOS_TRANSITO P, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP,
    SGC_TP_CAJAS_PAGO C
    WHERE P.ID_ENTPAGO = E.COD_ENTIDAD
    AND P.ID_PTOPAGO = PP.ID_PUNTO_PAGO
    AND P.ID_CAJA = C.ID_CAJA
    AND P.ID_PAGO = $id_pago";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerFechaRecaudo($id_rec)
    {
        $sql = "SELECT TO_CHAR(R.FECHA,'DD/MM/YYYY HH24:MI:SS')FECHA_PAGO, U.LOGIN, S.DESC_SERVICIO
        FROM SGC_TT_OTROS_RECAUDOS R, SGC_TT_USUARIOS U, SGC_TP_SERVICIOS S
        WHERE R.USUARIO = U.ID_USUARIO
        AND R.CONCEPTO = S.COD_SERVICIO
        AND R.CODIGO = $id_rec";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerDatosPago($id_pago)
    {
        $sql = "SELECT F.PERIODO, ROUND(F.TOTAL) PENDIENTE, ROUND(PF.IMPORTE) PAGADO, PF.COMPROBANTE
    FROM SGC_TT_FACTURA F, SGC_TT_PAGO_FACTURAS PF
    WHERE F.CONSEC_FACTURA = PF.FACTURA
    AND PF.ID_PAGO = $id_pago
    ORDER BY PERIODO";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerDatosPagoRec($id_pago)
    {
        $sql = "SELECT S.DESC_SERVICIO, O.IMPORTE
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TP_SERVICIOS S
    WHERE O.CONCEPTO = S.COD_SERVICIO
    AND O.CODIGO = $id_pago";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerMaxPago($cod_inmueble)
    {
        $sql = "SELECT MAX(P.ID_PAGO) ID_PAGO
        FROM SGC_TT_PAGOS P
        WHERE P.INM_CODIGO = '$cod_inmueble'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }
    }

    public function obtenerMaxPagoPromotora($cod_inmueble)
    {
        $sql = "SELECT MAX(P.ID_PAGO) ID_PAGO
        FROM SGC_TT_PAGOS_TRANSITO P
        WHERE P.ID_INMUEBLE = '$cod_inmueble'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function obtenerCantidadDatosPago($id_pago)
    {
        $sql = "SELECT COUNT(F.PERIODO)CANTIDAD
    FROM SGC_TT_FACTURA F, SGC_TT_PAGO_FACTURAS PF
    WHERE F.CONSEC_FACTURA = PF.FACTURA
    AND PF.ID_PAGO = $id_pago";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }
    }

    public function obtenerCantidadDatosPagoPromotora($id_pago)
    {
        $sql = "SELECT COUNT(F.PERIODO)CANTIDAD
    FROM SGC_TT_FACTURA F, SGC_TT_PAGO_FACTURAS PF
    WHERE F.CONSEC_FACTURA = PF.FACTURA
    AND PF.ID_PAGO = $id_pago";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }
    }

    public function obtenerDescConcepto($concepto)
    {
        $sql = "SELECT DESC_SERVICIO
        FROM SGC_TP_SERVICIOS S
        WHERE S.COD_SERVICIO = '$concepto'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaDatosArchivo($archivo)
    {
        $sql = "SELECT COUNT(*) CANTIDAD FROM SGC_TT_PAGOS_TRANSITO WHERE NOM_ARCHIVO = '$archivo'";
        // echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }
    public function seleccionaDatosArchivoGc($archivo)
    {
        $sql = "SELECT COUNT(*) CANTIDAD FROM SGC_TT_PAGOS_GC WHERE NOM_ARCHIVO = '$archivo'";
        // echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function IngresaPagoTransito($inmueble, $punto, $fecha_emi, $medio, $valor, $archivo, $coduser)
    {
        $sql = "BEGIN SGC_P_ING_PAGOS_TRANSITO('$inmueble','$punto',TO_DATE('$fecha_emi','YYYY-MM-DD'),'$medio','$valor','$archivo','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }
    public function IngresaPagoGc($inmueble, $fecha_emi, $medio, $numCheque, $valor, $archivo, $coduser)
    {
        $sql = "BEGIN SGC_P_ING_PAGOS_GC('$inmueble',TO_DATE('$fecha_emi','yyyy-mm-dd'),'$medio','$numCheque','$valor','$archivo','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function BorraPagoTransito($archivo)
    {
        $sql = "BEGIN SGC_P_BORRA_PAGOS_TRANSITO('$archivo',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function BorraPagoGC($archivo)
    {
        $sql = "BEGIN SGC_P_BORRA_PAGOS_GC('$archivo',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function DatosPagosInmueble($cod_inmueble, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT P.ID_PAGO, TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS') FECHA_PAGO, TO_DATE(TO_CHAR(FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY HH24:MI:SS')FECPAGO, P.IMPORTE, P.ID_USUARIO, SUBSTR(P.FECIND,0,6) PERIODO, 'PAGO' TIPO
                        FROM SGC_TT_PAGOS P
                        WHERE P.INM_CODIGO = '$cod_inmueble' AND ESTADO NOT IN ('I')
                        UNION
                        SELECT P.CODIGO ID_PAGO, TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS') FECHA_PAGO,  TO_DATE(TO_CHAR(FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY HH24:MI:SS')FECPAGO, P.IMPORTE, P.USUARIO ID_USUARIO, TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO, 'OTROS REC' TIPO
                        FROM SGC_TT_OTROS_RECAUDOS P
                        WHERE P.INMUEBLE = '$cod_inmueble' AND ESTADO NOT IN ('I')
                        ORDER BY 3 DESC
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function seleccionaDatosReciboPago($id_pago)
    {
        $sql = "SELECT P.INM_CODIGO, U.LOGIN ID_USUARIO, E.DESC_ENTIDAD, V.DESCRIPCION, C.NUM_CAJA DESC_CAJA, P.INGRESO_BRUTO, P.DEUDA, P.CAMBIO,
        TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS')FECHA_PAGO
        FROM SGC_TT_PAGOS P, SGC_TT_USUARIOS U, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO V, SGC_TP_ENTIDAD_PAGO E
        WHERE U.ID_USUARIO = P.ID_USUARIO AND P.ID_CAJA = C.ID_CAJA AND C.ID_PUNTO_PAGO = V.ID_PUNTO_PAGO
        AND E.COD_ENTIDAD = V.ENTIDAD_COD
        AND P.ID_PAGO = '$id_pago'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //    echo "false";
            return false;
        }
    }


    public function seleccionaDatosReciboPagoPromotora($id_pago)
    {
        $sql = "SELECT P.ID_INMUEBLE, U.LOGIN ID_USUARIO, E.DESC_ENTIDAD, V.DESCRIPCION, C.NUM_CAJA DESC_CAJA, P.VALOR_IMPORTE,
        TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS')FECHA_PAGO
        FROM SGC_TT_PAGOS_TRANSITO P, SGC_TT_USUARIOS U, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO V, SGC_TP_ENTIDAD_PAGO E
        WHERE U.ID_USUARIO = P.USUARIO_REG AND P.ID_CAJA = C.ID_CAJA AND C.ID_PUNTO_PAGO = V.ID_PUNTO_PAGO
        AND E.COD_ENTIDAD = V.ENTIDAD_COD
        AND P.ID_PAGO = '$id_pago'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //    echo "false";
            return false;
        }
    }

    public function seleccionaDatosReciboPagoRec($id_pago)
    {
        $sql = "SELECT O.INMUEBLE, U.LOGIN ID_USUARIO, E.DESC_ENTIDAD, V.DESCRIPCION, C.NUM_CAJA DESC_CAJA, O.IMPORTE,
        TO_CHAR(O.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS')FECHA_PAGO
        FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_USUARIOS U, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO V, SGC_TP_ENTIDAD_PAGO E
        WHERE U.ID_USUARIO = O.USUARIO AND O.CAJA = C.ID_CAJA AND C.ID_PUNTO_PAGO = V.ID_PUNTO_PAGO
        AND E.COD_ENTIDAD = V.ENTIDAD_COD
        AND O.CODIGO = '$id_pago'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //    echo "false";
            return false;
        }

    }

    public function ObtieneMedioPagoRecibo($id_pago)
    {
        $sql = "SELECT M.ID_FORM_PAGO, F.DESCRIPCION
        FROM SGC_TT_MEDIOS_PAGO M, SGC_TP_FORMA_PAGO F
        WHERE M.ID_FORM_PAGO = F.CODIGO
        AND M.ID_PAGO = '$id_pago'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function ObtieneValoresPago($id_pago)
    {
        $sql = "SELECT P.CAMBIO, P.INGRESO_BRUTO, P.DEUDA
        FROM SGC_TT_PAGOS P
        WHERE P.ID_PAGO = '$id_pago'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }

    public function ObtieneClientePago($cod_inmueble)
    {
        $sql = "SELECT C.ALIAS
        FROM SGC_TT_CONTRATOS C
        WHERE C.CODIGO_INM = '$cod_inmueble' AND FECHA_FIN IS NULL";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function DatosPagosTransito($sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT P.ID_PAGO, P.CODIGO, P.ID_INMUEBLE, E.DESC_ENTIDAD, T.DESCRIPCION, P.ID_CAJA, TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY')FECHA_PAGO,
                        TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECHA_REGISTRO, P.VALOR_IMPORTE, P.OBSERVACION
                        FROM SGC_TT_PAGOS_TRANSITO P, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO T
                        WHERE P.ID_ENTPAGO = E.COD_ENTIDAD AND P.ID_PTOPAGO = T.COD_PTO_AAA
                        AND P.ESTADO_PAGO= 'P'
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function CantidadPagosTransito($fname, $tname, $where, $sort)
    {
        $sql = "SELECT COUNT($fname) CANTIDAD
                        FROM $tname
                        WHERE $where
                          $sort";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function ClientePagosTransito($inmueble)
    {
        $sql = "SELECT C.ALIAS, L.NOMBRE_CLI
        FROM SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L
        WHERE C.CODIGO_CLI = L.CODIGO_CLI
        AND C.CODIGO_INM = '$inmueble' AND C.FECHA_FIN IS NULL";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function AnulaPagosTransito()
    {
        $sql = "UPDATE SGC_TT_PAGOS_TRANSITO SET ESTADO_PAGO = 'E' WHERE ID_PAGO IN ($this->id_pago)";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }
    }

    public function AplicaPagosTransito($num_pago)
    {
        $sql = "SELECT T.ID_INMUEBLE, T.ID_ENTPAGO, T.ID_PTOPAGO, T.ID_CAJA, TO_CHAR(T.FECHA_PAGO,'DD/MM/YYYY')FECHA_PAGO, T.FORMA_PAGO, T.VALOR_IMPORTE,
                T.USUARIO_REG, I.ID_PROYECTO , TO_CHAR(T.FECHA_REGISTRO,'DD/MM/YYYY')FECHA_REGISTRO, I.ID_ESTADO
                FROM SGC_TT_PAGOS_TRANSITO T, SGC_TT_INMUEBLES I
                WHERE T.ID_INMUEBLE = I.CODIGO_INM
                AND ID_PAGO = $num_pago";
        //echo $sql.'<br>';
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //    echo "false";
            return false;
        }
    }

    public function CantidadDiasFestivos($fname, $tname, $where, $sort)
    {
        $sql = "SELECT COUNT($fname) CANTIDAD
                        FROM $tname
                            WHERE $where
                          $sort";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //   echo "false";
            return false;
        }

    }

    public function DatosDiasFestivos($sort, $start, $end, $where)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT D.AGNO, D.MES, D.DIA, TO_CHAR(D.FECHA,'DD/MM/YYYY')FECHA, D.CODIGO
                        FROM SGC_TP_DIAS_FESTIVOS D
                        WHERE $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //  echo "false";
            return false;
        }

    }

    public function EliminaDiaFeriado()
    {
        $sql = "DELETE FROM SGC_TP_DIAS_FESTIVOS WHERE CODIGO IN ($this->cod_fecha)";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //    echo "false";
            return false;
        }
    }

    public function IngresaDiaFeriado($agno, $mes, $dia, $fecha, $coduser)
    {
        $sql = "BEGIN SGC_P_ING_DIA_FERIADO('$agno','$mes','$dia',TO_DATE('$fecha','YYYY-MM-DD'),'$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function IngresaPagoTrans2($importe, $id_caja, $inmueble, $origen, $usuariopago, $importe2, $cod_pro, $deuda, $fecpago, $estado)
    {
        $sql = "BEGIN SGC_P_INGRESA_PAGO_TRANS2('$importe','$id_caja','$inmueble','$origen','$usuariopago','$importe2','$cod_pro','$deuda',TO_DATE('$fecpago','DD/MM/YYYY'),'$estado',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "123");
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, "123");
        $bandera = oci_execute($resultado);

        if ($bandera) {
            if ($this->codresult == 0) {
                return true;
            } else {
                oci_close($this->_db);
                return false;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function ActualizaAplicaPagosTransito($num_pago, $observacion, $estado)
    {
        $sql = "UPDATE SGC_TT_PAGOS_TRANSITO SET ESTADO_PAGO = '$estado', OBSERVACION = '$observacion' WHERE ID_PAGO = $num_pago";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }
    }

    public function obtieneLogin($id_usr)
    {
        $sql = "SELECT LOGIN FROM SGC_TT_USUARIOS USR WHERE ID_USUARIO='$id_usr'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            if (oci_fetch($resultado)) {
                return oci_result($resultado, "LOGIN");
            }
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }
    }

    ///////////////////// obtiene los pagos en una caja y en un rango de fechas

    public function getPagosByFechaCaja($fechIni, $fechFin, $idCaja, $proyecto)
    {
        $sql = "SELECT
                P.INM_CODIGO INMUEBLE,
                P.ID_PAGO,
                (SELECT DISTINCT C.ALIAS FROM SGC_TT_CONTRATOS C WHERE C.FECHA_FIN IS NULL AND C.CODIGO_INM = P.INM_CODIGO) NOMBRE_CLIENTE,
                --C.ALIAS NOMBRE_CLIENTE,
                P.FECHA_PAGO,
                MP.VALOR IMPORTE,
                --(SELECT  SUM( F.TOTAL) FROM SGC_TT_FACTURA F, SGC_TT_PAGO_DETALLEFAC PD WHERE PD.FACTURA = F.CONSEC_FACTURA AND P.ID_PAGO = PD.PAGO) FACTURADO,
                (SELECT  SUM( F.TOTAL) FROM SGC_TT_FACTURA F, SGC_TT_PAGO_FACTURAS PD WHERE PD.FACTURA = F.CONSEC_FACTURA AND P.ID_PAGO = PD.ID_PAGO) FACTURADO,
                U.LOGIN,
                FP.DESCRIPCION
               FROM SGC_TT_PAGOS P, SGC_TT_USUARIOS U, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP, SGC_TP_CAJAS_PAGO CP,
               SGC_TP_PUNTO_PAGO PP--,SGC_TT_CONTRATOS C
              WHERE
                P.ID_CAJA = CP.ID_CAJA AND
                CP.ID_PUNTO_PAGO = PP.ID_PUNTO_PAGO AND
                P.ID_USUARIO = U.ID_USUARIO AND
                P.ID_PAGO = MP.ID_PAGO AND
                MP.ID_FORM_PAGO = FP.CODIGO AND
                --C.CODIGO_INM = P.INM_CODIGO AND
                P.ID_CAJA=$idCaja AND
                P.ESTADO='A' AND
                P.ACUEDUCTO = '$proyecto' AND
                P.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fechFin 23:59:59','YYYY-MM-DD hh24:mi:ss') --AND
                --C.FECHA_FIN IS NULL
               ORDER BY 2 ASC,1 DESC";
        /* $sql="SELECT
        P.INM_CODIGO INMUEBLE,
        P.ID_PAGO,
        P.FECHA_PAGO,
        MP.VALOR IMPORTE,
        U.LOGIN,
        FP.DESCRIPCION
        FROM SGC_TT_PAGOS P, SGC_TT_USUARIOS U, SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP
        WHERE
        P.ID_USUARIO = U.ID_USUARIO AND
        P.ID_PAGO = MP.ID_PAGO AND
        MP.ID_FORM_PAGO = FP.CODIGO AND
        P.ID_CAJA=$idCaja AND
        P.ESTADO='A' AND
        P.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fechFin 23:59:59','YYYY-MM-DD hh24:mi:ss')
        ORDER BY 2 DESC,1 DESC";*/
        //echo $sql."<br/>";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        oci_close($this->_db);
        if ($bandera) {
            $i = 0;
            while ($row = oci_fetch_array($resultado, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $con[$i] = $row;
                $i++;
            }
            return json_encode($con);
        } else {
            return false;
        }

    }

    ///////////////////// obtiene los pagos en una caja y en un rango de fechas

    public function getRecaudosByFechaCaja($fechIni, $fechFin, $idCaja, $proyecto)
    {
        $sql = "SELECT
                P.INMUEBLE,
                P.CODIGO ID_PAGO,
                --C.ALIAS NOMBRE_CLIENTE,
                (SELECT DISTINCT C.ALIAS FROM SGC_TT_CONTRATOS C WHERE C.FECHA_FIN IS NULL AND C.CODIGO_INM = P.INMUEBLE) NOMBRE_CLIENTE,
                P.FECHA_PAGO,
                MP.VALOR IMPORTE,
                '0' FACTURADO,
                U.LOGIN,
                FP.DESCRIPCION
              FROM SGC_TT_OTROS_RECAUDOS P, SGC_TT_USUARIOS U, SGC_TT_MEDIOS_RECAUDO MP, SGC_TP_FORMA_PAGO FP, SGC_TP_CAJAS_PAGO CP,
               SGC_TP_PUNTO_PAGO PP--,SGC_TT_CONTRATOS C
              WHERE
              P.CAJA = CP.ID_CAJA AND
                CP.ID_PUNTO_PAGO = PP.ID_PUNTO_PAGO AND
                  P.USUARIO = U.ID_USUARIO AND
                P.CODIGO = MP.ID_OTRREC AND
                MP.ID_FORM_PAGO = FP.CODIGO AND
                --C.CODIGO_INM = P.INMUEBLE AND
                P.CAJA=$idCaja AND
                P.ACUEDUCTO = '$proyecto' AND
                P.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fechFin 23:59:59','YYYY-MM-DD hh24:mi:ss') AND
                P.ESTADO IN ('A','T') 
                --AND C.FECHA_FIN IS NULL
              ORDER BY 2 ASC,1 DESC";
        /* $sql = "SELECT
        P.INMUEBLE,
        P.CODIGO ID_PAGO,
        P.FECHA_PAGO,
        MP.VALOR IMPORTE,
        U.LOGIN,
        FP.DESCRIPCION
        FROM
        SGC_TT_OTROS_RECAUDOS P, SGC_TT_USUARIOS U, SGC_TT_MEDIOS_RECAUDO MP, SGC_TP_FORMA_PAGO FP
        WHERE
        P.USUARIO = U.ID_USUARIO AND
        P.CODIGO = MP.ID_OTRREC AND
        MP.ID_FORM_PAGO = FP.CODIGO AND
        P.CAJA=$idCaja AND
        P.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fechFin 23:59:59','YYYY-MM-DD hh24:mi:ss') AND
        P.ESTADO IN ('A','T')
        ORDER BY 2 DESC,1 DESC";*/
        //echo $sql."<br/>";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        oci_close($this->_db);
        if ($bandera) {
            $i = 0;
            while ($row = oci_fetch_array($resultado, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $con[$i] = $row;
                $i++;
            }
            return json_encode($con);
        } else {
            return false;
        }

    }

    public function obtieneDeuda($cod_inmueble)
    {
        $sql = "SELECT NVL(SUM(F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO),0) DEUDA, COUNT(F.CONSEC_FACTURA)FACPEND
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I
        WHERE F.FACTURA_PAGADA = 'N'
        AND F.FEC_EXPEDICION IS NOT NULL
        AND I.CODIGO_INM = F.INMUEBLE
        AND I.CODIGO_INM = $cod_inmueble";
        // echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );
        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }


    public function obtieneDiferidoCorte($cod_inmueble)
    {
        $sql = "select
     DET.VALOR
    from SGC_TT_DIFERIDOS DIF, SGC_TT_DETALLE_DIFERIDOS DET
        WHERE DET.COD_DIFERIDO=DIF.CODIGO AND
      DIF.CONCEPTO=53 AND
      DIF.INMUEBLE=$cod_inmueble AND
      DET.FECHA_PAGO IS NULL AND
      DET.NUM_CUOTA=1";
        // echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );
        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

    public function obtieneDeudaNumFact($cod_inmueble,$numfact)
    {
        $sql = "select sum(deuda) DEUDA from(
SELECT NVL(F.TOTAL - F.TOTAL_PAGADO - F.TOTAL_CREDITO + F.TOTAL_DEBITO,0) DEUDA
FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I
WHERE F.FACTURA_PAGADA = 'N'
  AND F.FEC_EXPEDICION IS NOT NULL
  AND I.CODIGO_INM = F.INMUEBLE
  AND I.CODIGO_INM = $cod_inmueble
    order by PERIODO asc)
where rownum<=$numfact";
        // echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );
        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

    public function obtieneProyecto($cod_inmueble)
    {
        $sql = "SELECT ID_PROYECTO
        FROM SGC_TT_INMUEBLES
        WHERE CODIGO_INM = '$cod_inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            // echo "false";
            return false;
        }

    }
}
