<?php  
  
require_once './../Clases/ModulosClass.php';
 
$Id_Usuario = utf8_encode($_REQUEST['id_usuario']);
//$Id_Usuario =1024525260;

$moduloUsuario = new ModulosClass();
$MODULO=$moduloUsuario->permisos_menu($Id_Usuario);
$i=0;
   
while ($row = oci_fetch_array($MODULO, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $permisos[$i]=$row;
    $i++; 
}


echo json_encode($permisos);
       