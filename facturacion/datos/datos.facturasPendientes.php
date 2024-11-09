<?php
include '../clases/class.facturas.php';
$inmueble=$_GET['inmueble'];

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
$tname="SGC_TT_FACTURA";
$where = " AND INMUEBLE='$inmueble'";

if ($query)
{ $where = " AND INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros = $l->facpend($where, $sort, $start, $end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $factura=oci_result($registros, 'CONSEC_FACTURA');
    $periodo = oci_result($registros, 'PERIODO');
    $expedicion = oci_result($registros, 'FEC_EXPEDICION');
    $valor = oci_result($registros, 'TOTAL');
    $ncf = oci_result($registros, 'NCF');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($factura)."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($expedicion)."'";
    $json .= ",'".addslashes($ncf)."'";
    $json .= ",'<b style=\"color:#FF0000\">".addslashes("RD$ ".$valor)."</b>'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>