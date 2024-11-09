<?php
include '../clases/class.AnulaPagos.php';
$inmueble=$_GET['inmueble'];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "CODIGO";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$l=new AnulaPagos();
$registros = $l->RecaudosRegistrados($inmueble, $sort, $start, $end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $id_pago=oci_result($registros, 'CODIGO');
    $fec_pago = oci_result($registros, 'FECHA_PAGO');
    $valor_pago = oci_result($registros, 'IMPORTE');
	$cajero = oci_result($registros, 'ID_USUARIO');
	$concepto = oci_result($registros, 'CONCEPTO');
	$des_concepto = oci_result($registros, 'DESC_SERVICIO');
	
	$estado = oci_result($registros, 'ESTADO');
	$mot_rev = oci_result($registros, 'MOTIVO_REV');
	$usr_rev = oci_result($registros, 'USR_REV');
	$fec_rev = oci_result($registros, 'FECHA_REV');
	
		
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_pago."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($id_pago)."'";
    $json .= ",'".addslashes($fec_pago)."'";
    $json .= ",'RD$ ".addslashes($valor_pago)."'";
	$json .= ",'".addslashes($cajero)."'";
	$json .= ",'".addslashes($concepto)."'";
	$json .= ",'".addslashes($des_concepto)."'";
	$json .= ",'".addslashes($estado)."'";
	$json .= ",'".addslashes($mot_rev)."'";
	$json .= ",'".addslashes($usr_rev)."'";
	$json .= ",'".addslashes($fec_rev)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>