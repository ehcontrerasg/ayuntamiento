<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.diferidos.php';
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

if (!$sortname) $sortname = "numero_cuotas";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="numero_cuotas";
$tname="SGC_TT_DIFERIDOS";
$where = " AND INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new diferidos();
$registros=$l->Obtenerresdif($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
	$cod_diferido=oci_result($registros, 'CODIGO');
	$des_diferido=oci_result($registros, 'DESC_SERVICIO');
    $valor_diferido=oci_result($registros, 'VALOR_DIFERIDO');
    $numero_cuotas = oci_result($registros, 'NUMERO_CUOTAS');
	$valor_cuota = oci_result($registros, 'VALOR_CUOTA');
    $cuotas_pagadas = oci_result($registros, 'CUOTAS_PAGADAS');
    $valor_pagado = oci_result($registros, 'VALOR_PAGADO');
    $pendiente = oci_result($registros, 'PENDIENTE');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$cod_diferido."',";
    $json .= "cell:['" .$numero."'";
	$json .= ",'".addslashes($cod_diferido)."'";
	$json .= ",'".addslashes($des_diferido)."'";
    $json .= ",'<b>".addslashes("RD$ ".$valor_diferido)."</b>'";
    $json .= ",'".addslashes($numero_cuotas)."'";
	$json .= ",'<b>".addslashes("RD$ ".$valor_cuota)."</b>'";
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