<?php  
  
require_once './../Clases/SubModulosClass.php';
 
$Id_Usuario = utf8_encode($_REQUEST['id_usuario']);
$Id_Modulo = utf8_encode($_REQUEST['id_modulo']);

$submoduloUsuario = new SubModulosClass();
$SUBMODULO=$submoduloUsuario->permisos_submenu($Id_Usuario, $Id_Modulo);
$i=0;
   
while ($row = oci_fetch_array($SUBMODULO, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $subpermisos[$i]=$row;
    $i++; 
}


echo json_encode($subpermisos);
       