<?php
include '../clases/class.admindiferidos.php';
sleep(2);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_CONCEPTO_DIF';
$f=new diferidos();
$temporal=explode(" ",$field);

$field=$temporal[0];
$id_diferido=$temporal[1];
$f->actualizadiferidos($id_diferido, $tname, $field, $data);
echo strtoupper($data);
?>