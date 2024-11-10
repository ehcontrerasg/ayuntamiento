<?php

include_once "../clases/class.domiciliacion.php";

$domiciliacion = new Domiciliacion();

//Obtener todos los inmuebles a los que hay que realizarle el cobro
$listado_inmuebles = $domiciliacion->getDatosDomiciliacion();

while($row = oci_fetch_assoc($listado_inmuebles)){
    $codigo_inmueble = $row["INMUEBLE"];
   // $codigo_inmueble = '80801';
    $domiciliacion = new Domiciliacion();
    $sale = $domiciliacion->setSale($codigo_inmueble);
}




