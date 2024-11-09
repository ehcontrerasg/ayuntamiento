<?php 
class corte extends ConexionClass{
	private $idinmueble;
	private $lectura;
	private $imposibilidad;
	private $latitud;
	private $longitud;
	private $fechalec;
	private $periodo;
	private $usreje;
	private $tipocorte;
	private $impolec;
	private $tipoimpolec;
	private $tipoimpocor;
	private $orden;
	private $obsgen;
	private $usrviejo;
	private $obsmat;
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
		$this->tipocorte="";
		$this->impolec="";
		$this->tipoimpolec;
		$this->tipoimpocor;
		$this->orden="";
		$this->obsgen="";
		$this->usrviejo="";
		$this->obsmat="";
	}
	
	public function setinmueble			($valor){$this->idinmueble=$valor;}
	public function setolectura			($valor){$this->lectura=$valor;}
	public function setimposicor		($valor){$this->imposibilidad=$valor;}
	public function setlatitud			($valor){$this->latitud=$valor;}
	public function setlongitud			($valor){$this->longitud=$valor;}
	public function setfecha			($valor){$this->fechalec=$valor;}
	public function setperiodo			($valor){$this->periodo=$valor;}
	public function setusr				($valor){$this->usreje=$valor;}
	public function settipocorte		($valor){$this->tipocorte=$valor;}
	public function setimpolec			($valor){$this->impolec=$valor;}
	public function settipoimpocor		($valor){$this->tipoimpocor=$valor;}
	public function settipoimpolec		($valor){$this->tipoimpolec=$valor;}
	public function setorden			($valor){$this->orden=$valor;}
	public function setobsgen			($valor){$this->obsgen=$valor;}
	public function setusrviejo			($valor){$this->usrviejo=$valor;}
	public function setobsmat			($valor){$this->obsmat=$valor;}
	




	public function guardacorte(){

		$sql="BEGIN SGC_P_INGRESACORTE($this->idinmueble,'$this->lectura','$this->imposibilidad','$this->latitud','$this->longitud','$this->fechalec','$this->usreje','$this->tipocorte','$this->impolec','$this->orden','$this->obsgen',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

		$resultado=oci_parse($this->_db,$sql);
		oci_bind_by_name($resultado,":PMSGRESULT",$this->msresult,10000);
		oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,10000);
		if(oci_execute($resultado)){

			if ($this->codresult<>0){
				$nombrelog=date('Y-m-d');
				$file = fopen("Logs/log-$nombrelog-corteFail- $this->usreje.txt", "a");
				fwrite($file, date('Ymd H:i:s') . PHP_EOL);
				fwrite($file, $sql . PHP_EOL);
				fwrite($file,  "error:  ".$this->msresult);
				fclose($file);
				echo "Error al guardar el corte $this->msresult   $this->idinmueble   $this->orden ";
			}else{
				$nombrelog=date('Y-m-d');
				$file = fopen("Logs/log-$nombrelog-corteOK- $this->usreje.txt", "a");
				fwrite($file, date('Ymd H:i:s') . PHP_EOL);
				fwrite($file, $sql . PHP_EOL);
				fclose($file);
				return $resultado;
			}


		}
		else{

			echo "error consulta ingresa corte";
			return false;
		}
	}


    public function guardacorteRev(){

        $sql="BEGIN SGC_P_INGRESACORTE_REV($this->idinmueble,'$this->orden','$this->imposibilidad','$this->lectura','$this->latitud','$this->longitud','$this->fechalec','$this->usreje','$this->tipocorte','$this->impolec','$this->obsgen',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msresult,10000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,10000);
        if(oci_execute($resultado)){

            if ($this->codresult<>0){
                $nombrelog=date('Y-m-d');
                $file = fopen("Logs/log-$nombrelog-corteFail- $this->usreje.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fwrite($file,  "error:  ".$this->msresult);
                fclose($file);
                echo "Error al guardar el corte $this->msresult   $this->idinmueble   $this->orden ";
            }else{
                $nombrelog=date('Y-m-d');
                $file = fopen("Logs/log-$nombrelog-corteOK- $this->usreje.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
                return $resultado;
            }


        }
        else{

            echo "error consulta ingresa corte REV";
            return false;
        }
    }

}