<?php
include_once "../../clases/class.conexion.php";
//include_once 'class.conexion.php';
class AreaRes extends ConexionClass{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerareares ($motivo, $ger_ini){

        $sql = "SELECT M.AREA_PERTENECE, A.DESC_AREA
		FROM SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_AREAS A
		WHERE M.AREA_PERTENECE = A.ID_AREA
		AND M.ID_MOTIVO_REC = '$motivo' AND (M.GERENCIA = 'T' OR GERENCIA = '$ger_ini')";
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
