<?php
include '../clases/classPagos.php';
$cod_inmueble=$_GET['cod_inmueble'];
$cliente=$_GET['cliente'];
//$id_pago=$_GET['id_pago'];
//$inmueble='160280';
//$importe1=$_GET['importe1'];
//$importe2=$_GET['importe2'];
//$importe3=$_GET['importe3'];
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "FECHA_REGISTRO";
if (!$sortorder) $sortorder = "DESC";

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
$l=new Pagos();
$registros = $l->DatosPagosInmueble($cod_inmueble, $sort, $start,$end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
	$id_pago=oci_result($registros, 'ID_PAGO');
	$fec_pago = oci_result($registros, 'FECHA_PAGO');
	$importe_pago = oci_result($registros, 'IMPORTE');
    $cajera=oci_result($registros, 'ID_USUARIO');
	$periodo = oci_result($registros, 'PERIODO');
	$tipo = oci_result($registros, 'TIPO');
    	
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_pago."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($id_pago)."'";
    $json .= ",'".addslashes($fec_pago)."'";
	$json .= ",'RD$ ".addslashes($importe_pago)."'";
	$json .= ",'".addslashes($cajera)."'";
	$json .= ",'".addslashes($periodo)."'";
	$json .= ",'".addslashes($tipo)."'";
    $json .= ",'" . "<b><a href=\"JAVASCRIPT:EnvioEmail(". $cod_inmueble .", ". $id_pago .");\">" . "Envio Comprobante" . "</a></b>" . "'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>