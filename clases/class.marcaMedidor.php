<?php
include_once "class.conexion.php";


class MarcaMedidor extends ConexionClass
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



    public function getMarcMed (){
        $sql="SELECT
                MED.DESC_MED DESCRIPCION,
                MED.CODIGO_MED CODIGO
              FROM
                SGC_TP_MEDIDORES MED
              WHERE
                MED.ACTIVO='S'
              ORDER BY 1";
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