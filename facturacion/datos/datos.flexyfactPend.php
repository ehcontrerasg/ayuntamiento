<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.pagos.php';
 $pago=$_GET['pago'];
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

if (!$sortname) $sortname = "PERIODO";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="PERIODO";
$tname="SGC_TT_PAGO_FACTURAS";
$where = " AND PF.ID_PAGO='$pago'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = "  AND PF.ID_PAGO='$pago' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Pago();
$registros=$l->pagApliAfacTotal($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $periodo=oci_result($registros, 'PERIODO');
    $factura = oci_result($registros, 'CONSEC_FACTURA');
    $totfac = oci_result($registros, 'TOTAL');
    $importeapl = oci_result($registros, 'IMPORTE');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$periodo."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($factura)."'";
    $json .= ",'".addslashes($totfac)."'";
    $json .= ",'".addslashes($importeapl)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>