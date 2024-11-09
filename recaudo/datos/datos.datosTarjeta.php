<?php

extract($tip);

if($tip=="getDatosTarjeta"){

    include_once  "../clases/class.domiciliacion.php";
    $d = new Domiciliacion();

   $datos =  $d->getDatosTarjetaByInmueble($codigo_inmueble);

}