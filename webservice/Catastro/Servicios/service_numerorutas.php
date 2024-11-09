<?php  
 require_once './../Clases/RutasClass.php';
 $Id_Operario = utf8_encode($_REQUEST['usuario']);
 $Tiporuta = utf8_encode($_REQUEST['Tiporuta']);
//$Id_Operario=1024525260;
$rutas = new RutasClass();
$arrayNumRutas=$rutas->ObtenerNumeroRutas($Id_Operario);
$i=0;
   
while ($row = oci_fetch_array($arrayNumRutas, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listarutas[$i]=$row;
    $i++; 
}
echo json_encode($listarutas);