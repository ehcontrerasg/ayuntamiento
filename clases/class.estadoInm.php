<?php
include_once "class.conexion.php";


class EstadoInm extends ConexionClass
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



    public function getEstadosByEst ($est){
        $est=addslashes($est);
        $where='';
        if(trim($est)!=''){
            $where=" WHERE
              IE.ESTADO_FACT='$est'";
        }
        $sql="SELECT
                IE.DESC_ESTADO DESCRIPCION,
                IE.ID_ESTADO CODIGO
            FROM
              SGC_TP_ESTADOS_INMUEBLES IE
              $where
            ";
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


    public function getEstadosSerByEst (){
        $sql="SELECT
                IE.DESCRIPCION,
                IE.ID
            FROM
              SGC_TP_CONDICION_SERV IE
              WHERE
                VISIBLE_MOVIL='S'

            ";
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