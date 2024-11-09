<?php
include "class.conexion.php";


class Foto extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getFotMantByInmPer($inm,$per)
    {
        $inm=addslashes($inm);
        $per=addslashes($per);
        $sql="SELECT
               '../../../webservice/fotos_sgc//'||FM.URL_FOTO URL_FOTO,FM.CONSECUTIVO
              FROM
                SGC_TT_FOTOS_MANTENIMIENTO FM
              WHERE
                FM.ID_PERIODO=(SELECT MAX(FM1.ID_PERIODO)
                                FROM SGC_TT_FOTOS_MANTENIMIENTO FM1
                                WHERE FM1.ID_INMUEBLE = FM.ID_INMUEBLE) AND 
                FM.ID_INMUEBLE=$inm
                ORDER BY 2 ASC ";

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
