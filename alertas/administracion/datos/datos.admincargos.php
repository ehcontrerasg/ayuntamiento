<?php
include '../clases/class.personal.php';
sleep(1);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_CARGOS';
$f=new personal();
$temporal=explode(" ",$field);

$field=$temporal[0];
$id_area=$temporal[1];
$f->actualizacargo($id_area, $tname, $field, $data);
echo strtoupper($data);
?>