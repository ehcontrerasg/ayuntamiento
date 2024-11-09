<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.reconexiones.php';
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

$where = " AND RR.ID_INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = "  AND RR.ID_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Reconexion();
$registros=$l->recTotal($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $fechapla=oci_result($registros, 'FECHA_PLANIFICACION');
    $fecharea = oci_result($registros, 'FECHA_EJE');
    $tipo = oci_result($registros, 'TIPO_RECONEXION');
    $obs = oci_result($registros, 'OBS_GENERALES');
    $fechaac = oci_result($registros, 'FECHA_ACUERDO');
    $usueje = oci_result($registros, 'LOGIN');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$numero."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($fechapla)."'";
    $json .= ",'".addslashes($fecharea)."'";
    $json .= ",'".addslashes($tipo)."'";
    $json .= ",'".addslashes($obs)."'";
    $json .= ",'".addslashes($fechaac)."'";
    $json .= ",'".addslashes($usueje)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>