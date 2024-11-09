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
                PR.SIGLA_PROYECTO DESCRIPCION,
                PR.DESC_PROYECTO
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

    public function obtenerGrupos($usr)
    {
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

    public function obtenerSectores($proyecto)
    {
        $sql="SELECT
                    S.ID_SECTOR CODIGO,
                    S.ID_SECTOR DESCRIPCION
                FROM
                    SGC_TP_SECTORES S
                WHERE
                        S.ID_PROYECTO = '$proyecto'
                AND S.ACTIVO = 'S'
                ORDER BY S.ID_SECTOR";

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

    public function obtenerRutas($sector)
    {
        $sql="SELECT
                    R.ID_RUTA CODIGO,
                    R.ID_RUTA DESCRIPCION
                FROM
                    SGC_TP_RUTAS R
                WHERE
                        R.ID_SECTOR = '$sector'
                ORDER BY R.ID_RUTA";

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

    public function obtenerCiclos()
    {
        $sql="SELECT
                    C.ID_CICLO CODIGO,
                    C.ID_CICLO DESCRIPCION
                FROM
                    SGC_TP_CICLOS C
                ORDER BY C.ID_CICLO";

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
