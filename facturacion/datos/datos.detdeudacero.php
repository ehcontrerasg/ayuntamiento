<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.deuda_cero.php';
$inmueble=$_GET['inmueble'];
//$factura=75169582;
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {

    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "total_diferido";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="total_diferido";
$tname="sgc_tt_deuda_cero";
$where = " AND COD_INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND COD_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new deuda_cero();
$registros=$l->Obtenerdeudacero($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;

while (oci_fetch($registros)) {

    $numero=oci_result($registros, 'RNUM');
    $valor_diferido=oci_result($registros, 'TOTAL_DIFERIDO');
    $numero_cuotas = oci_result($registros, 'TOTAL_CUOTAS');
    $cuotas_pagadas = oci_result($registros, 'TOTAL_CUOTAS_PAG');
    $valor_pagado = oci_result($registros, 'TOTAL_PAGADO');
    $pendiente = oci_result($registros, 'PENDIENTE');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$numero.$pendiente."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'<b>".addslashes("RD$ ".$valor_diferido)."</b>'";
    $json .= ",'".addslashes($numero_cuotas)."'";
    $json .= ",'".addslashes($cuotas_pagadas)."'";
    $json .= ",'<b>".addslashes("RD$ ".$valor_pagado)."</b>'";
    $json .= ",'<b style=color:#990000>".addslashes("RD$ ".$pendiente)."</b>'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>