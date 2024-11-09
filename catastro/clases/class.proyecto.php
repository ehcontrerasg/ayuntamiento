<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/

class Proyecto extends ConexionClass{
	Private $id_proyecto;
	Private $sigla;
	Private $descripcion;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->sigla="";
		$this->descripcion="";
		
	}
	public function setid($valor){
		$this->id_proyecto=$valor;
	}
	
	public function setsigla($valor){
		$this->sigla=$valor;
	}
	
	public function setdescripcion($valor){
		$this->descripcion=$valor;
	}
	

	public function NuevoProyecto(){


 		$resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_PROYECTOS VALUES('$this->id_proyecto',
 				'$this->sigla','$this->descripcion') ");
 			
 		
 		
 		$banderas=oci_execute($resultado);
 		if($banderas==TRUE)
 		{
 			oci_close($this->_db);
 			return $resultado;
 		}
 		else
 		{
 			echo "false";
 			oci_close($this->_db);
 			return false;
 		}

	}
	
	
	public function obtenerproyectos ($usr){
		$resultado = oci_parse($this->_db,"SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
							WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$usr'");
		"SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
							WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$usr'";

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
