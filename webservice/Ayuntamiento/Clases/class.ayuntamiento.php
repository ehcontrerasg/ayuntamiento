<?php 
date_default_timezone_set('America/Santo_Domingo');
include_once 'class.conexion.php';
class ayuntamiento extends ConexionClass{
	
	private $cliente;
	private $alto;
	private $ancho;
    private $direccion;
    private $observacion;
    private $latitud;
    private $longitud;
    private $fechalec;
	private $operario;
	private $tipoPublicidad;

	public function __construct(){
		
		parent::__construct();
		$this->cliente="";
		$this->ancho="";
		$this->alto="";
		$this->observacion="";
		$this->direccion="";
		$this->latitud="";
		$this->longitud="";
		$this->fechalec="";
		$this->operario="";
        $this->tipoPublicidad="";
	}
	
	public function setCliente		($valor){$this->cliente=$valor;}
	public function setAncho		($valor){$this->ancho=$valor;}
	public function setAlto		($valor){$this->alto=$valor;}
	public function setObservacion		($valor){$this->observacion=$valor;}
	public function setDireccion		($valor){$this->direccion=$valor;}
	public function setLatitud		($valor){$this->latitud=$valor;}
	public function setLongitud	($valor){$this->longitud=$valor;}
	public function setFechaEje		($valor){$this->fechalec=$valor;}
	public function setOperario		($valor){$this->operario=$valor;}
    public function setTipoPublicidad($valor)
    {
        $this->tipoPublicidad=$valor;
    }


	public function guardarAyuntamiento(){

		$sql=" BEGIN SGC_P_INGRESA_AYUNTAMIENTO('$this->cliente',$this->ancho,$this->alto,'$this->observacion','$this->direccion','$this->latitud','$this->longitud','$this->fechalec','$this->operario','$this->tipoPublicidad',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		$resultado=oci_parse($this->_db,$sql);
		oci_bind_by_name($resultado,':PCODRESULT',$codresult,10000);
		oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,10000);
		$bandera=oci_execute($resultado);

       

		if($bandera==TRUE && $codresult==0 ){
			 $nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-ayuntOK- $this->operario .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fclose($file);
			oci_close($this->_db);
			return true;
		}else{

 			oci_close($this->_db);
			$nombrelog=date('Y-m-d');
			$file = fopen("logs/log-$nombrelog-ayuntFail- $this->operario .txt", "a");
			fwrite($file, date('Ymd H:i:s') . PHP_EOL);
			fwrite($file, $sql . PHP_EOL);
			fwrite($file,  "error: ".$msgresult);
			fclose($file);
 			echo "Error al guardar la el trabajo $msgresult  $this->direccion  $this->cliente ";
			return false;
		}
	
	}

    public function obtenerTipPublicidad()
    {
        $resultado=oci_parse($this->_db,"SELECT ID CODIGO, DESCRIPCION FROM
				SGC_TP_TIPO_PUBLICIDAD");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al los tipos de publicidad
            ";
            return false;
        }
    }



}