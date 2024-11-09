<?php
include_once '../../clases/class.conexion.php';
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

    public function seleccionaAcueducto()
    {
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

    public function seleccionaTipoPqr()
    {
        $sql = "SELECT ID_TIPO_RECLAMO, DESC_TIPO_RECLAMO
		FROM SGC_TP_TIPOS_RECLAMOS WHERE CLASIFICACION = 'P'
		AND ID_TIPO_RECLAMO IN (1,2)";
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

    public function obtieneAreaUsuario($coduser)
    {
        $sql = "SELECT A.ID_AREA
		FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C, SGC_TP_AREAS A
		WHERE U.ID_CARGO = C.ID_CARGO
		AND C.ID_AREA = A.ID_AREA
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

    public function CantidadRegistrosPqr($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $where, $area_user)
    {
        $sql = "SELECT COUNT(P.CODIGO_PQR) CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
		AND F.AREA_ACTUAL = '2' $where";
        if ($tipo_pqr != '') {
            $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        }

        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
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

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
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

    public function obtenerDatosPQRs($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $start, $end, $where, $area_user)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
		FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, P.COD_INMUEBLE, (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO,
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECHAMAX, I.ID_PROCESO, A.DESC_AREA,
          CASE WHEN (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) <= 0 THEN 0 ELSE (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) END PORVENCER,
        CASE WHEN (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) >= 0 THEN 0 ELSE (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL))END VENCIDOS
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
		AND F.AREA_ACTUAL = '2' $where";
        if ($tipo_pqr != '') {
            $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        }

        if ($proyecto != '') {
            $sql .= " AND I.ID_PROYECTO = '$proyecto'";
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

        if ($fecini != '' && $fecfin != '') {
            $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql .= " ORDER BY P.CODIGO_PQR ASC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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

    public function obtieneResolucionesPqrs($cod_pqr)
    {
        $sql = "SELECT A.DESC_AREA, F.RESPUESTA
		FROM SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
		WHERE A.ID_AREA = F.AREA_ACTUAL
		AND F.CODIGO_PQR = '$cod_pqr'
		AND F.FECHA_SALIDA IS NOT NULL
		ORDER BY F.ORDEN ASC";
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

    public function generaDocPqr($cod_pqr)
    {
        $sql = "SELECT (SELECT 
               RL2.LECTURA_ORIGINAL 
                        FROM 
                        SGC_TT_REGISTRO_LECTURAS RL2
                        WHERE RL2.COD_INMUEBLE=P.COD_INMUEBLE AND
                         PERIODO=
                         (SELECT MAX(RL3.PERIODO) 
                         FROM ACEASOFT.SGC_TT_REGISTRO_LECTURAS RL3 
                             WHERE RL3.COD_INMUEBLE=P.COD_INMUEBLE AND
                              RL3.FECHA_LECTURA_ORI<=P.FECHA_PQR     
                             )      
    ) LECTURA ,  MI.SERIAL, P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
        M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR,
        U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO, C.EMAIL EMAIL_CLIENTE, I.ID_PROYECTO ACUEDUCTO, MI.SERIAL
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
        SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U, SGC_TT_CONTRATOS C, SGC_TT_MEDIDOR_INMUEBLE MI
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND MI.FECHA_BAJA IS NULL
        AND MI.COD_INMUEBLE(+) = P.COD_INMUEBLE
        AND C.CODIGO_INM = I.CODIGO_INM
        AND C.FECHA_FIN (+) IS NULL
        AND P.GERENCIA = M.GERENCIA
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

    //////////consulta de reclamos anteriores
    public function reclamosAnteriores($codinmueble)
    {
        $sql = "SELECT COUNT (P.CODIGO_PQR) CANTREC
        FROM SGC_TT_PQRS P
        WHERE P.COD_INMUEBLE = '$codinmueble'";
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

    public function TodosHistReclamos($codinmueble, $sort)
    {
        $sql = "SELECT DISTINCT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHA_PQR, T.DESC_TIPO_RECLAMO, R.DESC_MOTIVO_REC,
		DECODE(P.CERRADO,'S','Cerrado','N','Abierto')CERRADO, DECODE(P.DIAGNOSTICO,'1','Procedente','2','No Procedente')DIAGNOSTICO
        FROM SGC_TT_PQRS P, SGC_TP_TIPOS_RECLAMOS T, SGC_TP_MOTIVO_RECLAMOS R
        WHERE P.TIPO_PQR = T.ID_TIPO_RECLAMO
        AND P.MOTIVO_PQR = R.ID_MOTIVO_REC
		AND P.COD_INMUEBLE = '$codinmueble'
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

    // Funcion para evaluar si los clientes de los reclamos de facturacion tienen cedula registradas
    public function cedExists($codInm)
    {
        $sql = "SELECT t.tipo_doc
			  from sgc_tt_contratos c, sgc_tt_clientes t
			 where c.codigo_inm = '$codInm'
			   and T.TIPO_DOC NOT LIKE '99%'  AND t.codigo_cli = c.codigo_cli";

        $result = oci_parse($this->_db, $sql);

        oci_execute($result);

        oci_fetch($result);
        if (oci_num_rows($result) > 0) {

            return true;

        } else {

            return false;
        }

        oci_free_statement($result);
        oci_close($this->_db);

    }

}
