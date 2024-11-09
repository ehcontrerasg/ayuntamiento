<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */

//error_reporting(0);
include '../clases/class.facturas.php';
 $factura=$_GET['factura'];
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

if (!$sortname) $sortname = "LOGIN";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="FECHA_LECTURA";
$tname="SGC_TT_FACTURA";
$where = " AND F.CONSEC_FACTURA='$factura'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND F.CONSEC_FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->detallelectura($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {

    $lectura=oci_result($registros, 'LECTURA_ORIGINAL');
    $observacion = oci_result($registros, 'OBSERVACION');
    $login =oci_result($registros, 'LOGIN');
    $consumo =oci_result($registros, 'CONSUMO');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['$lectura'";

    $json .= ",'".addslashes($observacion)."'";
    $json .= ",'".addslashes($login)."'";
    $json .= ",'".addslashes($consumo)."'";
    $json .= "]},";
    $rc = true;
}


if ($rc) $json .= "";
$json .= "\n{";
$json .= "id:'".'TotFacturas'."',";
$json .= "cell:['<font class=\'titulo1\' style=\'color:#000000\'>".'Entrega'."</font>'";
$json .= ",'<font class=\'titulo1\' style=\'color:#000000\'>".addslashes('Observaci&oacute;n')."</font>'";
$json .= ",'<font class=\'titulo1\' style=\'color:#000000\'>".addslashes("Operario")."</font>'";
$json .= ",'<font class=\'titulo1\' style=\'color:#000000\'>".addslashes('Fecha')."</font>'";

$json .= "]},";
$rc = true;



$fname ="FECHA_EJECUCION";
$tname="SGC_TT_FACTURA";
$where = " AND F.CONSEC_FACTURA='$factura'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND F.CONSEC_FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
}


$l=new facturas();
$registros=$l->detalleentfactura($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);

$rc = false;
while (oci_fetch($registros)) {

    $entregado = oci_result($registros, 'ENTREGADO');
    $login = oci_result($registros, 'LOGIN');
    $observacion = oci_result($registros, 'OBS_NOENTREGA');

    $fechaeje = oci_result($registros, 'FECHA_EJECUCION');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'" . $factura . "',";
    $json .= "cell:['$entregado'";
    $json .= ",'" . addslashes($observacion) . "'";
    $json .= ",'" . addslashes($login) . "'";
    $json .= ",'" . addslashes($fechaeje) . "'";
    $json .= "]}";
    $rc = true;

}/**/
$json .= "]\n";
$json .= "}";
echo $json;
?>