<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.medidores.php';
 $inmueble=$_GET['inmueble'];
 //$inmueble=1030754;

function countRec() {
    return 100;
}
if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH


$l=new Medidor();
$registros=$l->TotMedTot($inmueble);
$total =countRec();
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'ROWNUM');
    $codigopadre=oci_result($registros, 'COD_INM_PADRE');
    $codigohijo = oci_result($registros, 'COD_INM_HIJO');



    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$numero."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($codigopadre)."'";
    $json .= ",'".addslashes($codigohijo)."'";


    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>