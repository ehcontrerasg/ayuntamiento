<?php
require_once './../Clases/class.observacion.php';
$a=new observacion();
$datos=$a->obtenerActPrev();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	 $calibres[$i]=$row;
	$i++;
}
echo json_encode($calibres);