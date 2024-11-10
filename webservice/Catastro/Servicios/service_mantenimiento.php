<?php  
  
require_once './../Clases/MantenimientoClass.php';
 
$Ruta = utf8_encode($_REQUEST['ruta']);
//$Ruta = 2128;

$Rutas = new MantenimientoClass();
$RutaOperario=$Rutas->ObtenerRuta($Ruta);
$i=0;
   
while ($row = oci_fetch_array($RutaOperario, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $rutasmantenimiento[$i]=$row;
    $i++; 
}


echo json_encode($rutasmantenimiento);

