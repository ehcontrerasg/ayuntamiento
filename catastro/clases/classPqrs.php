<?php

if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

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
		AND ID_TIPO_RECLAMO IN (1,2)";
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
		
		 $sql;
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
		$sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, NVL(P.TEL_CLIENTE, P.CEL_CLIENTE)P.TEL_CLIENTE, P.DESCRIPCION, F.AREA_ACTUAL, F.ORDEN, P.GERENCIA
        FROM SGC_TT_PQRS P, SGC_TT_PQR_FLUJO F
        WHERE P.CODIGO_PQR = F.CODIGO_PQR
        AND F.FECHA_SALIDA IS NULL 
        AND P.CODIGO_PQR = '$cod_pqr'
		union
        SELECT TO_NUMBER(111111)COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, NVL(P.TEL_CLIENTE, P.CEL_CLIENTE)P.TEL_CLIENTE, P.DESCRIPCION, F.AREA_ACTUAL, F.ORDEN, 'E' GERENCIA
        FROM SGC_TT_PQRS_CATASTRALES P, SGC_TT_PQR_FLUJO_CAT F
        WHERE P.CODIGO_PQR = F.CODIGO_PQR
        AND F.FECHA_SALIDA IS NULL 
        AND P.CODIGO_PQR = '$cod_pqr'";
		
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
	
	public function seleccionaDepartamento ($area_user){
		$sql = "SELECT ID_AREA, DESC_AREA
		FROM SGC_TP_AREAS
		WHERE ID_AREA NOT IN ('$area_user')
		AND   RECIBE_PQR = 'S'
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
}