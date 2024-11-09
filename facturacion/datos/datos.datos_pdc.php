<?php
include '../clases/class.deuda_cero.php';
$codinmueble=$_GET['codinmueble'];
$page = $_POST['page'];
$rp = $_POST['rp'];

if (!$page) $page = 1;

$i=new deuda_cero();
$mora=$i->obtiene_mora($codinmueble);
$servicios=$i->obtiene_servicios($codinmueble);
$total=$mora+$servicios;
$cuotas=24;
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;

if ($rc) $json .= ",";
$json .= "\n{";
$json .= "id:'".'1'."',";
$json .= "cell:['" .'1'."'";
$json .= ",'RD$ ".addslashes($mora)."'";
$json .= ",'RD$ ".addslashes($servicios)."'";
$json .= ",'RD$ ".addslashes($total)."'";
$json .= ",'".addslashes($cuotas)."'";
$json .= "]}";
$rc = true;
$json .= "]\n";
$json .= "}";
echo $json;
?>