<?php
include_once "class.conexion.php";


class NCF extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getRaizNCF()
    {

        $sql="SELECT
                NCF.ID_NCF AS NCF 
              FROM
                SGC_TP_NCF NCF 
              WHERE
                NCF.VISIBLE='S'";

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
    public function obtenerNCF(){

        $sql="SELECT N.ID_NCF, F.CONSECUTIVO, F.LIMITE, N.DESCRIPCION, F.PROYECTO
                     FROM SGC_TP_NCF N, SGC_TT_CONSE_NCF F
		            WHERE N.ID_NCF = F.ID_NCF 
		            AND N.VISIBLE='S' 
		           ";

        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            return false;
        }

    }

    public function actualizaNCF($nfc, $consecutivo, $limite,$proyecto){

        $nfc = addslashes($nfc);
        $consecutivo = addslashes($consecutivo);
        $limite = addslashes($limite);
        $proyecto = addslashes($proyecto);

        $sql       = "BEGIN SGC_P_ACTNCF('$nfc','$proyecto','$limite','$consecutivo',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);

       /*$sql = "UPDATE SGC_TT_CONSE_NCF F SET F.CONSECUTIVO = '$consecutivo', F.LIMITE ='$limite'
WHERE ID_NCF = '$nfc' AND PROYECTO='$proyecto'";
          $resultado = oci_parse($this->_db,$sql);
       */

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return true;
        }
        else{
            oci_close($this->_db);
            echo "error con la actualizacion";
            return false;
        }

    }

}