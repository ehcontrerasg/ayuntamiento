<?php
if (is_file("../Configuraciones/Configuracion.php"))
{
    include "../Configuraciones/Configuracion.php";
}
if (is_file("Configuraciones/Configuracion.php"))
{
    include "Configuraciones/Configuracion.php";
}


class ConexionClass
{
 protected $_db;

 public function __construct()
 {
  $this->_db = oci_connect(USUARIO, PASSWORD, STRING_CONEXION);
  if ( $this->_db==FALSE )
  {

   echo "Fallo al conectar la base";
   
   return; 
  }
  else {
      
      }
   }
 
}