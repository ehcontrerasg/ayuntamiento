<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../../ ../clases/class.conexion.php';}*/
class Grupo extends ConexionClass{
	Private $cod;
	Private $descripcion;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->cod="";
		$this->descripcion="";
		
	}
	public function setcodigo($valor){
		$this->cod=$valor;
	}
	public function  getcodigo(){
		return  $this->cod;
	}

	public function setdescripcion($valor){
		$this->descripcion=$valor;
	}
	public function  getdescripcion(){
		return  $this->descripcion;
	}
	

	

	public function NuevoGrupo(){
		
 		$resultado = oci_parse($this->_db," INSERT INTO SGC_TP_GRUPOS VALUES($this->cod,$this->descripcion) ");
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

	
	public function Todos (){
		$resultado = oci_parse($this->_db,"SELECT COD_GRUPO, DESC_GRUPO FROM SGC_TP_GRUPOS ORDER BY DESC_GRUPO");
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

