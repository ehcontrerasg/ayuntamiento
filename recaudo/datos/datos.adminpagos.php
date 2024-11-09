<?php
include '../clases/class.admin_pagos.php';
sleep(2);
$data = $_POST['value'];
$field = $_POST['field'];

$temporal=explode(" ",$field);
$field=$temporal[0];
$id_pago=$temporal[1];
$tipo_pago=$temporal[2];

if($tipo_pago == 'P') {
    $tname = 'SGC_TT_PAGOS';
}
if($tipo_pago == 'O') {
    $tname = 'SGC_TT_OTROS_RECAUDOS';
}

$f=new AdminstraPagos();
$f->corrigePagos($id_pago, $tname, $field, $data);

echo $data;
?>