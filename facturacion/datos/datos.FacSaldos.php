<?php
include '../../clases/class.factura.php';
$inmueble=$_GET['inmueble'];
$id_pago=$_GET['id_pago'];
//$inmueble='160280';
//$importe1=$_GET['importe1'];
//$importe2=$_GET['importe2'];
//$importe3=$_GET['importe3'];
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "PERIODO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
//$where = " AND INM_CODIGO='$inmueble'";

/*if($importe1 != '' && $importe2 == '' && $importe3 == '') $importe = $importe1;
if($importe1 == '' && $importe2 != '' && $importe3 == '') $importe = $importe2;
if($importe1 == '' && $importe2 == '' && $importe3 != '') $importe = $importe3;
if($importe1 != '' && $importe2 == '' && $importe3 != '') $importe = ($importe1 + $importe3);
if($importe1 != '' && $importe2 != '' && $importe3 == '') $importe = ($importe1 + $importe2);
*/
$l=new Factura();
$registros = $l->getFacAplSalFavByPagFlexy($id_pago, $sort,$start,$end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
	$num_inmueble=oci_result($registros, 'INMUEBLE');
    $id_fac=oci_result($registros, 'CONSEC_FACTURA');
	$periodo = oci_result($registros, 'PERIODO');
    $fec_pago = oci_result($registros, 'FECHA_PAGO');
    $total_fac = oci_result($registros, 'TOTAL');
	$importe_pago = oci_result($registros, 'IMPORTE');
	$comprobante = oci_result($registros, 'COMPROBANTE');
	
	$saldo = $total_fac - $importe_pago;
		
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_fac."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($id_fac)."'";
	$json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($fec_pago)."'";
    $json .= ",'RD$ ".addslashes($total_fac)."'";
	$json .= ",'RD$ ".addslashes($importe_pago)."'";
	$json .= ",'RD$ ".addslashes($saldo)."'";
	$json .= ",'".addslashes($comprobante)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>