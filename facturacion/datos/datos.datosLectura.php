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

if (!$sortname) $sortname = "PERIODO";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 100;

//$periodo = date('Ym');
$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
//$where = " WHERE R.COD_INMUEBLE = '$codinmueble'";

if ($query){ 
	$where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}

$l=new inmuebles();
$registros = $l->datosLectura($where,$sort,$start,$end,$codinmueble);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "total: 1,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
    $periodo=oci_result($registros, 'PERIODO');
	$lec_actual = oci_result($registros, 'LECTURA_ACTUAL');
    $fec_lec = oci_result($registros, 'FECLEC');
    $observacion = oci_result($registros, 'OBSERVACION_ACTUAL');
    $cod_lector = oci_result($registros, 'LECTOR');
    $consumo = oci_result($registros, 'CONSUMO');
	
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$periodo."',";
    $json .= "cell:['" .$numero."'";
	$json .= ",'".addslashes($periodo)."'";
	$json .= ",'".addslashes($lec_actual)."'";
	$json .= ",'".addslashes($consumo)."'";
	$json .= ",'".addslashes($fec_lec)."'";
    $json .= ",'".addslashes($observacion)."'"; 
	$json .= ",'".addslashes($cod_lector)."'";
	
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>