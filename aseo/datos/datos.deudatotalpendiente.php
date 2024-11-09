<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */
include '../../clases/class.facturas.php';
$codinmueble=$_GET['codinmueble'];
function countRec($fname,$tname,$where,$sort) {
    return 100;
}
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$total =countRec($fname, $tname, $where, $sort);

$fname ="CATIDAD";
$tname="SGC_TT_FACTURA_ASEO";
$where = " AND F.INMUEBLE='$codinmueble'";

/*if ($query)
{ $where = " AND F.INMUEBLE='$codinmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}*/
$l=new facturas();
$registros=$l->numfacven($where, $sort, $start, $end);

$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {

    $factura=oci_result($registros, 'CANTIDAD');
    $periodomax = oci_result($registros, 'PERIODOMAX');
    $periodomin = oci_result($registros, 'PERIODOMIN');
    $deuda = oci_result($registros, 'DEUDA');
	$deuda = "RD$ ".number_format($deuda,0,',','.');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['$factura'";

    $json .= ",'".addslashes($periodomin)."'";
   $json .= ",'".addslashes($periodomax)."'";
    $json .= ",'<b style=\'color:#FF0000\'>".$deuda."</b>'";
    $json .= "]}";
    $rc = true;
}

$json .= "]\n";
$json .= "}";
echo $json;
?>