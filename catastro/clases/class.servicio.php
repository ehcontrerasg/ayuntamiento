<?php
include_once '../../clases/class.conexion.php';
class Servicio extends ConexionClass{
// 	Private $cod_emplazamiento;
// 	Private $desc_emplazamiento;
// 	Private $fac_corte;		
	
// 	public function Emplazamientos()
// 	{
// 		parent::__construct();
// 		$this->cod_emplazamiento="";
// 		$this->desc_emplazamiento="";
// 		$this->fac_corte="";
		
// 	}
// 	public function setcodemplazamiento($valor){
// 		$this->cod_emplazamiento=$valor;
// 	}
	
// 	public function setdesc_emplazamiento($valor){
// 		$this->desc_emplazamiento=$valor;
// 	}
	
// 	public function setfaccorte($valor){
// 		$this->fac_corte=$valor;
// 	}
	

	
	public function obtenerservicio (){
		$resultado = oci_parse($this->_db,"SELECT CON.COD_SERVICIO, CON.DESC_SERVICIO
				 FROM SGC_TP_SERVICIOS CON WHERE CON.CATASTRO='S' ORDER BY(CON.DESC_SERVICIO)");
		
		
	
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

