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

if (!$sortname) $sortname = "ID_CONTRATO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 100;

//$periodo = date('Ym');
$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$where = " CODIGO_INM = '$codinmueble'";

if ($query){ 
	$where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}

$l=new inmuebles();
$registros = $l->datosContratos($where,$sort,$start,$end);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "total: 1,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero = oci_result($registros, 'RNUM');
    $id_contrato = oci_result($registros, 'ID_CONTRATO');
 	$fec_ini = oci_result($registros, 'FECHA_INICIO');
	$fec_fin = oci_result($registros, 'FECHA_FIN');
    $cod_cli = oci_result($registros, 'CODIGO_CLI');
    $alias = oci_result($registros, 'ALIAS');
    $fec_sol = oci_result($registros, 'FECHA_SOLICITUD');
   
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_contrato."',";
    $json .= "cell:['" .$id_contrato."'";
	$json .= ",'".addslashes($fec_ini)."'";
	$json .= ",'".addslashes($fec_fin)."'";
	$json .= ",'".addslashes($cod_cli)."'";
	$json .= ",'".addslashes($alias)."'";
    $json .= ",'".addslashes($fec_sol)."'"; 
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>