<?php
include_once "class.conexion.php";


class Entidad extends ConexionClass
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



    public function getEntidadByProyecto ($proyecto){
        $sql="SELECT
                EP.COD_ENTIDAD CODIGO,
                EP.DESC_ENTIDAD DESCRIPCION
              FROM
                SGC_TP_ENTIDAD_PAGO EP
                
              WHERE
                EP.PROYECTO='$proyecto'
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