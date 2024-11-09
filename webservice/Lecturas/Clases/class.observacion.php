<?php
include_once 'class.conexion.php';
class observacion extends ConexionClass{

	private $descripcion;
	
	public function __construct(){
		parent::__construct();

	}
	

	public function setdescripcion($valor){$this->descripcion=$valor;}
	
	public function obtenerobs(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION, LEER, MOSTRAR_MOVIL
				FROM SGC_TP_OBSERVACIONES_LECT WHERE MOSTRAR_MOVIL='S'");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener las observaciones de lectura";
			return false;
		}
		
	}
	public function obtenecodigo(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO	FROM SGC_TP_OBSERVACIONES_LECT  WHERE
				DESCRIPCION='$this->descripcion'");
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_fetch($resultado);
			$idobs=oci_result($resultado, "CODIGO");
			oci_close($this->_db);
			return $idobs;
		}else{
			oci_close($this->_db);
			echo "Error al obtener el codigo de obs";
			return false;
		}
	
	}
	
	public function obtenercalculo(){
		$resultado=oci_parse($this->_db,"SELECT CALCULO	FROM SGC_TP_OBSERVACIONES_LECT  WHERE
				DESCRIPCION='$this->descripcion'");
		
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_fetch($resultado);
			$idobs=oci_result($resultado, "CALCULO");
			oci_close($this->_db);
			return $idobs;
		}else{
			oci_close($this->_db);
			echo "Error al obtener el codigo de calculo";
			return false;
		}
	
	}
	
	
}