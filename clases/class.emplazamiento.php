<?php
include_once "class.conexion.php";


class Emplazamiento extends ConexionClass
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



    public function getEmplazamiento (){
        $sql="SELECT
                EMP.COD_EMPLAZAMIENTO CODIGO,
                EMP.DESC_EMPLAZAMIENTO DESCRIPCION
              FROM
                SGC_TP_EMPLAZAMIENTO EMP
              WHERE
                EMP.ACTIVO='S'
              ORDER BY 2";
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
}