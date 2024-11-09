<?php
include_once "../../clases/class.conexion.php";
//include_once 'class.conexion.php';
class FechaRes extends ConexionClass{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerfechares ($motivo){
        $sql = "SELECT  TO_CHAR(SGC_F_FECRESOLUCION ( SYSDATE, DIAS_RESOLUCION ),'DD/MM/YYYY hh24:mi:ss')FEC_RESOL
				 FROM SGC_TP_MOTIVO_RECLAMOS
				WHERE ID_MOTIVO_REC = '$motivo'";
        //echo $sql;
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
