<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Actividad extends ConexionClass{
	Private $sec_actividad;
	Private $id_actividad;
	Private $desc_actividad;
	Private $id_uso;		
	
	public function __construct()
	{
		parent::__construct();
		$this->sec_actividad="";
		$this->id_actividad="";
		$this->desc_actividad="";
		$this->id_uso="";
		
	}
	public function setsecac($valor){
		$this->sec_actividad=$valor;
	}
	
	public function setid_actividad($valor){
		$this->id_actividad=$valor;
	}
	
	public function setdescactividad($valor){
		$this->desc_actividad=$valor;
	}
	public function setiduso($valor){
		$this->id_uso=$valor;
	}

	
	public function obteneractividades ($id_uso){
		$resultado = oci_parse($this->_db,"SELECT ACT.SEC_ACTIVIDAD, ACT.DESC_ACTIVIDAD
				 FROM SGC_TP_ACTIVIDADES ACT WHERE ACT.ID_USO='$id_uso'");
		
	
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
