<?php
include '../clases/class.tarifa.php';

$servicio = $_POST['id_servicio'];
$uso = $_POST['uso'];
$tipo= $_POST['tipo'];
$tarifa=$_POST['id_tarifa'];


if($tipo=='tarifa'){
	$p=new Tarifa();
	$p->setcodconcepto($servicio);
	$p->setcodproyecto('SD');
	$p->setcoduso($uso);
	$stid = $p->obtenertarifa();
	$html .= '<option value=" "></option>';
	while (oci_fetch($stid)) {
		$Starifa2= oci_result($stid, 'CONSEC_TARIFA');
		$Sdesctarifa2= oci_result($stid, 'DESC_TARIFA');
		$html .= '<option value="'.$Starifa2.'">'.$Sdesctarifa2.'</option>';
	}oci_free_statement($stid);

	echo $html;
}

if($tipo=='cupbas'){
	$p=new Tarifa();
	$p->settarifa($tarifa);
	$stid = $p->obtenercupobasico2();
 	while (oci_fetch($stid)) {
 		$cupobas= oci_result($stid, 'CONSUMO_MIN');
 		$html =$cupobas;
 	}oci_free_statement($stid);

	echo $html;
}

?>
