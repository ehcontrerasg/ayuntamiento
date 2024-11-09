<?php
include "../clases/class.conexion.php";
class AdminPqr extends ConexionClass{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }
	
	 public function cantidadMedios(){
		$sql = "SELECT COUNT(ID_MEDIO_REC)CANTIDAD
		FROM SGC_TP_MEDIO_RECEPCION";
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
	
	public function totalMedios ($sort, $start, $end, $where)
	{	
		$sql="SELECT * 
				FROM ( 
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM ( 
						SELECT  ID_MEDIO_REC, DESC_MEDIO_REC
						FROM SGC_TP_MEDIO_RECEPCION
						$where
						 $sort
						)a WHERE  ROWNUM<$start
					) WHERE rnum >= $end+1";
		//echo $sql;
		$resultado = oci_parse(
				$this->_db,$sql
				);
			
			
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	 public function obtenerDatosCliente($cod_inmueble){
		$sql = "SELECT I.CODIGO_INM, I.DIRECCION, U.DESC_URBANIZACION, C.CODIGO_CLI, C.ALIAS, I.ID_ESTADO, A.DESC_ACTIVIDAD, A.ID_USO, I.ID_PROYECTO, P.DESC_PROYECTO,
        I.ID_ZONA, I.UNIDADES_HAB, S.CONSUMO_MINIMO, L.EMAIL, L.DOCUMENTO, L.TELEFONO, SC.ID_GERENCIA
        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TP_ACTIVIDADES A, SGC_TP_PROYECTOS P, 
        SGC_TT_SERVICIOS_INMUEBLES S, SGC_TT_CLIENTES L, SGC_TP_SECTORES SC
        WHERE I.CONSEC_URB = U.CONSEC_URB 
        AND I.ID_SECTOR = SC.ID_SECTOR
        AND S.COD_INMUEBLE =I.CODIGO_INM
        AND L.CODIGO_CLI = C.CODIGO_CLI
        AND S.COD_SERVICIO IN(1,3)
        AND C.CODIGO_INM = I.CODIGO_INM AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND I.ID_PROYECTO = P.ID_PROYECTO AND I.CODIGO_INM = '$cod_inmueble'";
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
	
	
	public function obtenerDatosCliente2($cod_inmueble){
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
	
	
	public function CantidadFacHis ($fname,$tname,$where,$sort)
	{	
		$resultado = oci_parse($this->_db,"
				
				SELECT COUNT(*)CANTIDAD  FROM SGC_TT_FACTURA F 
				WHERE FACTURA_PAGADA = 'N'
				 $where");

			
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
	
	public function CantidadFacHisEnt ($fname,$tname,$where,$sort)
	{	
		$resultado = oci_parse($this->_db,"
				
				SELECT COUNT(*)CANTIDAD  FROM SGC_TT_FACTURA F 
				WHERE F.FACTURA_PAGADA = 'S'
				 $where");

			
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
	
	
	public function TodosFacHis ($where,$sort,$start,$end, $inmueble)
	{	
		$sql="SELECT * 
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
				$this->_db,$sql
				);
			
			
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
	
	
	
	public function TodosFacHisEnt ($where,$sort,$start,$end, $inmueble)
	{	
		$sql="SELECT * 
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
				$this->_db,$sql
				);
			
			
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
	
	
	
	public function totalfacven ($cod_inmueble)
    {
        $sql="SELECT NVL(SUM(F.TOTAL-F.TOTAL_PAGADO),0) DEUDA, COUNT (F.FACTURA_PAGADA) FACPEND
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I 
        WHERE F.FACTURA_PAGADA = 'N'
        AND FEC_VCTO < SYSDATE
        AND F.FEC_EXPEDICION IS NOT NULL
        AND I.CODIGO_INM = F.INMUEBLE 
        AND I.CODIGO_INM = '$cod_inmueble'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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
	
	public function totalfacent ($cod_inmueble)
    {
        $sql="SELECT COUNT (F.FACTURA_PAGADA) FACENT
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I 
        WHERE F.FACTURA_PAGADA = 'S'
        AND I.CODIGO_INM = F.INMUEBLE 
        AND I.CODIGO_INM = '$cod_inmueble'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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
	
	 public function facpend ($where,$sort,$start,$end)
    {
        $sql="SELECT *
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
            $this->_db,$sql
        );


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
	
	public function urlfotomant($inmueble)
	{
		$sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_MANTENIMIENTO 
		WHERE ID_INMUEBLE='$inmueble'";
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
	
	public function urlfotolect($inmueble)
	{
		$sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_LECTURA 
		WHERE ID_INMUEBLE='$inmueble'";
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
	
	public function urlfotofact($inmueble)
	{
		$sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_FACTURA 
		WHERE ID_INMUEBLE='$inmueble'";
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
	
	
	public function urlfotocorte($inmueble)
	{
		$sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_CORTE 
		WHERE ID_INMUEBLE='$inmueble'";
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
	
	 public function obtenerDatosMedidor($cod_inmueble){
		$sql = "SELECT ME.DESC_MED, E.DESC_EMPLAZAMIENTO, C.DESC_CALIBRE, M.SERIAL, EM.DESCRIPCION, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FEC_INSTAL
		FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES ME, SGC_TP_EMPLAZAMIENTO E, SGC_TP_CALIBRES C, SGC_TP_ESTADOS_MEDIDOR EM
		WHERE M.COD_MEDIDOR = ME.CODIGO_MED(+)
		AND M.COD_EMPLAZAMIENTO = E.COD_EMPLAZAMIENTO(+)
		AND M.COD_CALIBRE = C.COD_CALIBRE(+)
		AND M.ESTADO_MEDIDOR = EM.CODIGO(+)
		AND M.COD_INMUEBLE = '$cod_inmueble'";
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
	
	 public function seleccionaMedioRecep(){
		$sql = "SELECT ID_MEDIO_REC, DESC_MEDIO_REC
		FROM SGC_TP_MEDIO_RECEPCION ORDER BY 1";
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
		FROM SGC_TP_TIPOS_RECLAMOS WHERE CLASIFICACION = 'P'";
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
	
	public function seleccionaMotivoPqr(){
		$sql = "SELECT DISTINCT ID_MOTIVO_REC, DESC_MOTIVO_REC
				 FROM SGC_TP_MOTIVO_RECLAMOS
				ORDER BY ID_MOTIVO_REC ";
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
	
	 public function IngresaPqr($fecha,$cod_inmueble,$nom_cli,$ced_cli,$direccion,$tel_cli,$mail_cli,$medio,$tipo,$motivo,$fecha_res,$descripcion,$cod_ent,$id_punto,$num_caja,$coduser,$ger_inm,$area_res){
       	$sql="BEGIN SGC_P_INGRESA_PQR(TO_DATE('$fecha','DD/MM/YYYY HH24:MI:SS'),'$cod_inmueble','$nom_cli','$ced_cli','$direccion','$tel_cli','$mail_cli','$medio','$tipo','$motivo',TO_DATE('$fecha_res','DD/MM/YYYY'),'$descripcion','$cod_ent','$id_punto','$num_caja','$coduser','$ger_inm','$area_res',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		//echo $sql;
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
	
	
	public function seleccionaDepartamento (){
		$sql = "SELECT ID_AREA, DESC_AREA
		FROM SGC_TP_AREAS
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
	
	public function CantidadRegistrosPqr ($proyecto,$departamento,$secini,$secfin,$rutini,$rutfin,$fecini,$fecfin,$cod_inmueble,$where){
		$sql = "SELECT COUNT(P.CODIGO_PQR) CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL  $where";
		if ($departamento != '') $sql .= " AND F.AREA_ACTUAL = $departamento";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
		if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
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
	
	
	 public function obtenerDatosPQRs($proyecto, $departamento, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble,$start,$end,$where){
		$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum 
		FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, P.COD_INMUEBLE, (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO, 
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECHAMAX, I.ID_PROCESO, A.DESC_AREA,
        CASE WHEN (P.FECHA_MAX_RESOL - SYSDATE) <= 0 THEN 0 ELSE ROUND(P.FECHA_MAX_RESOL - SYSDATE) END PORVENCER, 
        CASE WHEN (SYSDATE - P.FECHA_MAX_RESOL) <= 0 THEN 0 ELSE ROUND(SYSDATE - P.FECHA_MAX_RESOL)END VENCIDOS 
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL  $where";
		if ($departamento != '') $sql .= " AND F.AREA_ACTUAL = $departamento";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
		if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= " ORDER BY TO_DATE(TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY hh24:mi:ss') DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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
	
	
	 public function IngresaFlujoPqr($cod_inm,$cod_pqr,$resolucion,$area_act,$orden,$coduser,$area_res){
       	$sql="BEGIN SGC_P_INGRESA_FLUJO_PQR('$cod_inm','$cod_pqr','$resolucion','$area_act','$orden','$coduser','$area_res',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		
		//echo $sql;
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
	
	public function obtieneResolucionesPqrs ($cod_pqr){
		$sql = "SELECT A.DESC_AREA, F.RESPUESTA
		FROM SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
		WHERE A.ID_AREA = F.AREA_ACTUAL 
		AND F.CODIGO_PQR = '$cod_pqr'
		AND F.FECHA_SALIDA IS NOT NULL
		ORDER BY F.ORDEN ASC";
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
	
	public function obtieneMotivoResolucionPqrs ($gerencia){
		$sql = "SELECT M.ID_MOTIVO_REC, M.DESC_MOTIVO_REC
		FROM SGC_TP_MOTIVO_RECLAMOS M
		WHERE M.RESOLUCION = 'S'
		AND M.GERENCIA = '$gerencia'
		ORDER BY M.ID_MOTIVO_REC";
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
	
	public function obtieneDiagnosticoPqr(){
		$sql = "SELECT D.ID_DIAGNOSTICO, D.DESC_DIAGNOSTICO
		FROM SGC_TP_DIAGNOSTICOS_PQR D
		ORDER BY D.ID_DIAGNOSTICO";
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
	
	 public function CierraPqr($cod_inm,$cod_pqr,$observa,$area_act,$orden,$tipo_res,$diagnostico,$coduser){
       	$sql="BEGIN SGC_P_CIERRA_PQR('$cod_inm','$cod_pqr','$observa','$area_act','$orden','$tipo_res','$diagnostico','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		
		//echo $sql;
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
	
	
	public function generaDocPqr ($cod_pqr){
		$sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,  
        M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR, 
        U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO, P.EMAIL_CLIENTE
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
        SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U
        WHERE P.COD_INMUEBLE = I.CODIGO_INM 
        AND P.GERENCIA = M.GERENCIA
        AND M.ID_MOTIVO_REC = P.TIPO_PQR
        AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
        AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
        AND E.COD_ENTIDAD = P.COD_ENTIDAD
        AND PP.ID_PUNTO_PAGO = P.COD_PUNTO
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
	
	public function CantidadDatosResumenPqr($proyecto,$zonini,$zonfin,$secini,$secfin,$rutini,$rutfin,$tipo_resol,$tipo_estado,$ofiini,$ofifin,$motivo,$fecinirad,$fecfinrad,$fecinires,$fecfinres,$recini,$recfin,$start,$end,$where){
		$sql = "SELECT COUNT(P.CODIGO_PQR)CANTIDAD
		FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO U,SGC_TP_MOTIVO_RECLAMOS R, SGC_TT_PQR_FLUJO F
		WHERE P.COD_INMUEBLE = I.CODIGO_INM
		AND P.COD_PUNTO = U.ID_PUNTO_PAGO
		AND R.ID_MOTIVO_REC = P.MOTIVO_PQR
		AND R.GERENCIA = P.GERENCIA
		AND F.CODIGO_PQR = P.CODIGO_PQR
		AND F.ORDEN = (SELECT MAX(A.ORDEN) FROM SGC_TT_PQR_FLUJO A WHERE A.CODIGO_PQR = F.CODIGO_PQR)";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($zonini != '' && $zonfin != '') $sql .= " AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($tipo_resol == 1) $sql .= " AND P.DIAGNOSTICO = 1";
		if ($tipo_resol == 2) $sql .= " AND P.DIAGNOSTICO = 2";
		if ($tipo_resol == 3) $sql .= " ";
		if ($tipo_estado == 1) $sql .= " AND FECHA_CIERRE IS NULL";
		if ($tipo_estado == 2) $sql .= " AND FECHA_CIERRE IS NOT NULL";
		if ($tipo_estado == 3) $sql .= "";
		if ($ofiini != '' && $ofifin != '') $sql .= " AND U.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
		if ($motivo != '') $sql .= " AND P.MOTIVO_PQR = $motivo";
		if ($fecinirad != '' && $fecfinrad != '') $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		if ($fecinires != '' && $fecfinres != '') $sql .= " AND P.FECHA_CIERRE BETWEEN TO_DATE('$fecinires 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfinres 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		if ($recini != '' && $recfin != '') $sql .= " AND P.CODIGO_PQR BETWEEN $recini AND $recfin $where ";
		//$sql .= " ORDER BY I.ID_PROCESO DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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
	
	public function datosResumenPqr($proyecto,$zonini,$zonfin,$secini,$secfin,$rutini,$rutfin,$tipo_resol,$tipo_estado,$ofiini,$ofifin,$motivo,$fecinirad,$fecfinrad,$fecinires,$fecfinres,$recini,$recfin,$start,$end,$where){
		$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum 
		FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECRAD, P.COD_INMUEBLE, P.NOM_CLIENTE, P.MEDIO_REC_PQR, I.ID_ZONA,  P.GERENCIA,
		U.COD_VIEJO, P.DESCRIPCION, TO_CHAR(P.FECHA_CIERRE,'DD/MM/YYYY')FECDIAG, DECODE(P.DIAGNOSTICO,1,'PROCEDENTE',2,'NO PROCEDENTE')DIAGNOSTICO,
		TO_CHAR(F.FECHA_SALIDA,'DD/MM/YYYY')FECRESOL, R.DESC_MOTIVO_REC, DECODE(R.FAVORABLE,'S','FAVORABLE','N','DESFAVORABLE')RESOLUCION, F.RESPUESTA
		FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO U, SGC_TP_MOTIVO_RECLAMOS R, SGC_TT_PQR_FLUJO F
		WHERE P.COD_INMUEBLE = I.CODIGO_INM
		AND P.COD_PUNTO = U.ID_PUNTO_PAGO
		AND R.ID_MOTIVO_REC = P.MOTIVO_PQR
		AND R.GERENCIA = P.GERENCIA
		AND F.CODIGO_PQR = P.CODIGO_PQR
		AND F.ORDEN = (SELECT MAX(A.ORDEN) FROM SGC_TT_PQR_FLUJO A WHERE A.CODIGO_PQR = F.CODIGO_PQR)";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($zonini != '' && $zonfin != '') $sql .= " AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
		if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
		if ($tipo_resol == 1) $sql .= " AND P.DIAGNOSTICO = 1";
		if ($tipo_resol == 2) $sql .= " AND P.DIAGNOSTICO = 2";
		if ($tipo_resol == 3) $sql .= " ";
		if ($tipo_estado == 1) $sql .= " AND FECHA_CIERRE IS NULL";
		if ($tipo_estado == 2) $sql .= " AND FECHA_CIERRE IS NOT NULL";
		if ($tipo_estado == 3) $sql .= "";
		if ($ofiini != '' && $ofifin != '') $sql .= " AND U.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
		if ($motivo != '') $sql .= " AND P.MOTIVO_PQR = $motivo";
		if ($fecinirad != '' && $fecfinrad != '') $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		if ($fecinires != '' && $fecfinres != '') $sql .= " AND P.FECHA_CIERRE BETWEEN TO_DATE('$fecinires 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfinres 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		if ($recini != '' && $recfin != '') $sql .= " AND P.CODIGO_PQR BETWEEN $recini AND $recfin $where ";
		$sql .= " ORDER BY I.ID_PROCESO DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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
	
	public function cantDatosTeleCorreo($proyecto,$fecini,$fecfin,$start,$end,$where){
		$sql = "SELECT COUNT(*)CANTIDAD FROM(SELECT COUNT(*)
		FROM SGC_TT_PQRS P, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
		WHERE U.ID_USUARIO = P.USER_RECIBIO_PQR
		AND I.CODIGO_INM = P.COD_INMUEBLE";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		$sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND (TEL_CLIENTE IS NOT NULL OR EMAIL_CLIENTE IS NOT NULL) 
		GROUP BY U.LOGIN, (U.NOM_USR||' '||U.APE_USR))";
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
	
	
	public function datosTeleCorreo($proyecto,$fecini,$fecfin,$start,$end,$where){
		$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum 
		FROM (SELECT U.LOGIN, (U.NOM_USR||' '||U.APE_USR)USUARIO, COUNT(TEL_CLIENTE)CANTTEL, COUNT(EMAIL_CLIENTE)CANTEMAIL
		FROM SGC_TT_PQRS P, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
		WHERE U.ID_USUARIO = P.USER_RECIBIO_PQR
		AND I.CODIGO_INM = P.COD_INMUEBLE";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		$sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND (TEL_CLIENTE IS NOT NULL OR EMAIL_CLIENTE IS NOT NULL) 
		GROUP BY U.LOGIN, (U.NOM_USR||' '||U.APE_USR)";
		$sql .= " ORDER BY U.LOGIN ASC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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
	
	
	public function CantidadDatosInteraccion($proyecto,$ofiini,$ofifin,$fecinirad,$fecfinrad,$login,$start,$end,$where){
		$sql = "SELECT COUNT(*)CANTIDAD
		FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO T
		WHERE I.CODIGO_INM = P.COD_INMUEBLE 
		AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
		AND M.GERENCIA = P.GERENCIA 
		AND U.ID_USUARIO = P.USER_RECIBIO_PQR
		AND T.ID_PUNTO_PAGO = P.COD_PUNTO
		AND P.TIPO_PQR IN(2,5)";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($login != '') $sql .= " AND U.LOGIN = '$login'";
		if ($ofiini != '' && $ofifin != '') $sql .= " AND T.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
		if ($fecinirad != '' && $fecfinrad != '') $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= " ORDER BY U.LOGIN ASC, I.ID_PROCESO";
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
	
	
	public function datosInteraccion($proyecto,$ofiini,$ofifin,$fecinirad,$fecfinrad,$login,$start,$end,$where){
		$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum 
		FROM (SELECT TO_CHAR(FECHA_REGISTRO,'DD/MM/YYYY')FECREG, M.DESC_MOTIVO_REC, P.DESCRIPCION, (U.NOM_USR||' '||U.APE_USR)USUARIO, P.COD_INMUEBLE
		FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO T
		WHERE I.CODIGO_INM = P.COD_INMUEBLE 
		AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
		AND M.GERENCIA = P.GERENCIA 
		AND U.ID_USUARIO = P.USER_RECIBIO_PQR
		AND T.ID_PUNTO_PAGO = P.COD_PUNTO
		AND P.TIPO_PQR IN(2,5)";
		if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
		if ($login != '') $sql .= " AND U.LOGIN = '$login'";
		if ($ofiini != '' && $ofifin != '') $sql .= " AND T.COD_VIEJO BETWEEN $ofiini AND $ofifin ";
		if ($fecinirad != '' && $fecfinrad != '') $sql .= " AND P.FECHA_REGISTRO BETWEEN TO_DATE('$fecinirad 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfinrad 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql .= " ORDER BY U.LOGIN ASC, I.ID_PROCESO)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
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
	
	public function obtenerDatosDiametro($cod_inmueble){
		$sql = "SELECT I.COD_DIAMETRO
        FROM SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = '$cod_inmueble'";
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
	
	public function obtenerDatosCalibre($cod_inmueble){
		$sql = "SELECT M.COD_CALIBRE
        FROM SGC_TT_MEDIDOR_INMUEBLE M
        WHERE M.COD_INMUEBLE = '$cod_inmueble'";
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
	
	public function obtenerValorReconex($calibre, $diametro, $uso){
		$sql = "SELECT T.VALOR_TARIFA
        FROM SGC_TP_TARIFAS_RECONEXION T
        WHERE T.CODIGO_CALIBRE = '$calibre' AND T.CODIGO_DIAMETRO = '$diametro' AND T.CODIGO_USO = '$uso'";
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
	
	public function CantidadObserva ($inmueble)
	{	
		$resultado = oci_parse($this->_db,"
				
				SELECT COUNT(CONSECUTIVO)CANTIDAD  
				FROM SGC_TT_OBSERVACIONES_INM 
				WHERE INM_CODIGO = '$inmueble'");

		//echo $sql;	
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
	
		public function TodasObserva ($sort,$start,$end, $inmueble)
	{	
		$sql="SELECT * 
				FROM ( 
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM ( 
						SELECT  CODIGO_OBS, ASUNTO, DESCRIPCION, TO_CHAR(FECHA,'DD/MM/YYYY')FECHA, USR_OBSERVACION
						FROM SGC_TT_OBSERVACIONES_INM 
						WHERE INM_CODIGO = '$inmueble'
						 $sort
						)a WHERE  ROWNUM<$start
					) WHERE rnum >= $end+1";
		//echo $sql;
		$resultado = oci_parse(
				$this->_db,$sql
				);
			
			
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