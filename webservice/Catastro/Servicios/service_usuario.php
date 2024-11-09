<?php  
  
require_once './../Clases/UsuariosClass.php';
 
$Usuario = utf8_encode($_REQUEST['usuario']);
$Contrasena = utf8_encode($_REQUEST['contrasena']);
$usuarioModel = new UsuariosClass();
$e_user=$usuarioModel->Usuario_Especifico($Usuario, $Contrasena);
$i=0;
   
while ($row = oci_fetch_array($e_user, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $resultado[$i]=$row;
    $control=1; 
    $i++;
    
}
  
echo json_encode($resultado);
       

