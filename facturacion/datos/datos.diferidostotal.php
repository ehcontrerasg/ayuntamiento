<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.diferidos.php';
$inmueble=$_GET['inmueble'];
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {
    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "D.PER_INI";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="D.PER_INI";
$tname="SGC_TT_DIFERIDOS";
$where = " AND D.INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND D.INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new diferidos();
$registros=$l->todos_dif($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $diferido=oci_result($registros,'CODIGO');
    $descripcion=oci_result($registros,'DESC_SERVICIO');
    $fecha_apertura= oci_result($registros,'FECHA_APERTURA');
    $valordif=oci_result($registros,'VALOR_DIFERIDO');
    $activo=oci_result($registros,'ACTIVO');
    $cuotas_pag=oci_result($registros,'CUOTAS_PAGADAS');
    $valpag=oci_result($registros,'VALOR_PAGADO');
    $numcuo=oci_result($registros,'NUMERO_CUOTAS');
    $perini=oci_result($registros,'PER_INI');
	$tipodif=oci_result($registros,'CONCEPTO_DIF');


    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$diferido."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($diferido)."'";
    $json .= ",'".addslashes($descripcion)."'";
	$json .= ",'".addslashes($tipodif)."'";
    $json .= ",'".addslashes($fecha_apertura)."'";
    $json .= ",'".addslashes($valordif)."'";
    $json .= ",'".addslashes($activo)."'";
    $json .= ",'".addslashes($cuotas_pag)."'";
    $json .= ",'".addslashes($valpag)."'";
    $json .= ",'".addslashes($numcuo)."'";
    $json .= ",'".addslashes($perini)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>