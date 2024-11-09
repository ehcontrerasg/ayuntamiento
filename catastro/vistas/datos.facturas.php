<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.facturas.php';
 $inmueble=$_GET['inmueble'];
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
$fname ="PERIODO";
$tname="SGC_TT_FACTURA";
$where = " AND INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND COD_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->factotal($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $factura=oci_result($registros, 'CONSEC_FACTURA');
    $periodo = oci_result($registros, 'PERIODO');
    $expedicion = oci_result($registros, 'FEC_EXPEDICION');
    $total = oci_result($registros, 'TOTAL');
    $ncf = oci_result($registros, 'NCF');
    $totalpagado = oci_result($registros, 'TOTAL_PAGADO');
    $feclectura= oci_result($registros, 'FECHA_LECTURA');
    $fecpago= oci_result($registros, 'FECHA_PAGO');
    $diaspago= oci_result($registros, 'DIAS');
    $lectura= oci_result($registros, 'LECTURA');



    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($factura)."'";
    $json .= ",'".addslashes($periodo)."'";
    $json .= ",'".addslashes($feclectura)."'";
    $json .= ",'".addslashes($lectura)."'";
    $json .= ",'".addslashes($expedicion)."'";
    $json .= ",'".addslashes($ncf)."'";
    $json .= ",'".addslashes("$".$total)."'";
    $json .= ",'".addslashes("$".$totalpagado)."'";
    $json .= ",'".addslashes($fecpago)."'";
    $json .= ",'".addslashes($diaspago)."'";
    if($l->verificarel($factura)){
        $json .= ",'"."<b><a href=\"JAVASCRIPT:rel(".$factura.");\">" ."<img src=\"../../images/reliquidacion.png\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
    }
    else{
        $json .= ",'"."NO REL"."'";
    }

    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>