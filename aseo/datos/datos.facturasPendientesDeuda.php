<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../../clases/class.facturas.php';
$codinmueble=$_GET['codinmueble'];
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {
    $l=new facturas();
    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "PERIODO";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="PERIODO";
$tname="SGC_TT_FACTURA_ASEO";
$where = " AND INMUEBLE='$codinmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND COD_INMUEBLE='$codinmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->facpenddeuda($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $factura=oci_result($registros, 'CONSEC_FACTURA');
    $periodo = oci_result($registros, 'PERIODO');
    $expedicion = oci_result($registros, 'FEC_EXPEDICION');
    $total = oci_result($registros, 'TOTAL');
    $vencimiento = oci_result($registros, 'FEC_VCTO');
	//$abono = 0;
	//$saldo = $total-$abono;
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($factura)."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($expedicion)."'";
    $json .= ",'".addslashes($vencimiento)."'";
    $json .= ",'<b style=\'color:#FF0000\'>".addslashes("RD$ ".$total)."</b>'";
   // $json .= ",'<b style=\'color:#009900\'>".addslashes("RD$ ".$abono)."</b>'";
	//$json .= ",'<b style=\'color:#FF0000\'>".addslashes("RD$ ".$saldo)."</b>'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>