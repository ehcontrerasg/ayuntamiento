<?php
include '../clases/class.inmuebles.php';
$proyecto=$_GET['proyecto'];
$codinmueble=$_GET['codinmueble'];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "FECHA_INSTALACION";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 100;

//$periodo = date('Ym');
$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$where = " AND M.COD_INMUEBLE = '$codinmueble'";

if ($query){ 
	$where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}

$l=new inmuebles();
$registros = $l->datosMedidor($where,$sort,$start,$end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "total: 1,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
    $marcamed=oci_result($registros, 'DESC_MED');
	$emplazamiento = oci_result($registros, 'DESC_EMPLAZAMIENTO');
    $calibremed = oci_result($registros, 'DESC_CALIBRE');
    $serialmed = oci_result($registros, 'SERIAL');
    $fecinstal = oci_result($registros, 'FECHA');
    $metodosum = oci_result($registros, 'DESC_SUMINISTRO');
    $estadomed = oci_result($registros, 'DESCRIPCION');
	$lecinstal = oci_result($registros, 'LECTURA_INSTALACION');
	$obsins = oci_result($registros, 'OBS_INS');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$serialmed."',";
    $json .= "title:'".$obsins."',";
    $json .= "cell:['" .$numero."'";
	$json .= ",'".addslashes($serialmed)."'";
	$json .= ",'".addslashes($marcamed)."'";
	$json .= ",'".addslashes($calibremed)."'";
    $json .= ",'".addslashes($emplazamiento)."'"; 
	$json .= ",'".addslashes($fecinstal)."'";
	$json .= ",'".addslashes($estadomed)."'";
	$json .= ",'".addslashes($metodosum)."'";
	$json .= ",'".addslashes($lecinstal)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>