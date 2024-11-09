<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Emplazamiento extends ConexionClass{
	Private $cod_emplazamiento;
	Private $desc_emplazamiento;
	Private $fac_corte;		
	
	public function __construct()
	{
		parent::__construct();
		$this->cod_emplazamiento="";
		$this->desc_emplazamiento="";
		$this->fac_corte="";
		
	}
	public function setcodemplazamiento($valor){
		$this->cod_emplazamiento=$valor;
	}
	
	public function setdesc_emplazamiento($valor){
		$this->desc_emplazamiento=$valor;
	}
	
	public function setfaccorte($valor){
		$this->fac_corte=$valor;
	}
	

	
	public function obteneremplazamiento (){
		$resultado = oci_parse($this->_db,"SELECT EMPL.COD_EMPLAZAMIENTO, EMPL.DESC_EMPLAZAMIENTO
				 FROM SGC_TP_EMPLAZAMIENTO EMPL ");
		
		
	
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

