<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */
include '../clases/class.deuda_cero.php';
$codinmueble=$_GET['codinmueble'];
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

if (!$sortname) $sortname = "DC.PERIODO_INI";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="DC.PERIODO_INI";
$tname="SGC_TT_DEUDA_CERO";
$where = " AND DC.COD_INMUEBLE='$codinmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND DC.COD_INMUEBLE='$codinmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new deuda_cero();
$registros=$l->Obtenerdeudaceroinmueble($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;



while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $id_deudacero=oci_result($registros,'ID_DEUDA_CERO');
    $perini=oci_result($registros,'PERIODO_INI');
    $totcuotas= oci_result($registros,'TOTAL_CUOTAS');
    $fecultpago=oci_result($registros,'FECH_ULTPAGO');
    $activo=oci_result($registros,'ACTIVA');
    $cuotas_pag=oci_result($registros,'TOTAL_CUOTAS_PAG');
    $totdif=oci_result($registros,'TOTAL_DIFERIDO');
    $totmora=oci_result($registros,'TOTAL_MORA');
    $nomcli=oci_result($registros,'NOMBRE_CLI');
    $perrev=oci_result($registros,'PERREV');


    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_deudacero."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($id_deudacero)."'";
    $json .= ",'".addslashes($perini)."'";
    $json .= ",'".addslashes($totcuotas)."'";
    $json .= ",'".addslashes($fecultpago)."'";
    $json .= ",'".addslashes($activo)."'";
    $json .= ",'".addslashes($cuotas_pag)."'";
    $json .= ",'".addslashes($totdif)."'";
    $json .= ",'".addslashes($totmora)."'";
    $json .= ",'".addslashes($nomcli)."'";
    $json .= ",'".addslashes($perrev)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>