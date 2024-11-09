<?php
include '../clases/class.AnulaPagos.php';
$inmueble=$_GET['inmueble'];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "ID_PAGO";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1000;
if (!$rp) $rp = 1;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$l=new AnulaPagos();
$registros = $l->PagosRegistrados($inmueble, $sort, $start, $end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "rows: [";
$rc = false;

while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $id_pago=oci_result($registros, 'ID_PAGO');
    $fec_pago = oci_result($registros, 'FECHA_PAGO');
    $valor_pago = oci_result($registros, 'IMPORTE');
	$cajero = oci_result($registros, 'ID_USUARIO');
	$entidad = oci_result($registros, 'DESC_ENTIDAD');
	$punto = oci_result($registros, 'DESCRIPCION');
	$caja = oci_result($registros, 'DESC_CAJA');
	$estado = oci_result($registros, 'ESTADO');
	$mot_anula = oci_result($registros, 'MOTIVO_REV');
	$usu_anula = oci_result($registros, 'USR_REV');
	$fec_anula = oci_result($registros, 'FECHA_REV');
	
	$h=new AnulaPagos();
	$valores = $h->obtieneMedioPago($id_pago);
	$mediopago = '';
	$medio = '';
	while (oci_fetch($valores)) {
		$id_forma = oci_result($valores, 'ID_FORM_PAGO');
    	$mediopago = oci_result($valores, 'DESCRIPCION');
		$medio .= ' '.$mediopago;
		$mediopago = '';
	}
	//$mediopago = oci_result($registros, 'ID_FORM_PAGO');
		
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_pago."',";
	$json .= "title:'".$estado." - ".$mot_anula."',";
	$json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($id_pago)."'";
    $json .= ",'".addslashes($fec_pago)."'";
    $json .= ",'RD$ ".addslashes($valor_pago)."'";
	$json .= ",'".addslashes($cajero)."'";
	$json .= ",'".addslashes($entidad)."'";
	$json .= ",'".addslashes($punto)."'";
	$json .= ",'".addslashes($caja)."'";
	$json .= ",'".addslashes($medio)."'";
	$json .= ",'".addslashes($estado)."'";
	$json .= ",'".addslashes($mot_anula)."'";
	$json .= ",'".addslashes($usu_anula)."'";
	$json .= ",'".addslashes($fec_anula)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>