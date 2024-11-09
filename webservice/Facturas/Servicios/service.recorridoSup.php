<?php
require_once './../Clases/class.recorrido.php';
$operario=utf8_encode($_REQUEST['id_operario']);
$tiporuta=utf8_encode($_REQUEST['tiporuta']);
$a=new recorrido();
$a->setoperario($operario);
$a->settiporuta($tiporuta);
$datos=$a->obtenerrecorridoSup();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$recorrido[$i]=$row;
	$i++;
}
echo json_encode($recorrido);