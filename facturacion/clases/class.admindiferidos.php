<?php
include_once "../../clases/class.conexion.php";

class diferidos extends ConexionClass {


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



    public function obtenerdiferidos($where,$sort,$start,$end, $tname){
        $resultado = oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION, COD_CONCEPTO
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
	
	
	 public function actualizadiferidos($id_diferido, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE CODIGO = '$id_diferido'";
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