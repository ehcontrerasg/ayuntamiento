<?php
include "../clases/class.conexion.php";

class NCF extends ConexionClass {


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



    public function obtenerNCF(){

        $sql="SELECT N.ID_NCF, F.CONSECUTIVO, F.LIMITE, N.DESCRIPCION
                     FROM SGC_TP_NCF N, SGC_TT_CONSE_NCF F 
		            WHERE N.ID_NCF = F.ID_NCF ";

        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            return false;
        }

    }

    public function actualizaNCF($nfc, $consecutivo, $limite){
        $sql = "UPDATE SGC_TT_CONSE_NCF  SET CONSECUTIVO = '$consecutivo',SET  LIMITE ='$limite' WHERE ID_NCF = '$nfc'";
        //echo $sql;
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
	
	
/*	 public function actualizaNCF($cod_ncf, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = '$data' WHERE ID_NCF = '$cod_ncf'";
		//echo $sql;
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

    }*/
	
}