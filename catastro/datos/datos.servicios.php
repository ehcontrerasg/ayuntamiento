<?php
include '../clases/class.concepto_inmueble.php';
$inmueble = ($_GET['cod_inmueble']);

function countRec($fname,$tname,$where,$sort) {
		$l=new Concepto_inmueble();
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

if (!$sortname) $sortname = "CON.COD_SERVICIO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="CON.COD_SERVICIO";
$tname="SGC_TT_SERVICIOS_INMUEBLES CON , SGC_TP_SERVICIOS CON2";
$where = " AND CON.COD_INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Concepto_inmueble();
$registros=$l->Todos($where, $sort, $start, $end, $inmueble);

$total = countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
	$id_concepto = oci_result($registros, 'COD_SERVICIO');
	$desc_concepto = oci_result($registros, 'DESC_SERVICIO');
	$unidadestot = oci_result($registros, 'UNIDADES_TOT');	
	$unidadeshab = oci_result($registros, 'UNIDADES_HAB');
	$cupobasico = oci_result($registros, 'CUPO_BASICO');
	$promedio = oci_result($registros, 'PROMEDIO');
	$consumomin = oci_result($registros, 'CONSUMO_MINIMO');
	$estado = oci_result($registros, 'ACTIVO');
	$tarifa = oci_result($registros, 'DESC_TARIFA');
	$uso = oci_result($registros, 'COD_USO');
	$consecserv = oci_result($registros, 'CONSEC_SERVICIO_INM');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$id_concepto."',";
	//$json .= "id2:'".$consecserv."',";
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($id_concepto)."'";
	$json .= ",'".addslashes($desc_concepto)."'";	
	$json .= ",'".addslashes($unidadestot)."'";	
	$json .= ",'".addslashes($unidadeshab)."'";
	$json .= ",'".addslashes($cupobasico)."'";
	$json .= ",'".addslashes($promedio)."'";
	$json .= ",'".addslashes($consumomin)."'";
	$json .= ",'".addslashes($tarifa)."'";
	$json .= ",'".addslashes($uso)."'";
	$json .= ",'".addslashes($estado)."'";
	$json .= "]}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>