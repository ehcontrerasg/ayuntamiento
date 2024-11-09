<?php
session_start();
include_once ('../../include.php');

include '../../clases/class.inspeccionCorte.php';
$fecini = ($_GET['fecini']);
$fecfin = ($_GET['fecfin']);
$operario = ($_GET['operario']);
$ruta = ($_GET['ruta']);
$periodo = ($_GET['periodo']);



// $fecini = '17082015091506';
// $fecfin = '17082015094515';
// $operario = '001-1575155-4';
// $ruta = '2801';
// $periodo = ($_GET['periodo']);

// $fecini = '12082015091452';
// $fecfin = '12082015100914';
// $operario = '223-0025595-1';
// $ruta = '2917';
// $periodo = ($_GET['periodo']);


function countRec($fname,$tname,$where) {
	
	$a=new InspeccionCorte();
	$cantidad=$a->CantidaddetalleIns($fname, $tname, $where);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "IC.FECHA_EJE";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname = "IC.FECHA_EJE";
$tname = "SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM";
$where = " AND IC.USR_ASIG='$operario' AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta  AND IC.FECHA_ASIG BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')";

if ($query) $where = " AND IC.USR_ASIG='$operario' AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta AND IC.FECHA_ASIG BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss') AND $qtype LIKE UPPER('$query%') ";


$l=new InspeccionCorte();
$registros=$l->TodosDetalleIns($where, $sort, $start, $end);
$total = countRec("$fname","$tname","$where");
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$codigo_inm = oci_result($registros, 'CODIGO_INM');
   	$id_proceso = oci_result($registros, 'ID_PROCESO');
   	$catastro = oci_result($registros, 'CATASTRO');
   	$nombre = oci_result($registros, 'ALIAS');
	$direccion = oci_result($registros, 'DIRECCION');
	$serial = oci_result($registros, 'SERIAL');
	$observacion = oci_result($registros, 'OBS');
	$reconectado = oci_result($registros, 'RECONECTADO');
	$fecha = oci_result($registros, 'FECHA_EJECUCION');
	$numero = oci_result($registros, 'RNUM');
	$latitud = oci_result($registros, 'LATITUD');
	$longitud = oci_result($registros, 'LONGITUD');
	$ins=oci_result($registros, 'CONSEC_INSPECCION');
	

	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$codigo_inm."',";
	$json .= "cell:['<b>" .$numero."</b>'";	
	$json .= ",'".addslashes($codigo_inm)."'";
	$json .= ",'".addslashes($id_proceso)."'";
	$json .= ",'".addslashes($catastro)."'";
	$json .= ",'".addslashes($nombre)."'";
	$json .= ",'".addslashes($direccion)."'";
	$json .= ",'".addslashes($serial)."'";
	$json .= ",'".addslashes(trim(trim($observacion,','),','))."'";
	$json .= ",'".addslashes($reconectado)."'";
	$json .= ",'".addslashes($fecha)."'";
	$f=new InspeccionCorte();
	$total_fotos=$f->existefotoins($codigo_inm, $fecini, $fecfin, $operario);
	$f=new InspeccionCorte();
	$total_coordenada=$f->existecoordenadains($ins);
	
	if($total_fotos == true){
		$json .= ",'"."<b><a href=\"JAVASCRIPT:fotos(".$codigo_inm.",\'".$fecini."\',\'".$fecfin."\');\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
	}	
	else{
		$json .= ",'"."<b></b>"."'";
	}
	if($total_coordenada){
		$json .= ",'"."<b><a href=\"JAVASCRIPT:ubicacion(".$latitud.",".$longitud.");\">" ."<img src=\"../../images/mundo.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."']";
	}else{
		$json .= ",'"."<b></b>"."']";
	}
	$json .= "}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>