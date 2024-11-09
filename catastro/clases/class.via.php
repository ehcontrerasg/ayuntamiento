<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Via extends ConexionClass{
	Private $id_tipovia;
	Private $desc_tipovia;
	Private $proyecto;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_tipovia="";
		$this->desc_tipovia="";
		$this->proyecto="";
		
	}
	public function setid($valor){
		$this->id_tipovia=$valor;
	}
	
	public function setdesc_via($valor){
		$this->desc_tipovia=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
	
	
	


	public function obtenervia ($proyecto){
		$resultado = oci_parse($this->_db,"SELECT TIVI.ID_TIPO_VIA, TIVI.DESC_TIPO_VIA
				 FROM SGC_TP_TIPO_VIA TIVI 
				WHERE TIVI.ID_PROYECTO = '$proyecto'");
		
	
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
	
	
	public function obtenerdescvia ($proyecto){
		$resultado = oci_parse($this->_db,"SELECT TIVI.ID_TIPO_VIA, TIVI.DESC_TIPO_VIA
				FROM SGC_TP_TIPO_VIA TIVI
				WHERE TIVI.ID_PROYECTO = '$proyecto' AND 
				TIVI.ID_TIPO_VIA='$this->id_tipovia'");

	
	
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
