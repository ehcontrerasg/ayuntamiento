<?php
include_once "class.conexion.php";


class Proyecto extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerProyecto($codUser)
    {
        $sql="SELECT
                PR.ID_PROYECTO CODIGO,
                PR.SIGLA_PROYECTO DESCRIPCION
              FROM
                SGC_TP_PROYECTOS PR,
                SGC_TT_PERMISOS_USUARIO PU
              WHERE
                PR.ID_PROYECTO = PU.ID_PROYECTO AND
                PU.ID_USUARIO = '$codUser'
              ORDER BY 2  ";
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

    public function obtenerGrupos($usr){
        $resultado = oci_parse($this->_db,"SELECT G.COD_GRUPO, G.DESC_GRUPO FROM SGC_TP_GRUPOS G WHERE G.ACTIVO = 'S' ORDER BY G.COD_GRUPO");

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
