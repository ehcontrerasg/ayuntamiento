<?php
require_once("../../dompdf/dompdf_config.inc.php");
include '../clases/class.adminparametros.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "COD_PARAMETRO";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="COD_PARAMETRO";
$tname="SGC_TP_PARAMETROS";

$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
            <th align="center" style="color:#FFFFFF; background-color:#336699">N&deg;</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">P&aacute;rametro</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Valor</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripci&oacute;n</th>
        </tr>
	</thead>
	<tbody>
		';
  
		$l=new Parametros();
		$registros=$l->obtenerParametros($sort,$tname);
		while (oci_fetch($registros)) {
			$cod_param = oci_result($registros, 'COD_PARAMETRO');
			$nom_param = oci_result($registros, 'NOM_PARAMETRO');
			$val_param = oci_result($registros, 'VAL_PARAMETRO');
			$des_param = oci_result($registros, 'DES_PARAMETRO');
			$codigoHTML.='
			<tr>
			<td align="center"><b>'.utf8_decode($cod_param).'</b></td>
			<td align="center">'.utf8_decode($nom_param).'</td>
			<td align="center">'.utf8_decode($val_param).'</td>
			<td align="center">'.utf8_decode($des_param).'</td>		
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
$dompdf->stream("Reporte_Parametros.pdf");
?>