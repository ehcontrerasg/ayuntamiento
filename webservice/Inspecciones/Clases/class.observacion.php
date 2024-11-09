<?php
include_once 'class.conexion.php';
class observacion extends ConexionClass{
	private $fecha;

	public function __construct(){
		parent::__construct();
		$this->fecha="";	
	}
	
	public function setFecha($valor){
		$this->fecha=$valor;
	}

	public function obtenerObsIns(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION
				FROM SGC_TP_OBS_CORTE WHERE TIPO='I' ");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener los tipos de corte";
			return false;
		}
	}



    public function obtenerobscorte(){

        $resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION
				FROM SGC_TP_TIPO_CORTE ");
        $bandera=oci_execute($resultado);
        if($bandera=TRUE){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            echo "Error al obtener llos tipos de corte";
            return false;
        }

    }
}