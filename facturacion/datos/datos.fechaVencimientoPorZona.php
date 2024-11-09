<?php

$tip = $_POST["tip"];

if($tip=="getFechaVencPorZona"){

    $periodo=$_POST["periodo"];
    $proyecto=$_POST["proyecto"];
    $zona = strtoupper($_POST["zona"]);

    include "../../clases/class.factura.php";
    $f= new Factura();

    $datos = $f->getReporteFechaVencimientoPorZona($periodo,$proyecto,$zona);
    $data = array();

    while(oci_fetch($datos)){
        $fecha_vencimiento = oci_result($datos,'FECHA_VENCIMIENTO');
        $fecha_corte = oci_result($datos,'FECHA_CORTE');
        $id_sector = oci_result($datos,'ID_SECTOR');
        $id_zona = oci_result($datos,'ID_ZONA');

        $arr= array(

            $fecha_vencimiento,
            $fecha_corte,
            $id_sector,
            $id_zona
        );
        array_push($data,$arr);
    } oci_free_statement($datos);

    echo json_encode($data);
}