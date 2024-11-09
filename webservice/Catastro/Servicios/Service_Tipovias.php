<?php  
  
require_once './../Clases/TipoviaClass.php';
$fecha_actualizacion = utf8_encode($_REQUEST['fecha']);

$tipovia = new TipoviaClass();
$tipovias=$tipovia ->Todos($fecha_actualizacion);
$i=0;
   
while ($row = oci_fetch_array($tipovias, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listatipovias[$i]=$row;
    $i++; 
}
echo json_encode($listatipovias);