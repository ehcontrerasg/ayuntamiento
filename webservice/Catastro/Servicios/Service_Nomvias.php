<?php  
  
require_once './../Clases/NomviaClass.php';
require_once './../Clases/TipoviaClass.php';
$fecha_actualizacion = utf8_encode($_REQUEST['fecha']);




$nomvia = new NomviaClass();
$nomvias=$nomvia->Todos($fecha_actualizacion);
$i=0;
   
while ($row = oci_fetch_array($nomvias, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listanomvias[$i]=$row;
    $i++; 
}
echo json_encode($listanomvias);