<?php
include_once "../../clases/class.conexion.php";

class conceptos extends ConexionClass {


    public function CantidadRegistros ($fname,$tname,$where,$sort){

        $resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE  $where $sort");
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

   /* public function tar_especifica($consec_tar){
        $sql="SELECT * FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$consec_tar'";
        $resultado=oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE){
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }*/



    public function obtenerconceptos($where,$sort,$start,$end, $tname){
        $resultado = oci_parse($this->_db,"SELECT COD_SERVICIO, DESC_SERVICIO, ORDEN, DIFERIDO, MORA, LIMITE_MAX_CUOTAS
        FROM $tname 
        WHERE $where $sort ");
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
	
	
	 public function actualizaconceptos($id_concepto, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE COD_SERVICIO = $id_concepto";
        $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}

    }
	
}