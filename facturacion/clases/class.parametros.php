<?php
include_once "../../clases/class.conexion.php";


class Parametro extends ConexionClass
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



    public function getParametroByNomPar ($nompar){
        $nompar=addslashes($nompar);
        $sql="SELECT
                P.VAL_PARAMETRO
              FROM SGC_TP_PARAMETROS P
              WHERE P.NOM_PARAMETRO='$nompar'";
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

    public function getParametros()
    {
        $sql="SELECT * FROM SGC_TP_PARAMETROS P";
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

    public function actualizarParametro($id,$val)
    {

        /*$sql="update SGC_TP_PARAMETROS P set P.VAL_PARAMETRO ='$val' where P.COD_PARAMETRO='$id' ";*/
        $sql       = "BEGIN SGC_P_ACT_PARAMETRO($id,'$val',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db,$sql);

        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);


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