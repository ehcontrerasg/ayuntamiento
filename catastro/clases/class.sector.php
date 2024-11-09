<?php

if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Sector extends ConexionClass{
	Private $id_sector;
	Private $desc_sector;
	Private $proyecto;
	Private $gerencia;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_sector="";
		$this->desc_sector="";
		$this->gerencia="";
		$this->proyecto="";
		
	}
	public function setid($valor){
		$this->id_sector=$valor;
	}
	
	public function setdesc_sector($valor){
		$this->desc_sector=$valor;
	}
	
	public function setgerencia($valor){
		$this->gerencia=$valor;
	}
	
	public function setidproyecto($valor){
		$this->proyecto=$valor;
	}

	public function NuevoSector(){
		
 		$resultado=oci_parse($this->_db," BEGIN SGC_P_INSERTA_SECTOR('$this->desc_sector','$this->gerencia','$this->proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;");
		//echo "BEGIN SGC_P_INSERTA_CALLE('$this->desc_sector','$this->proyecto','$this->gerencia',:PMSGRESULT,:PCODRESULT)";
		oci_bind_by_name($resultado,':PCODRESULT',$codresult,"123");
		oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,"123");
		$bandera=oci_execute($resultado);
		if($bandera=TRUE && $codresult==0 ){
			oci_close($this->_db);
			return $codresult;
		}else{
			oci_close($this->_db);
			echo "$msgresult: $codresult";
			return $codresult;
		}
	}
	
	
	public function obtenersectores ($proyecto){
		$resultado = oci_parse($this->_db,"SELECT SEC.ID_SECTOR
				 FROM SGC_TP_SECTORES SEC 
				WHERE SEC.ID_PROYECTO = '$proyecto' ORDER BY ID_SECTOR ASC");
		
	
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
	
	
	public function obtenergerencias (){
		$resultado = oci_parse($this->_db,"SELECT ID_GERENCIA, DESC_GERENCIA
				 FROM SGC_TP_GERENCIAS
				ORDER BY ID_GERENCIA ASC");
		
	
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
