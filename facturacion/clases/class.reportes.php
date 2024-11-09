<?php

include_once '../../clases/class.conexion.php';

class Reportes extends ConexionClass{
	

		
	public function __construct()
	{
		parent::__construct();

	}
	


	public function TodosRendFac ($where,$sort,$start,$end)
	{
	    $sql="SELECT * 
				FROM ( 
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM ( 
						SELECT USR.ID_USUARIO,USR.LOGIN, concat(INM.ID_SECTOR,INM.ID_RUTA) ruta, TO_CHAR(MIN(FAC.FECHA_EJECUCION),'dd/mm/yyyy hh24:mi:ss') INICIO, to_char(MAX(FAC.FECHA_EJECUCION),'dd/mm/yyyy hh24:mi:ss') FIN, COUNT(1) TOTAL 
						FROM SGC_TT_USUARIOS USR, SGC_TT_INMUEBLES INM, SGC_TT_REGISTRO_ENTREGA_FAC FAC
						WHERE USR.ID_USUARIO=FAC.USR_EJE
						AND FAC.COD_INMUEBLE=INM.CODIGO_INM
						$where
						GROUP BY USR.ID_USUARIO,USR.LOGIN, INM.ID_SECTOR, INM.ID_RUTA  $sort
						)a WHERE rownum <= $start 
					) WHERE rnum >= $end+1";
		$resultado = oci_parse(
				$this->_db,
				$sql);
			
			
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
	
	
	
	public function CantidadRendFac ($fname,$tname,$where,$sort)
	{	
		$resultado = oci_parse($this->_db,"
				SELECT COUNT(1)CANTIDAD FROM ( 
				SELECT count(1) CANTIDAD FROM SGC_TT_USUARIOS USR, SGC_TT_INMUEBLES INM, SGC_TT_REGISTRO_ENTREGA_FAC FAC WHERE
				USR.ID_USUARIO=FAC.USR_EJE
				AND FAC.COD_INMUEBLE=INM.CODIGO_INM $where 
				GROUP BY (USR.ID_USUARIO,USR.LOGIN, INM.ID_SECTOR, INM.ID_RUTA)
						
				) ");

			
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
	
	public function CantidaddetalleFac ($fname,$tname,$where)
	{
		
		$sql="SELECT COUNT(1) CANTIDAD FROM (
			SELECT  INM.CODIGO_INM  FROM SGC_TT_REGISTRO_ENTREGA_FAC EF, SGC_TT_INMUEBLES INM		
 			WHERE INM.CODIGO_INM=EF.COD_INMUEBLE $where
			)";
		//echo $sql;
		$resultado = oci_parse($this->_db,$sql);		
		$banderas=oci_execute($resultado);
	
		if($banderas==TRUE)
		{
			while (oci_fetch($resultado)) {
				$cantidad = oci_result($resultado, 'CANTIDAD');	
			}oci_free_statement($resultado);
			oci_close($this->_db);
			return $cantidad;
			
			
			
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	
	}
	
	
	public function TodosDetalleFac ($where,$sort,$start,$end)
	{
		$sql="SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
				FROM (
				SELECT INM.CODIGO_INM,  EF.LATITUD,EF.LONGITUD, INM.ID_PROCESO,INM.DIRECCION, to_char(EF.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHA_EJECUCION
				FROM SGC_TT_REGISTRO_ENTREGA_FAC EF, SGC_TT_INMUEBLES INM
				WHERE  INM.CODIGO_INM=EF.COD_INMUEBLE $where $sort
		)a WHERE rownum <= $start
		) WHERE rnum >= $end+1";
		
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
		
		
		
		public function existefoto($inmueble,$fechaini,$fechafin,$operario)
		{
			$sql="SELECT COUNT(*) CANTIDAD FROM SGC_TT_FOTOS_FACTURA FF,SGC_TT_REGISTRO_ENTREGA_FAC EF
				WHERE EF.COD_INMUEBLE=FF.ID_INMUEBLE
				AND EF.USR_EJE='$operario'
				AND FF.ID_INMUEBLE='$inmueble'
				AND FF.FECHA BETWEEN TO_DATE('".$fechaini."000000', 'DDMMYYYYHH24MISS') AND TO_DATE('".$fechafin."235959', 'DDMMYYYYHH24MISS') ";
		
			//echo $sql;
			$resultado = oci_parse($this->_db,$sql);		
			$banderas=oci_execute($resultado);
			if($banderas==TRUE)
			{
				while (oci_fetch($resultado)) {
					$cantidad = oci_result($resultado, 'CANTIDAD');	
				}oci_free_statement($resultado);
				if($cantidad==0){
					$existe=false;
				}else{
					$existe=true;
				}
			
				oci_close($this->_db);
				return $existe;
			}
			else
			{
				oci_close($this->_db);
				echo "false";
				return false;
			}
		
		}
		
		
		
		
		public function existecoordenadafac($inmueble,$fechaini,$fechafin,$operario)
		{
			$sql="SELECT COUNT(*) CANTIDAD FROM  SGC_TT_REGISTRO_ENTREGA_FAC EF
			where
			EF.USR_EJE='$operario'
			AND EF.COD_INMUEBLE='$inmueble'
			AND EF.FECHA_EJECUCION BETWEEN TO_DATE('".$fechaini."000000', 'DDMMYYYYHH24MISS') AND TO_DATE('".$fechafin."235959', 'DDMMYYYYHH24MISS')
			AND EF.LATITUD>0 ";
			
				
			//echo $sql;
			$resultado = oci_parse($this->_db,$sql);
			$banderas=oci_execute($resultado);
			if($banderas==TRUE)
			{
				while (oci_fetch($resultado)) {
					$cantidad = oci_result($resultado, 'CANTIDAD');
				}oci_free_statement($resultado);
				if($cantidad==0){
					$existe=false;
				}else{
					$existe=true;
				}
					
				oci_close($this->_db);
				return $existe;
			}
			else
			{
				oci_close($this->_db);
				echo "false";
				return false;
			}
		
		}
		
		
		
		public function CantidadRendLec ($fname,$tname,$where,$sort)
		{
			$resultado = oci_parse($this->_db,"
					SELECT COUNT(1)CANTIDAD FROM (
					SELECT count(1) CANTIDAD FROM SGC_TT_USUARIOS USR, SGC_TT_INMUEBLES INM, SGC_TT_REGISTRO_LECTURAS LEC WHERE
					USR.ID_USUARIO=lec.COD_LECTOR
					AND LEC.COD_INMUEBLE=INM.CODIGO_INM $where
					GROUP BY (USR.ID_USUARIO,USR.LOGIN, INM.ID_SECTOR, INM.ID_RUTA)
		
			) ");
		
						
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
	
	
	
	public function TodosRendLec ($where,$sort,$start,$end)
	{
		$resultado = oci_parse(
				$this->_db,
				"SELECT *
				FROM (
				SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
				FROM (
				SELECT USR.ID_USUARIO,USR.LOGIN, concat(INM.ID_SECTOR,INM.ID_RUTA) ruta, TO_CHAR(MIN(LEC.FECHA_LECTURA_ORI),'dd/mm/yyyy hh24:mi:ss') INICIO, to_char(MAX(LEC.FECHA_LECTURA_ORI),'dd/mm/yyyy hh24:mi:ss') FIN, COUNT(1) TOTAL
				FROM SGC_TT_USUARIOS USR, SGC_TT_INMUEBLES INM, SGC_TT_REGISTRO_LECTURAS LEC
				WHERE USR.ID_USUARIO=LEC.COD_LECTOR_ORI
				AND LEC.COD_INMUEBLE=INM.CODIGO_INM AND LEC.MEDIO='M'
				$where
				GROUP BY USR.ID_USUARIO,USR.LOGIN, INM.ID_SECTOR, INM.ID_RUTA  $sort
		)a WHERE rownum <= $start
		) WHERE rnum >= $end+1");
			
			
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
	
	
	public function CantidaddetalleLec ($fname,$tname,$where)
	{
	
		$sql="SELECT COUNT(1) CANTIDAD FROM (
		SELECT  INM.CODIGO_INM  FROM SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM
		WHERE INM.CODIGO_INM=LEC.COD_INMUEBLE $where
		)";
		//echo $sql;
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
	
		if($banderas==TRUE)
		{
		while (oci_fetch($resultado)) {
		$cantidad = oci_result($resultado, 'CANTIDAD');
		}oci_free_statement($resultado);
		oci_close($this->_db);
		return $cantidad;
			
			
			
		}
		else
		{
		oci_close($this->_db);
		echo "false";
		return false;
		}
	
	}
	
	
	public function TodosDetalleLec ($where,$sort,$start,$end)
	{
		$sql="SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
		FROM (
		SELECT INM.CODIGO_INM,LEC.LECTURA_ACTUAL LECTURA, LEC.CONSUMO, LEC.OBSERVACION, LEC.LATITUD,LEC.LONGITUD, INM.ID_PROCESO,INM.DIRECCION, to_char(LEC.FECHA_LECTURA,'DD/MM/YYYY HH24:MI:SS') FECHA_EJECUCION
		FROM SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM
		WHERE  INM.CODIGO_INM=LEC.COD_INMUEBLE AND LEC.MEDIO='M' $where $sort
		)a WHERE rownum <= $start
		) WHERE rnum >= $end+1";
	
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
	
	
	public function existefotolec($inmueble,$fechaini,$fechafin,$operario)
	{
		$sql="SELECT COUNT(*) CANTIDAD FROM SGC_TT_FOTOS_LECTURA FL,SGC_TT_REGISTRO_LECTURAS RL
		WHERE FL.ID_INMUEBLE=rl.cod_INMUEBLE
		AND RL.COD_LECTOR='$operario'
		AND RL.COD_INMUEBLE='$inmueble'
		AND FL.FECHA BETWEEN TO_DATE('".$fechaini."000000', 'DDMMYYYYHH24MISS') AND TO_DATE('".$fechafin."235959', 'DDMMYYYYHH24MISS') ";
	
		//echo $sql;
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			while (oci_fetch($resultado)) {
				$cantidad = oci_result($resultado, 'CANTIDAD');
			}oci_free_statement($resultado);
			if($cantidad==0){
				$existe=false;
			}else{
				$existe=true;
			}
				
			oci_close($this->_db);
			return $existe;
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	
	}
		
		
		
	public function existecoordenadalec($inmueble,$fechaini,$fechafin,$operario)
	{
		$sql="SELECT COUNT(*) CANTIDAD FROM  SGC_TT_REGISTRO_LECTURAS RL
		where
		RL.COD_LECTOR='$operario'
		AND RL.COD_INMUEBLE='$inmueble'
		AND RL.FECHA_LECTURA BETWEEN TO_DATE('".$fechaini."000000', 'DDMMYYYYHH24MISS') AND TO_DATE('".$fechafin."235959', 'DDMMYYYYHH24MISS')
		AND RL.LATITUD>0 ";
			
	
		//echo $sql;
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
		while (oci_fetch($resultado)) {
		$cantidad = oci_result($resultado, 'CANTIDAD');
		}oci_free_statement($resultado);
		if($cantidad==0){
		$existe=false;
		}else{
		$existe=true;
		}
			
		oci_close($this->_db);
		return $existe;
	}
	else
	{
	oci_close($this->_db);
	echo "false";
	return false;
	}
	
	}
	
	
	public function urlfotofotolec($inmueble,$fechaini,$fechafin)
	{
		$sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_LECTURA FL,SGC_TT_REGISTRO_LECTURAS L
		WHERE L.COD_INMUEBLE=FL.ID_INMUEBLE
		AND FL.ID_INMUEBLE='$inmueble'
		AND FL.FECHA BETWEEN TO_DATE(concat(substr('$fechaini',0,8),'000000'), 'DDMMYYYYHH24MISS') AND TO_DATE(concat(substr('$fechafin',0,8),'235959'), 'DDMMYYYYHH24MISS')   ";
	
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
		
		
public function ultimo_periodo_zona($proyecto,$zonini)
	{
		$sql="SELECT MAX(PERIODO)PERIODO
		FROM SGC_TP_PERIODO_ZONA
		WHERE CODIGO_PROYECTO = '$proyecto'
		AND ID_ZONA = '$zonini'
		AND FEC_CIERRE IS NOT NULL";
	
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