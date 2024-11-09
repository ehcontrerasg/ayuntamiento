<?php
include "class.conexion.php";


class Suministro extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getSuministro()
    {
        $sql="SELECT
                COD_SUMINISTRO CODIGO,
                DESC_SUMINISTRO DESCRIPCION
              FROM
                SGC_TP_MET_SUMINISTRO";

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
