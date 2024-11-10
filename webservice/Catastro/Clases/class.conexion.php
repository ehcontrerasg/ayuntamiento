<?php
require_once "./../Configuraciones/conf.bd.php";

class ConexionClass
{
 protected $_db;

 public function __construct()
 {
  	$this->_db = oci_connect(USUARIO, PASSWORD, STRING_CONEXION,'AL32UTF8');
  	
  	if ( $this->_db==FALSE )
  	{
   		echo "Fallo al conectar la base 1";
   
   		return; 
  	}
 	
      
 	
     
}
 
}
