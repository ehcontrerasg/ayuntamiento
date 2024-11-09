<?php
include '../clases/class.tarifa.php';

$servicio = $_POST['id_servicio'];


$p=new Tarifa();
$p->setcodconcepto($servicio);
$p->setcodproyecto('SD');
$p->setcoduso('R');
$stid = $p->obtenertarifa();
while (oci_fetch($stid)) {
	$Starifa2= oci_result($stid, 'CONSEC_TARIFA') ;
	$Sdesctarifa2= oci_result($stid, 'DESC_TARIFA') ;
	$html .= '<option value="'.$Starifa2.'">'.$Sdesctarifa2.'</option>';
}oci_free_statement($stid);

echo $html;
?>
