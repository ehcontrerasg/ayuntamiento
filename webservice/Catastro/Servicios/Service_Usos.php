<?php  
  
require_once './../Clases/UsosClass.php';
 
$fecha_actualizacion = utf8_encode($_REQUEST['fecha']);
$Uso = new UsoClass();
$Usos=$Uso->Todos($fecha_actualizacion);
$i=0;
   
while ($row = oci_fetch_array($Usos, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listaUsos[$i]=$row;
    $i++; 
}


echo json_encode($listaUsos);