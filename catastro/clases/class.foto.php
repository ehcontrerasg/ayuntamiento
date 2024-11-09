<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 7/4/2016
 * Time: 11:44 AM
 */
include_once 'class.conexion.php';
class Foto extends ConexionClass{
    public function __construct()
    {
        parent::__construct();
    }


    public function eliminaFoto($urlFoto){
        $urlFoto = str_replace(' ','',$urlFoto);
        $sql="DELETE  FROM SGC_TT_FOTOS_MANTENIMIENTO FM
              WHERE FM.URL_FOTO='$urlFoto'";

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return true;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

}