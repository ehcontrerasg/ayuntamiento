<?php
class OracleConn2 {
	
    var $link;
    var $Database;
    var $Username;
    var $Password;
    var $e;

    function OracleConn2($username, $password) {
        $this->e = "error";
        $this->link = "error";
        $this->Database = "error";
        $conexion = oci_connect($username, $password, Server2);
        if (!$conexion) {
            $this->Database = "NULL";
            $this->Username = "NULL";
            $this->Password = "NULL";
            $this->link = false;
            $this->e = oci_error();
        } else {
            $this->Database = Server2;
            $this->Username = $username;
            $this->Password = $password;
            $this->link = $conexion;
            $this->e = "NULL";
        }
    }

    function Closed2() {
        oci_close($this->link);
        return;
    }

}
?>