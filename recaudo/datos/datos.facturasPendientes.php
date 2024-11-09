<?php
include '../clases/classPagos.php';
$inmueble=$_GET['inmueble'];
$monto=$_GET['monto'];
$importe1=$_GET['importe1'];
$importe2=$_GET['importe2'];
$importe3=$_GET['importe3'];
$importe4=$_GET['importe4'];
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
$where = " AND INMUEBLE='$inmueble'";

if($importe1 != '' && $importe2 == '' && $importe3 == '' && $importe4 == '') $importe = $importe1;
if($importe1 == '' && $importe2 != '' && $importe3 == '' && $importe4 == '') $importe = $importe2;
if($importe1 == '' && $importe2 == '' && $importe3 != '' && $importe4 == '') $importe = $importe3;
if($importe1 == '' && $importe2 == '' && $importe3 == '' && $importe4 != '') $importe = $importe4;
if($importe1 != '' && $importe2 == '' && $importe3 != '' && $importe4 == '') $importe = ($importe1 + $importe3);
if($importe1 != '' && $importe2 != '' && $importe3 == '' && $importe4 == '') $importe = ($importe1 + $importe2);

$l=new Pagos();
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
	$vencimiento = oci_result($registros, 'FEC_VCTO');
    $valor = oci_result($registros, 'TOTAL');
	$pendiente = oci_result($registros, 'PENDIENTE');
    $ncf = oci_result($registros, 'NCF');
	
	if($importe >= $pendiente){
		$abono = $pendiente;
		$saldo = 0;
		$importe = $importe - $pendiente;
		$importereal = $importereal + $pendiente;
	}
	
	else if($importe < $pendiente && $importe > 0){
		//$abono = $importe;
        //$saldo = $pendiente - $importe;
        $importe = $importe - $pendiente;

        $abono = 0;
		$saldo = $pendiente;
		/*$vuelta = $importe;*/

	}
	
	else{
		$abono = 0;
		$saldo = $pendiente;
		$importe=0;
	}
	
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($factura)."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($expedicion)."'";
	$json .= ",'".addslashes($vencimiento)."'";
    $json .= ",'".addslashes($ncf)."'";
    $json .= ",'<b style=\"color:#000080\">".addslashes("RD$ ".$valor)."</b>'";
	$json .= ",'<b style=\"color:#FF0000\">".addslashes("RD$ ".$pendiente)."</b>'";
	if($abono == $pendiente){
		$json .= ",'<b style=\"color:#009900\">".addslashes("RD$ ".$abono)."</b>'";
		$json .= ",'<b style=\"color:#009900\">".addslashes("RD$ ".$saldo)."</b>'";
	}
	else if($abono > 0 && $abono < $pendiente){
		$json .= ",'<b style=\"color:#FF9900\">".addslashes("RD$ ".$abono)."</b>'";
		$json .= ",'<b style=\"color:#FF0000\">".addslashes("RD$ ".$saldo)."</b>'";
	}
	else{
		$json .= ",'<b style=\"color:#FF0000\">".addslashes("RD$ ".$abono)."</b>'";
		$json .= ",'<b style=\"color:#FF0000\">".addslashes("RD$ ".$saldo)."</b>'";
	}
    
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>