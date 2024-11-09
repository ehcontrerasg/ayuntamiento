<?php
include_once "class.conexion.php";


class PuntoPago extends ConexionClass
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



    public function getPuntoByEntidad ($entidad){
         $sql="SELECT
                EP.ID_PUNTO_PAGO CODIGO,
                EP.DESCRIPCION DESCRIPCION
              FROM
                SGC_TP_PUNTO_PAGO ep
              WHERE
                EP.ENTIDAD_COD='$entidad'
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