<?php
$error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED;
error_reporting($error_reporting);
ini_set('display_errors', '1');
require_once './../Clases/class.ayuntamiento.php';
$a=new ayuntamiento();
$datos=$a->obtenerTipPublicidad();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	 $calibres[$i]=$row;
	$i++;
}
echo json_encode($calibres);