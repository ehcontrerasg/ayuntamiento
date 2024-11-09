<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Calculo extends ConexionClass{
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
	

	



	
	public function Todos (){
		$resultado = oci_parse($this->_db,"SELECT COD_CALCULO, DESC_CALCULO FROM SGC_TP_CALCULO ORDER BY DESC_CALCULO");
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

