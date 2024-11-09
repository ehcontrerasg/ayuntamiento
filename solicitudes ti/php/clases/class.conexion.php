<?php

if (is_file("../../../configuraciones/conf.bd.php")) {
    include_once "../../../configuraciones/conf.bd.php";
}

class ConexionClass
{
    protected $_db;

    public function __construct()
    {
        $this->_db = oci_connect(USUARIO, PASSWORD, STRING_CONEXION, 'AL32UTF8');
        if ($this->_db == false) {
            oci_close($this->_db);
            echo "Fallo al conectar la base";

            return false;
        }

    }

}
