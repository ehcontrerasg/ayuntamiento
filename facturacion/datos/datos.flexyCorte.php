<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.cortes.php';
 $inmueble=$_GET['inmueble'];
 //$inmueble=203050;
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

if (!$sortname) $sortname = "FECHA_EJE";
if (!$sortorder) $sortorder = "desc";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$where = " AND  RC.ID_INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = "  AND  RC.ID_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Corte();
$registros=$l->corTotal($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $codigo=oci_result($registros, 'ORDEN');
    $fechpla = oci_result($registros, 'FECHA_PLANIFICACION');
    $fecRea = oci_result($registros, 'FECHA_EJE');
    $descripcion = oci_result($registros, 'DESCRIPCION');
    $tipo = oci_result($registros, 'TIPO_CORTE');
    $usr = oci_result($registros, 'LOGIN');
    $obs = oci_result($registros, 'OBS');
    $impoCort = oci_result($registros, 'IMPO_CORTE');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$codigo."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($codigo)."'";
    $json .= ",'".addslashes($fechpla)."'";
    $json .= ",'".addslashes($fecRea)."'";
    $json .= ",'".addslashes($descripcion)."'";
    $json .= ",'".addslashes($tipo)."'";
    $json .= ",'".addslashes($usr)."'";
    $json .= ",'".addslashes($obs)."'";
    $json .= ",'".addslashes($impoCort)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>