<?php
include_once "../../clases/class.conexion.php";

class tarifasReco extends ConexionClass {


  /* public function CantidadRegistros ($fname,$tname,$where,$sort){

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

    }*/

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



    public function obtenertarifasreco($where,$sort,$tname){

        $sql="SELECT R.PROYECTO, R.CODIGO_USO, R.CODIGO_CALIBRE, R.CODIGO_DIAMETRO, R.MEDIDOR, R.VALOR_TARIFA
        FROM $tname 
        WHERE $where $sort ";
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        //echo $sql;

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
	
	
	 public function actualizaTarifasReco($proyecto, $uso, $calibre, $diametro, $medidor, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE CODIGO_USO = '$uso' AND CODIGO_CALIBRE = '$calibre' AND MEDIDOR = '$medidor' AND PROYECTO = '$proyecto' AND CODIGO_DIAMETRO = '$diametro'";
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