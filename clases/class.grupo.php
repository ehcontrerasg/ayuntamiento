<?php
include "class.conexion.php";


class Grupo extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getGrupos()
    {
        $sql="SELECT
                G.COD_GRUPO CODIGO,
                G.DESC_GRUPO DESCRIPCION
              FROM
                ACEASOFT.SGC_TP_GRUPOS G
                ORDER BY G.DESC_GRUPO ASC ";

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
