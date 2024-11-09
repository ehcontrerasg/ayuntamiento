<?php
include '../clases/class.adminconceptos.php';
sleep(1);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_SERVICIOS';
$f=new conceptos();
$temporal=explode(" ",$field);

$field=$temporal[0];
$id_concepto=$temporal[1];
$f->actualizaconceptos($id_concepto, $tname, $field, $data);
echo strtoupper($data);
?>