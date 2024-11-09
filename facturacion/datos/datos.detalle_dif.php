<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.diferidos.php';
$coddif=$_GET['coddif'];
//$factura=75169582;
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

if (!$sortname) $sortname = "PERIODO";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="numero_cuotas";
$tname="sgc_tt_DETALLE_DIFERIDOS";
$where = " AND COD_DIFERIDO='$coddif'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND COD_DIFERIDO='$coddif' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new diferidos();
$registros=$l->detalledif($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $periodo = oci_result($registros, 'PERIODO');
    $numcuota = oci_result($registros, 'NUM_CUOTA');
    $valorcuota = oci_result($registros, 'VALOR');
    $valorpag = oci_result($registros, 'VALOR_PAGADO');
    $fechapago = oci_result($registros, 'FECHA_PAGO');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$numero."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'<b>".addslashes("$".$periodo)."</b>'";
    $json .= ",'".addslashes($numcuota)."'";
    $json .= ",'".addslashes($valorcuota)."'";
    $json .= ",'<b>".addslashes("$".$valorpag)."</b>'";
    $json .= ",'<b>".addslashes("$".$fechapago)."</b>'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>