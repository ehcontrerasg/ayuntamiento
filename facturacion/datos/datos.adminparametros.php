<?php
include '../clases/class.adminparametros.php';
sleep(1);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_PARAMETROS';
$f=new Parametros();
$temporal=explode(" ",$field);

$field=$temporal[0];
$cod_param=$temporal[1];
$f->actualizaParametros($cod_param, $tname, $field, $data);
echo strtoupper($data);
?>