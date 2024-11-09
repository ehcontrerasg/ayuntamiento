<?php
require_once "class.conexion.php";
class PQRs extends ConexionClass{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
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


    public function seleccionaAcueducto (){
		$sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
		FROM SGC_TP_PROYECTOS
		ORDER BY SIGLA_PROYECTO";
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
	
	 public function seleccionaTipoPqr(){
		$sql = "SELECT ID_TIPO_RECLAMO, DESC_TIPO_RECLAMO
		FROM SGC_TP_TIPOS_RECLAMOS WHERE CLASIFICACION = 'P'
		AND ID_TIPO_RECLAMO IN (1,2,3)";
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
	
	public function obtieneAreaUsuario ($coduser){
		$sql = "SELECT A.ID_AREA
		FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C, SGC_TP_AREAS A
		WHERE U.ID_CARGO = C.ID_CARGO
		AND C.ID_AREA = A.ID_AREA
		AND U.ID_USUARIO = '$coduser'";
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
	
	
	public function CantidadRegistrosPqr ($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $where, $area_user){
		$sql = "SELECT SUM(CANTIDAD)CANTIDAD FROM(
		SELECT COUNT(P.CODIGO_PQR) CANTIDAD, SUM(P.CODIGO_PQR)
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL 
		AND F.AREA_ACTUAL = '1' $where";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        if ($codpqr != '') $sql .= " AND P.CODIGO_PQR = '$codpqr'";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
		if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
		if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= " UNION
		SELECT COUNT(P.CODIGO_PQR) CANTIDAD, SUM(P.CODIGO_PQR)
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO_CAT F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND M.GERENCIA = 'E'
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL 
		AND F.AREA_ACTUAL = '1' $where";
        if ($proyecto != '') $sql .= " AND P.PROYECTO = '$proyecto'";
        if ($codpqr != '') $sql .= " AND P.CODIGO_PQR = '$codpqr'";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($cod_inmueble != '') $sql .= " AND P.MOTIVO_PQR <> 64";
		if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
		if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= " )";
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
	
	public function obtenerDatosPQRs($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $start, $end, $where, $area_user){
		$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum 
		FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, P.COD_INMUEBLE, (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO, 
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY HH24:MI:SS')FECHAMAX, I.ID_PROCESO, A.DESC_AREA,
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
		AND F.AREA_ACTUAL = '1' $where";
		if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
		if ($codpqr != '') $sql .= " AND P.CODIGO_PQR = '$codpqr'";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
		if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= "UNION
		SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, to_number(111111), (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO, 
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECHAMAX, '00000000000', A.DESC_AREA,
        CASE WHEN (P.FECHA_MAX_RESOL - SYSDATE) <= 0 THEN 0 ELSE ROUND(P.FECHA_MAX_RESOL - SYSDATE) END PORVENCER, 
        CASE WHEN (SYSDATE - P.FECHA_MAX_RESOL) <= 0 THEN 0 ELSE ROUND(SYSDATE - P.FECHA_MAX_RESOL)END VENCIDOS 
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO_CAT F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND M.GERENCIA = 'E'
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
		AND F.AREA_ACTUAL = '1' $where";
		if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
		if ($proyecto != '') $sql .= " AND P.PROYECTO = '$proyecto'";
        if ($codpqr != '') $sql .= " AND P.CODIGO_PQR = '$codpqr'";
		if ($cod_inmueble != '') $sql .= "  AND P.MOTIVO_PQR <> 64";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= " ORDER BY 1 ASC )a WHERE rownum <= $start ) WHERE rnum >= $end+1";
		
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
	
	public function obtieneDatosPqr ($cod_pqr){
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
	
	public function obtieneResolucionesPqrs ($cod_pqr){
		$sql = "SELECT A.DESC_AREA, F.RESPUESTA
		FROM SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
		WHERE A.ID_AREA = F.AREA_ACTUAL 
		AND F.CODIGO_PQR = '$cod_pqr'
		AND F.FECHA_SALIDA IS NOT NULL
		UNION
        SELECT A.DESC_AREA, FC.RESPUESTA
        FROM SGC_TT_PQR_FLUJO_CAT FC, SGC_TP_AREAS A
        WHERE A.ID_AREA = FC.AREA_ACTUAL 
        AND FC.CODIGO_PQR = '$cod_pqr'
        AND FC.FECHA_SALIDA IS NOT NULL";
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


	public function IngresaFlujoPqr($cod_inm,$cod_pqr,$resolucion,$area_act,$orden,$coduser,$area_res){
        $resolucion=addslashes($resolucion);
       	$sql="BEGIN SGC_P_INGRESA_FLUJO_PQR('$cod_inm','$cod_pqr','$resolucion','$area_act','$orden','$coduser','$area_res',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

		$resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);
		
		if($bandera){
	        if($this->codresult==0){
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }
		}
		else{
        	oci_close($this->_db);
            return false;
        }
    }	
    
    public function actualizaQuejaSugerencia($cod_form,$cod_motivo_rec){
        $cod_form=addslashes($cod_form);
        $cod_motivo_rec=addslashes($cod_motivo_rec);
       	$sql="UPDATE TBL_FORM_QUEJAS_SUGERENCIAS@LK_CAASDENLINEA SET MOTIVO_REC_ID_ASIGNADO=$cod_motivo_rec WHERE CODIGO_FORMULARIO=$cod_form
 ";
		
		//echo $sql;
		$resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
		
		if($bandera){
		    return true;
		}
		else{
        	oci_close($this->_db);
            return false;
        }
    }
	
	public function seleccionaDepartamento ($area_user){
		$sql = "SELECT ID_AREA, DESC_AREA
		FROM SGC_TP_AREAS
		WHERE ID_AREA NOT IN ('$area_user')
		ORDER BY DESC_AREA";
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
	
	public function generaDocPqr ($cod_pqr){
		$sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,  
        M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR, 
        U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO, P.EMAIL_CLIENTE, I.ID_PROYECTO ACUEDUCTO
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

    public function obtenerDeudaAcumuldaInm($codImm,$proyecto){
        $sql = "";
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

    public function GetInmPqrsReSolicitadas($proyecto, $tipo, $motivo, $fecIni, $fecFin)
    {
      //  echo $motivo;
        $where="";
        if ($tipo!="Tipo PQR:")
            $where.=" AND TIPO_PQR='$tipo' ";

       if ($motivo!=0)
            $where.=" AND MOTIVO_PQR='$motivo' ";

        if ($fecIni!="")
            $where.=" AND TO_CHAR(FECHA_REGISTRO,'YYYY-MM-DD')  >= '$fecIni' ";

        if ($fecFin!="")
            $where.="  AND TO_CHAR(FECHA_REGISTRO,'YYYY-MM-DD')  <= '$fecFin' ";

        $sql = "SELECT COD_INMUEBLE,MOTIVO_PQR
             FROM SGC_TT_PQRS, SGC_TT_INMUEBLES
   WHERE COD_INMUEBLE=CODIGO_INM AND 
   ID_PROYECTO='$proyecto' 
    $where
    GROUP BY  COD_INMUEBLE, MOTIVO_PQR
    HAVING count(MOTIVO_PQR) > 1";

    // echo $sql;
        //  echo $motivo;


        $result = oci_parse($this->_db, $sql);

        $flag = oci_execute($result);
        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False in GetInmPqrsReSolicitadas';
            return false;
        }
    }

    public function GetFechPqrsReSol($inmueble, $motivo,$fecIni,$fecFin)
    {

        $where="";
        if ($fecIni!="")
            $where.=" AND TO_CHAR(FECHA_REGISTRO,'YYYY-MM-DD')  >= '$fecIni' ";

        if ($fecFin!="")
            $where.="  AND TO_CHAR(FECHA_REGISTRO,'YYYY-MM-DD')  <= '$fecFin' ";

        $sql = "SELECT  pq.COD_INMUEBLE,pq.MOTIVO_PQR,TO_CHAR(FECHA_REGISTRO,'YYYY-MM-DD HH24:MI:SS') FECHA_REGISTRO
from SGC_TT_PQRS pq
WHERE pq.COD_INMUEBLE=$inmueble AND MOTIVO_PQR=$motivo
GROUP BY COD_INMUEBLE,MOTIVO_PQR,pq.FECHA_REGISTRO
HAVING COUNT(FECHA_REGISTRO)=1
$where
ORDER BY FECHA_REGISTRO";

  //echo  $sql ;

        $result = oci_parse($this->_db, $sql);

        $flag = oci_execute($result);
        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False in GetFechPqrsReSol';
            return false;
        }
    }

    public function GetPqrsReSolValidas($inmueble, $motivo, $fec)
    {
        $sql = "SELECT pq2.CODIGO_PQR, pq2.COD_INMUEBLE,(pq2.MOTIVO_PQR||'-'|| r.DESC_MOTIVO_REC) MOTIVO_PQR,DIAS_RESOLUCION, TO_CHAR(FECHA_REGISTRO,'DD/MM/YYYY HH12:MI:SS') FECHA_REGISTRO,(  SELECT 24 * (to_date(to_char(max(pq.FECHA_REGISTRO), 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi') - to_date(to_char( min(pq.FECHA_REGISTRO),'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi'))/24
                                                                             FROM SGC_TT_PQRS pq
                                                                             WHERE pq.COD_INMUEBLE=$inmueble and MOTIVO_PQR=$motivo AND FECHA_REGISTRO BETWEEN to_date('$fec','YYYY-MM-DD HH24:MI:SS')
    AND to_date('$fec','YYYY-MM-DD HH24:MI:SS')+30) as DIAS
FROM  SGC_TT_PQRS pq2,SGC_TP_MOTIVO_RECLAMOS r
WHERE pq2.MOTIVO_PQR=r.ID_MOTIVO_REC and pq2.COD_INMUEBLE=$inmueble and pq2.MOTIVO_PQR=$motivo  AND r.GERENCIA='E' AND pq2.FECHA_REGISTRO BETWEEN to_date('$fec','YYYY-MM-DD HH24:MI:SS')
    AND to_date('$fec','YYYY-MM-DD HH24:MI:SS')+30
    ORDER BY  pq2.FECHA_REGISTRO";

      // echo $sql ;

        $result = oci_parse($this->_db, $sql);

        $flag = oci_execute($result);
        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False in GetPqrsReSolValidas';
            return false;
        }
    }


    function GetFormQuejasSugerencias($codFormulario,$fecini, $fecfin,$proyecto){
	    $where="";
	    if (!empty($fecini) && !empty($fecfin))
            $where.=" AND FECHA BETWEEN TO_DATE('$fecini','YYYY-MM-DD') AND TO_DATE('$fecfin','YYYY-MM-DD')+1";

	    if (!empty($codFormulario))
            $where.=" AND QS.CODIGO_FORMULARIO='$codFormulario'";

	    if (!empty($proyecto))
            $where.=" AND QS.PROYECTO='$proyecto'";



        $sql="SELECT QS.*,PR.CODIGO_PREGUNTA,PR.CODIGO_RESPUESTA,P.PREGUNTA,R.RESPUESTA,PQR.MOTIVO_PQR
              FROM TBL_FORM_QUEJAS_SUGERENCIAS@LK_CAASDENLINEA QS,
                   TBL_FORM_PREGUNTAS_RESPUESTAS@LK_CAASDENLINEA  PR,
                   TBL_FORM_PREGUNTAS@LK_CAASDENLINEA  P,
                   TBL_FORM_RESPUESTAS@LK_CAASDENLINEA R,
                   SGC_TT_PQRS PQR
              WHERE QS.CODIGO_FORMULARIO=PR.CODIGO_FORMULARIO
                AND QS.CODIGO_PQR=PQR.CODIGO_PQR(+)
                AND PR.CODIGO_RESPUESTA=R.CODIGO
                AND PR.CODIGO_PREGUNTA=P.CODIGO
                            $where         
                            ORDER BY  FECHA,CODIGO_PREGUNTA";

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



    function verificaRespuesta($codPregunta,$codRespuesta,$codFormulario){


        $sql="SELECT QS.*,PR.CODIGO_PREGUNTA,PR.CODIGO_RESPUESTA
              FROM TBL_FORM_QUEJAS_SUGERENCIAS@LK_CAASDENLINEA QS,
                   TBL_FORM_PREGUNTAS_RESPUESTAS@LK_CAASDENLINEA  PR
              WHERE QS.CODIGO_FORMULARIO=PR.CODIGO_FORMULARIO
              AND PR.CODIGO_RESPUESTA=$codRespuesta
              AND PR.CODIGO_PREGUNTA=$codPregunta
              AND QS.CODIGO_FORMULARIO=$codFormulario";
       //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            if (oci_num_rows($resultado) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            oci_close($this->_db);
            echo "error consulta en funcion verificaRespuesta()";
        }
    }

    public function getPregunta()
    {
        $sql = "SELECT * FROM TBL_FORM_PREGUNTAS@LK_CAASDENLINEA";
        $result = oci_parse($this->_db, $sql);
        $flag = oci_execute($result);

        if ($flag) {
            oci_fetch_all($result, $data);
            return $data;
        } else {
            return 0;
        }
    }

    public function getRespuesta()
    {
        $sql = "SELECT * FROM TBL_FORM_RESPUESTAS@LK_CAASDENLINEA";

        $result = oci_parse($this->_db, $sql);
        $flag = oci_execute($result);

        if ($flag) {
            oci_fetch_all($result, $data);
            return $data;
        } else {
            return 0;
        }
    }


    public function getCantPreguntas($codFormulario)
    {
        $sql = "SELECT COUNT(*) CANTCONT
FROM TBL_FORM_QUEJAS_SUGERENCIAS@LK_CAASDENLINEA QS,
     TBL_FORM_PREGUNTAS_RESPUESTAS@LK_CAASDENLINEA  PR
/*     TBL_FORM_PREGUNTAS@LK_CAASDENLINEA  P,
     TBL_FORM_RESPUESTAS@LK_CAASDENLINEA R,*/
WHERE QS.CODIGO_FORMULARIO=PR.CODIGO_FORMULARIO AND QS.CODIGO_FORMULARIO='$codFormulario'";

        $result = oci_parse($this->_db, $sql);
        $flag   = oci_execute($result);

        if ($flag == true) {
            return oci_fetch_assoc($result)['CANTCONT'];
        } else {
            return 0;
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

    // Reporte estadistica de PQRS detallados

    public function GetRepEstDet($proyecto, $tipo, $motivo=0, $fecIni, $fecFin)
    {
        $sql = "SELECT distinct pqr.codigo_pqr        Codigo,
                       TO_CHAR(pqr.FECHA_REGISTRO, 'DD-MM-YYYY HH:MI:SS AM') FECHA_PQR,
                       pqr.cod_inmueble      inmueble,
                       pqr.nom_cliente,
                       pqr.dir_cliente       direccion,
                       pqr.tel_cliente       telefono,
                       pqr.medio_rec_pqr,
                       i.id_zona             zona,
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
                           SGC_TP_PUNTO_PAGO p
                 where i.codigo_inm = pqr.cod_inmueble
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
                        SGC_TP_PUNTO_PAGO p
                 where i.codigo_inm = pqr.cod_inmueble
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
                        TO_CHAR(pqr.FECHA_REGISTRO, 'DD-MM-YYYY HH:MI:SS AM') FECHA_PQR,
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
                            and pf2.respuesta is not null) respuesta,p.DESCRIPCION OFICINA,
                        (select dif_diaslab( pqr.FECHA_REGISTRO, (select min(pf.fecha_salida)
                           from sgc_tt_pqr_flujo_cat pf
                          where pf.codigo_pqr = pqr.codigo_pqr)) from dual) Tiempo_respuesta
                      from sgc_tt_pqrs_catastrales          pqr,
                           sgc_tp_diagnosticos_pqr dpqr,
                           sgc_tp_motivo_reclamos  mrc,
                            SGC_TP_PUNTO_PAGO p
                     where pqr.diagnostico = dpqr.id_diagnostico
                       and pqr.COD_PUNTO=p.ID_PUNTO_PAGO
                       and pqr.motivo_pqr = mrc.id_motivo_rec
                       and pqr.diagnostico in (1,2)
                       and pqr.proyecto = '$proyecto'
                       and pqr.cerrado = 'S'
                       AND pqr.fecha_pqr BETWEEN To_date('$fecIni' || '00:00:00', 'YYYY-MM-DD HH24:MI:SS') and
                           to_date('$fecFin' || '23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                       and pqr.tipo_pqr = $tipo
                       and pqr.motivo_pqr = 64
                       order by pqr.fecha_pqr";

     //  echo $sql;

        $result = oci_parse($this->_db, $sql);
        $flag = oci_execute($result);


        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False';
            return false;
        }
    }

    public function obtenerDatosInmueblePorPqr($cod_pqr){

        $sql ="SELECT I.CODIGO_INM,I.DIRECCION||' '||U.DESC_URBANIZACION DIRECCION,I.ID_ZONA,I.ID_PROYECTO
              FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U,SGC_TT_PQRS PQR
              WHERE I.CONSEC_URB = U.CONSEC_URB AND
              I.CODIGO_INM = PQR.COD_INMUEBLE AND
              PQR.CODIGO_PQR=$cod_pqr";

        $result = oci_parse($this->_db, $sql);

        $flag = oci_execute($result);
        if ($flag == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo 'False';
            return false;
        }
    }

    public function pqrsSegmentado($fechaInicio, $fechaFin,$proyecto = '',$departamento = ''){

        $where = " AND A.ID_AREA IN (1,2,3,4,9,10)";
	    if ($departamento != '')
            $where = " AND A.ID_AREA = $departamento";

        $whereProyecto = "";
	    if($proyecto != ''){
            $whereProyecto =  " AND INM1.ID_PROYECTO = '$proyecto'";
            $whereProyectoPqrsCatastrales =  " AND PQRS1.PROYECTO = '$proyecto'";
        }


        $sql = "SELECT DISTINCT A.DESC_AREA DEPARTAMENTO, TR.DESC_TIPO_RECLAMO TIPO_RECLAMO, PQRS.MOTIVO_PQR CODIGO_RECLAMO,
                            MR.DESC_MOTIVO_REC TIPO_PQR,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                    AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                    ".$whereProyecto.")
                                +
                                (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                    ".$whereProyectoPqrsCatastrales.")) GENERADOS,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND (PQRS1.DIAGNOSTICO = 0  OR PQRS1.DIAGNOSTICO IS NULL)
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                    ".$whereProyecto.")
                                +
                                (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND (PQRS1.DIAGNOSTICO = 0  OR PQRS1.DIAGNOSTICO IS NULL)
                                    ".$whereProyectoPqrsCatastrales.")
                            ) PENDIENTES,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO = 1
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                            ".$whereProyecto.")
                            +
                            (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO = 1
                            ".$whereProyectoPqrsCatastrales.")
                            ) PROCEDENTE,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO = 2
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                            ".$whereProyecto.")
                            +
                            (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO = 2
                            ".$whereProyectoPqrsCatastrales.")
                            ) NO_PROCEDENTE,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO = 3
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                            ".$whereProyecto.")
                            +
                            (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO = 3
                            ".$whereProyectoPqrsCatastrales.")
                            ) PESTAÑAS_INCORRECTAS,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO IN (1,2,3)
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                            ".$whereProyecto.")
                            +
                            (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO IN (1,2,3)
                            ".$whereProyectoPqrsCatastrales.")) TOTAL_CERRADO,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO IN (1,2)
                            AND (select min(pf.fecha_salida)
                                    from sgc_tt_pqr_flujo pf
                                    where pf.codigo_pqr = PQRS1.codigo_pqr) <= PQRS1.FECHA_MAX_RESOL
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                            ".$whereProyecto.")
                            +
                            (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO IN (1,2)
                            AND (select min(pf.fecha_salida)
                                    from sgc_tt_pqr_flujo_cat pf
                                    where pf.codigo_pqr = PQRS1.codigo_pqr) <= PQRS1.FECHA_MAX_RESOL
                            ".$whereProyectoPqrsCatastrales.")) DENTRO_TIEMPO,
                            ((SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO IN (1,2)
                            AND (select min(pf.fecha_salida)
                                    from sgc_tt_pqr_flujo pf
                                    where pf.codigo_pqr = PQRS1.codigo_pqr) > PQRS1.FECHA_MAX_RESOL
                            AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                            ".$whereProyecto.")
                            +
                            (SELECT COUNT(*)CANTIDAD
                            FROM SGC_TT_PQRS_CATASTRALES PQRS1
                            WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                            AND PQRS1.FECHA_PQR BETWEEN TO_DATE('$fechaInicio 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                    AND TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                            AND PQRS1.DIAGNOSTICO IN (1,2)
                            AND (select min(pf.fecha_salida)
                                    from sgc_tt_pqr_flujo_cat pf
                                    where pf.codigo_pqr = PQRS1.codigo_pqr) > PQRS1.FECHA_MAX_RESOL
                            ".$whereProyectoPqrsCatastrales.")) FUERA_TIEMPO,                    
                            (SELECT ROUND(((
                                (SELECT NVL(SUM(PQRS1.FECHA_CIERRE - PQRS1.FECHA_PQR), 0) VALOR
                                        FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                                        WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                        ".$whereProyecto.")
                                        +
                                        (SELECT NVL(SUM(PQRS1.FECHA_CIERRE - PQRS1.FECHA_PQR), 0)VALOR
                                    FROM SGC_TT_PQRS_CATASTRALES PQRS1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        ".$whereProyectoPqrsCatastrales.")
                                        )
                                        /
                                        CASE WHEN((SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                        ".$whereProyecto.")
                                        +
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS_CATASTRALES PQRS1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        ".$whereProyectoPqrsCatastrales.")) = 0 THEN 1 
                                        ELSE
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                        ".$whereProyecto.")
                                        +
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS_CATASTRALES PQRS1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        ".$whereProyectoPqrsCatastrales.")
                                        END ),2) FROM DUAL) TIEMPO_PROMEDIO_DIAS,
                                        (SELECT ROUND((
                                                ((SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND (select min(pf.fecha_salida)
                                            from sgc_tt_pqr_flujo pf
                                            where pf.codigo_pqr = PQRS1.codigo_pqr) <=
                                            PQRS1.FECHA_MAX_RESOL
                                        AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                        ".$whereProyecto.")
                                        +
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS_CATASTRALES PQRS1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND (select min(pf.fecha_salida)
                                            from sgc_tt_pqr_flujo_cat pf
                                            where pf.codigo_pqr = PQRS1.codigo_pqr) <=
                                            PQRS1.FECHA_MAX_RESOL
                                        ".$whereProyectoPqrsCatastrales.")
                                        )
                                        /
                                        CASE WHEN(
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                        ".$whereProyecto.")
                                        +
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS_CATASTRALES PQRS1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        ".$whereProyectoPqrsCatastrales.") 
                                        ) = 0 THEN 1
                                        ELSE
                                        (
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS PQRS1, ACEASOFT.SGC_TT_INMUEBLES INM1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        AND INM1.CODIGO_INM = PQRS1.COD_INMUEBLE
                                        ".$whereProyecto.")
                                        +
                                        (SELECT COUNT(*) VALOR
                                    FROM SGC_TT_PQRS_CATASTRALES PQRS1
                                    WHERE PQRS1.MOTIVO_PQR = PQRS.MOTIVO_PQR
                                        AND PQRS1.FECHA_PQR BETWEEN
                                            TO_DATE('$fechaInicio 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaFin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                                        AND PQRS1.DIAGNOSTICO IN (1, 2)
                                        ".$whereProyectoPqrsCatastrales.") 
                                        ) END),2) * 100 FROM DUAL)EFECTIVIDAD
                    FROM SGC_TT_PQRS PQRS, SGC_TP_TIPOS_RECLAMOS TR,SGC_TP_MOTIVO_RECLAMOS MR, SGC_TP_AREAS A
                    WHERE PQRS.TIPO_PQR IN (1,2)
                        AND MR.ID_MOTIVO_REC(+) = PQRS.MOTIVO_PQR
                        AND A.ID_AREA = MR.AREA_PERTENECE
                        AND TR.ID_TIPO_RECLAMO = PQRS.TIPO_PQR                                  
                        ".$where."
                    ORDER BY A.DESC_AREA, TR.DESC_TIPO_RECLAMO, PQRS.MOTIVO_PQR";

	    $statement = oci_parse($this->_db, $sql);

	    if(oci_execute($statement)){

	        $json = array();
	        while($fila = oci_fetch_assoc($statement)){

	            $arr = array(

	                'departamento'     => $fila["DEPARTAMENTO"],
	                'tipo_reclamo'     => $fila["TIPO_RECLAMO"],
	                'codigo_reclamo'   => $fila["CODIGO_RECLAMO"],
	                'tipo_pqr'         => $fila["TIPO_PQR"],
	                'generados'        => $fila["GENERADOS"],
	                'pendientes'       => $fila["PENDIENTES"],
	                'procedente'       => $fila["PROCEDENTE"],
	                'no_procedente'    => $fila["NO_PROCEDENTE"],
                    'ps_incorrecta'    => $fila["PESTAÑAS_INCORRECTAS"],
	                'total_cerrado'    => $fila["TOTAL_CERRADO"],
	                'dentro_tiempo'    => $fila["DENTRO_TIEMPO"],
	                'fuera_tiempo'     => $fila["FUERA_TIEMPO"],
	                'tiempo_promedio'  => $fila["TIEMPO_PROMEDIO_DIAS"],
	                'efectividad'      => $fila["EFECTIVIDAD"]
                );
	            array_push($json,$arr);
            }

	        return array('codigo' => 200, 'mensaje' => $json);
        }

        $error = oci_error($statement);
        return array('codigo' => $error["code"], 'mensaje' => $error["message"]);
    }

    public function seleccionaDepartamentoPorUsuario($cod_user){
		/* Función utilizada para rellenar el select de departamentos en la vista para dar resolución a PQRS que tienen los encargados de departamento (vista.edita_pqr) */
	    $sql = "SELECT A.ID_AREA, INITCAP(A.DESC_AREA) DESC_AREA
		FROM SGC_TP_AREA_ENVIA_PQR AP, SGC_TP_AREAS A
	   WHERE AP.AREA = (SELECT C.ID_AREA
						  FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C
						 WHERE U.ID_USUARIO = '$cod_user'
						   AND C.ID_CARGO = U.ID_CARGO)
		 AND AP.ENVIA_A = A.ID_AREA";
		
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
}