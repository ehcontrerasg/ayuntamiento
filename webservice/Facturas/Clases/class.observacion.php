<?php
include_once 'class.conexion.php';
class observacion extends ConexionClass{
	private $fecha;
	private $descripcion;
	
	public function __construct(){
		parent::__construct();
		$this->fecha="";	
	}
	
	public function setfecha($valor){$this->fecha=$valor;}
	public function setdescripcion($valor){$this->descripcion=$valor;}
	
	public function obtenerobs(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION,TIPO
				FROM SGC_TP_OBSERVACIONES_FAC  ");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener las observaciones de factura	 ";
			return false;
		}
		
	}

	
	
}