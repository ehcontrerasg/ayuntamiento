<?php
include "class.conexion.php";


class Actividad extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getActividadesByUso($uso)
    {
        $uso=addslashes($uso);
        $sql="SELECT
                ACT.DESC_ACTIVIDAD DESCRIPCION,
                ACT.SEC_ACTIVIDAD CODIGO,
                ACT.ID_ACTIVIDAD CODIGO_ACT
              FROM
                SGC_TP_ACTIVIDADES ACT
              WHERE
                ACT.ID_USO='$uso'
                ORDER BY DESC_ACTIVIDAD ASC ";

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
