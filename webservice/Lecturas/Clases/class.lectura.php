<?php 
date_default_timezone_set('America/Santo_Domingo');
class lectura extends ConexionClass{
	
	private $idinmueble;
	private $lectura;
	private $observacion;
	private $latitud;
	private $longitud;
	private $fechalec;
	private $periodo;
	private $imei;
	private $calculo;
	private $operario;
	
	public function __construct(){
		
		parent::__construct();
		$this->idinmueble="";
		$this->lectura="";
		$this->lectura1="";
		$this->lectura2="";
		$this->lectura3="";
		$this->intentos="";
		$this->observacion="";
		$this->latitud="";
		$this->longitud="";
		$this->fechalec="";
		$this->periodo="";
		$this->imei="359608053505332";
		$this->calculo="";
		$this->operario="";
		$this->medio="M";
	}
	
	public function setinmueble		($valor){$this->idinmueble=$valor;}
	public function setlectura		($valor){$this->lectura=$valor;}
	public function setlectura1		($valor){$this->lectura1=$valor;}
	public function setlectura2		($valor){$this->lectura2=$valor;}
	public function setlectura3		($valor){$this->lectura3=$valor;}
	public function setintentos		($valor){$this->intentos=$valor;}
	public function setobservacion	($valor){$this->observacion=$valor;}
	public function setlatitud		($valor){$this->latitud=$valor;}
	public function setlongitud		($valor){$this->longitud=$valor;}
	public function setfecha		($valor){$this->fechalec=$valor;}
	public function setperiodo		($valor){$this->periodo=$valor;}
	public function setimei			($valor){$this->imei=$valor;}
	public function setcalculo		($valor){$this->calculo=$valor;}
	public function setoperario		($valor){$this->operario=$valor;}
	public function setMedio		($valor){$this->medio=$valor;}

	public function guardarlecturanuevo(){

		$sql=" BEGIN SGC_P_INGRESA_LECTURA_TOT($this->periodo,$this->idinmueble,'$this->lectura','$this->fechalec','$this->observacion','$this->operario','M','$this->lectura1','$this->lectura2','$this->lectura3','$this->intentos','$this->latitud','$this->longitud',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		$resultado=oci_parse($this->_db,$sql);
		oci_bind_by_name($resultado,':PCODRESULT',$codresult,10000);
		oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,10000);
		$bandera=oci_execute($resultado);

       

		if($bandera==TRUE && $codresult==0 ){
			 $nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-lecturaOK- $this->operario .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fclose($file);
			oci_close($this->_db);
			return true;
		}else{

 			oci_close($this->_db);
			$nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-lecturaFail- $this->operario .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fwrite($file,  "error: ".$msgresult);
			fclose($file);
 			echo "Error al guardar la lectura $msgresult  $this->idinmueble  $this->periodo ";
			return false;
		}
	
	}


public function guardarlecturanuevo2(){

		$sql=" BEGIN SGC_P_INGRESA_LECTURA_EXT('$this->periodo','$this->idinmueble','$this->lectura','$this->fechalec','$this->observacion','$this->operario','M','$this->lectura1','$this->lectura2','$this->lectura3','$this->intentos','$this->latitud','$this->longitud',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		$resultado=oci_parse($this->_db,$sql);
		oci_bind_by_name($resultado,':PCODRESULT',$codresult,10000);
		oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,10000);
		$bandera=oci_execute($resultado);



		if($bandera==TRUE && $codresult==0 ){
			 $nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-lecturaOK- $this->operario .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fclose($file);
			oci_close($this->_db);
			return true;
		}else{

 			oci_close($this->_db);
			$nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-lecturaFail- $this->operario .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fwrite($file,  "error: ".$msgresult);
			fclose($file);
 			echo "Error al guardar la lectura $msgresult  $this->idinmueble  $this->periodo ";
			return false;
		}

	}



    public function guardarlecturaOnline(){

        echo $sql=" BEGIN SGC_P_INGRESA_LECTURA_TOT_ON($this->periodo,$this->idinmueble,'$this->lectura','$this->fechalec','$this->observacion','$this->operario','M','$this->lectura1','$this->lectura2','$this->lectura3','$this->intentos','$this->latitud','$this->longitud',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        /*$resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$codresult,10000);
        oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,10000);
        $bandera=oci_execute($resultado);



        if($bandera==TRUE && $codresult==0 ){
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-lecturaOK- $this->operario .txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            oci_close($this->_db);
            return true;
        }else{

            oci_close($this->_db);
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-lecturaFail- $this->operario .txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fwrite($file,  "error: ".$msgresult);
            fclose($file);
            echo "Error al guardar la lectura $msgresult  $this->idinmueble  $this->periodo ";
            return false;
        }*/

    }

}