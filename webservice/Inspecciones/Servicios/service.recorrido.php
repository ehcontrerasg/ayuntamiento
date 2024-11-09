<?php
require_once './../Clases/class.inspecciones.php';
$operario=utf8_encode($_REQUEST['id_operario']);
$a=new Inspecciones();
$a->setUsr($operario);
$datos=$a->obtenerrecorrido();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$recorrido[$i]=$row;
	$i++;
}
echo json_encode($recorrido);