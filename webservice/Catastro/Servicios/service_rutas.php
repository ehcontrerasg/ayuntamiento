<?php  
 require_once './../Clases/RutasClass.php';
 $Id_Operario = utf8_encode($_REQUEST['id_operario']);
 $tiporuta = utf8_encode($_REQUEST['tiporuta']);
//$Id_Operario=1024525260;
$rutas = new RutasClass();
$arrayNumRutas=$rutas->ObtenerRuta($Id_Operario,1);
$i=0;
   
while ($row = oci_fetch_array($arrayNumRutas, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listarutas[$i]=$row;
    $i++; 
}
echo json_encode($listarutas);