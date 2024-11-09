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

if (!$sortname) $sortname = "COD_SERVICIO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 100;

//$periodo = date('Ym');
$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$where = " AND S.COD_INMUEBLE = '$codinmueble'";

if ($query){ 
	$where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}

$l=new inmuebles();
$registros = $l->datosServicios($where,$sort,$start,$end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "total: 1,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
    $cod_ser=oci_result($registros, 'COD_SERVICIO');
 	$des_ser=oci_result($registros, 'DESC_SERVICIO');
	$tarifa = oci_result($registros, 'DESC_TARIFA');
    $uni_tot = oci_result($registros, 'UNIDADES_TOT');
    $uni_hab = oci_result($registros, 'UNIDADES_HAB');
    $uni_des = oci_result($registros, 'UNIDADES_DESH');
    $cupo_basico = oci_result($registros, 'CUPO_BASICO');
	$promedio = oci_result($registros, 'PROMEDIO');
	$consumo_min = oci_result($registros, 'CONSUMO_MINIMO');
	$calculo = oci_result($registros, 'DESC_CALCULO');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$cod_ser."',";
    $json .= "cell:['" .$cod_ser."'";
	$json .= ",'".addslashes($des_ser)."'";
	$json .= ",'".addslashes($tarifa)."'";
	$json .= ",'".addslashes($uni_tot)."'";
	$json .= ",'".addslashes($uni_hab)."'";
    $json .= ",'".addslashes($uni_des)."'"; 
	$json .= ",'".addslashes($cupo_basico)."'";
	$json .= ",'".addslashes($promedio)."'";
	$json .= ",'".addslashes($consumo_min)."'";
	$json .= ",'".addslashes($calculo)."'";
	
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>