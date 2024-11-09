<?php
include '../clases/class.cortes.php';
$inmueble = ($_GET['cod_inmueble']);
// $inmueble = 183166;

function countRec($fname,$tname,$where,$sort) {
		$l=new cortes_inmuebles();
		$valores=$l->CantidadRegistros($fname, $tname,$where,$sort);
	
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

if (!$sortname) $sortname = "FECHA_EJE";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="FECHA_EJE";
$tname="SGC_TT_REGISTRO_CORTES";
if($inmueble==''){
	$where = " AND ID_INMUEBLE<>'1'";
}else{
$where = " AND ID_INMUEBLE='$inmueble'";
}

//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new cortes_inmuebles();
$registros=$l->Todos($where, $sort, $start, $end);

$total = countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
	$periodo = oci_result($registros, 'ID_PERIODO');
	$tipocorte = oci_result($registros, 'TIPO_CORTE');
	$lectura = oci_result($registros, 'LECTURA');	
	$impocorte = oci_result($registros, 'IMPO_CORTE');
	$impolectura= oci_result($registros, 'IMPO_LECTURA');
	$fecha = oci_result($registros, 'FECHA');
	$obs = oci_result($registros, 'OBS_GENERALES');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$serial."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($periodo)."'";
	$json .= ",'".addslashes($tipocorte)."'";
	$json .= ",'".addslashes($impocorte)."'";	
	$json .= ",'".addslashes($lectura)."'";	
	$json .= ",'".addslashes($impolectura)."'";
	$json .= ",'".addslashes($fecha)."'";
	$json .= ",'".addslashes(trim($obs,','))."'";
	$json .= "]}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>
