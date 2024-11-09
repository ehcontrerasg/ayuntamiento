<?php  
  
require_once './../Clases/EstadosClass.php';
 

$Estado = new EstadosClass();
$Estados=$Estado ->Todos();
$i=0;
   
while ($row = oci_fetch_array($Estados, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listaEstados[$i]=$row;
    $i++; 
}


echo json_encode($listaEstados);