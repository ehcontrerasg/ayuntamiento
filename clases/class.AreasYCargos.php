<?php
include_once "class.conexion.php";

class AreasYCargos extends ConexionClass
{
    function __constructor(){

        parent::_constructor();
    }

    function getAreas(){

        $sql= "SELECT A.ID_AREA,A.DESC_AREA DESC_AREA
               FROM ACEASOFT.SGC_TP_AREAS A
               WHERE A.VISIBLE = 'S'
               ORDER BY A.DESC_AREA
               ";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){

            oci_close($this->_db);
            return $resultado;
        }else{
            return false;
        }

    }

    function getCargoByUser($id_usuario){

        $sql= " SELECT U.ID_CARGO
                FROM ACEASOFT.SGC_TT_USUARIOS U 
                WHERE U.ID_USUARIO = '$id_usuario'
               ";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){

            oci_close($this->_db);
            return $resultado;
        }else{
            return false;
        }

    }

    function getCargosPorArea($idArea){
        $sql = "SELECT C.ID_CARGO, C.DESC_CARGO FROM SGC_TP_CARGOS C WHERE C.ID_AREA = $idArea";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            $json = array();
            while($fila = oci_fetch_assoc($resultado)){
                   $arr = array("codigo" => $fila["ID_CARGO"], "descripcion" => $fila["DESC_CARGO"]);
                   array_push($json,$arr);
            }
            return $json;
        }

        return false;
    }

}