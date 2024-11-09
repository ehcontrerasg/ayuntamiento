<?php
date_default_timezone_set('America/Santo_Domingo');
if (is_file("../../clases/class.conexion.php"))
{
    include_once "../../clases/class.conexion.php";
}
else if (is_file("../clases/class.conexion.php"))
{
    include_once "../clases/class.conexion.php";
}

class coordenadasfac extends ConexionClass{	

	public function __construct(){
		parent::__construct();
		
	}
	
	public function obtenerpripunto($ruta,$fecini,$fecfin,$usuario)
	{
		
		$sql="   SELECT * FROM (
				 SELECT FAC.LONGITUD, FAC.LATITUD, FAC.COD_INMUEBLE ,TO_CHAR(FAC.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
				 FROM SGC_TT_REGISTRO_ENTREGA_FAC FAC, SGC_TT_INMUEBLES INM 
				 WHERE
				 INM.CODIGO_INM=FAC.COD_INMUEBLE
				 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
				 AND FAC.USR_EJE='$usuario'
				 AND FAC.FECHA_EJECUCION BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
				 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
				 ORDER BY FECHA_EJECUCION ASC) 
				 WHERE ROWNUM = 1";
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
		
		if(oci_execute($resultdo)){
			oci_close($this->_db);
			return $resultdo;
		}
		else{
			oci_close($this->_db);
			echo "error consulta primer punto";
			return false;			
		}
	}
	
	public function obtenerultpunto($ruta,$fecini,$fecfin,$usuario)
	{
	
		$sql="   SELECT * FROM (
		SELECT FAC.LONGITUD, FAC.LATITUD, FAC.COD_INMUEBLE ,TO_CHAR(FAC.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_REGISTRO_ENTREGA_FAC FAC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=FAC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND FAC.USR_EJE='$usuario'
		AND FAC.FECHA_EJECUCION BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		ORDER BY FECHA_EJECUCION DESC)
		WHERE ROWNUM = 1";
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
			echo "error consulta ultimo punto";
			return false;
		}
	}
	
	public function obtenerrestpunto($ruta,$fecini,$fecfin,$usuario,$codini,$codfin)
	{
	
		$sql="
		SELECT FAC.LONGITUD, FAC.LATITUD, FAC.COD_INMUEBLE ,TO_CHAR(FAC.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN,(ROWNUM+1) SEC
		FROM SGC_TT_REGISTRO_ENTREGA_FAC FAC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=FAC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND FAC.USR_EJE='$usuario'
		AND FAC.FECHA_EJECUCION BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		AND FAC.COD_INMUEBLE<>'$codini'
		AND FAC.COD_INMUEBLE<>'$codfin'
		ORDER BY FECHA_EJECUCION ASC" ;
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
		echo "error consulta resto de  puntos";
			return false;
		}
	}
	
	
	public function obtenertodtpunto($ruta,$fecini,$fecfin,$usuario)
	{
	
		$sql="SELECT FAC.LONGITUD, FAC.LATITUD, FAC.COD_INMUEBLE ,TO_CHAR(FAC.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN,(ROWNUM+1) SEC
		FROM SGC_TT_REGISTRO_ENTREGA_FAC FAC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=FAC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND FAC.USR_EJE='$usuario'
		AND FAC.FECHA_EJECUCION BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		ORDER BY FECHA_EJECUCION ASC" ;
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
		echo "error consulta resto de  puntos";
			return false;
		}
	}
	
	
	
	public function obtenerpripuntolec($ruta,$fecini,$fecfin,$usuario)
	{
	
		$sql="   SELECT * FROM (
		SELECT LEC.LONGITUD, LEC.LATITUD, LEC.COD_INMUEBLE ,TO_CHAR(LEC.FECHA_LECTURA,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=LEC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND LEC.COD_LECTOR='$usuario'
		AND LEC.FECHA_LECTURA BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		ORDER BY FECHA_LECTURA ASC)
		WHERE ROWNUM = 1";
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
			echo "error consulta primer punto";
			return false;
		}
	}
	
	
	public function obtenerultpuntolec($ruta,$fecini,$fecfin,$usuario)
	{
	
		$sql="   SELECT * FROM (
		SELECT LEC.LONGITUD, LEC.LATITUD, LEC.COD_INMUEBLE ,TO_CHAR(LEC.FECHA_LECTURA,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=LEC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND LEC.COD_LECTOR='$usuario'
		AND LEC.FECHA_LECTURA BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		ORDER BY FECHA_LECTURA DESC)
		WHERE ROWNUM = 1";
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
		echo "error consulta ultimo punto";
			return false;
		}
	}
	
	
	public function obtenerrestpuntolec($ruta,$fecini,$fecfin,$usuario,$codini,$codfin)
	{
	
		$sql="
		SELECT LEC.LONGITUD, LEC.LATITUD, LEC.COD_INMUEBLE ,TO_CHAR(LEC.FECHA_LECTURA,'DD/MM/YYYY HH24:MI:SS') FECHAFIN,(ROWNUM+1) SEC
		FROM SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=LEC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND LEC.COD_LECTOR='$usuario'
		AND LEC.FECHA_LECTURA BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		AND LEC.COD_INMUEBLE<>'$codini'
		AND LEC.COD_INMUEBLE<>'$codfin'
		ORDER BY FECHA_LECTURA ASC" ;
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
		echo "error consulta resto de  puntos";
			return false;
		}
	}
	
	public function obtenertodtpuntolec($ruta,$fecini,$fecfin,$usuario)
	{
	
		$sql="SELECT lec.LONGITUD, LEC.LATITUD, LEC.COD_INMUEBLE ,TO_CHAR(LEC.FECHA_LECTURA,'DD/MM/YYYY HH24:MI:SS') FECHAFIN,(ROWNUM+1) SEC
		FROM SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM
		WHERE
		INM.CODIGO_INM=LEC.COD_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND LEC.COD_LECTOR='$usuario'
		AND LEC.FECHA_LECTURA BETWEEN TO_DATE('$fecini', 'DDMMYYYYhh24miss') AND TO_DATE('$fecfin', 'DDMMYYYYhh24miss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
		ORDER BY FECHA_LECTURA ASC" ;
		//echo $sql;
		$resultdo=oci_parse($this->_db,$sql);
	
		if(oci_execute($resultdo)){
		oci_close($this->_db);
		return $resultdo;
		}
		else{
		oci_close($this->_db);
		echo "error consulta resto de  puntos";
			return false;
		}
		}
	
	
	
	
}
