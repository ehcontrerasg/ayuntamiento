<?php 
class asignacion extends ConexionClass{
	private $idinmueble;
	private $latitud;
	private $longitud;
	private $fechalec;
	private $periodo;
	private $idtiporuta;
	
	public function __construct(){
		parent::__construct();
		$this->idinmueble="";
		$this->latitud="";
		$this->longitud="";
		$this->fechalec="";
		$this->periodo="";	
		$this->idtiporuta="2";
	}
	
	public function setinmueble	($valor){$this->idinmueble=$valor;}
	public function setlatitud	($valor){$this->latitud=$valor;}
	public function setlongitud	($valor){$this->longitud=$valor;}
	public function setfecha	($valor){$this->fechalec=$valor;}
	public function setperiodo	($valor){$this->periodo=$valor;}
	public function settiporuta	($valor){$this->idtiporuta=$valor;}

	
	public function actualizarasignacion(){
		$resultado=oci_parse($this->_db,"UPDATE ASIGNACION
				SET FECHA_FIN=TO_DATE('$this->fechalec', 'DD/MM/YYYY'), LATITUD='$this->latitud',
				LONGITUD='$this->longitud'
				WHERE ID_INMUEBLE='$this->idinmueble' AND ID_PERIODO='$this->periodo' AND ID_TIPO_RUTA='$this->idtiporuta'");
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return true;
		}else{
			oci_close($this->_db);
			echo "Error al actualizar tabla asignacion";
			return false;
		}
		
	}
		
	
}