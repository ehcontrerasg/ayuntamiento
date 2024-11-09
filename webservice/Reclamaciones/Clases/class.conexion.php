<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 9/5/2018
 * Time: 3:51 PM
 */

require_once "./../Configuraciones/conf.bd.php";

class ConexionClass
{
    protected $_db;

    public function __construct()
    {
        $this->_db = oci_connect(USUARIO, PASSWORD, STRING_CONEXION,'AL32UTF8');

        if ( $this->_db==FALSE )
        {
            echo "Fallo al conectar la base";

            return;
        }
        else {

        }
    }

}