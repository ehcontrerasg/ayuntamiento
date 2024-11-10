<?php
namespace App;

/**
 * Clase para la conexion a la base de datos.
 */
class Conexion
{
    protected $_db;

    public function __construct()
    {
        $this->_db = oci_connect(DB_USER, DB_PASS, '(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ' . DB_HOST . ')(PORT = 1521)))(CONNECT_DATA=(SID=' . DB_SID . ')))', 'AL32UTF8');
        if ($this->_db == false) {
            oci_close($this->_db);
            echo "Fallo al conectar la base";

            return false;
        }
    }
}
