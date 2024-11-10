<?php
session_start();

include '../clases/class.reportes.php';
$fecini = ($_GET['fecini']);
$fecfin = ($_GET['fecfin']);
$operario = ($_GET['operario']);
$ruta = ($_GET['ruta']);
$periodo = ($_GET['periodo']);



// $fecini = '13082015000000';
// $fecfin = '13082015235959';
// $operario = '022-0028376-6';
// $ruta = '3055';
// $periodo = ($_GET['periodo']);

// $fecini = '12082015091452';
// $fecfin = '12082015100914';
// $operario = '223-0025595-1';
// $ruta = '2917';
// $periodo = ($_GET['periodo']);


function countRec($fname,$tname,$where) {
	
	$a=new Reportes();
	$cantidad=$a->CantidaddetalleFac($fname, $tname, $where);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "EF.FECHA_EJECUCION";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname = "EF.FECHA_EJECUCION";
$tname = "SGC_TT_REGISTRO_ENTREGA_FAC EF, SGC_TT_INMUEBLES INM";
$where = "AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta  AND EF.FECHA_EJECUCION BETWEEN TO_DATE('$fecini', 'DDMMYYYYHH24MISS') AND TO_DATE('$fecfin', 'DDMMYYYYHH24MISS')";

if ($query) $where = " AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta AND EF.FECHA_EJECUCION BETWEEN TO_DATE('$fecini', 'DDMMYYYYHH24MISS') AND TO_DATE('$fecfin', 'DDMMYYYYHH24MISS') AND $qtype LIKE UPPER('$query%') ";


$l=new Reportes();
$registros=$l->TodosDetalleFac($where, $sort, $start, $end);
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
	$direccion = oci_result($registros, 'DIRECCION');
	$fecha_mant = oci_result($registros, 'FECHA_EJECUCION');
	$numero = oci_result($registros, 'RNUM');
	$latitud = oci_result($registros, 'LATITUD');
	$longitud = oci_result($registros, 'LONGITUD');
	

	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$codigo_inm."',";
	$json .= "cell:['<b>" .$numero."</b>'";	
	$json .= ",'".addslashes($codigo_inm)."'";
	$json .= ",'".addslashes($id_proceso)."'";
	$json .= ",'".addslashes($direccion)."'";
	$json .= ",'".addslashes($fecha_mant)."'";
	
	$f=new Reportes();
	$total_fotos=$f->existefoto($codigo_inm, substr($fecini,0,8), substr($fecfin,0,8), $operario);
	$f=new Reportes();
	$total_coordenada=$f->existecoordenadafac($codigo_inm, substr($fecini,0,8), substr($fecfin,0,8), $operario);
	
	if($total_fotos == true){
		$json .= ",'"."<b><a href=\"JAVASCRIPT:fotos(".$codigo_inm.",".$periodo.");\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
	}	
	else{
		$json .= ",'"."<b></b>"."'";
	}
	if($total_coordenada==true){
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