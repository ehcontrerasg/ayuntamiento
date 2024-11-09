<?php
include_once '../../clases/class.conexion.php';
class Ruta extends ConexionClass{
	Private $id_sector;
	Private $id_ruta;
	Private $id_proyecto;
	
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_sector="";
		$this->id_ruta="";
		$this->id_proyecto="";
		
	}
	public function setidsector($valor){
		$this->id_sector=$valor;
	}
	
	public function setidruta($valor){
		$this->id_ruta=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
		
	public function obtenerrutas($proyecto,$sector){
		$resultado = oci_parse($this->_db,"SELECT RUT.ID_RUTA
				 FROM SGC_TP_RUTAS RUT 
				WHERE RUT.ID_PROYECTO = '$proyecto' AND RUT.ID_SECTOR='$sector'");
		
	
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


public function getRutaByZonPer($zona,$periodo){
		$resultado = oci_parse($this->_db,"SELECT RL.RUTA FROM SGC_TT_REGISTRO_ENTREGA_FAC RL
WHERE RL.ID_ZONA='$zona' AND
      RL.PERIODO=$periodo AND
      RL.FECHA_EJECUCION IS NOT NULL
GROUP BY RL.RUTA
ORDER BY RL.RUTA
");


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
