<?php

require_once "./../Clases/TabParametricasClass.php";
$u = new TablasParametricas();
$materiales=$u->ObtenerVersion();
$i=0;
 
while ($row = oci_fetch_array($materiales, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$resultado[$i]=$row;
	$i++;

}

echo json_encode($resultado);