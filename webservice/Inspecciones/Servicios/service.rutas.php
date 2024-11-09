<?php
require_once './../Clases/class.inspecciones.php';
$operario=utf8_encode($_REQUEST['usuario']);
$a=new Inspecciones();
$a->setUsr($operario);
$datos=$a->obteneCantRuta();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$recorrido[$i]=$row;
	$i++;
}
echo json_encode($recorrido);
