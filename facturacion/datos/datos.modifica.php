<?php
session_start();
include '../clases/class.modifica.php';
$coduser = $_SESSION['codigo'];
sleep(1);
$data = $_POST['value'];
$field = $_POST['field'];

$temporal=explode(" ",$field);
$field=$temporal[0];
$cod_inm=$temporal[1];
$periodo=$temporal[2];

$tname = 'SGC_TT_REGISTRO_LECTURAS';
$where = 'COD_INMUEBLE = '.$cod_inm.' AND PERIODO = '.$periodo;

if($field == 'consumo'){
	$f=new modificacion();
	$f->actualizaConsumo($periodo, $cod_inm, $data,$coduser);
	echo strtoupper($data);
}
else{
	if($field == 'lectura_actual'){
		$f=new modificacion();
		$registros=$f->obtenerObserva($periodo,$cod_inm,$where,$tname);
		while (oci_fetch($registros)) {
			$observa = oci_result($registros, 'OBSERVACION');
		}	
		$l=new modificacion();
		$l->actualizaLectura($periodo, $cod_inm, $data, $observa, $coduser);
		echo strtoupper($data);
	}
	if($field == 'observacion_actual'){
		$f=new modificacion();
		$registros=$f->obtenerLectura($periodo,$cod_inm,$where,$tname);
		while (oci_fetch($registros)) {
			$lectura = oci_result($registros, 'LECTURA_ACTUAL');
		}	
		$l=new modificacion();
		$data = strtoupper($data);
		$l->actualizaLectura($periodo, $cod_inm, $lectura, $data, $coduser);
		echo strtoupper($data);
	}
	
}
?>