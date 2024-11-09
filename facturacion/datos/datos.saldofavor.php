<?php
include '../clases/class.saldo_favor.php';
$codinmueble=$_GET['codinmueble'];
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "CODIGO";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 100;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$l=new saldo_favor();
$registros = $l->obtiene_saldos($codinmueble,$sort,$start,$end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "total: 1,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero = oci_result($registros, 'RNUM');
    $cod_sf = oci_result($registros, 'CODIGO');
 	$fec_sf = oci_result($registros, 'FECHA');
	$mot_sf = oci_result($registros, 'MOTIVO');
    $imp_sf = oci_result($registros, 'IMPORTE');
    $apl_sf = oci_result($registros, 'VALOR_APLICADO');
   
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$cod_sf."',";
    $json .= "cell:['" .$numero."'";
	$json .= ",'".addslashes($cod_sf)."'";
	$json .= ",'".addslashes($fec_sf)."'";
	$json .= ",'".addslashes($imp_sf)."'";
    $json .= ",'".addslashes($apl_sf)."'"; 
	$json .= ",'".addslashes($imp_sf - $apl_sf)."'";
	$json .= ",'".addslashes($mot_sf)."'"; 
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>