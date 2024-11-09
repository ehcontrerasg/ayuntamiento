<?php
include_once "class.conexion.php";


class TipoVia extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getTipoViaByPro($proyecto)
    {
        $proyecto=addslashes($proyecto);
        $sql="SELECT
                TP.ID_TIPO_VIA CODIGO,
                TP.DESC_TIPO_VIA DESCRIPCION
                FROM SGC_TP_TIPO_VIA TP
                WHERE TP.ID_PROYECTO='$proyecto'";
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }


    }

}
