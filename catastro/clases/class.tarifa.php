<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Tarifa extends ConexionClass{
 	Private $cod_concepto;
 	Private $cod_uso;
 	Private $cod_proyecto;	
 	private $sector;
 	private $ruta;
 	private $tarifa;
	
	public function __construct()
	{
		parent::__construct();
		$this->cod_concepto="";
		$this->cod_uso="";
		$this->cod_proyecto="";
		$this->sector="";
		$this->ruta="";
		$this->tarifa="";
		
	}
	public function setcodconcepto($valor){
		$this->cod_concepto=$valor;
	}
	
	public function setcoduso($valor){
		$this->cod_uso=$valor;
	}
	
	public function setcodproyecto($valor){
		$this->cod_proyecto=$valor;
	}
	
	public function setruta($valor){
		$this->ruta=$valor;
	}
	
	public function setsector($valor){
		$this->sector=$valor;
	}
	
	public function settarifa($valor){
		$this->tarifa=$valor;
	}
	

	
	public function obtenertarifa (){
		$resultado = oci_parse($this->_db,"SELECT TAR.DESC_TARIFA, TAR.CONSEC_TARIFA
				 FROM SGC_TP_TARIFAS TAR WHERE COD_SERVICIO='$this->cod_concepto' AND COD_PROYECTO='$this->cod_proyecto'
				AND COD_USO='$this->cod_uso' AND VISIBLE='S'
				ORDER BY CODIGO_TARIFA ");
		
		
		
	
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
	
	
		public function obtenertarifainm ($inmueble){
		$resultado = oci_parse($this->_db,"SELECT TAR.DESC_TARIFA, TAR.CONSEC_TARIFA
											FROM SGC_TP_TARIFAS TAR, SGC_tT_INMUEBLES I
											WHERE COD_SERVICIO='$this->cod_concepto' 
											 AND COD_PROYECTO=I.ID_PROYECTO
											 AND I.CODIGO_INM=$inmueble
											 AND COD_USO='$this->cod_uso' AND VISIBLE='S'
											 ORDER BY CODIGO_TARIFA");
		
		
		
	
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
	
	
	
	
	public function obtenercupobasico(){
		$resultado = oci_parse($this->_db,"SELECT CUPO_BASICO 
				FROM SGC_TP_VALOR_CONTRATOS 
				WHERE ID_SECTOR='$this->sector' AND ID_RUTA='$this->ruta' AND ID_USO='$this->cod_uso'
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
	
	public function obtenercupobasico2(){
		$resultado = oci_parse($this->_db,"SELECT CONSUMO_MIN
				FROM SGC_TP_TARIFAS
				WHERE CONSEC_TARIFA='$this->tarifa'
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

