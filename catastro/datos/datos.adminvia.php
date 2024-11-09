<?php
include '../clases/class.administracion.php';
sleep(2);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_NOMBRE_VIA';
$f=new Administracion();
$temporal=explode(" ",$field);

$field=$temporal[0];
$id_via=$temporal[1];
$f->actualizavia($id_via, $tname, $field, $data);
echo strtoupper($data);
?>