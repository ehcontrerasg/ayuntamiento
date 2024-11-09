<?php
include_once "../../clases/class.conexion.php";
//include_once 'class.conexion.php';
class Motivo extends ConexionClass{

    public function __construct()
    {
        parent::__construct();
    }

    public function obtenermotivo ($tipo, $ger_inm){
        $sql = "SELECT ID_MOTIVO_REC, DESC_MOTIVO_REC
				 FROM SGC_TP_MOTIVO_RECLAMOS
				WHERE ID_TIPO_RECLAMO = '$tipo' AND GERENCIA IN('T','$ger_inm')
                AND VISIBLE = 'S'
                AND AYUNTAMIENTO = 'S'
				ORDER BY ID_MOTIVO_REC ";
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
