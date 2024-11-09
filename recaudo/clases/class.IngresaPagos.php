<?php
include_once "../../clases/class.conexion.php";
class IngresaPagos extends ConexionClass{
	private $coduser;
	private $importe;
	private $referencia;
	private $num_caja;
	private $cod_inmueble;
	private $origen;
	private $monto;
	private $cod_pro;
	private $medio;
	private $id_pago;
    private $codresult;
    private $msgresult;
	
	public function __construct(){
		parent::__construct();
		$this->coduser="";
		$this->importe="";
		$this->referencia="";
		$this->num_caja="";
		$this->cod_inmueble="";
		$this->origen="";
		$this->monto="";
		$this->cod_pro="";
		$this->medio="";
		$this->id_pago="";
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }
	
	/*public function setcodigo($valor){
		$this->id_pago=$valor;
	}
	
	public function setfecha($valor){
		$this->cod_fecha= $valor;
	}*/
	
	

    public function datosDeudaInmueble($cod_inmueble){
		$sql = "SELECT I.CODIGO_INM, I.ID_ESTADO, I.ESTADO_CREDITO, 
		NVL((SELECT SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) FROM SGC_TT_FACTURA F WHERE I.CODIGO_INM = F.INMUEBLE AND F.FACTURA_PAGADA = 'N' AND FEC_EXPEDICION IS NOT NULL),0)DEUDA,
		(SELECT COUNT(1) FROM SGC_TP_RUT_EXC_CORTE RE
        WHERE I.ID_SECTOR=RE.ID_SECTOR AND
        I.ID_RUTA=RE.ID_RUTA  AND 
      RE.FECHA_ANULACION IS NULL
        ) EXCLUIDO
		
		FROM SGC_TT_INMUEBLES I
		WHERE I.CODIGO_INM = $cod_inmueble";
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }


    public function obtenerDatosSerial($cod_inmueble)
    {
        $sql = "SELECT MI.SERIAL SERIAL
        FROM SGC_TT_MEDIDOR_INMUEBLE MI
        WHERE MI.COD_INMUEBLE = '$cod_inmueble'
        and MI.FECHA_BAJA IS NULL
        ";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            //echo "false";
            return false;
        }

    }

}