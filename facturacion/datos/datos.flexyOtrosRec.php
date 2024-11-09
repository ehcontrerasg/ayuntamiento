<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.otrosRecaudos.php';
 $inmueble=$_GET['inmueble'];
 //$inmueble=89984;
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

if (!$sortname) $sortname = "ORE.FECHA";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$where = " AND ORE.INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = "  AND ORE.INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new OtrosRec();
$registros=$l->OtrosRecTotal($where, $sort, $start, $end);
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
    $fechaDig = oci_result($registros, 'FECHADIG');
    $fechaPag = oci_result($registros, 'FECHAPAG');
    $concepto = oci_result($registros, 'DESC_SERVICIO');
    $importe = oci_result($registros, 'IMPORTE');
    $usring= oci_result($registros, 'LOGIN');
    $fechaAnul = oci_result($registros, 'FECHA_REV');
    $usrAnul = oci_result($registros, 'USRREV');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$codigo."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($codigo)."'";
    $json .= ",'".addslashes($fechaDig)."'";
    $json .= ",'".addslashes($fechaPag)."'";
    $json .= ",'".addslashes($concepto)."'";
    $json .= ",'RD$ ".addslashes($importe)."'";
    $json .= ",'".addslashes($usring)."'";
    $json .= ",'".addslashes($fechaAnul)."'";
    $json .= ",'".addslashes($usrAnul)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>