<?php
date_default_timezone_set('America/Santo_Domingo');
require_once("../../dompdf/dompdf_config.inc.php");

include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_plan_fac.php';

$proyecto = $_GET['proyecto'];
$perini = $_GET['perini'];
$secini = $_GET['secini'];
$secfin = $_GET['secfin'];
$fecini = $_GET['fecini'];

if($secini != '' && $secfin == '') $secfin = $secini;
if($secini == '' && $secfin != '') $secini = $secfin;
if($secini != '' && $secfin != '') $nomrepo = '_Sector_'.$secini.'_a_'.$secfin;

if($proyecto == 'SD'){ 
	$logo = 'LogoCaasd.jpg';
	$sigla = 'CAASD';
}
else {
	$logo = 'coraabo.jpg';
	$sigla = 'CORAABO';
}

$dia_actual = strtoupper(strftime('%d-%b-%Y %H:%M:%S'));
$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte Planificaci&oacute;n Entrega de Facturas</title>
</head>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="25%" align="center">
				<img src="../../images/'.$logo.'" width="70" height="90"/><br>
				<font size="12px">'.$sigla.', Gerencia Comercial</font>
			</td>
			<td width="10%" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			<td width="65%">
				<font size="26px" style="font-family:'."Times New Roman".', Times, serif">&nbsp;&nbsp;&nbsp;&nbsp;PLANIFICACI&Oacute;N ENTREGA DE FACTURAS</font>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">
			<font size="12px">'.$dia_actual.'</font>
			</td>
		</tr>
	</table>
	
	<table width="100%" border="1" cellspacing="1" cellpadding="1">
	<thead>
		<tr>
            <th align="center" style="color:#FFFFFF; background-color:#336699">
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">N&deg;</font>
			</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">Zona</font>
			</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">Distribuidor</font>
			</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">Rutas Planificadas</font>
			</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">Cantidad</font>
			</th>
        </tr>
	</thead>
	<tbody>	';
		$c=new Reportes_Plan_Fac();
			$registros=$c-> ObtienePlanificacionRuta($perini,$proyecto,$secini,$secfin,$fecini);
			$item = 0;
			$totallec = 0;
			while (oci_fetch($registros)) {
				$canrutas = '';
				$cod_lector = oci_result($registros, 'USR_EJE');
				$lector = oci_result($registros, 'USUARIO');
				$zona = oci_result($registros, 'ID_ZONA');
				$cantidad = oci_result($registros, 'CANTIDAD');
				$asignador = oci_result($registros, 'USR_ASIGNA');
				$fec_asig = oci_result($registros, 'FECHA_PLANIFICACION');
				$h=new Reportes_Plan_Fac();
				$registrosC=$h->ObtieneDetalleRutas($cod_lector, $zona, $perini,$fec_asig);
				while (oci_fetch($registrosC)) {
					$ruta = oci_result($registrosC, 'RUTA');
					$canrutas .= $ruta.', ';
				}oci_free_statement($registrosC);
				$canrutas = substr($canrutas,0,strlen($canrutas)-2);
				$item++;
				$codigoHTML.='
				<tr>
					<td align="center"><b><font size="12px" style="font-family:'."Times New Roman".', Times, serif">'.$item.'</font></b></td>
					<td align="center"><font size="12px" style="font-family:'."Times New Roman".', Times, serif">'.$zona.'</font></td>
					<td align="left"><font size="12px" style="font-family:'."Times New Roman".', Times, serif">'.utf8_decode($lector).'</font></td>
					<td align="left"><font size="12px" style="font-family:'."Times New Roman".', Times, serif">'.$canrutas.'</font></td>
					<td align="right"><font size="12px" style="font-family:'."Times New Roman".', Times, serif">'.$cantidad.'</font></td>
				</tr>';
				
				$totallec += $cantidad;
			}oci_free_statement($registros);
			
				$codigoHTML.='
				<tr>
			<td align="left" style="background-color:#990000; color:#FFFFFF;" colspan="3">
				<b><font size="13px" style="font-family:'."Times New Roman".', Times, serif">Total Distribuidores: '.$item.'</font></b>
			</td>
			<td align="left" style="background-color:#990000; color:#FFFFFF;" >
				<b><font size="13px" style="font-family:'."Times New Roman".', Times, serif">Total Facturas</font></b>
			</td>
			<td align="right" style="background-color:#990000; color:#FFFFFF;">
				<b><font size="13px" style="font-family:'."Times New Roman".', Times, serif">'.$totallec.'</font></b>
			</td>
		</tr>
		</tbody>
		</table>';
		$h=new Reportes_Plan_Fac();
		$registrosC=$h->ObtieneAsignador($asignador);
		while (oci_fetch($registrosC)) {
			$nomasignador = oci_result($registrosC, 'NOM_ASIGNADOR');
		}oci_free_statement($registrosC);
		$codigoHTML.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="50%" align="left"><b>
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">Responsable Asignaci&oacute;n: '.utf8_decode($nomasignador).'</font>
			</b></td>
			<td width="50%" align="right"><b>
				<font size="15px" style="font-family:'."Times New Roman".', Times, serif">Fecha Asignaci&oacute;n: '.$fec_asig.'</font>
			</b></td>
		</tr>
	</table>
	</body>
</html>';
			
			 ob_end_clean();//rompimiento de pagina
		
		

ini_set("memory_limit","2048M");
$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->set_paper('letter', 'landscape');
$dompdf->load_html($codigoHTML);
$dompdf->render();
$dompdf->stream("Reporte_Planificacion_Entrega_Facturas.pdf");
?>