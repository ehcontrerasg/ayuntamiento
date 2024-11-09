<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.diferidos.php';
$diferido=$_GET['diferido'];
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

if (!$sortname) $sortname = "DD.PERIODO";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="D.PER_INI";
$tname="sgc_tt_detalle_diferidos";
$where = " AND DD.COD_DIFERIDO='$diferido'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND DD.COD_DIFERIDO='$diferido' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new diferidos();
$registros=$l->cuotasdif($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $numcuota=oci_result($registros,'NUM_CUOTA');
    $valor= oci_result($registros,'VALOR');
    $valpagado=oci_result($registros,'VALOR_PAGADO');
    $periodo=oci_result($registros,'PERIODO');
    $fechpago=oci_result($registros,'FECHA_PAGO');



    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$diferido."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($numcuota)."'";
    $json .= ",'".addslashes($valor)."'";
    $json .= ",'".addslashes($valpagado)."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($fechpago)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>