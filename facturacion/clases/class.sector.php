<?php
include_once '../../clases/class.conexion.php';
class Sector extends ConexionClass{
	Private $id_sector;
	Private $proyecto;
	Private $gerencia;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_sector="";
		$this->gerencia="";
		$this->proyecto="";
		
	}
	public function setid($valor){
		$this->id_sector=$valor;
	}
	
	public function setgerencia($valor){
		$this->gerencia=$valor;
	}
	
	public function setidproyecto($valor){
		$this->proyecto=$valor;
	}
	

// 	public function NuevaUrbanizacion(){
		
//  		$resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_PROYECTOS VALUES('$this->id_proyecto',
//  				'$this->sigla','$this->descripcion') ");
 	
 		
 		
//  		$banderas=oci_execute($resultado);
//  		if($banderas==TRUE)
//  		{
//  			oci_close($this->_db);
//  			return $resultado;
//  		}
//  		else
//  		{
//  			echo "false";
//  			return false;
//  		}

// 	}
	
	
	public function obtenersectores ($proyecto){
		$resultado = oci_parse($this->_db,"SELECT SEC.ID_SECTOR
				 FROM SGC_TP_SECTORES SEC 
				WHERE SEC.ID_PROYECTO = '$proyecto'");
		
	
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
