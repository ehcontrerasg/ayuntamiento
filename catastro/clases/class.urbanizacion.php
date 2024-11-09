<?php

if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/

class Urbanizacion extends ConexionClass{
	Private $id_urbanizacion;
	Private $desc_urbanizacion;
	Private $id_proyecto;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_urbanizacion="";
		$this->desc_urbanizacion="";
		$this->id_proyecto="";
		
	}
	public function setid($valor){
		$this->id_urbanizacion=$valor;
	}
	
	public function setdesc_urbanizacion($valor){
		$this->desc_urbanizacion=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
	

	public function NuevaUrbanizacion(){
		
 		$resultado=oci_parse($this->_db," BEGIN SGC_P_INSERTA_URB('$this->desc_urbanizacion','$this->id_proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;");
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
	
	public function obtenerurbanizacion ($proyecto){
		$resultado = oci_parse($this->_db,"SELECT URB.CONSEC_URB, URB.DESC_URBANIZACION
				 FROM SGC_TP_URBANIZACIONES URB 
				WHERE URB.ID_PROYECTO = '$proyecto' ORDER BY DESC_URBANIZACION ASC");
		
	
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
	
	public function obtenerurbanizacion2 ($proyecto,$termino){
		$resultado = oci_parse($this->_db,"SELECT URB.CONSEC_URB, URB.DESC_URBANIZACION
				FROM SGC_TP_URBANIZACIONES URB
				WHERE URB.DESC_URBANIZACION LIKE'%$termino%'  ORDER BY DESC_URBANIZACION ASC");
	
	
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
