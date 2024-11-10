<?php
session_start();

include '../clases/class.reportes.php';
$fecini = ($_GET['fecini']);
$fecfin = ($_GET['fecfin']);
$operario = ($_GET['operario']);
$ruta = ($_GET['ruta']);
$periodo = ($_GET['periodo']);



  //$fecini = '13072015000000';
 // $fecfin = '13072015235959';
 // $operario = '005-0040680-6';
 // $ruta = '1470';
//  $periodo = ($_GET['periodo']);

// $fecini = '12082015091452';
// $fecfin = '12082015100914';
// $operario = '223-0025595-1';
// $ruta = '2917';
// $periodo = ($_GET['periodo']);


function countRec($fname,$tname,$where) {
	
	$a=new Reportes();
	$cantidad=$a->CantidaddetalleLec($fname, $tname, $where);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "LEC.FECHA_LECTURA";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname = "LEC.FECHA_LECTURA";
$tname = "SGC_TT_REGISTRO_LECTURAS LEC, SGC_TT_INMUEBLES INM";
$where = "AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta  AND LEC.FECHA_LECTURA BETWEEN TO_DATE('$fecini', 'DDMMYYYYHH24MISS') AND TO_DATE('$fecfin', 'DDMMYYYYHH24MISS')";

if ($query) $where = " AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta AND LEC.FECHA_LECTURA BETWEEN TO_DATE('$fecini', 'DDMMYYYYHH24MISS') AND TO_DATE('$fecfin', 'DDMMYYYYHH24MISS') AND $qtype LIKE UPPER('$query%') ";


$l=new Reportes();
$registros=$l->TodosDetalleLec($where, $sort, $start, $end);
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
	$lectura = oci_result($registros, 'LECTURA');
	$observacion = oci_result($registros, 'OBSERVACION');
	$fecha_mant = oci_result($registros, 'FECHA_EJECUCION');
	$numero = oci_result($registros, 'RNUM');
	$latitud = oci_result($registros, 'LATITUD');
	$longitud = oci_result($registros, 'LONGITUD');
	$consumo = oci_result($registros, 'CONSUMO');
	

	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$codigo_inm."',";
	$json .= "cell:['<b>" .$numero."</b>'";	
	$json .= ",'".addslashes($codigo_inm)."'";
	$json .= ",'".addslashes($id_proceso)."'";
	$json .= ",'".addslashes($direccion)."'";
	$json .= ",'"."<b><a href=\"JAVASCRIPT:hislec(".$codigo_inm.");\">" .$lectura." </a></b>"."'";
	$json .= ",'".addslashes($observacion)."'";
	$json .= ",'".addslashes($consumo)."'";
	$json .= ",'".addslashes($fecha_mant)."'";
	
	$f=new Reportes();
	$total_fotos=$f->existefotolec($codigo_inm, substr($fecini,0,8),substr($fecfin,0,8), $operario);
	$f=new Reportes();
	$total_coordenada=$f->existecoordenadalec($codigo_inm, substr($fecini,0,8), substr($fecfin,0,8), $operario);
	
	if($total_fotos == true){
		$json .= ",'"."<b><a href=\"JAVASCRIPT:fotos(".$codigo_inm.",\'".$fecini."\',\'".$fecfin."\');\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
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