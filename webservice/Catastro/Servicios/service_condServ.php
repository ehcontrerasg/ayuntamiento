<?php  
  
require_once "./../Clases/CondServClass.php";
$Actividad = new CondicionServClass();
$Actividades=$Actividad->Todos();
$i=0;
   
while ($row = oci_fetch_array($Actividades, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listaActividades[$i]=$row;
    $i++; 
}


echo  json_encode($listaActividades);