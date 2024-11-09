<?php
include_once /*dirname(__FILE__).*/"./class.domiciliacion.php";

//echo dirname(__FILE__);
$domiciliacion = new Domiciliacion();

//Obtener todos los inmuebles a los que hay que realizarle el cobro
$listado_inmuebles = $domiciliacion->getDatosDomiciliacion();

while($row = oci_fetch_assoc($listado_inmuebles)){

    $currentDateTime = date('Y-m-d H:i:s'); // para obtener la fecha actual
    $codigo_inmueble = $row["INMUEBLE"];

    $domiciliacion  = new Domiciliacion();
    $sale = $domiciliacion->setSale($codigo_inmueble);
    
    if(!is_dir('../../logs')) {
        mkdir('../../logs',0777);
    }

    error_log($codigo_inmueble.' '.$currentDateTime,3,'../../logs/inmuebles_pagos_recurrentes.log');

}