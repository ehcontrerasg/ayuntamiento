<?php 

date_default_timezone_set('America/Santo_Domingo');
class factura extends ConexionClass{
	private $idinmueble;
	private $observacionfacturas;
	private $observacioncatastro;
	private $latitud;
	private $longitud;
	private $fechalec;
	private $periodo;
	private $imei;
	private $usr;
	private $mesrror;
    private $coderror;




    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }
	
	public function __construct(){
		parent::__construct();
		$this->idinmueble="";
		$this->observaciofacturas="";
		$this->observacioncatastro="";
		$this->latitud="";
		$this->longitud="";
		$this->fechalec="";
		$this->periodo="";
		$this->usr="";
		$this->imei="359608053505332";
	}
	
	public function setinmueble			($valor){$this->idinmueble=$valor;}
	public function setobservacionfac	($valor){$this->observacionfacturas=$valor;}
	public function setobservacioncat	($valor){$this->observacioncatastro=$valor;}
	public function setlatitud			($valor){$this->latitud=round($valor,10);}
	public function setlongitud			($valor){$this->longitud=round($valor,10);}
	public function setfecha			($valor){$this->fechalec=$valor;}
	public function setperiodo			($valor){$this->periodo=$valor;}
	public function setimei				($valor){$this->imei=$valor;}
	public function setusr				($valor){$this->usr=$valor;}
	

	
	
	public function guardafacturasnuevo(){
		$nombrelog=date('Y-m-d');
		
		$sql="BEGIN SGC_P_INGRESAFACT($this->idinmueble,'$this->observaciofacturas','$this->observacioncatastro',
		'$this->latitud','$this->longitud','$this->fechalec',$this->periodo,'$this->usr',:PMSGRESULT, :PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE && $this->coderror ==0 ){
			$nombrelog=date('Y-m-d');
			$file = fopen("Logs/log-$nombrelog-facturaOK- $this->usr .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fclose($file);
           return true;       
        }
        else{
			$nombrelog=date('Y-m-d');
			$file = fopen("Logs/log-$nombrelog-facturaFail- $this->usr .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fwrite($file,  "error:  ".$this->mesrror);
			fclose($file);
 			echo "Error al guardar la factura $this->mesrror   $this->idinmueble   $this->periodo ";
            return false;
        }
	}
	
	

	public function guardaSupFacturas(){
		$nombrelog=date('Y-m-d');

		$sql="BEGIN SGC_P_INGRESASUPFACT($this->idinmueble,'$this->observacionfacturas',
		'$this->latitud','$this->longitud','$this->fechalec',$this->periodo,'$this->usr',:PMSGRESULT, :PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE && $this->coderror ==0 ){
			$nombrelog=date('Y-m-d');
			$file = fopen("Logs/log-$nombrelog-facturaOK- $this->usr .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fclose($file);
           return true;
        }
        else{
			$nombrelog=date('Y-m-d');
			$file = fopen("Logs/log-$nombrelog-facturaFail- $this->usr .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fwrite($file,  "error:  ".$this->mesrror);
			fclose($file);
 			echo "Error al guardar la factura $this->mesrror   $this->idinmueble   $this->periodo ";
            return false;
        }
	}

}