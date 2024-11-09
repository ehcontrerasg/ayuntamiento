<?php  
	require_once "./../Clases/TabParametricasClass.php";
	$id_usuario = utf8_encode($_REQUEST['ID_USUARIO']);
	$Modulo = new TablasParametricas();
	$Modulos=$Modulo->ObtenerPermisos($id_usuario);
	$i=0;
   
	while ($row = oci_fetch_array($Modulos, OCI_ASSOC+OCI_RETURN_NULLS)) {
    	$listaModulos[$i]=$row;
    	$i++; 
	}
	echo json_encode($listaModulos);
?>