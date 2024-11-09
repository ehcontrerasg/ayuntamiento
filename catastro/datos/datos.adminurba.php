<?php
include '../clases/class.administracion.php';
sleep(2);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_URBANIZACIONES';
$f=new Administracion();
$temporal=explode(" ",$field);

$field=$temporal[0];
$id_urba=$temporal[1];
$f->actualizaurba($id_urba, $tname, $field, $data);
echo strtoupper($data);
?>