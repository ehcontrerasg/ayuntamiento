<?php
include '../clases/class.motivo.php';
$tipo=$_POST['id_tipo'];
$valor=$_POST['valor'];
$ger_inm=$_GET['ger_inm'];
//$motivo=$_POST['motivo'];

if($valor=='motivo')
{
	if ($tipo == 7) { $tipo = 3; }

	$p=new Motivo();
	$stid = $p->obtenermotivo($tipo,$ger_inm);
	$html .= '<option value="">Seleccione Motivo PQR...</option>';
	while (oci_fetch($stid)) {
		$cod_motivo= oci_result($stid, 'ID_MOTIVO_REC');
		$des_motivo = oci_result($stid, 'DESC_MOTIVO_REC');
		$html .= '<option value="'.$cod_motivo.'">'.$cod_motivo." - ".$des_motivo.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

