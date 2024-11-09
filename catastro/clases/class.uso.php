<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Uso extends ConexionClass{
	Private $id_uso;
	Private $desc_uso;
	Private $multiuso;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_uso="";
		$this->desc_uso="";
		$this->multiuso="";
		
	}
	public function setid($valor){
		$this->id_uso=$valor;
	}
	
	public function setdes_uso($valor){
		$this->desc_uso=$valor;
	}
	
	public function setmultiuso($valor){
		$this->multiuso=$valor;
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
	
	
	public function obtenerusos (){
		$resultado = oci_parse($this->_db,"SELECT USOS.ID_USO, USOS.DESC_USO
				 FROM SGC_TP_USOS USOS WHERE VISIBLE='S'");
		
	
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
	
	public function obtenerusoespecifico ($valor){
		$resultado = oci_parse($this->_db,"SELECT USOS.ID_USO, USOS.DESC_USO
				 FROM SGC_TP_USOS USOS WHERE ID_USO='$valor'");
	
	
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
