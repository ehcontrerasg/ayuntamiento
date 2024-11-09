<?php
require_once("../../dompdf/dompdf_config.inc.php");
include '../clases/class.adminconceptos.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "COD_SERVICIO";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="COD_SERVICIO";
$tname="SGC_TP_SERVICIOS";
$where= "CALCULO IS NOT NULL";

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
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripci�n</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Orden</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Tipo</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Mora</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Cuotas</th>
        </tr>
	</thead>
	<tbody>
		';
  $cont = 0;
		$l=new conceptos();
		$registros=$l->obtenerconceptos($where,$sort,$start,$end, $tname);
		while (oci_fetch($registros)) {
			$cont++;
			$id_concepto = oci_result($registros, 'COD_SERVICIO');
			$desc_concepto = oci_result($registros, 'DESC_SERVICIO');
			$orden = oci_result($registros, 'ORDEN');
			$tipo_concepto = oci_result($registros, 'DIFERIDO');
			$mora = oci_result($registros, 'MORA');
			$num_cuotas = oci_result($registros, 'LIMITE_MAX_CUOTAS');
			$codigoHTML.='
			<tr>
			<td align="center"><b>'.utf8_decode($cont).'</b></td>
			<td align="center">'.utf8_decode($id_concepto).'</td>
			<td align="center">'.utf8_decode($desc_concepto).'</td>
			<td align="center">'.utf8_decode($orden).'</td>	
			<td align="center">'.utf8_decode($tipo_concepto).'</td>
			<td align="center">'.utf8_decode($mora).'</td>
			<td align="center">'.utf8_decode($num_cuotas).'</td>		
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
$dompdf->stream("Reporte_Conceptos.pdf");
?>