<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.pagos.php';
 $inmueble=$_GET['inmueble'];
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {
    $l=new Pago();
    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "FECHA_PAGO";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="FECHA_PAGO";
$tname="SGC_TT_PAGOS";
$where = " AND INM_CODIGO='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND INM_CODIGO='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Pago();
$registros=$l->pagTotal($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $codigo=oci_result($registros, 'CODIGO');
    $fechapag = oci_result($registros, 'FECHA_PAGO');
    $referencia = oci_result($registros, 'REFERENCIA');
    $importe = oci_result($registros, 'IMPORTE');
	$motivoanula = oci_result($registros, 'MOTIVO_REV');
	$fechaanula = oci_result($registros, 'FECHA_REV');
	$usuarioanula = oci_result($registros, 'USR_REV');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$codigo."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($codigo)."'";
    $json .= ",'".addslashes($fechapag)."'";
    $json .= ",'".addslashes($referencia)."'";
    $json .= ",'RD$ ".addslashes($importe)."'";
	$json .= ",'".addslashes($motivoanula)."'";
	$json .= ",'".addslashes($fechaanula)."'";
	$json .= ",'".addslashes($usuarioanula)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>