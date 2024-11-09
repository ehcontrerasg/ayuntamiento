<?php
include "class.conexion.php";


class Urbanizacion extends ConexionClass{

    public function getUrbByProParc($proyecto,$parcial){
        $proyecto=addslashes($proyecto);
        $parcial=addslashes($parcial);
        $sql="SELECT
                U.CONSEC_URB CODIGO,
                U.DESC_URBANIZACION DESCRIPCION
              FROM
                SGC_TP_URBANIZACIONES U
              WHERE
                U.ID_PROYECTO='$proyecto' AND
                U.DESC_URBANIZACION LIKE '%$parcial%'";

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
            echo "false";
            return false;
        }
    }


    public function getDescUrbByCod($cod,$pro){
        $cod=trim(addslashes($cod));
        $pro=addslashes($pro);
        $sql = "SELECT
                U.CONSEC_URB CODIGO,
                U.DESC_URBANIZACION DESCRIPCION
                 FROM SGC_TP_URBANIZACIONES U
                 WHERE U.ID_PROYECTO='$pro'
                 AND U.CONSEC_URB='$cod'";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            if(oci_fetch($resultado)){
                if($resultado)
                $cant=oci_result($resultado,"DESCRIPCION");
                return $cant;
            }else{
                return false;
            }
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
