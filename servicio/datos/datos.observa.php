<?php
include '../clases/classPqrs.php';
 $inmueble=$_GET['inmueble'];
// $inmueble=70050;
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($inmueble) {
	$l=new PQRs();
	$valores=$l->CantidadObserva($inmueble);
	while (oci_fetch($valores)) {
			$cantidad = oci_result($valores, 'CANTIDAD');				
	}oci_free_statement($valores);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "CODIGO_OBS";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
//$fname ="F.PERIODO";
//$tname="SGC_TT_FACTURA F";
//$where = " AND F.INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

/*if ($query)
{ $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}*/
$l=new PQRs();
$registros=$l->TodasObserva($sort,$start,$end, $inmueble);
//$total =countRec($inmueble);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
$numero = 1;
while (oci_fetch($registros)) {
	//$numero=oci_result($registros, 'RNUM');
	$codigo = oci_result($registros, 'CODIGO_OBS');
	$asunto = oci_result($registros, 'ASUNTO'); 	
	$descripcion = oci_result($registros, 'DESCRIPCION');
	$fecha = oci_result($registros, 'FECHA');
	$observacion = oci_result($registros, 'USR_OBSERVACION');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$numero."',";	
	$json .= "title:'".$descripcion."',";
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($codigo)."'";
	$json .= ",'".addslashes($asunto)."'";
	$json .= ",'".addslashes($descripcion)."'";
	$json .= ",'".addslashes($fecha)."'";
	$json .= ",'".addslashes($observacion)."'";
	$json .= "]}";
	$rc = true; 
	$numero++;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>