<?php  
require_once './../Clases/TipoDocumentoClass.php';


$Documento = new TipoDocumentoClass();
$documentos=$Documento->Todos();
$i=0;
   
while ($row = oci_fetch_array($documentos, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $listadocumentos[$i]=$row;
    $i++; 
}


echo json_encode($listadocumentos);
