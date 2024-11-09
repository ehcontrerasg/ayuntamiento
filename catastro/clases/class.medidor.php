<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Medidor extends ConexionClass{
	Private $cod_medidor;
	Private $desc_medidor;
	Private $estado_med;
	Private $usr_creacion;		
	
	public function __construct()
	{
		parent::__construct();
		$this->cod_medidor="";
		$this->desc_medidor="";
		$this->estado_med="";
		$this->usr_creacion="";
		
	}
	public function setcodmedidor($valor){
		$this->cod_medidor=$valor;
	}
	
	public function setdesc_medidor($valor){
		$this->desc_medidor=$valor;
	}
	
	public function setestmedidor($valor){
		$this->estado_med=$valor;
	}
	public function setusr_creacion($valor){
		$this->usr_creacion=$valor;
	}

	
	public function obtenermedidores (){
		$resultado = oci_parse($this->_db,"SELECT MED.CODIGO_MED, MED.DESC_MED
				 FROM SGC_TP_MEDIDORES MED ");
		
	
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
