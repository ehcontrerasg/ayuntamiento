<?php

require_once '../clases/class.conexion.php';

/**
 * Clase para realizar las Pruebas.
 */

class TestClass extends ConexionClass
{

    // $this->_db // Es la Variable de conexion llamada desde la clase extendida ConexionClass.

    public function __construct()
    {
        parent::__construct();

    }

    public function Insertar()
    {
        $codigo    = $_GET['codigo'];
        $nombre    = $_GET['nombre'];
        $apellido  = $_GET['apellido'];
        $sexo      = $_GET['sexo'];
        $edad      = $_GET['edad'];
        $telefono  = $_GET['telefono'];
        $direccion = $_GET['direccion'];
        $sql       = "INSERT INTO prueba values ($codigo,'$nombre','$apellido','$sexo', '$edad', '$telefono','$direccion')";
        $result    = oci_parse($this->_db, $sql);
        oci_execute($result);
        oci_close($this->_db);

    }

    /*  $sql = 'SELECT * FROM PRUEBA';

$result = oci_parse(connection, sql_text);

oci_execute(statement);

oci_close(connection);*/

}

$inst = new TestClass;

$inst->Insertar();
