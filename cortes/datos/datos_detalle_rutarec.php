<?php
session_start();
include_once ('../../clases/class.reconexion.php');

$fecini = ($_GET['fecini']);
$fecfin = ($_GET['fecfin']);
$operario = ($_GET['operario']);
$ruta = ($_GET['ruta']);
$periodo = ($_GET['periodo']);
$fechAsig = ($_GET['fechAsig']);



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
	
	$a=new Reconexion();
	$cantidad=$a->getCantDetalleRec($fname, $tname, $where);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "C.FECHA_EJE";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname = "C.FECHA_EJE";
$tname = "SGC_TT_REGISTRO_RECONEXION C, SGC_TT_INMUEBLES INM";
$where = " AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta AND TO_CHAR(C.FECHA_PLANIFICACION,'YYYYMMDD')=TO_CHAR(TO_DATE('$fechAsig','DD/MM/YYYY'),'YYYYMMDD')  AND C.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')";

if ($query) $where = "  AND C.USR_EJE='$operario' AND TO_CHAR(C.FECHA_PLANIFICACION,'YYYYMMDD')=TO_CHAR(TO_DATE('$fechAsig','DD/MM/YYYY'),'YYYYMMDD')  AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)= $ruta AND C.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss') AND $qtype LIKE UPPER('$query%') ";


$l=new Reconexion();
$registros=$l->getTodosDetalleRec($where, $sort, $start, $end);
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
	$lectura = oci_result($registros, 'LECTURA');
	$observaciones = oci_result($registros, 'OBS_GENERALES');
	$fecha_mant = oci_result($registros, 'FECHA_EJECUCION');
	$tipocorte = oci_result($registros, 'TIPO_RECONEXION');
	$numero = oci_result($registros, 'RNUM');
	$latitud = oci_result($registros, 'LATITUD');
	$longitud = oci_result($registros, 'LONGITUD');
	$impocorte=oci_result($registros, 'IMPO_REC');
	

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
	$json .= ",'".addslashes($lectura)."'";
	$json .= ",'".addslashes($tipocorte)."'";
	$json .= ",'".addslashes($impocorte)."'";
	$json .= ",'".addslashes(trim(trim($observaciones,','),','))."'";
	$json .= ",'".addslashes($fecha_mant)."'";
	
	$f=new Reconexion();
	$total_fotos=$f->getExisteFotoRec($codigo_inm, $fecha_mant, $fecha_mant, $operario);
	$f=new Reconexion();
	$total_coordenada=$f->getExisteCoordenadaRec($codigo_inm,$fecha_mant,$operario);
	
	if($total_fotos == true){
		$json .= ",'"."<b><a href=\"JAVASCRIPT:fotos(".$codigo_inm.",\'".$fecha_mant."\',\'".$fecha_mant."\');\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
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