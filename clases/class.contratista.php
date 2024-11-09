<?php
include_once "class.conexion.php";


class Contratista extends ConexionClass
{


    private $mesrror;
    private $coderror;


    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }



    public  function getContratistas($cod){



        $sql="SELECT C.ID_CONTRATISTA ,C.DESCRIPCION,C.ID_CONTRATISTA CODIGO 
        FROM SGC_TP_CONTRATISTAS C, SGC_TT_PERMISOS_CONTRATISTA PC
        WHERE PC.ID_USUARIO='$cod'
        AND C.ID_CONTRATISTA= pc.ID_CONTRATISTA
        ";
        $resourse=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resourse);
        if($bandera)
        {
            oci_close($this->_db);
            return $resourse;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
}