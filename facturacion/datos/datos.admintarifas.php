<?php
include '../clases/class.admintarifas.php';
sleep(2);
$data = $_POST['value'];
$field = $_POST['field'];
$tname = 'SGC_TP_TARIFAS';
$tnamea = 'SGC_TP_RANGOS_TARIFAS';

$temporal=explode(" ",$field);
$field=$temporal[0];
$id_tarifa=$temporal[1];

$f=new tarifas();
if($field == 'desc_tarifa' || $field == 'cod_servicio' || $field == 'cod_uso' || $field == 'codigo_tarifa' || $field == 'consumo_min'){
	$f->actualizaTarifas($id_tarifa, $tname, $field, $data);
}
if(substr($field,0,6) == 'limite' || substr($field,0,5) == 'valor'){
	if (substr($field,0,6) == 'limite'){
		$rango = substr($field,11,1);
		$field = substr($field,0, strlen($field)-2);
		$f->actualizaRangosTarifas($id_tarifa, $tnamea, $field, $data, $rango);
	}
	else{ 
		$rango = substr($field,6,1);
		$field = 'valor_metro';
		$f->actualizaRangosTarifas($id_tarifa, $tnamea, $field, $data, $rango);
	}
	
}	
echo strtoupper($data);
?>