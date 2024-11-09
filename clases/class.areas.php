<?php
include_once "class.conexion.php";


class Area extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getAreas()
    {
        $sql="SELECT
                AR.DESC_AREA DESCRIPCION,
                AR.ID_AREA CODIGO
              FROM
                SGC_TP_AREAS AR
              WHERE
                AR.VISIBLE='S'";

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

    public function getAreaNotAreaByArea ($area_user){
        $sql = "SELECT ID_AREA, DESC_AREA
		FROM SGC_TP_AREAS
		WHERE ID_AREA NOT IN ('$area_user')
        AND RECIBE_PQR = 'S'
		ORDER BY DESC_AREA";
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

    public function getClase()
    {
        $sql="SELECT
                ID.ID_TIPO_INGRESO CODIGO,
                ID.DESC_TIPO_INGRESO DESCRIPCION
              FROM
                SGC_TP_TIPO_INGRESOS_DOC ID
              ";

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
