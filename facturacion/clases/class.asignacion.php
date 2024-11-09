<?php
include_once '../../clases/class.conexion.php';
date_default_timezone_set('America/Santo_Domingo');
class Asignacion extends ConexionClass{	
	private $idtiporuta;
	private $idinmueble;
	private $idoperario;
	private $idsector;
	private $idruta;
	private $idperiodo;
	private $fechafin;
	private $latitud;
	private $longitud;
	private $fechaasignacion;
	private $asignador;
	
	public function __construct(){
		parent::__construct();
		$this->idtiporuta=2;
		$this->idinmueble="";
		$this->idoperario="";
		$this->idsector="";
		$this->idruta="";
		$this->idperiodo="";
		$this->fechafin="";
		$this->latitud="";
		$this->longitud="";
		$this->fechaasignacion=date("d/m/Y H:i:s");
		$this->aignador="";
	}
	
	public function setinmueble ($valor){$this->idinmueble=$valor;}
	public function setoperario ($valor){$this->idoperario=$valor;}
	public function setsector 	($valor){$this->idsector=$valor;}
	public function setruta		($valor){$this->idruta=$valor;}
	public function setperiodo	($valor){$this->idperiodo=valor;}
	public function setfechafin	($valor){$this->fechafin=$valor;}
	public function setlatitud	($valor){$this->latitud=$valor;}
	public function setlongitud	($valor){$this->longitud=$valor;}
	public function setasignador($valor){$this->asignador=$valor;}
	
	
	
	public function asignar(){
		$resultdo=oci_parce($this->_db,"INSERT INTO SGC_TT_ASIGNACION VALUES($this->idtiporuta,$this->idinmueble,'$this->idoperario',
				'$this->idsector','$this->idruta','$this->idperiodo','$this->fechafin','$this->latitud','$this->longitud',
				TO_DATE('$this->fechaasignacion','MM/DD/YYYY HH24:MI:SS'),'$this->asignador')");
		if(oci_execute($resultdo)){
			oci_close($this->_db);
			return $resultdo;
		}
		else{
			oci_close($this->_db);
			echo "error consulta asignacion";
			return false;			
		}
	}
	
	public function reasignar(){
		$resultado=oci_parce($this->_db,"UPDATE SGC_TT_ASIGNACION SET ID_OPERARIO='$this->idoperario'
				WHERE ID_SECTOR='$this->idsector' AND ID_RUTA='$this->idruta' AND ID_PERIODO='$this->idperiodo'
				AND FECHA_FIN IS NULL");
		if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la reasignacion";
			return false;
		}
	}
}
