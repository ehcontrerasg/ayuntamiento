<?php
require_once("../../dompdf/dompdf_config.inc.php");
include '../clases/class.admindiferidos.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "CODIGO";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="CODIGO";
$tname="SGC_TP_CONCEPTO_DIF";
$where= "ACTIVO = 'S'";

$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t�tulo</title>
</head>
<body>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
            <th align="center" style="color:#FFFFFF; background-color:#336699">N&deg;</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digoo</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripci&oacute;n</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Concepto</th>
        </tr>
	</thead>
	<tbody>
		';
  
	$cont = 0;
		$l=new diferidos();
		$registros=$l->obtenerdiferidos($where,$sort,$start,$end, $tname);
		while (oci_fetch($registros)) {
			$cont++;
			$id_diferido = oci_result($registros, 'CODIGO');
			$desc_diferido = oci_result($registros, 'DESCRIPCION');
			$cod_concepto = oci_result($registros, 'COD_CONCEPTO');
			$codigoHTML.='
			<tr>
			<td align="center"><b>'.utf8_decode($cont).'</b></td>
			<td align="center">'.utf8_decode($id_diferido).'</td>
			<td align="center">'.utf8_decode($desc_diferido).'</td>
			<td align="center">'.utf8_decode($cod_concepto).'</td>		
			</tr>';
			
		}
		$codigoHTML.='
	</tbody>
</table>
</body>
</html>';
$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->set_paper('A4', 'landscape');
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","128M");
$dompdf->render();
$dompdf->stream("Reporte_Diferidos.pdf");
?>