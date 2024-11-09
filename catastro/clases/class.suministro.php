<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Suministro extends ConexionClass{
	Private $cod_suministro;
	Private $desc_suministro;
	Private $fac_corte;		
	
	public function __construct()
	{
		parent::__construct();
		$this->cod_suministro="";
		$this->desc_suministro="";
		$this->fac_corte="";
		
	}
	public function setcodsuministro($valor){
		$this->cod_suministro=$valor;
	}
	
	public function setdesc_suministro($valor){
		$this->desc_suministro=$valor;
	}
	
	public function setfaccorte($valor){
		$this->fac_corte=$valor;
	}
	public function setusr_creacion($valor){
		$this->usr_creacion=$valor;
	}

	
	public function obtenersuministro (){
		$resultado = oci_parse($this->_db,"SELECT SUMI.COD_SUMINISTRO, SUMI.DESC_SUMINISTRO
				 FROM SGC_TP_MET_SUMINISTRO SUMI ");
		
		
	
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

