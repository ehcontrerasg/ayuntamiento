<?php
require_once("../../dompdf/dompdf_config.inc.php");

include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_fac_ruta.php';

$proyecto = $_GET['proyecto'];
$secini = $_GET['secini'];
$perini = $_GET['perini'];



$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t?tulo</title>
</head>
<body>
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
	<thead>
	   <tr>
        <th colspan="3" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Sector '.$secini.' Periodo '.$perini.'</th>
        </tr>
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ruta</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad Medidores</th>
		</tr>
	</thead>
	<tbody>	';
$item = 0;
$totalfac = 0;
$c=new Reportes_Fac_Ruta();
$registros=$c->obtieneRutasCantidadMed($proyecto,$secini,$perini);
while (oci_fetch($registros)) {
    $item ++;
    $ruta = oci_result($registros, 'ID_RUTA');
    $cantidad_fac = oci_result($registros, 'CANTIDAD');
    $codigoHTML.='
				<tr>
				<td align="center" style="border-color:#DEDEDE"><b>'.$item.'</b></td>
				<td align="left" style="border-color:#DEDEDE">'.$ruta.'</td>
				<td align="left" style="border-color:#DEDEDE">'.$cantidad_fac.'</td>
			</tr>';

    $totalfac += $cantidad_fac;
}oci_free_statement($registros);

$codigoHTML.='
				<tr>
			<td align="left" style="border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;"><b>Totales</b></td>
			<td align="left" style="border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;"><b>'.$item.'</b></td>
			<td align="left" style="border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;"><b>'.$totalfac.'</b></td>
		</tr>
		</tbody>
		</table>
	</body>
</html>';

ob_end_clean();//rompimiento de pagina



ini_set("memory_limit","2048M");
$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->set_paper('letter', 'portrait');
$dompdf->load_html($codigoHTML);
$dompdf->render();
$dompdf->stream("Medidores_Por_Ruta_Periodo_".$perini."_Sector_".$secini.".pdf");
?>