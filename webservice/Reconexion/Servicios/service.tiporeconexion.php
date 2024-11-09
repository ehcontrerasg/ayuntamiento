<?php
require_once './../Clases/class.observacion.php';
$fecha_actualizacion = utf8_encode($_REQUEST['fecha']);
$a=new observacion();
$a->setfecha($fecha_actualizacion);
$datos=$a->obtenertiporeconexion();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
	$tiposcortes[$i]=$row;
	$i++;
}
echo json_encode($tiposcortes);