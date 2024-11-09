<?php

$proyecto= $_POST["proyecto"];
$sectorIni= $_POST["sectorIni"];
$sectorFin= $_POST["sectorFin"];
$rutaIni= $_POST["rutaIni"];
$rutaFin= $_POST["rutaFin"];
$perIni = $_POST["perIni"];
$perFin = $_POST["perFin"];

//Importar Sectores seleccionados
//include_once "../../clases/class.sector.php";
include_once "../../catastro/clases/class.ruta.php";
$r = new Ruta();
/*$s = new Sector();
$datosSector = $s->getRangoSectores('BC',1,30);

$contSector=0;
$sector=[];*/
$contRuta=0;
$ruta=[];

$datosRuta = $r->getRangoRutas($proyecto,$rutaIni,$rutaFin,$sectorIni,$sectorFin);
while(oci_fetch($datosRuta)){
    $ruta[$contRuta] = [oci_result($datosRuta,"ID_RUTA"),oci_result($datosRuta,"ID_SECTOR")];
    $contRuta++;

}oci_free_statement($datosRuta);

//Importar periodos
include "../clases/class.recaudadovsfacturado.php";
$rf = new RecaudadoVsFacturado();

$datosPeriodos = $rf->getPeriodos($perIni,$perFin);
$contPeriodo=0;
$periodo=[];
while(oci_fetch($datosPeriodos)){
    $periodo[$contPeriodo] = oci_result($datosPeriodos,"PERIODO");
    $contPeriodo++;

}oci_free_statement($datosPeriodos);


$cantidad_pagos=0;
$data=[];

$tabla=
    "
<html>
    <head>
        <meta charset='utf-8'>
    </head>
    <body>
        <h1 align='center' >Facturado&nbsp;vs&nbsp;Recaudado&nbsp;por&nbsp;sector&nbsp;y&nbsp;ruta</h1>
    <table border='1px' style='font-size: 16px;'>
            <tr>
                <th>Cantidad&nbsp;facturas</th>
                <th>Cantidad&nbsp;de&nbsp;pagos</th>
                <th>Eficiencia&nbsp;de&nbsp;pagos</th>
                <th>Valor&nbsp;Facturado</th>
                <th>Valor&nbsp;de&nbsp;recaudos</th>
                <th>Eficiencia&nbsp;de&nbsp;recaudos</th>
                <th>Sector</th>
                <th>Ruta</th>
                <th>Período</th>
            </tr>";

for($contRuta=0;$contRuta<count($ruta);$contRuta++){

    for($contPeriodo=0;$contPeriodo<count($periodo);$contPeriodo++) {


        $tabla.="<tr>";

        $datos_cantidad_facturas = $rf->getCantidadFacturas($periodo[$contPeriodo],$ruta[$contRuta][0],$ruta[$contRuta][1]);
        $cantidad_facturas=oci_fetch_assoc($datos_cantidad_facturas)["CANTIDAD_FACTURAS"];
        $tabla.= "<td>".$cantidad_facturas."</td>";

        $datos_cantidad_pagos = $rf->gatCantidadPagos($proyecto,$periodo[$contPeriodo],$ruta[$contRuta][0],$ruta[$contRuta][1]);
        $cantidad_pagos=oci_fetch_assoc($datos_cantidad_pagos)["CANTIDAD"];
        $tabla.= "<td>".$cantidad_pagos."</td>";

        $tabla.= "<td>".round((($cantidad_pagos/$cantidad_facturas)*100),0)."%</td>";

        $datos_valor_facturado=$rf->getValorFacturado($periodo[$contPeriodo],$ruta[$contRuta][0],$ruta[$contRuta][1]);
        $valor_facturado=oci_fetch_assoc($datos_valor_facturado)["VALOR_FACTURADO"];
        $tabla.=  "<td>".$valor_facturado."</td>";

        $datos_valor_recaudos = $rf->getvalorRecaudos($periodo[$contPeriodo],$ruta[$contRuta][0],$ruta[$contRuta][1]);
        $valor_recaudo=oci_fetch_assoc($datos_valor_recaudos)["RECAUDOS"];
        $tabla.=  "<td>".$valor_recaudo."</td>";

        $tabla.= "<td>".round((($valor_recaudo/$valor_facturado)*100),0)."%</td>";

        $tabla.="<td>".$ruta[$contRuta][1]."</td>";
        $tabla.="<td>".$ruta[$contRuta][0]."</td>";
        $tabla.="<td>".$periodo[$contPeriodo]."</td>";
        $tabla.="</tr>";
    }

}

$tabla.=  "</table></body></html>";
echo $tabla;

