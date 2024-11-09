<?php
include_once "../../clases/class.conexion.php";
class PQRs extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getcodresult()
    {
        return $this->codresult;
    }

    public function getmsgresult()
    {
        return $this->msgresult;
    }

    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function getSolCerradas($proyecto, $nom_cliente, $codigo_pqr, $doc_cliente,$tipo_sol)
    {
        /*************************************
         *
         *    @Author : Allendy Valdez Pillier
         *    @Fecha  : 14/02/2017
         *
         *************************************/

        // Esta funcion nos devuelve los datos de una busqueda
        $proyecto    = trim($proyecto);
        $nom_cliente = trim($nom_cliente);
        $codigo_pqr  = trim($codigo_pqr);
        $doc_cliente = trim($doc_cliente);
        $nom_cliente = trim($nom_cliente);
        $tipo_sol = trim($tipo_sol);

        if ($codigo_pqr == 0) {
            $codigo_pqr = '';
        }
        $com = "";
        if (trim($codigo_pqr) != '') {
            $com .= " and codigo_pqr = '$codigo_pqr' ";
        }

        if (trim($nom_cliente) != '') {
            $com .= " and nom_cliente LIKE '%$nom_cliente%' ";
        }
        if (trim($doc_cliente) != '') {
            $com .= " and doc_cliente LIKE '%$doc_cliente%' ";
        }

        $query = "SELECT
                        C.codigo_pqr,
                        C.fecha_pqr,
                        nvl(C.nom_cliente, ' '),
                        nom_cliente,
                        nvl(C.doc_cliente, ' ') doc_cliente,
                        C.cod_entidad,
                        C.COD_CAJA,
                        C.fecha_registro,
                        C.fecha_cierre,
                        (select F1.RESPUESTA from sgc_tt_pqr_flujo_cat f1 where f1.codigo_pqr = C.codigo_pqr AND f1.AREA_ACTUAL =1 AND F1.CONSECUTIVO = (SELECT MAX(F2.CONSECUTIVO) FROM sgc_tt_pqr_flujo_cat f2 WHERE F2.CODIGO_PQR = f1.codigo_pqr AND f2.AREA_ACTUAL =1 AND F2.RESPUESTA IS NOT NULL)) RESPUESTA
                    from
                        sgc_tt_pqrs_catastrales c
                    where C.proyecto = '$proyecto' AND
                          c.MOTIVO_PQR='$tipo_sol' 
                          $com
                        order by c.nom_cliente";

        // Preparar la sentencia
        $parse = oci_parse($this->_db, $query);
        if (!$parse) {
            $e = oci_error($this->_db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        // Realizar la lógica de la consulta
        $r = oci_execute($parse);
        if (!$r) {
            $e = oci_error($parse);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //$result = oci_fetch_array($parse);
        oci_fetch_all($parse, $result);
        oci_close($this->_db);
        oci_free_statement($parse);
        return $result;
    }

    public function seleccionaUser($coduser)
    {
        $sql = "SELECT C.ID_CAJA, C.NUM_CAJA, P.ID_PUNTO_PAGO, P.DESCRIPCION, E.COD_ENTIDAD, E.DESC_ENTIDAD, (U.NOM_USR||' '||U.APE_USR) NOMBRE
        FROM SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO P, SGC_TP_ENTIDAD_PAGO E, SGC_TT_USUARIOS U
        WHERE C.ID_PUNTO_PAGO = P.ID_PUNTO_PAGO AND P.ENTIDAD_COD = E.COD_ENTIDAD
        AND C.ID_USUARIO = U.ID_USUARIO AND C.ID_USUARIO = '$coduser'";
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

    public function seleccionaUserAcuerdo($coduser)
    {
        $sql = "SELECT C.ID_CAJA, C.NUM_CAJA, P.ID_PUNTO_PAGO, P.DESCRIPCION, E.COD_ENTIDAD, E.DESC_ENTIDAD, (U.NOM_USR||' '||U.APE_USR) NOMBRE, U.ID_USUARIO
        FROM SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO P, SGC_TP_ENTIDAD_PAGO E, SGC_TT_USUARIOS U
        WHERE C.ID_PUNTO_PAGO = P.ID_PUNTO_PAGO AND P.ENTIDAD_COD = E.COD_ENTIDAD
        AND C.ID_USUARIO = U.ID_USUARIO AND C.ID_USUARIO = '$coduser'";
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

    public function obtenerDatosCliente($cod_inmueble)
    {
        $sql = "SELECT I.CODIGO_INM, I.DIRECCION, U.DESC_URBANIZACION, C.CODIGO_CLI, C.ALIAS, I.ID_ESTADO, A.DESC_ACTIVIDAD, A.ID_USO, I.ID_PROYECTO, P.DESC_PROYECTO,
        I.ID_ZONA, I.UNIDADES_HAB, S.CONSUMO_MINIMO, NVL(C.EMAIL, L.EMAIL) EMAIL, L.DOCUMENTO, L.TELEFONO, SC.ID_GERENCIA,I.ID_PROCESO, I.CATASTRO, L.NOMBRE_CLI
        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TP_ACTIVIDADES A, SGC_TP_PROYECTOS P,
        SGC_TT_SERVICIOS_INMUEBLES S, SGC_TT_CLIENTES L, SGC_TP_SECTORES SC
        WHERE I.CONSEC_URB = U.CONSEC_URB(+)
        AND I.ID_SECTOR = SC.ID_SECTOR
        AND S.COD_INMUEBLE =I.CODIGO_INM
        AND L.CODIGO_CLI(+) = C.CODIGO_CLI
        AND S.COD_SERVICIO IN(1,3)
        AND C.CODIGO_INM(+) = I.CODIGO_INM AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND I.ID_PROYECTO = P.ID_PROYECTO AND I.CODIGO_INM = '$cod_inmueble'";
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
                       /*NVL(SUM(DF.VALOR - DF.VALOR_PAGADO), 0) DEUDA */
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TP_URBANIZACIONES U,
                       SGC_TT_CONTRATOS C,
                       SGC_TP_ACTIVIDADES A,
                       SGC_TP_PROYECTOS P,
                       SGC_TT_FACTURA F,
                       SGC_TT_DETALLE_FACTURA DF
                 WHERE I.CONSEC_URB = U.CONSEC_URB(+)
                   AND C.CODIGO_INM(+) = I.CODIGO_INM
                   AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                   AND I.ID_PROYECTO = P.ID_PROYECTO
                   AND C.FECHA_FIN IS NULL
                   --AND F.FEC_EXPEDICION IS NOT NULL
                   AND DF.FACTURA (+) = F.CONSEC_FACTURA
                   AND I.CODIGO_INM = F.INMUEBLE(+)
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
            echo "false";
            return false;
        }

    }

    public function CantidadFacHis($fname, $tname, $where, $sort)
    {
        $resultado = oci_parse($this->_db, "

                SELECT COUNT(*)CANTIDAD  FROM SGC_TT_FACTURA F
                WHERE FACTURA_PAGADA = 'N'
                 $where");

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

    public function CantidadFacHisEnt($fname, $tname, $where, $sort)
    {
        $resultado = oci_parse($this->_db, "

                SELECT COUNT(*)CANTIDAD  FROM SGC_TT_FACTURA F
                WHERE F.FACTURA_PAGADA = 'S'
                 $where");

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

    public function TodosFacHis($where, $sort, $start, $end, $inmueble)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT  PERIODO, CONSEC_FACTURA, TOTAL, TO_CHAR(FEC_VCTO,'DD/MM/YYYY')FECHAVCO
                        FROM SGC_TT_FACTURA F
                        WHERE FACTURA_PAGADA = 'N'
                        $where
                         $sort
                        )a WHERE  ROWNUM<$start
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

    public function TodosFacHisEnt($where, $sort, $start, $end, $inmueble)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT  F.PERIODO, F.CONSEC_FACTURA, F.TOTAL_PAGADO, TO_CHAR(F.FECHA_PAGO,'DD/MM/YYYY')FECHA_PAGO, (U.NOM_USR||' '||U.APE_USR)OPERARIO
                        FROM SGC_TT_FACTURA F, SGC_TT_REGISTRO_ENTREGA_FAC E, SGC_TT_USUARIOS U
                        WHERE F.INMUEBLE = E.COD_INMUEBLE(+)
                        AND F.PERIODO = E.PERIODO(+)
                        AND E.USR_EJE = U.ID_USUARIO(+)
                        AND FACTURA_PAGADA = 'S'
                        $where
                         $sort
                        )a WHERE  ROWNUM<$start
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

    public function totalfacven($cod_inmueble)
    {
        $sql = "SELECT NVL(SUM(F.TOTAL-F.TOTAL_PAGADO-F.TOTAL_CREDITO+TOTAL_DEBITO),0) DEUDA, COUNT (F.FACTURA_PAGADA) FACPEND
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I
        WHERE F.FACTURA_PAGADA = 'N'
        AND F.FEC_EXPEDICION IS NOT NULL
        AND I.CODIGO_INM = F.INMUEBLE
        AND I.CODIGO_INM = '$cod_inmueble'";
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

    public function totalfacent($cod_inmueble)
    {
        $sql = "SELECT COUNT (F.FACTURA_PAGADA) FACENT
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I
        WHERE F.FACTURA_PAGADA = 'S'
        AND I.CODIGO_INM = F.INMUEBLE
        AND I.CODIGO_INM = '$cod_inmueble'";
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

    public function facpend($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT F.CONSEC_FACTURA, F.TOTAL, (F.TOTAL - F.TOTAL_PAGADO)PENDIENTE, F.PERIODO, F.FEC_EXPEDICION,concat(NU.ID_NCF,F.NCF_CONSEC) ncf FROM SGC_TT_FACTURA F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
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

    public function urlfotomant($inmueble)
    {
        $sql = "SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_MANTENIMIENTO
        WHERE ID_INMUEBLE='$inmueble'";
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

    public function urlfotolect($inmueble)
    {
        $sql = "SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_LECTURA
        WHERE ID_INMUEBLE='$inmueble'";
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

    public function urlfotofact($inmueble)
    {
        $sql = "SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_FACTURA
        WHERE ID_INMUEBLE='$inmueble'";
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

    public function urlfotocorte($inmueble)
    {
        $sql = "SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_CORTE
        WHERE ID_INMUEBLE='$inmueble'";
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

    public function obtenerDatosMedidor($cod_inmueble)
    {
        $sql = "SELECT ME.DESC_MED, E.DESC_EMPLAZAMIENTO, C.DESC_CALIBRE, M.SERIAL, EM.DESCRIPCION, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FEC_INSTAL
        FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES ME, SGC_TP_EMPLAZAMIENTO E, SGC_TP_CALIBRES C, SGC_TP_ESTADOS_MEDIDOR EM
        WHERE M.COD_MEDIDOR = ME.CODIGO_MED(+)
        AND M.COD_EMPLAZAMIENTO = E.COD_EMPLAZAMIENTO(+)
        AND M.COD_CALIBRE = C.COD_CALIBRE(+)
        AND M.ESTADO_MEDIDOR = EM.CODIGO(+)
        AND M.COD_INMUEBLE = '$cod_inmueble'";
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

    public function seleccionaMedioRecep()
    {
        $sql = "SELECT ID_MEDIO_REC, DESC_MEDIO_REC
        FROM SGC_TP_MEDIO_RECEPCION ORDER BY 1";
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

    public function seleccionaTipoPqr()
    {
        $sql = "SELECT ID_TIPO_RECLAMO, DESC_TIPO_RECLAMO
        FROM SGC_TP_TIPOS_RECLAMOS WHERE CLASIFICACION = 'P' $where
        ORDER BY ID_TIPO_RECLAMO";
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

    public function seleccionaTipoPqrCat()
    {
        $sql = "SELECT ID_TIPO_RECLAMO, DESC_TIPO_RECLAMO
        FROM SGC_TP_TIPOS_RECLAMOS WHERE CLASIFICACION = 'P' AND ID_TIPO_RECLAMO = 2";
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

    public function seleccionaMotivoPqr($tipo_pqr)
    {
        $sql="SELECT DISTINCT MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC
              FROM SGC_TP_MOTIVO_RECLAMOS MR,SGC_TP_TIPOS_RECLAMOS TR
              WHERE TR.ID_TIPO_RECLAMO = MR.ID_TIPO_RECLAMO AND
                    TR.ID_TIPO_RECLAMO = $tipo_pqr 
              ORDER BY ID_MOTIVO_REC";
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

    public function seleccionaMotivosPqr()
    {
        $sql = "SELECT DISTINCT ID_MOTIVO_REC, DESC_MOTIVO_REC
                 FROM SGC_TP_MOTIVO_RECLAMOS
                WHERE ID_TIPO_RECLAMO in(1,2)
                ORDER BY ID_MOTIVO_REC ";

        //echo $sql;
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

    public function seleccionaMotivoPqrporTipo($tipo_pqr)
    {
        $sql="SELECT DISTINCT MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC
              FROM SGC_TP_MOTIVO_RECLAMOS MR,SGC_TP_TIPOS_RECLAMOS TR
              WHERE TR.ID_TIPO_RECLAMO = MR.ID_TIPO_RECLAMO AND
                    TR.ID_TIPO_RECLAMO = $tipo_pqr
              ORDER BY ID_MOTIVO_REC";
        //echo $sql;
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

    public function seleccionaMotivoPqrCat()
    {
        $sql = "SELECT DISTINCT ID_MOTIVO_REC, DESC_MOTIVO_REC
                 FROM SGC_TP_MOTIVO_RECLAMOS
                 WHERE ID_MOTIVO_REC IN  (64,101) AND GERENCIA = 'E'";
        //echo $sql;
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

    public function IngresaPqr($fecha, $cod_inmueble, $nom_cli, $ced_cli, $direccion, $tel_cli, $mail_cli, $medio, $tipo, $motivo, $fecha_res, $descripcion, $cod_ent, $id_punto, $num_caja, $coduser, $ger_inm, $area_res, $tel_cli_nuevo, $mail_cli_nuevo)
    {
        addcslashes($fecha);
        addcslashes($cod_inmueble);
        addslashes($nom_cli);
        addcslashes($ced_cli);
        addcslashes($direccion);
        addcslashes($tel_cli);
        addcslashes($mail_cli);
        addcslashes($medio);
        addcslashes($tipo);
        addcslashes($motivo);
        addcslashes($fecha_res);
        addcslashes($descripcion);
        addcslashes($cod_ent);
        addcslashes($id_punto);
        addcslashes($num_caja);
        addcslashes($coduser);
        addcslashes($ger_inm);
        addcslashes($area_res);
        addcslashes($tel_cli_nuevo);
        addcslashes($mail_cli_nuevo);

        $sql = "BEGIN SGC_P_INGRESA_PQR(SYSDATE,'$cod_inmueble','$nom_cli','$ced_cli','$direccion','$tel_cli','$mail_cli','$medio','$tipo','$motivo',TO_DATE('$fecha_res','DD/MM/YYYY hh24:mi:ss'),'$descripcion','$cod_ent','$id_punto','$num_caja','$coduser','$ger_inm','$area_res','$tel_cli_nuevo', '$mail_cli_nuevo',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, "1000");
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

    public function seleccionaAcueducto()
    {
//colocar $usr cuando se migren todos los reportes
        $sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
        FROM SGC_TP_PROYECTOS
        ORDER BY SIGLA_PROYECTO";
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

    public function actualizaQuejaSugerencia($cod_form,$cod_pqr){
        $cod_form=addslashes($cod_form);
        $cod_pqr=addslashes($cod_pqr);
        $sql="UPDATE TBL_FORM_QUEJAS_SUGERENCIAS@LK_CAASDENLINEA SET CODIGO_PQR=$cod_pqr WHERE CODIGO_FORMULARIO=$cod_form";

        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);

        if($bandera){
            return true;
        }
        else{
            $error=oci_error($resultado);
            oci_close($this->_db);
            return $error['message'];
        }
    }

    public function seleccionaDepartamento($area_user)
    {
        $sql = "SELECT ID_AREA, DESC_AREA
        FROM SGC_TP_AREAS
        WHERE ID_AREA NOT IN ('$area_user')
        AND   RECIBE_PQR = 'S'
        ORDER BY DESC_AREA";
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

    public function seleccionaDepartamentoConsulta()
    {
        $sql = "SELECT A.ID_AREA, A.DESC_AREA
        FROM SGC_TP_AREAS A
       WHERE A.RECIBE_PQR = 'S'
       ORDER BY A.DESC_AREA";
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

    public function seleccionaDepartamentoConsultaCat()
    {
        $sql = "SELECT ID_AREA, DESC_AREA
        FROM SGC_TP_AREAS
        WHERE ID_AREA = 1";
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

    public function obtieneAreaUsuario($coduser)
    {
        $sql = "SELECT A.ID_AREA
        FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C, SGC_TP_AREAS A
        WHERE U.ID_CARGO = C.ID_CARGO
        AND C.ID_AREA = A.ID_AREA
        AND C.ID_CARGO IN ('301','500','501','300')
        AND U.ID_USUARIO = '$coduser'";
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

    public function CantidadRegistrosPqr($proyecto, $departamento, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $codpqr, $tipo_pqr, $motivo_pqr, $where)
    {
        $sql = "SELECT SUM(CANTIDAD)CANTIDAD FROM(
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
        $where";
        if ($departamento != '') {
            $sql .= " AND F.AREA_ACTUAL = $departamento";
        }

        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        if ($codpqr != '') {
            $sql .= " AND P.CODIGO_PQR = '$codpqr'";
        }

        if ($secini != '' && $secfin != '') {
            $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
        }

        if ($rutini != '' && $rutfin != '') {
            $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
        }

        if ($tipo_pqr != '') {
            $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        }

        if ($motivo_pqr != '') {
            $sql .= " AND P.MOTIVO_PQR = '$motivo_pqr'";
        }

        if ($cod_inmueble != '') {
            $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
        }

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " UNION
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO_CAT F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND M.GERENCIA = 'E'
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
        $where";
        if ($departamento != '') {
            $sql .= " AND F.AREA_ACTUAL = $departamento";
        }

        if ($proyecto != '') {
            $sql .= " AND P.PROYECTO = '$proyecto'";
        }

        if ($codpqr != '') {
            $sql .= " AND P.CODIGO_PQR = '$codpqr'";
        }

        if ($cod_inmueble != '') {
            $sql .= " AND P.MOTIVO_PQR <> 64";
        }

        if ($tipo_pqr != '') {
            $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        }

        if ($motivo_pqr != '') {
            $sql .= " AND P.MOTIVO_PQR = '$motivo_pqr'";
        }

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " )";
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

    public function obtenerDatosPQRs($proyecto, $departamento, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $codpqr, $start, $end, $tipo_pqr, $motivo_pqr, $where)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, P.COD_INMUEBLE, (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO,
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY HH24:MI:SS')FECHAMAX, I.ID_PROCESO, A.DESC_AREA,
        CASE WHEN (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) <= 0 THEN 0 ELSE (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) END PORVENCER,
        CASE WHEN (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) >= 0 THEN 0 ELSE (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL))END VENCIDOS, P.CERRADO
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
        $where";
        if ($departamento != '') {
            $sql .= " AND F.AREA_ACTUAL = $departamento";
        }

        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        if ($codpqr != '') {
            $sql .= " AND P.CODIGO_PQR = '$codpqr'";
        }

        if ($secini != '' && $secfin != '') {
            $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
        }

        if ($rutini != '' && $rutfin != '') {
            $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
        }

        if ($cod_inmueble != '') {
            $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
        }

        if ($tipo_pqr != '') {
            $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        }

        if ($motivo_pqr != '') {
            $sql .= " AND P.MOTIVO_PQR = '$motivo_pqr'";
        }

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= "UNION
        SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, to_number(111111), (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO,
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECHAMAX, '00000000000', A.DESC_AREA,
        CASE WHEN (P.FECHA_MAX_RESOL - SYSDATE) <= 0 THEN 0 ELSE ROUND(P.FECHA_MAX_RESOL - SYSDATE) END PORVENCER,
        CASE WHEN (SYSDATE - P.FECHA_MAX_RESOL) <= 0 THEN 0 ELSE ROUND(SYSDATE - P.FECHA_MAX_RESOL)END VENCIDOS, P.CERRADO
        FROM SGC_TT_PQRS_catastrales P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO_CAT F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND M.GERENCIA = 'E'
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
        $where";
        if ($departamento != '') {
            $sql .= " AND F.AREA_ACTUAL = $departamento";
        }

        if ($proyecto != '') {
            $sql .= " AND P.PROYECTO = '$proyecto'";
        }

        if ($codpqr != '') {
            $sql .= " AND P.CODIGO_PQR = '$codpqr'";
        }

        if ($cod_inmueble != '') {
            $sql .= " AND P.MOTIVO_PQR <> 64";
        }

        if ($tipo_pqr != '') {
            $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        }

        if ($motivo_pqr != '') {
            $sql .= " AND P.MOTIVO_PQR = '$motivo_pqr'";
        }

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " ORDER BY 1 ASC )a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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

    public function obtenerDatosPQRsCat($departamento, $fecini, $fecfin, $codpqr, $start, $end, $where)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (SELECT O.CODIGO_PQR, TO_CHAR(O.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, TO_NUMBER(111111)COD_INMUEBLE, (O.MOTIVO_PQR||'-'||MR.DESC_MOTIVO_REC)MOTIVO,
        O.COD_ENTIDAD, UR.LOGIN, TO_CHAR(O.FECHA_MAX_RESOL,'DD/MM/YYYY')FECHAMAX, '00000000000' ID_PROCESO, AR.DESC_AREA,
        CASE WHEN (O.FECHA_MAX_RESOL - SYSDATE) <= 0 THEN 0 ELSE ROUND(O.FECHA_MAX_RESOL - SYSDATE) END PORVENCER,
        CASE WHEN (SYSDATE - O.FECHA_MAX_RESOL) <= 0 THEN 0 ELSE ROUND(SYSDATE - O.FECHA_MAX_RESOL)END VENCIDOS
        FROM SGC_TT_PQRS_CATASTRALES O, SGC_TP_MOTIVO_RECLAMOS MR, SGC_TT_USUARIOS UR, SGC_TT_PQR_FLUJO_CAT FC, SGC_TP_AREAS AR
        WHERE O.MOTIVO_PQR = MR.ID_MOTIVO_REC
        AND MR.GERENCIA IN ('E')
        AND UR.ID_USUARIO = O.USER_RECIBIO_PQR
        AND O.CODIGO_PQR = FC.CODIGO_PQR
        AND AR.ID_AREA = FC.AREA_ACTUAL
        AND O.CERRADO = 'N'
        AND FC.FECHA_SALIDA IS NULL  $where";
        if ($departamento != '') {
            $sql .= " AND FC.AREA_ACTUAL = $departamento";
        }

        if ($codpqr != '') {
            $sql .= " AND O.CODIGO_PQR = '$codpqr'";
        }

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND O.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " ORDER BY TO_DATE(TO_CHAR(O.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY hh24:mi:ss') DESC

        )a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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

    public function obtieneDatosPqr($cod_pqr)
    {
        $sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, F.AREA_ACTUAL, F.ORDEN, P.GERENCIA
        FROM SGC_TT_PQRS P, SGC_TT_PQR_FLUJO F
        WHERE P.CODIGO_PQR = F.CODIGO_PQR
        AND F.FECHA_SALIDA IS NULL
        AND P.CODIGO_PQR = '$cod_pqr'
        union
        SELECT TO_NUMBER(111111)COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, F.AREA_ACTUAL, F.ORDEN, 'E' GERENCIA
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TT_PQR_FLUJO_CAT F
        WHERE P.CODIGO_PQR = F.CODIGO_PQR
        AND F.FECHA_SALIDA IS NULL
        AND P.CODIGO_PQR = '$cod_pqr'";
        //echo $sql;
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

    public function IngresaFlujoPqr($cod_inm, $cod_pqr, $resolucion, $area_act, $orden, $coduser, $area_res)
    {
        $sql = "BEGIN SGC_P_INGRESA_FLUJO_PQR('$cod_inm','$cod_pqr','$resolucion','$area_act','$orden','$coduser','$area_res',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function ModificaFlujoPqr($cod_inm, $cod_pqr, $tipo, $motivo, $ger_inm, $area_res, $coduser)
    {
        $sql = "BEGIN SGC_P_MODIFICA_PQR('$cod_inm','$cod_pqr','$tipo','$motivo','$ger_inm','$area_res','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function obtieneResolucionesPqrs($cod_pqr)
    {
        $sql = "SELECT A.DESC_AREA, F.RESPUESTA, U.LOGIN
        FROM SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A, SGC_TT_USUARIOS U
        WHERE A.ID_AREA = F.AREA_ACTUAL
        AND U.ID_USUARIO = F.USUARIO_RESPUESTA
        AND F.CODIGO_PQR = $cod_pqr
        AND F.FECHA_SALIDA IS NOT NULL
        UNION
        SELECT A.DESC_AREA, FC.RESPUESTA, U.LOGIN
        FROM SGC_TT_PQR_FLUJO_CAT FC, SGC_TP_AREAS A, SGC_TT_USUARIOS U
        WHERE A.ID_AREA = FC.AREA_ACTUAL
        AND U.ID_USUARIO = FC.USUARIO_RESPUESTA
        AND FC.CODIGO_PQR = $cod_pqr
        AND FC.FECHA_SALIDA IS NOT NULL";
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

    public function obtieneMotivoResolucionPqrs($gerencia)
    {
        $sql = "SELECT M.ID_MOTIVO_REC, M.DESC_MOTIVO_REC
        FROM SGC_TP_MOTIVO_RECLAMOS M
        WHERE M.RESOLUCION = 'S'
        AND M.GERENCIA = '$gerencia'
        ORDER BY M.ID_MOTIVO_REC";
        //echo $sql;
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

    public function obtieneMotivoResolucionPqrsCodigo($cod_pqr, $gerencia)
    {
        $sql = "SELECT M.ID_MOTIVO_REC, M.DESC_MOTIVO_REC
        FROM SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_PQRS P
        WHERE  M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND P.GERENCIA = '$gerencia'
        AND P.CODIGO_PQR = '$cod_pqr'
        UNION
        SELECT M.ID_MOTIVO_REC, M.DESC_MOTIVO_REC
        FROM SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_PQRS_CATASTRALES P
        WHERE  M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND P.CODIGO_PQR = '$cod_pqr'";
        //echo $sql;
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

    public function obtieneDiagnosticoPqr()
    {
        $sql = "SELECT D.ID_DIAGNOSTICO, D.DESC_DIAGNOSTICO
        FROM SGC_TP_DIAGNOSTICOS_PQR D
        WHERE ESTADO IN ('A') AND VISIBLE IN ('S')
        ORDER BY D.ID_DIAGNOSTICO";
        //echo $sql;
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

    public function CierraPqr($cod_inm, $cod_pqr, $observa, $area_act, $orden, $tipo_res, $diagnostico, $coduser)
    {
        $sql = "BEGIN SGC_P_CIERRA_PQR('$cod_inm','$cod_pqr','$observa','$area_act','$orden','$tipo_res','$diagnostico','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function generaDocPqr($cod_pqr)
    {
        $sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE_NUEVO, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
        M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR,
        U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO, EMAIL_CLIENTE_NUEVO,
        I.ID_PROYECTO ACUEDUCTO
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
        SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.GERENCIA = M.GERENCIA
        AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
        AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
        AND E.COD_ENTIDAD = P.COD_ENTIDAD
        AND PP.ID_PUNTO_PAGO(+) = P.COD_PUNTO
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = '$cod_pqr'
        UNION
        SELECT TO_NUMBER(111111)COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, 'E' GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
        M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR,
        U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, '1111111' ID_PROCESO, '111111111-1' CATASTRO, T.DESC_TIPO_RECLAMO, P.EMAIL_CLIENTE,
        P.PROYECTO ACUEDUCTO
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
        SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U
        WHERE M.GERENCIA = 'E'
        AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
        AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
        AND E.COD_ENTIDAD = P.COD_ENTIDAD
        AND PP.ID_PUNTO_PAGO(+) = P.COD_PUNTO
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = '$cod_pqr'";
        //echo $sql;
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

    public function CantidadDatosResumenPqr($proyecto, $zonini, $zonfin, $secini, $secfin, $rutini, $rutfin, $tipo_resol, $tipo_estado, $ofiini, $ofifin, $motivo, $fecinirad, $fecfinrad, $fecinires, $fecfinres, $recini, $recfin, $start, $end, $where)
    {
        if ($motivo == 64) {
            $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
            FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_PUNTO_PAGO U, SGC_TT_PQR_FLUJO_CAT F
            WHERE P.COD_PUNTO = U.ID_PUNTO_PAGO
            AND F.CODIGO_PQR = P.CODIGO_PQR
            AND F.ORDEN = (SELECT MAX(A.ORDEN) FROM SGC_TT_PQR_FLUJO_CAT A WHERE A.CODIGO_PQR = F.CODIGO_PQR AND A.RESPUESTA IS NOT NULL)";
        } else {
            $sql = "SELECT COUNT(P.CODIGO_PQR)CANTIDAD
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO U,SGC_TP_MOTIVO_RECLAMOS R, SGC_TT_PQR_FLUJO F
            WHERE P.COD_INMUEBLE = I.CODIGO_INM
            AND P.COD_PUNTO = U.ID_PUNTO_PAGO
            AND R.ID_MOTIVO_REC = P.MOTIVO_PQR
            AND R.GERENCIA = P.GERENCIA
            AND F.CODIGO_PQR = P.CODIGO_PQR
            AND F.ORDEN = (SELECT MAX(A.ORDEN) FROM SGC_TT_PQR_FLUJO A WHERE A.CODIGO_PQR = F.CODIGO_PQR AND A.RESPUESTA IS NOT NULL)";
        }
        if ($motivo == 64) {
            if ($proyecto != '') {
                $sql .= " AND P.PROYECTO = '$proyecto'";
            }

        } else {
            if ($proyecto != '') {
                $sql .= " AND I.ID_PROYECTO = '$proyecto'";
            }

        }
        if ($zonini != '' && $zonfin != '') {
            $sql .= " AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
        }

        if ($secini != '' && $secfin != '') {
            $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
        }

        if ($rutini != '' && $rutfin != '') {
            $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
        }

        if ($tipo_resol == 1) {
            $sql .= " AND P.DIAGNOSTICO = 1";
        }

        if ($tipo_resol == 2) {
            $sql .= " AND P.DIAGNOSTICO = 2";
        }

        if ($tipo_resol == 3) {
            $sql .= " ";
        }

        if ($tipo_estado == 1) {
            $sql .= " AND FECHA_CIERRE IS NULL";
        }

        if ($tipo_estado == 2) {
            $sql .= " AND FECHA_CIERRE IS NOT NULL";
        }

        if ($tipo_estado == 3) {
            $sql .= "";
        }

        if ($ofiini != '' && $ofifin != '') {
            $sql .= " AND U.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
        }

        if ($motivo != '') {
            $sql .= " AND P.MOTIVO_PQR = $motivo";
        }

        if ($fecinirad != '' && $fecfinrad != '') {
            $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        if ($fecinires != '' && $fecfinres != '') {
            $sql .= " AND P.FECHA_CIERRE BETWEEN TO_DATE('$fecinires 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfinres 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        if ($recini != '' && $recfin != '') {
            $sql .= " AND P.CODIGO_PQR BETWEEN $recini AND $recfin $where ";
        }

        //$sql .= " ORDER BY I.ID_PROCESO DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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

    public function datosResumenPqr($proyecto, $zonini, $zonfin, $secini, $secfin, $rutini, $rutfin, $tipo_resol, $tipo_estado, $ofiini, $ofifin, $motivo, $fecinirad, $fecfinrad, $fecinires, $fecfinres, $recini, $recfin, $start, $end, $where)
    {
        if ($motivo == 64) {
            $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
            FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY HH24:MI:SS')FECRAD, '1111111' COD_INMUEBLE,P.NOM_CLIENTE, P.MEDIO_REC_PQR,  '' ID_ZONA, '' GERENCIA,
            U.COD_VIEJO, REPLACE(REPLACE(P.DESCRIPCION,CHR(10),' '),CHR(13),' ')DESCRIPCION, TO_CHAR(P.FECHA_CIERRE,'DD/MM/YYYY')FECDIAG,
            DECODE(P.DIAGNOSTICO,1,'PROCEDENTE',2,'NO PROCEDENTE')DIAGNOSTICO,
            TO_CHAR(F.FECHA_SALIDA,'DD/MM/YYYY')FECRESOL, 'SOLICITUD UBICACION CATASTRAL' DESC_MOTIVO_REC, 'FAVORABLE' RESOLUCION,
            TRIM(REPLACE(REPLACE(F.RESPUESTA,CHR(10),' '),CHR(13),' '))RESPUESTA
            FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_PUNTO_PAGO U, SGC_TT_PQR_FLUJO_CAT F
            WHERE P.COD_PUNTO = U.ID_PUNTO_PAGO
            AND F.CODIGO_PQR = P.CODIGO_PQR
            AND F.ORDEN = (SELECT MAX(A.ORDEN) FROM SGC_TT_PQR_FLUJO_CAT A WHERE A.CODIGO_PQR = F.CODIGO_PQR AND A.RESPUESTA IS NOT NULL)";
        } else {
            $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
            FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY HH24:MI:SS')FECRAD, P.COD_INMUEBLE, P.NOM_CLIENTE, P.MEDIO_REC_PQR, I.ID_ZONA,  P.GERENCIA,
            U.COD_VIEJO, REPLACE(REPLACE(P.DESCRIPCION,CHR(10),' '),CHR(13),' ')DESCRIPCION, TO_CHAR(P.FECHA_CIERRE,'DD/MM/YYYY')FECDIAG,
            DECODE(P.DIAGNOSTICO,1,'PROCEDENTE',2,'NO PROCEDENTE')DIAGNOSTICO,
            TO_CHAR(F.FECHA_SALIDA,'DD/MM/YYYY')FECRESOL, R.DESC_MOTIVO_REC, DECODE(R.FAVORABLE,'S','FAVORABLE','N','DESFAVORABLE')RESOLUCION,
            TRIM(REPLACE(REPLACE(F.RESPUESTA,CHR(10),' '),CHR(13),' '))RESPUESTA
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO U, SGC_TP_MOTIVO_RECLAMOS R, SGC_TT_PQR_FLUJO F
            WHERE P.COD_INMUEBLE = I.CODIGO_INM
            AND P.COD_PUNTO = U.ID_PUNTO_PAGO
            AND R.ID_MOTIVO_REC = P.MOTIVO_PQR
            AND R.GERENCIA = P.GERENCIA
            AND F.CODIGO_PQR = P.CODIGO_PQR
            AND F.ORDEN = (SELECT MAX(A.ORDEN) FROM SGC_TT_PQR_FLUJO A WHERE A.CODIGO_PQR = F.CODIGO_PQR AND RESPUESTA IS NOT NULL)";
        }
        if ($motivo == 64) {
            if ($proyecto != '') {
                $sql .= " AND P.PROYECTO = '$proyecto'";
            }

        } else {
            if ($proyecto != '') {
                $sql .= " AND I.ID_PROYECTO = '$proyecto'";
            }

            if ($zonini != '' && $zonfin != '') {
                $sql .= " AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
            }

            if ($secini != '' && $secfin != '') {
                $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
            }

            if ($rutini != '' && $rutfin != '') {
                $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
            }

        }

        if ($tipo_resol == 1) {
            $sql .= " AND P.DIAGNOSTICO = 1";
        }

        if ($tipo_resol == 2) {
            $sql .= " AND P.DIAGNOSTICO = 2";
        }

        if ($tipo_resol == 3) {
            $sql .= " ";
        }

        if ($tipo_estado == 1) {
            $sql .= " AND FECHA_CIERRE IS NULL";
        }

        if ($tipo_estado == 2) {
            $sql .= " AND FECHA_CIERRE IS NOT NULL";
        }

        if ($tipo_estado == 3) {
            $sql .= "";
        }

        if ($ofiini != '' && $ofifin != '') {
            $sql .= " AND U.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
        }

        if ($motivo != '') {
            $sql .= " AND P.MOTIVO_PQR = $motivo";
        }

        if ($fecinirad != '' && $fecfinrad != '') {
            $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        if ($fecinires != '' && $fecfinres != '') {
            $sql .= " AND P.FECHA_CIERRE BETWEEN TO_DATE('$fecinires 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfinres 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        if ($recini != '' && $recfin != '') {
            $sql .= " AND P.CODIGO_PQR BETWEEN $recini AND $recfin $where ";
        }

        if ($motivo == 64) {
            $sql .= " ORDER BY P.CODIGO_PQR DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
        } else {
            $sql .= " ORDER BY I.ID_PROCESO DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
        }
        // echo $sql;
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

        echo $sql;

    }

    public function cantDatosTeleCorreo($proyecto, $fecini, $fecfin, $start, $end, $where)
    {
        $sql = "SELECT COUNT(*)CANTIDAD FROM(SELECT COUNT(*)
        FROM SGC_TT_HISTORICO_TEL_COR H, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
        WHERE U.ID_USUARIO = H.USUARIO_ACTUALIZACION
        AND I.CODIGO_INM = H.CODIGO_INM";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        $sql .= " AND H.FECHA_ACTUALIZACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        --AND (TELEFONO_ANT <> TELEFONO_ACT OR CORREO_ANT <> CORREO_ACT)
        GROUP BY U.LOGIN, (U.NOM_USR||' '||U.APE_USR))";
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

    public function totalTeleCorreo($proyecto, $fecini, $fecfin, $start, $end, $where)
    {
        $sql = "SELECT SUM(CANTTEL) SUMTEL, SUM(CANTEMAIL) SUMEMAIL FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (SELECT U.LOGIN, (U.NOM_USR||' '||U.APE_USR)USUARIO, COUNT(TELEFONO_ACT)CANTTEL, COUNT(CORREO_ACT)CANTEMAIL
        FROM SGC_TT_HISTORICO_TEL_COR H, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
        WHERE U.ID_USUARIO = H.USUARIO_ACTUALIZACION
        AND I.CODIGO_INM = H.CODIGO_INM";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        $sql .= " AND H.FECHA_ACTUALIZACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        --AND (TELEFONO_ANT <> TELEFONO_ACT OR CORREO_ANT <> CORREO_ACT)
        GROUP BY U.LOGIN, (U.NOM_USR||' '||U.APE_USR)";
        $sql .= " ORDER BY U.LOGIN ASC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
        
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

    public function datosTeleCorreo($proyecto, $fecini, $fecfin, $start, $end, $where)
    {
         $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (SELECT U.LOGIN, (U.NOM_USR||' '||U.APE_USR)USUARIO, COUNT(TELEFONO_ACT)CANTTEL, COUNT(CORREO_ACT)CANTEMAIL
        FROM SGC_TT_HISTORICO_TEL_COR H, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
        WHERE U.ID_USUARIO = H.USUARIO_ACTUALIZACION
        AND I.CODIGO_INM = H.CODIGO_INM";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        $sql .= " AND H.FECHA_ACTUALIZACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        --AND (TELEFONO_ANT <> TELEFONO_ACT OR CORREO_ANT <> CORREO_ACT)
        GROUP BY U.LOGIN, (U.NOM_USR||' '||U.APE_USR)";
        $sql .= " ORDER BY U.LOGIN ASC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
        
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

    public function cantDatosHistoricoTelCor($proyecto, $fecini, $fecfin, $start, $end, $where)
    {
        $sql = "SELECT COUNT(*)CANTIDAD 
        FROM SGC_TT_HISTORICO_TEL_COR H, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = H.CODIGO_INM";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        $sql .= " AND H.FECHA_ACTUALIZACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
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

    public function datosHistoricoTelCor($proyecto, $fecini, $fecfin, $start, $end)
    {
         $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (SELECT H.CODIGO_INM, H.NOMBRE, H.TELEFONO_ANT, H.TELEFONO_ACT, H.CORREO_ANT, H.CORREO_ACT, H.FECHA_ACTUALIZACION, (U.NOM_USR || ' ' || U.APE_USR) USUARIO
        FROM SGC_TT_HISTORICO_TEL_COR H, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
        WHERE U.ID_USUARIO = H.USUARIO_ACTUALIZACION
        AND I.CODIGO_INM = H.CODIGO_INM";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        $sql .= " AND H.FECHA_ACTUALIZACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        $sql .= " ORDER BY H.FECHA_ACTUALIZACION DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
        
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

    public function CantidadDatosInteraccion($proyecto, $ofiini, $ofifin, $fecinirad, $fecfinrad, $login, $start, $end, $where)
    {
        $sql = "SELECT COUNT(*)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO T
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND M.GERENCIA = P.GERENCIA
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND T.ID_PUNTO_PAGO = P.COD_PUNTO
        AND P.TIPO_PQR IN(2,5)";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        if ($login != '') {
            $sql .= " AND U.LOGIN = '$login'";
        }

        if ($ofiini != '' && $ofifin != '') {
            $sql .= " AND T.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
        }

        if ($fecinirad != '' && $fecfinrad != '') {
            $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " ORDER BY U.LOGIN ASC, I.ID_PROCESO";
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

    public function datosInteraccion($proyecto, $ofiini, $ofifin, $fecinirad, $fecfinrad, $login, $start, $end, $where)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (SELECT MR.DESC_MEDIO_REC MEDIOREC, TO_CHAR(FECHA_REGISTRO,'DD/MM/YYYY HH24:MI:SS')FECREG,REPLACE(REPLACE(REPLACE(M.DESC_MOTIVO_REC,CHR(10),' ') ,CHR(13),' ') ,'  ',' ') DESC_MOTIVO_REC,REPLACE(REPLACE(REPLACE(p.DESCRIPCION,CHR(10),' ') ,CHR(13),' ') ,'  ',' ') DESCRIPCION, (U.NOM_USR||' '||U.APE_USR)USUARIO, P.COD_INMUEBLE, U.LOGIN
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO T, ACEASOFT.SGC_TP_MEDIO_RECEPCION MR
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND M.GERENCIA = P.GERENCIA
        AND MR.ID_MEDIO_REC=P.MEDIO_REC_PQR
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND T.ID_PUNTO_PAGO = P.COD_PUNTO
        AND P.TIPO_PQR IN(5,6,1,2)";
        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        }

        if ($login != '') {
            $sql .= " AND U.LOGIN = '$login'";
        }

        if ($ofiini != '' && $ofifin != '') {
            $sql .= " AND T.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
        }

        if ($fecinirad != '' && $fecfinrad != '') {
            $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " ORDER BY U.LOGIN ASC, I.ID_PROCESO)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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
            echo "false";
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
            echo "false";
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
            echo "false";
            return false;
        }

    }

    public function CantidadObserva($inmueble)
    {
        $sql = "SELECT COUNT(CONSECUTIVO)CANTIDAD
                FROM SGC_TT_OBSERVACIONES_INM
                WHERE INM_CODIGO = '$inmueble'";
        $resultado = oci_parse($this->_db, $sql);

        //echo $sql;
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

    public function TodasObserva($sort, $start, $end, $inmueble)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT  CODIGO_OBS, ASUNTO, DESCRIPCION, TO_DATE(TO_CHAR(FECHA,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY HH24:MI:SS'),TO_CHAR(FECHA,'DD/MM/YYYY HH24:MI:SS')FECHA,  USR_OBSERVACION
                        FROM SGC_TT_OBSERVACIONES_INM
                        WHERE INM_CODIGO = '$inmueble'
                         $sort
                        )a WHERE  ROWNUM<$start
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

//////////consulta de reclamos anteriores
    public function reclamosAnteriores($cod_inmueble)
    {
        $sql = "SELECT COUNT (P.CODIGO_PQR) CANTREC
        FROM SGC_TT_PQRS P
        WHERE P.COD_INMUEBLE = '$cod_inmueble' AND TIPO_PQR IN (1,2,4)";
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

    public function TodosHistReclamos($cod_inmueble, $sort)
    {
        $sql = "SELECT DISTINCT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHA_PQR, T.DESC_TIPO_RECLAMO, R.DESC_MOTIVO_REC,
        DECODE(P.CERRADO,'S','Cerrado','N','Abierto')CERRADO, DECODE(P.DIAGNOSTICO,'1','Procedente','2','No Procedente')DIAGNOSTICO
        FROM SGC_TT_PQRS P, SGC_TP_TIPOS_RECLAMOS T, SGC_TP_MOTIVO_RECLAMOS R
        WHERE P.TIPO_PQR = T.ID_TIPO_RECLAMO
        AND P.MOTIVO_PQR = R.ID_MOTIVO_REC
        AND P.TIPO_PQR IN (1,2,4)
        AND P.COD_INMUEBLE = '$cod_inmueble'
        $sort";
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

    //////////consulta de resoluciones dadas a un reclamo
    public function resolucionesAnteriores($cod_pqr)
    {
        $sql = "SELECT COUNT (P.CONSECUTIVO) CANTRES
        FROM SGC_TT_PQR_FLUJO P
        WHERE P.CODIGO_PQR = '$cod_pqr'";
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

    public function todosResolucionesAnteriores($cod_pqr, $sort)
    {
        $sql = "SELECT CONSECUTIVO, ORDEN, A.DESC_AREA, TO_CHAR(P.FECHA_ENTRADA,'DD/MM/YYYY HH24:MI:SS')FECHA_ENTRADA, TO_CHAR(P.FECHA_SALIDA,'DD/MM/YYYY HH24:MI:SS')FECHA_SALIDA,
        TRIM(REPLACE(REPLACE(RESPUESTA,CHR(10),' '),CHR(13),' '))RESPUESTA, U.LOGIN
        FROM SGC_TT_PQR_FLUJO P, SGC_TP_AREAS A, SGC_TT_USUARIOS U
        WHERE P.AREA_ACTUAL = A.ID_AREA
        AND P.USUARIO_RESPUESTA = U.ID_USUARIO
        AND P.CODIGO_PQR = '$cod_pqr'
        $sort";
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

    public function InmueblesDeuda($proyecto, $sectorinicial, $sectorfinal, $rutainicial, $rutafinal, $uniinicial, $unifinal, $periodo1, $periodo2)
    {

        $sql = "SELECT MI.SERIAL, I.ID_TIPO_CLIENTE, I.CODIGO_INM,C.ALIAS, CL.NOMBRE_CLI, SUM(F.TOTAL)TOTAL, CL.TELEFONO, I.ID_ESTADO, I.UNIDADES_HAB, I.CATASTRO, I.ID_PROCESO,
        SUBSTR(I.ID_ZONA,0,2)SECTOR, I.ID_ZONA, I.ID_RUTA, COUNT(F.CONSEC_FACTURA)CANTIDAD, NVL(MM.ESTADO_MED,'N') MEDIDO, A.ID_USO, U.DESC_URBANIZACION,
        DECODE(SI.COD_SERVICIO,1,'Agua',3,'Pozo')SUMINISTRO, DECODE(DC.ACTIVA,'S','PDC Activo','N','PDC Inactivo','Sin PDC') ESTADO_PDC, I.DIRECCION,
        NVL(( (select SUM(P.IMPORTE)IMPORTE
                        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R
                        WHERE C.ID_CAJA = P.ID_CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO='A'
                        AND I.CODIGO_INM = P.INM_CODIGO
                        AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')='$periodo1')),0)+
                        NVL((
                        SELECT SUM(P.IMPORTE)IMPORTE
                        FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R
                        WHERE C.ID_CAJA = P.CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO IN ('T','A')
                        AND I.CODIGO_INM = P.INMUEBLE
                        AND TO_CHaR(P.FECHA_PAGO,'YYYYMM')='$periodo1'),0) PERIODO1,
        NVL(( (select SUM(P.IMPORTE)IMPORTE
                        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R
                        WHERE C.ID_CAJA = P.ID_CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO='A'
                        AND I.CODIGO_INM = P.INM_CODIGO
                        AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')='$periodo2')),0)+
                        NVL((
                        SELECT SUM(P.IMPORTE)IMPORTE
                        FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R
                        WHERE C.ID_CAJA = P.CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.VALIDA_REPORTES='S'
                        AND P.ESTADO IN ('T','A')
                        AND I.CODIGO_INM = P.INMUEBLE
                        AND TO_CHaR(P.FECHA_PAGO,'YYYYMM')='$periodo2'),0) PERIODO2,
                        T.CATEGORIA 
        FROM SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_FACTURA F, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_URBANIZACIONES U,
        SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_DEUDA_CERO DC, ACEASOFT.SGC_TT_MEDIDOR_INMUEBLE MI, ACEASOFT.SGC_TP_MEDIDORES MM, SGC_TP_TARIFAS T 
        WHERE I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND MI.COD_INMUEBLE(+)=I.CODIGO_INM
        AND MI.FECHA_BAJA(+) IS NULL
        AND MM.CODIGO_MED(+)=MI.COD_MEDIDOR
        AND I.CODIGO_INM = DC.COD_INMUEBLE(+)
        AND I.CODIGO_INM = F.INMUEBLE(+)
        AND C.CODIGO_INM(+) = I.CODIGO_INM
        AND C.CODIGO_CLI = CL.CODIGO_CLI(+)
        AND nvl(to_char(DC.FECHA_CREACION,'DD/MM/YYYY'),'ninguna')=nvl((SELECT max(to_char(DC2.FECHA_CREACION,'DD/MM/YYYY')) FROM SGC_TT_DEUDA_CERO DC2 WHERE DC.COD_INMUEBLE=DC2.COD_INMUEBLE),'ninguna')
        AND U.CONSEC_URB = I.CONSEC_URB
        AND I.CODIGO_INM = SI.COD_INMUEBLE(+)
        AND SI.COD_SERVICIO (+)IN (1,3)
        AND C.FECHA_FIN (+)IS NULL
        --AND I.ID_ESTADO NOT IN ('CC','CT','CB')
        AND I.ID_PROYECTO = '$proyecto'
        AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA";

        if ($uniinicial != '' && $unifinal != '') {
            $sql .= " AND I.UNIDADES_HAB BETWEEN $uniinicial AND $unifinal";
        }

        if ($sectorinicial != '' && $sectorfinal != '') {
            $sql .= " AND I.ID_SECTOR BETWEEN $sectorinicial AND $sectorfinal";
        }

        if ($rutainicial != '' && $rutafinal != '') {
            $sql .= " AND I.ID_RUTA BETWEEN $rutainicial AND $rutafinal";
        }

        $sql .= " AND F.FACTURA_PAGADA(+) = 'N' AND F.FEC_EXPEDICION (+) IS NOT NULL
        GROUP BY  MI.SERIAL, I.CODIGO_INM, C.ALIAS, CL.NOMBRE_CLI, CL.TELEFONO, I.TELEFONO, I.ID_ESTADO, I.UNIDADES_HAB, I.CATASTRO, I.ID_PROCESO, SUBSTR(I.ID_ZONA,0,2), I.ID_ZONA, I.ID_RUTA, A.ID_USO,
        NVL(MM.ESTADO_MED,'N'), U.DESC_URBANIZACION, SI.COD_SERVICIO, DC.ACTIVA, I.DIRECCION,I.ID_TIPO_CLIENTE,T.CATEGORIA
        ORDER BY 4";
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

    public function InmueblesPdc($proyecto)
    {
        $sql = "SELECT I.CODIGO_INM, C.ALIAS, CL.NOMBRE_CLI, SUM(F.TOTAL)TOTAL, CL.TELEFONO, I.ID_ESTADO, I.UNIDADES_HAB, I.CATASTRO, I.ID_PROCESO,
        SUBSTR(I.ID_ZONA,0,2)SECTOR, I.ID_RUTA, COUNT(F.CONSEC_FACTURA)CANTIDAD, DECODE(I.FACTURAR,'D','Medido','P','Promedio')MEDIDO, A.ID_USO, U.DESC_URBANIZACION,
        DECODE(DC.ACTIVA,'S','Activo','N','Inactivo')ESTADO, DC.TOTAL_CUOTAS_PAG, (DC.TOTAL_DIFERIDO + DC.TOTAL_MORA) TOTAL_PDC, DC.TOTAL_PAGADO
        FROM SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_FACTURA F, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_URBANIZACIONES U, SGC_TT_DEUDA_CERO DC
        WHERE I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND I.CODIGO_INM = DC.COD_INMUEBLE
        AND I.CODIGO_INM = F.INMUEBLE
        AND C.CODIGO_INM = I.CODIGO_INM
        AND C.CODIGO_CLI = CL.CODIGO_CLI
        AND U.CONSEC_URB = I.CONSEC_URB
        AND I.ID_ESTADO NOT IN ('CC','CT','CB')
        AND I.ID_PROYECTO = '$proyecto'
        AND F.FACTURA_PAGADA = 'N'
        GROUP BY  I.CODIGO_INM, C.ALIAS, CL.NOMBRE_CLI, CL.TELEFONO, I.TELEFONO, I.ID_ESTADO, I.UNIDADES_HAB, I.CATASTRO, I.ID_PROCESO, SUBSTR(I.ID_ZONA,0,2), I.ID_RUTA, A.ID_USO,
        I.FACTURAR, U.DESC_URBANIZACION, DC.ACTIVA, DC.TOTAL_CUOTAS_PAG, DC.TOTAL_DIFERIDO + DC.TOTAL_MORA, DC.TOTAL_PAGADO
        ORDER BY 1";
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

    public function IngresaPqrCat($fecha, $nom_cli, $ced_cli, $direccion, $tel_cli, $cel_cli, $mail_cli, $medio, $tipo, $motivo, $fecha_res, $descripcion, $cod_ent, $id_punto, $num_caja, $coduser, $area_res)
    {
        $sql = "BEGIN SGC_P_INGRESA_PQR_CATASTRAL(TO_DATE('$fecha','DD/MM/YYYY HH24:MI:SS'),'$nom_cli','$ced_cli','$direccion','$tel_cli','$cel_cli','$mail_cli','$medio','$tipo','$motivo',TO_DATE('$fecha_res','DD/MM/YYYY HH24:MI:SS'),'$descripcion','$cod_ent','$id_punto','$num_caja','$coduser','$area_res',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msgresult, 1000);
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

    public function DataCredito($proyecto, $fecini, $fecfin)
    {
         $sql = " SELECT I.CODIGO_INM, C.ALIAS, CL.DOCUMENTO, I.ID_SECTOR, I.ID_RUTA, I.DIRECCION, U.DESC_URBANIZACION, CL.TELEFONO, A.ID_USO, I.ID_ESTADO, P.FECHA_PAGO,
  NVL((SELECT DISTINCT 'S' FROM SGC_TT_PQRS P WHERE P.COD_INMUEBLE = I.CODIGO_INM AND P.FECHA_CIERRE IS NULL),'N') RECLAMO_ABIERTO,
  (SELECT SUM(P1.IMPORTE) FROM SGC_TT_PAGOS P1, SGC_TP_CAJAS_PAGO CP
  WHERE P1.INM_CODIGO=I.CODIGO_INM AND
    CP.ID_CAJA=P1.ID_CAJA AND
    CP.VALIDA_REPORTES='S' AND
    P1.ESTADO='A'  AND
    P1.FECHA_PAGO BETWEEN  TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin','YYYY-MM-DD HH24:MI:SS')
  ) MONTO_PAGADO,
  (SELECT COUNT(P.ID_PAGO) FROM SGC_TT_PAGOS P1, SGC_TP_CAJAS_PAGO CP,SGC_TT_PAGO_FACTURAS PF
  WHERE P1.INM_CODIGO=I.CODIGO_INM AND
        CP.ID_CAJA=P1.ID_CAJA AND
        PF.ID_PAGO=P1.ID_PAGO AND
        CP.VALIDA_REPORTES='S' AND
        P1.ESTADO='A'  AND
        P1.FECHA_PAGO BETWEEN  TO_DATE('$fecini  00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  ) FACTURAS_PAGADAS

FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_ESTADOS_INMUEBLES E, SGC_TP_URBANIZACIONES U, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A
WHERE I.CODIGO_INM = C.CODIGO_INM
      AND C.CODIGO_CLI = CL.CODIGO_CLI
      AND E.ID_ESTADO = I.ID_ESTADO
      AND I.CONSEC_URB = U.CONSEC_URB
      AND I.CODIGO_INM = P.INM_CODIGO
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
      AND E.INDICADOR_ESTADO = 'A'
      AND I.ID_PROYECTO = '$proyecto'
      AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')   
         AND I.CODIGO_INM IN (SELECT INM_CODIGO FROM SGC_TP_DATA_CREDITO WHERE INM_CODIGO = I.CODIGO_INM)
ORDER BY CODIGO_INM
      
      



";
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

    public function obtieneZonAuto($proyecto, $parcial)
    {

        $sql = "SELECT
                ID_ZONA
              FROM
                SGC_TP_ZONAS
              WHERE
                ID_PROYECTO = '$proyecto' AND
                ID_ZONA LIKE '$parcial%'";
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

    public function MotivosDesfavorables()
    {
        $sql = "
        SELECT DISTINCT MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC
        FROM SGC_TP_MOTIVO_RECLAMOS MR
        WHERE MR.FAVORABLE = 'N'
        AND MR.ID_TIPO_RECLAMO IN (1,4)
        ORDER BY MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC";
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

    public function CantidadDesfavorablesNorte($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.GERENCIA = 'N' AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesNorteProc($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.DIAGNOSTICO IN (1)
        AND P.GERENCIA = 'N' AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesNorteNoProc($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.DIAGNOSTICO IN (2)
        AND P.GERENCIA = 'N' AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesEste($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.GERENCIA = 'E' AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesEsteProc($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.DIAGNOSTICO IN (1)
        AND P.GERENCIA = 'E' AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesEsteNoProc($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.DIAGNOSTICO IN (2)
        AND P.GERENCIA = 'E' AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesTotal($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.GERENCIA IN ('N','E') AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesTotalProc($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.DIAGNOSTICO IN (1)
        AND P.GERENCIA IN ('N','E') AND P.TIPO_PQR IN (1,4)";
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

    public function CantidadDesfavorablesTotalNoProc($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "SELECT  COUNT(P.CODIGO_PQR)CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = P.COD_INMUEBLE
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.DIAGNOSTICO IS NOT NULL
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'
        AND P.DIAGNOSTICO IN (2)
        AND P.GERENCIA IN ('N','E') AND P.TIPO_PQR IN (1,4)";
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

    public function MotivosFavorables()
    {
        $sql = "
        SELECT DISTINCT MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC
        FROM SGC_TP_MOTIVO_RECLAMOS MR
        WHERE MR.FAVORABLE = 'S'
        AND MR.ID_TIPO_RECLAMO IN (1,4)
        ORDER BY MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC";
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

    // Reporte estadistica de PQRS detallados

    public function GetRepEstDet($proyecto, $tipo, $motivo, $fecIni, $fecFin)
    {
        $sql = "SELECT distinct pqr.codigo_pqr        Codigo,
                       TO_CHAR(pqr.FECHA_REGISTRO, 'DD-MM-YYYY HH:MI:SS AM') FECHA_PQR,
                       pqr.cod_inmueble      inmueble,
                       pqr.nom_cliente,
                       pqr.dir_cliente       direccion,
                       pqr.tel_cliente       telefono,
                       pqr.medio_rec_pqr,
                       i.id_zona             zona,
                       USU.LOGIN     USUARIO,
                       pqr.gerencia,
                       pqr.descripcion       desc_pqr,
                                TO_CHAR((select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo pf
                           where pf.codigo_pqr = pqr.codigo_pqr), 'DD-MM-YYYY HH:MI:SS AM') fecha_diag,
                        dpqr.desc_diagnostico,
                TO_CHAR( pqr.fecha_cierre , 'DD-MM-YYYY HH:MI:SS AM') fecha_res,
                        mrc.desc_motivo_rec Tipo,
                        (select pf2.respuesta
                           from sgc_tt_pqr_flujo pf2
                          where pf2.codigo_pqr = pqr.codigo_pqr
                            and pf2.respuesta is not null
                            and rownum <= 1) respuesta,p.DESCRIPCION OFICINA,
                        (select dif_diaslab( pqr.FECHA_REGISTRO, (select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo pf
                          where pf.codigo_pqr = pqr.codigo_pqr)) from dual) Tiempo_respuesta
                  from sgc_tt_pqrs             pqr,
                       sgc_tt_inmuebles        i,
                       sgc_tp_diagnosticos_pqr dpqr,
                        sgc_tp_motivo_reclamos mrc,
                           SGC_TP_PUNTO_PAGO p,
                           SGC_TT_USUARIOS USU
                 where i.codigo_inm = pqr.cod_inmueble
                   and usu.ID_USUARIO=PQR.USER_RECIBIO_PQR
                   and pqr.COD_PUNTO=p.ID_PUNTO_PAGO
                   and pqr.diagnostico = dpqr.id_diagnostico
                   and pqr.motivo_pqr = mrc.id_motivo_rec
                   and i.id_proyecto = '$proyecto'
                   --and pqf.fecha_salida = pqr.fecha_cierre
                   and pqr.cerrado = 'S'
                   AND pqr.fecha_pqr BETWEEN To_date('$fecIni' || '00:00:00', 'YYYY-MM-DD HH24:MI:SS' ) and
                       to_date('$fecFin' || '23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                   and pqr.tipo_pqr = $tipo ";
        if ($motivo != 0) {
            $sql .= " and pqr.motivo_pqr = $motivo";
        }

        if($tipo==2 and $motivo == 0){
            $sql.="
            
              UNION
              SELECT distinct pqr.codigo_pqr        Codigo,
                       TO_CHAR(pqr.FECHA_REGISTRO, 'DD-MM-YYYY HH:MI:SS AM') FECHA_PQR,
                       pqr.cod_inmueble      inmueble,
                       pqr.nom_cliente,
                       pqr.dir_cliente       direccion,
                       pqr.tel_cliente       telefono,
                       pqr.medio_rec_pqr,
                       i.id_zona             zona,
                       USU.LOGIN     USUARIO,
                       pqr.gerencia,
                       pqr.descripcion       desc_pqr,
                      TO_CHAR((select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo pf
                           where pf.codigo_pqr = pqr.codigo_pqr), 'DD-MM-YYYY HH:MI:SS AM') fecha_diag,
                        dpqr.desc_diagnostico,
                TO_CHAR( pqr.fecha_cierre , 'DD-MM-YYYY HH:MI:SS AM') fecha_res,
                        mrc.desc_motivo_rec Tipo,
                        (select pf2.respuesta
                           from sgc_tt_pqr_flujo pf2
                          where pf2.codigo_pqr = pqr.codigo_pqr
                            and pf2.respuesta is not null
                            and rownum <= 1) respuesta,p.DESCRIPCION OFICINA,
                        (select dif_diaslab( pqr.FECHA_REGISTRO, (select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo pf
                          where pf.codigo_pqr = pqr.codigo_pqr)) from dual) Tiempo_respuesta
                  from sgc_tt_pqrs             pqr,
                       sgc_tt_inmuebles        i,
                       sgc_tp_diagnosticos_pqr dpqr,
                        sgc_tp_motivo_reclamos mrc,
                        SGC_TP_PUNTO_PAGO p,
                        SGC_TT_USUARIOS USU
                 where i.codigo_inm = pqr.cod_inmueble
                   and usu.ID_USUARIO=PQR.USER_RECIBIO_PQR
                   and pqr.COD_PUNTO=p.ID_PUNTO_PAGO
                   and pqr.diagnostico = dpqr.id_diagnostico
                   and pqr.motivo_pqr = mrc.id_motivo_rec
                   and i.id_proyecto = '$proyecto'
                   --and pqf.fecha_salida = pqr.fecha_cierre
                   and pqr.cerrado = 'S'
                   AND pqr.fecha_pqr BETWEEN To_date('$fecIni' || '00:00:00', 'YYYY-MM-DD HH24:MI:SS') and
                       to_date('$fecFin' || '23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                   and pqr.tipo_pqr = 5 
                    and pqr.motivo_pqr = 83";
        }
       // echo $sql;
      //  echo $motivo;

         //$sql .= " order by pqr.fecha_pqr";

        $result = oci_parse($this->_db, $sql);

        $flag = oci_execute($result);
        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False pqr';
            return false;
        }
    }

    // Reporte estadistica de PQRS detallados Catastro

    public function GetRepEstDetCat($proyecto, $tipo, $fecIni, $fecFin)
    {
        $sql = "SELECT distinct pqr.codigo_pqr Codigo,
                        pqr.fecha_pqr,
                        pqr.nom_cliente,
                        pqr.dir_cliente direccion,
                        pqr.tel_cliente telefono,
                        pqr.medio_rec_pqr,
                        pqr.descripcion desc_pqr,
                        (select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo_cat pf
                          where pf.codigo_pqr = pqr.codigo_pqr) fecha_diag,
                        dpqr.desc_diagnostico,
                        pqr.fecha_cierre fecha_res,
                        mrc.desc_motivo_rec Tipo,
                        (select max(pf2.respuesta)
                           from sgc_tt_pqr_flujo_cat pf2
                          where pf2.codigo_pqr = pqr.codigo_pqr
                            and pf2.respuesta is not null) respuesta,
                        (select dif_diaslab( pqr.fecha_pqr, (select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo_cat pf
                          where pf.codigo_pqr = pqr.codigo_pqr)) from dual) Tiempo_respuesta
                      from sgc_tt_pqrs_catastrales          pqr,
                           sgc_tp_diagnosticos_pqr dpqr,
                           sgc_tp_motivo_reclamos  mrc
                     where pqr.diagnostico = dpqr.id_diagnostico
                       and pqr.motivo_pqr = mrc.id_motivo_rec
                       and pqr.diagnostico in (1,2)
                       and pqr.proyecto = '$proyecto'
                       and pqr.cerrado = 'S'
                       AND pqr.fecha_pqr BETWEEN To_date('$fecIni', 'YYYY-MM-DD') and
                           to_date('$fecFin' || '23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                       and pqr.tipo_pqr = $tipo
                       and pqr.motivo_pqr = 64
                       order by pqr.fecha_pqr";

        $result = oci_parse($this->_db, $sql);

        $flag = oci_execute($result);
        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False pqr';
            return false;
        }
    }

    ///reporte estadistica de pqrs resumido

    public function ObtienePqrs($motivo)
    {
        $sql = "
        SELECT DISTINCT MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC
        FROM SGC_TP_MOTIVO_RECLAMOS MR
        WHERE MR.ID_TIPO_RECLAMO IN ($motivo)
        ORDER BY MR.ID_MOTIVO_REC, MR.DESC_MOTIVO_REC";
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

    public function CantidadPqrMes($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_MES
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function PendientesMes($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_PEND
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.DIAGNOSTICO IS NULL
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function CerradosProcedentes($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_PROC
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.DIAGNOSTICO = 1
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function CerradosNoProcedentes($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_NOPROC
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.DIAGNOSTICO = 2
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function CerradosTotal($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_CERRADOS
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function DentroTiempo($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) DENTRO_TIEMPO
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TT_PQR_FLUJO PF
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND PF.CODIGO_PQR = P.CODIGO_PQR
        AND P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin  23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND (select min(pf.fecha_salida)
         from sgc_tt_pqr_flujo pf
         where pf.codigo_pqr = p.codigo_pqr) <= P.FECHA_MAX_RESOL
        AND PF.CONSECUTIVO = (SELECT MAX(PFA.CONSECUTIVO) FROM SGC_TT_PQR_FLUJO PFA WHERE PFA.CODIGO_PQR = PF.CODIGO_PQR)
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function FueraTiempo($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) FUERA_TIEMPO
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TT_PQR_FLUJO PF
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND PF.CODIGO_PQR = P.CODIGO_PQR
        AND P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin  23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND (select min(pf.fecha_salida)
            from sgc_tt_pqr_flujo pf
            where pf.codigo_pqr = p.codigo_pqr) > P.FECHA_MAX_RESOL
        AND PF.CONSECUTIVO = (SELECT MAX(PFA.CONSECUTIVO) FROM SGC_TT_PQR_FLUJO PFA WHERE PFA.CODIGO_PQR = PF.CODIGO_PQR)
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    public function TiempoPromedio($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado)
    {
        $sql = "
        SELECT ROUND(SUM((P.FECHA_CIERRE - P.FECHA_PQR))/$cantidad_cerrado,2) PROMEDIO
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = $codmotivo
        AND I.ID_PROYECTO = '$proyecto'";
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

    //REPORTE ESTADISTICAS PQRS CATASTRALES

    public function CantidadPqrMesCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_MES
        FROM SGC_TT_PQRS_CATASTRALES P
        WHERE P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function PendientesMesCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_PEND
        FROM SGC_TT_PQRS_CATASTRALES P
        WHERE P.DIAGNOSTICO IS NULL
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function CerradosProcedentesCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_PROC
        FROM SGC_TT_PQRS_CATASTRALES P
        WHERE P.DIAGNOSTICO = 1
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function CerradosNoProcedentesCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_NOPROC
        FROM SGC_TT_PQRS_CATASTRALES P
        WHERE P.DIAGNOSTICO = 2
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function CerradosTotalCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) CANTIDAD_CERRADOS
        FROM SGC_TT_PQRS_CATASTRALES P
        WHERE P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function DentroTiempoCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) DENTRO_TIEMPO
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TT_PQR_FLUJO_CAT PF
        WHERE PF.CODIGO_PQR = P.CODIGO_PQR
        AND P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND PF.FECHA_ENTRADA <= P.FECHA_MAX_RESOL
        AND PF.CONSECUTIVO = (SELECT MAX(PFA.CONSECUTIVO) FROM SGC_TT_PQR_FLUJO_CAT PFA WHERE PFA.CODIGO_PQR = PF.CODIGO_PQR)
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function FueraTiempoCatastral($codmotivo, $proyecto, $fecini, $fecfin)
    {
        $sql = "
        SELECT COUNT(P.CODIGO_PQR) FUERA_TIEMPO
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TT_PQR_FLUJO_CAT PF
        WHERE PF.CODIGO_PQR = P.CODIGO_PQR
        AND P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND PF.FECHA_ENTRADA > P.FECHA_MAX_RESOL
        AND PF.CONSECUTIVO = (SELECT MAX(PFA.CONSECUTIVO) FROM SGC_TT_PQR_FLUJO_CAT PFA WHERE PFA.CODIGO_PQR = PF.CODIGO_PQR)
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function TiempoPromedioCatastral($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado)
    {
        $sql = "
        SELECT ROUND(SUM((P.FECHA_CIERRE - P.FECHA_PQR))/$cantidad_cerrado,2) PROMEDIO
        FROM SGC_TT_PQRS_CATASTRALES P
        WHERE P.DIAGNOSTICO IN (1,2)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND P.MOTIVO_PQR = 64
        AND P.PROYECTO = '$proyecto'";
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

    public function ReporteGestionCobro($proyecto, $fecini, $fecfin, $usr)
    {
        if ($usr != 0) {
            $where = "AND U.ID_USUARIO = '$usr'";
        }
        $sql = "SELECT DISTINCT  P.CODIGO_PQR,
                       P.COD_INMUEBLE,
                       P.NOM_CLIENTE,
                       P.DIR_CLIENTE,
                       P.DOC_CLIENTE,
                       P.TEL_CLIENTE,
                       TO_CHAR(P.FECHA_PQR, 'DD/MM/YYYY HH24:MI:SS') FECHA_GESTION_COBRO,
                       TO_CHAR(PA.FECHA_PAGO, 'DD/MM/YYYY HH24:MI:SS') FECHA_PAGO,
                       S.DESC_SERVICIO CONCEPTO,
                       FD.PAGADO,
                       U.NOM_USR || ' ' || U.APE_USR USUARIO,
                       CASE
                         WHEN (TO_DATE(P.FECHA_PQR, 'DD/MM/YYYY HH24:MI:SS') <=
                              ADD_MONTHS(TO_DATE(P.FECHA_PQR, 'DD/MM/YYYY HH24:MI:SS'), 1)) THEN
                          'S'
                         ELSE
                          'N'
                       END EFECTIVA
                  FROM SGC_TT_PQRS            P,
                       SGC_TT_PAGOS           PA,
                       SGC_TT_INMUEBLES       I,
                       SGC_TT_USUARIOS        U,
                       SGC_TT_PAGO_DETALLEFAC FD,
                       SGC_TP_SERVICIOS       S
                 WHERE PA.INM_CODIGO = P.COD_INMUEBLE
                   AND P.USER_RECIBIO_PQR = U.ID_USUARIO
                   AND I.CODIGO_INM = P.COD_INMUEBLE
                   AND I.CODIGO_INM = PA.INM_CODIGO
                   AND FD.PAGO = PA.ID_PAGO
                   AND PA.INM_CODIGO = I.CODIGO_INM
                   AND S.COD_SERVICIO = FD.CONCEPTO
                   AND P.FECHA_PQR BETWEEN
                       TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                       TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                   AND P.MOTIVO_PQR = 97
                   AND PA.FECHA_PAGO > P.FECHA_PQR
                   AND I.ID_PROYECTO = '$proyecto'
                   $where
                UNION
                SELECT DISTINCT P.CODIGO_PQR,
                       P.COD_INMUEBLE,
                       P.NOM_CLIENTE,
                       P.DIR_CLIENTE,
                       P.DOC_CLIENTE,
                       P.TEL_CLIENTE,
                       TO_CHAR(P.FECHA_PQR, 'DD/MM/YYYY HH24:MI:SS') FECHA_GESTION_COBRO,
                       TO_CHAR(R.FECHA_PAGO, 'DD/MM/YYYY HH24:MI:SS') FECHA_PAGO,
                       S.DESC_SERVICIO CONCEPTO,
                       R.IMPORTE PAGADO,
                       U.NOM_USR || ' ' || U.APE_USR USUARIO,
                       CASE
                         WHEN (TO_DATE(P.FECHA_PQR, 'DD/MM/YYYY HH24:MI:SS') <=
                              ADD_MONTHS(TO_DATE(P.FECHA_PQR, 'DD/MM/YYYY HH24:MI:SS'), 1)) THEN
                          'S'
                         ELSE
                          'N'
                       END EFECTIVA
                  FROM SGC_TT_PQRS           P,
                       SGC_TT_OTROS_RECAUDOS R,
                       SGC_TT_INMUEBLES      I,
                       SGC_TT_USUARIOS       U,
                       SGC_TP_SERVICIOS      S
                 WHERE R.INMUEBLE = P.COD_INMUEBLE
                   AND P.USER_RECIBIO_PQR = U.ID_USUARIO
                   AND I.CODIGO_INM = P.COD_INMUEBLE
                   AND I.CODIGO_INM = R.INMUEBLE
                   AND S.COD_SERVICIO = R.CONCEPTO
                   AND P.FECHA_PQR BETWEEN
                       TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                       TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                   AND P.MOTIVO_PQR = 97
                   AND R.FECHA_PAGO > P.FECHA_PQR
                   AND I.ID_PROYECTO = '$proyecto'
                   $where";
        //echo '<br>';
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

    public function usrServicio()
    {
        $sql = "SELECT us.id_usuario, us.login, us.nom_usr||' '||us.ape_usr nombre
                FROM sgc_tt_usuarios us
               WHERE us.id_cargo = 300
                 AND us.fec_fin IS NULL";
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

    public function obtenerfechares ($motivo){
        $sql = "SELECT  TO_CHAR(SGC_F_FECRESOLUCION ( SYSDATE, DIAS_RESOLUCION ),'DD/MM/YYYY hh24:mi:ss')FEC_RESOL
				 FROM SGC_TP_MOTIVO_RECLAMOS
				WHERE ID_MOTIVO_REC = '$motivo'";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);


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

    public function getDatByPqrAcuerdo ($inm){
        $sql = "SELECT MAX(CODIGO_PQR) CODIGO
                FROM SGC_TT_PQRS P
                WHERE P.COD_INMUEBLE = '$inm'
                AND P.MOTIVO_PQR = 23";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);


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

    public function getDatByPqrAmnistia ($inm){
        $sql = "SELECT MAX(CODIGO_PQR) CODIGO
                FROM SGC_TT_PQRS P
                WHERE P.COD_INMUEBLE = '$inm'
                AND P.MOTIVO_PQR = 108";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);


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
}
