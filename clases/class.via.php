<?php
include "class.conexion.php";


class Via extends ConexionClass{

    public function getViaByProTipViaParc($proyecto,$parcial,$tipVia){
        $proyecto=addslashes($proyecto);
        $parcial=addslashes($parcial);
        $tipVia=addslashes($tipVia);
        $sql="SELECT
                NV.CONSEC_NOM_VIA CODIGO,
                NV.DESC_NOM_VIA DESCRIPCION
              FROM
                SGC_TP_NOMBRE_VIA NV
              WHERE
                NV.ID_PROYECTO='$proyecto' AND
                NV.ID_TIPO_VIA='$tipVia' AND
                NV.DESC_NOM_VIA LIKE'%$parcial%'";

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

    

}
