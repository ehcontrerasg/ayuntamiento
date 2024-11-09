<?php
require_once './../Clases/class.recorrido.php';
$operario=utf8_encode($_REQUEST['id_operario']);
//$operario='000-0000000-0';
$a=new recorrido();
$a->setoperario($operario);
$a->settiporuta($tiporuta);
$datos=$a->obtenerrecorridoInsEstMed();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$recorrido[$i]=$row;
	$i++;
}
echo json_encode($recorrido);