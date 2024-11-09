<?php
require_once("../../dompdf/dompdf_config.inc.php");
include '../clases/class.admintarifas.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "CONSEC_TARIFA";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="CONSEC_TARIFA";
$tname="SGC_TP_TARIFAS";
$where= "VISIBLE IS NOT NULL";

$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin titulo</title>
</head>
<body>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
            <th align="center" style="color:#FFFFFF; background-color:#336699">N&deg;</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">AC</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripci&oacute;n</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo<br/>Servicio</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo<br/>Uso</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo<br/>Tarifa</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Consumo<br/>M&iacute;nimo</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&iacute;nimo<br/>Rango 1</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&aacute;ximo<br/>Rango 1</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Valor Metro<br/>Rango 1</th>
		   	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&iacute;nimo<br/>Rango 2</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&aacute;ximo<br/>Rango 2</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Valor Metro<br/>Rango 2</th>
		   	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&iacute;nimo<br/>Rango 3</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&aacute;ximo<br/>Rango 3</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Valor Metro<br/>Rango 3</th>
		   	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&iacute;nimo<br/>Rango 4</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&aacute;ximo<br/>Rango 4</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Valor Metro<br/>Rango 4</th>
		   	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&iacute;nimo<br/>Rango 5</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Limite M&aacute;ximo<br/>Rango 5</th>
	      	<th align="center" style="color:#FFFFFF; background-color:#336699">Valor Metro<br/>Rango 5</th>
        </tr>
	</thead>
	<tbody>
		';
  
		$l=new tarifas();
		$registros=$l->obtenertarifas($where,$sort,$start,$end, $tname);
		while (oci_fetch($registros)) {
			$id_tarifa = oci_result($registros, 'CONSEC_TARIFA');
			$acueducto = oci_result($registros, 'COD_PROYECTO');
			$desc_tarifa = oci_result($registros, 'DESC_TARIFA');
			$cod_servicio = oci_result($registros, 'COD_SERVICIO');
			$uso = oci_result($registros, 'COD_USO');
			$cod_tarifa = oci_result($registros, 'CODIGO_TARIFA');
			$consumo = oci_result($registros, 'CONSUMO_MIN');
			$lim_min_1 = oci_result($registros, 'LMIN_1');
			$lim_max_1 = oci_result($registros, 'LMAX_1');
			$valor_1 = oci_result($registros, 'VALOR_1');
			$lim_min_2 = oci_result($registros, 'LMIN_2');
			$lim_max_2 = oci_result($registros, 'LMAX_2');
			$valor_2 = oci_result($registros, 'VALOR_2');
			$lim_min_3 = oci_result($registros, 'LMIN_3');
			$lim_max_3 = oci_result($registros, 'LMAX_3');
			$valor_3 = oci_result($registros, 'VALOR_3');
			$lim_min_4 = oci_result($registros, 'LMIN_4');
			$lim_max_4 = oci_result($registros, 'LMAX_4');
			$valor_4 = oci_result($registros, 'VALOR_4');
			$lim_min_5 = oci_result($registros, 'LMIN_5');
			$lim_max_5 = oci_result($registros, 'LMAX_5');
			$valor_5 = oci_result($registros, 'VALOR_5');
			$codigoHTML.='
			<tr>
			<td align="center"><b>'.utf8_decode($id_tarifa).'</b></td>
			<td align="center">'.utf8_decode($acueducto).'</td>
			<td align="center">'.utf8_decode($desc_tarifa).'</td>
			<td align="center">'.utf8_decode($cod_servicio).'</td>
			<td align="center">'.utf8_decode($uso).'</td>
			<td align="center">'.utf8_decode($cod_tarifa).'</td>
			<td align="center">'.utf8_decode($consumo).'</td>
			<td align="center">'.utf8_decode($lim_min_1).'</td>	
			<td align="center">'.utf8_decode($lim_max_1).'</td>
			<td align="center">'.utf8_decode($valor_1).'</td>
			<td align="center">'.utf8_decode($lim_min_2).'</td>
			<td align="center">'.utf8_decode($lim_max_2).'</td>	
			<td align="center">'.utf8_decode($valor_2).'</td>
			<td align="center">'.utf8_decode($lim_min_3).'</td>
			<td align="center">'.utf8_decode($lim_max_3).'</td>
			<td align="center">'.utf8_decode($valor_3).'</td>
			<td align="center">'.utf8_decode($lim_min_4).'</td>
			<td align="center">'.utf8_decode($lim_max_4).'</td>
			<td align="center">'.utf8_decode($valor_4).'</td>
			<td align="center">'.utf8_decode($lim_min_5).'</td>	
			<td align="center">'.utf8_decode($lim_max_5).'</td>
			<td align="center">'.utf8_decode($valor_5).'</td>
			</tr>';
			
		}
		$codigoHTML.='
	</tbody>
</table>
</body>
</html>';
$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->set_paper('B2', 'landscape');
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","10096M");
$dompdf->render();
$dompdf->stream("Reporte_Tarifas.pdf");
?>