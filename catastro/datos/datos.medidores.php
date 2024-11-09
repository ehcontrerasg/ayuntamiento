<?php
include '../clases/class.medidor_inmueble.php';
$inmueble = ($_GET['cod_inmueble']);

function countRec($fname,$tname,$where,$sort) {
		$l=new Medidor_inmueble();
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

if (!$sortname) $sortname = "MI.SERIAL";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="MI.COD_MEDIDOR";
$tname="SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_EMPLAZAMIENTO, SGC_TP_MEDIDORES M, SGC_TP_CALIBRES CA";
if($inmueble==''){
	$where = " AND MI.COD_INMUEBLE<>'1'";
}else{
$where = " AND MI.COD_INMUEBLE='$inmueble'";
}

//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Medidor_inmueble();
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
	$medidor = oci_result($registros, 'DESC_MED');
	$emplazamiento = oci_result($registros, 'DESC_EMPLAZAMIENTO');
	$calibre = oci_result($registros, 'DESC_CALIBRE');	
	$serial = oci_result($registros, 'SERIAL');
	$codinmueble= oci_result($registros, 'COD_INMUEBLE');
	$fecha_instalacion = oci_result($registros, 'FECHA_INSTALACION');
	$fecha_baja = oci_result($registros, 'FECHA_BAJA');
	$metodo_sum = oci_result($registros, 'METODO_SUMINISTRO');
	$estmed= oci_result($registros, 'DESCRIPCION');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$serial."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($codinmueble)."'";
	$json .= ",'".addslashes($medidor)."'";
	$json .= ",'".addslashes($emplazamiento)."'";	
	$json .= ",'".addslashes($calibre)."'";	
	$json .= ",'".addslashes($serial)."'";
	$json .= ",'".addslashes($fecha_instalacion)."'";
	$json .= ",'".addslashes($fecha_baja)."'";
	$json .= ",'".addslashes($metodo_sum)."'";
	$json .= ",'".addslashes($estmed)."'";
	$json .= "]}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>
