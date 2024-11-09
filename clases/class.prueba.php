<?php
/**
 * Created by PhpStorm.
 * User: Edwin Contreras
 * Date: 21/03/2018
 * Time: 10:18
 */
include_once "class.conexion.php";
class Prueba extends ConexionClass {
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

    public function getCodes() {
        $sql = "SELECT CODIGO FROM PRUEBA";
        $result = oci_parse(
            $this->_db, $sql
        );
        $success = oci_execute($result);
        if ($success == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getDatosPrueba($id) {
        $sql = "SELECT * FROM PRUEBA WHERE CODIGO=".$id;
        $result = oci_parse(
            $this->_db, $sql
        );
        $success = oci_execute($result);
        if ($success == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function insertDatosPrueba($id, $description){
        $sql = "INSERT INTO PRUEBA (CODIGO, DESCRIPCION) VALUES('".$id."', '".$description."')";
        echo $sql;
        $result = oci_parse(
            $this->_db, $sql
        );
        $success = oci_execute($result);
        echo $success;
        if ($success == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function updateDatosPrueba($id, $description){
        $sql = "UPDATE PRUEBA set DESCRIPCION='".$description."' WHERE CODIGO=".$id;
        $result = oci_parse(
            $this->_db, $sql
        );
        $success = oci_execute($result);
        if ($success == true) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
}