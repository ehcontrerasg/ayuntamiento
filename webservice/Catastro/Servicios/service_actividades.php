<?php  
  
require_once "./../Clases/ActividadesClass.php";
$Actividad = new ActividadesClass();
$Actividades=$Actividad ->Todos();
$i=0;
   
while ($row = oci_fetch_array($Actividades, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listaActividades[$i]=$row;
    $i++; 
}


echo  json_encode($listaActividades);