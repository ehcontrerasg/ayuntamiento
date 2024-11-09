<?php  
  
require_once './../Clases/UrbanizacionesClass.php';
 

$Urbanizacion = new UrbanizacionesClass();
$Urbanizaciones=$Urbanizacion->Todos();
$i=0;
   
while ($row = oci_fetch_array($Urbanizaciones, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listaUrbanizaciones[$i]=$row;
    $i++; 
}


echo json_encode($listaUrbanizaciones);