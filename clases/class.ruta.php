<?php
include_once "class.conexion.php";


class Ruta extends ConexionClass{

    public function getRutaBySectorProyecto($sector,$proyecto){
        $sql = "SELECT ID_RUTA FROM SGC_TP_RUTAS WHERE ID_SECTOR = '$sector' AND ID_PROYECTO = '$proyecto'";

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
