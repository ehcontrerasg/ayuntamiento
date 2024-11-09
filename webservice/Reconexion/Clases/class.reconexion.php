<?php 
class reconexion extends ConexionClass{
	private $idinmueble;
	private $lectura;
	private $imposibilidad;
	private $latitud;
	private $longitud;
	private $fechalect;
	private $periodo;
	private $usreje;
	private $tiporec;
	private $impolec;
	private $tipoimpolec;
	private $tipoimporec;
	private $orden;
	private $obsgen;
	private $usrviejo;
	private $obsmat;
	private $serial;
	private $marca;
	private $calibre;
	private $codresult;
	private $msresult;
	public function __construct(){
		parent::__construct();
		$this->idinmueble="";
		$this->lectura="0";
		$this->imposibilidad="";
		$this->latitud="";
		$this->longitud="";
		$this->fechalect="";
		$this->periodo="";
		$this->usreje="";
		$this->tiporec="";
		$this->impolec="";
		$this->tipoimpolec;
		$this->tipoimporec;
		$this->orden="";
		$this->obsgen="";
		$this->usrviejo="";
		$this->obsmat="";
		$this->serial="";
		$this->marca="";
		$this->calibre="";
		$this->codresult="";
		$this->msresult="";
	}
	
	public function setinmueble			($valor){$this->idinmueble=$valor;}
	public function setolectura			($valor){$this->lectura=$valor;}
	public function setimposirec		($valor){$this->imposibilidad=$valor;}
	public function setlatitud			($valor){$this->latitud=$valor;}
	public function setlongitud			($valor){$this->longitud=$valor;}
	public function setfecha			($valor){$this->fechalec=$valor;}
	public function setusr				($valor){$this->usreje=$valor;}
	public function settiporec			($valor){$this->tiporec=$valor;}
	public function setimpolec			($valor){$this->impolec=$valor;}
	public function settipoimporec		($valor){$this->tipoimporec=$valor;}
	public function settipoimpolec		($valor){$this->tipoimpolec=$valor;}
	public function setorden			($valor){$this->orden=$valor;}
	public function setobsgen			($valor){$this->obsgen=$valor;}
	public function setobsmat			($valor){$this->obsmat=$valor;}

	public function getcod(){
		return $this->codresult;
	}
	public function getms(){
		return $this->msresult;
	}



	public function guardareconexion()
	{

		$sql="BEGIN SGC_P_INGRESA_RECONEXION('$this->tiporec','$this->lectura','$this->impolec','$this->fechalec',$this->latitud,$this->longitud,'$this->orden','$this->obsgen','$this->idinmueble','$this->imposibilidad','$this->usreje',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
		$resultado=oci_parse($this->_db,$sql);
		oci_bind_by_name($resultado,":PMSGRESULT",$this->msresult,123);
		oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,123);
		$bandera=oci_execute($resultado);
		if($bandera==TRUE && $this->codresult ==0){
			$nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-reconexionOK- $this->usreje .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fclose($file);
           return true;       
        }
        else{
			$nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-reconexionFail- $this->usreje.txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fwrite($file,  "error:  ".$this->msresult);
			fclose($file);
 			echo "Error al guardar la factura $this->msresult   $this->idinmueble   $this->orden ";
            return false;
        }
	}
}