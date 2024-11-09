<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.facturas.php';
 $factura=$_GET['factura'];
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {
    $l=new facturas();
    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "PERIODO";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="FACTURA";
$tname="SGC_TT_FACTURA_rel";
$where = " AND FACTURA='$factura'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->Obtenerfacrel($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $version=oci_result($registros, 'VERSION_FACTURA');
    $periodo = oci_result($registros, 'PERIODO');
    $expedicion = oci_result($registros, 'FEC_EXPEDICION');
    $total = oci_result($registros, 'VALOR_TOTAL');
    $ncf = oci_result($registros, 'NCF');
    $obs = oci_result($registros, 'DESCRIPCION');
    $tipoajuste = oci_result($registros, 'TIPO_AJUSTE');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($version)."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($expedicion)."'";
    $json .= ",'".addslashes($ncf)."'";
    $json .= ",'".addslashes($obs)."'";
    $json .= ",'".addslashes($tipoajuste)."'";
    $json .= ",'".addslashes("$".$total)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>