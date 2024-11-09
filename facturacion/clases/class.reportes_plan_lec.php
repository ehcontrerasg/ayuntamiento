<?php
include_once '../../clases/class.conexion.php';
class Reportes_Plan_Lec extends ConexionClass{
	private $id_proyecto;
	private $id_zonini;
	private $id_zonfin;
	private $id_perini;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_zonini="";
		$this->id_zonfin="";
		$this->id_perini="";
	}

	
	public function ObtienePlanificacionRuta($perini,$proyecto,$secini,$secfin,$fecini){
		if($secini != '' && $secfin == '') $secfin = $secini;
		if($secini == '' && $secfin != '') $secini = $secfin;
		if($secini != '' && $secfin != '') $where .= " AND SUBSTR(RL.ID_ZONA,0,2) BETWEEN UPPER('$secini') AND UPPER('$secfin')";
		if($fecini != '' ) $where .= " AND FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
				AND TO_DATE('$fecini 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql = "SELECT COD_LECTOR_ORI COD_LECTOR, (U.NOM_USR||' '||U.APE_USR) USUARIO, RL.ID_ZONA, COUNT(RL.COD_INMUEBLE) CANTIDAD, USR_ASIGNADOR, 
				TO_CHAR(FECHA_PLANIFICACION,'DD/MM/YYYY')FECHA_PLANIFICACION
				FROM SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
				WHERE U.ID_USUARIO = RL.COD_LECTOR_ORI AND RL.COD_INMUEBLE = I.CODIGO_INM 
				AND RL.PERIODO = $perini AND I.ID_PROYECTO = '$proyecto' $where
				GROUP BY COD_LECTOR_ORI, (U.NOM_USR||' '||U.APE_USR), RL.ID_ZONA, USR_ASIGNADOR, TO_CHAR(FECHA_PLANIFICACION,'DD/MM/YYYY')
				ORDER BY ID_ZONA ";
		//echo $sql.'<br>';
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
	
	public function ObtieneDetalleRutas($cod_lector, $zona, $perini){
		$sql = "SELECT SUBSTR(RUTA,3,2) RUTA
		FROM SGC_TT_REGISTRO_LECTURAS R
		WHERE COD_LECTOR = '$cod_lector' AND ID_ZONA = '$zona' AND PERIODO = $perini
		GROUP BY RUTA";
		//echo $sql.'<br>';
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
	
	
	public function ObtieneAsignador($asignador){
		$sql = "SELECT (NOM_USR||' '||APE_USR) NOM_ASIGNADOR
		FROM SGC_TT_USUARIOS U , SGC_TT_REGISTRO_LECTURAS R
		WHERE U.ID_USUARIO = R.USR_ASIGNADOR AND U.ID_USUARIO = '$asignador'";
		//echo $sql.'<br>';
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
