<?php
require_once("../../dompdf/dompdf_config.inc.php");
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_hisfac.php';

$proyecto = $_GET['proyecto'];
$zonini = $_GET['zonini'];
$zonfin = $_GET['zonfin'];
$perini = $_GET['perini'];
$canper = $_GET['canper'];

if($zonini != '' && $zonfin == '') $zonfin = $zonini;
if($zonini == '' && $zonfin != '') $zonini = $zonfin;
if($zonini != '' && $zonfin != '') $nomrepo = '_Zona_'.$zonini.'_a_'.$zonfin;

if($canper == '') $canper = 3;
else $canper = $canper;

$codigoHTML='
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
			<td colspan="14" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b>HIST&Oacute;RICO DE FACTURAS</b></td>
		</tr>';
		$c=new ReportesHistoricoFac();
		$registrosC=$c->CantidadZonas($perini,$zonini,$zonfin,$canper,$proyecto);
		while (oci_fetch($registrosC)) {
			$zona = oci_result($registrosC, 'ID_ZONA');
			$codigoHTML.='
			<tr>
				<td colspan="14" style="border:1px solid #DEDEDE; background-color:#0080C0; color:#FFFFFF"><b>ZONA&nbsp;&nbsp;'.$zona.'</b></td>
			</tr>';
			$c=new ReportesHistoricoFac();
			$registrosF=$c->InmueblesZona($perini,$zona);
			while (oci_fetch($registrosF)) {
				$inmueble = oci_result($registrosF, 'CODIGO_INM');
				$c=new ReportesHistoricoFac();
				$registrosG=$c->DatosInmueble($inmueble);
				while (oci_fetch($registrosG)) {
					$cliente = oci_result($registrosG, 'ALIAS');
					$urbaniza = oci_result($registrosG, 'DESC_URBANIZACION');
					$direccion = oci_result($registrosG, 'DIRECCION');
					$estado = oci_result($registrosG, 'ID_ESTADO');
					$uso = oci_result($registrosG, 'ID_USO');
					$proceso = oci_result($registrosG, 'ID_PROCESO');
					$catastro = oci_result($registrosG, 'CATASTRO');
					$unidades = oci_result($registrosG, 'UNIDADES_TOT');
					$consumomin = oci_result($registrosG, 'CONSUMO_MINIMO');
					$tarifa = oci_result($registrosG, 'CODIGO_TARIFA');
					$medidor = oci_result($registrosG, 'COD_MEDIDOR');
					$serial = oci_result($registrosG, 'SERIAL');
					$emplaza = oci_result($registrosG, 'COD_EMPLAZAMIENTO');
				}oci_free_statement($registrosG);
				$codigoHTML.='
				<tr>
					<td colspan="2" rowspan="2" style="border:1px solid #DEDEDE; background-color:#83B9FC; color:#FFFFFF; font-size:11px">
						<b>Inmueble&nbsp;&nbsp;'.$inmueble.'</b>
					</td>
					<td colspan="5" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; font-size:11px">
						<b>Cliente:&nbsp;</b>'. substr($cliente,0,50).'
					</td>
					<td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; border-left:none; font-size:11px" colspan="4">
						<b>Proceso:&nbsp;</b>'. $proceso.'
					</td>
					<td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; border-left:none; font-size:11px" colspan="3">
						<b>Catastro:&nbsp;</b>'. $catastro.'
					</td>
				</tr>
				<tr>
					<td colspan="5" style="border:1px solid #DEDEDE;background-color:#FFFFFF;color:#000000; font-size:11px"><b>Direcci&oacute;n:&nbsp;</b>'.
					substr($direccion.' '.$urbaniza,0,50).'</td>
					<td style="border:1px solid #DEDEDE;background-color:#FFFFFF;color:#000000;vertical-align:top;text-align:center;font-size:9px">
					<b>Estado</b><br />'. $estado.'</td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Unidad</b><br />'. $unidades.'</td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Uso</b><br />'. $uso.'</td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Tarifa</b><br />'. $tarifa.'</td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Cons.Min</b><br />'. $consumomin.'</td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Medidor</b><br />'. $medidor.'</td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Serial</b><br />'. $serial.'</td>
				</tr>
				<tr>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Periodo</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Fecha</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Lectura</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Lote</b></td>
					<td colspan="3" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Consumo</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>M&eacute;todo</b></td>
					<td rowspan="2" colspan="3" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Lector</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Observaci&oacute;n</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Base</b></td>
					<td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Total</b></td>
				</tr>
				<tr>
					<td style="background-color:#F3F3F3; color:#000000; font-size:9px">Dir</td>
					<td style="background-color:#F3F3F3; color:#000000; font-size:9px">Ajus</td>
					<td style="background-color:#F3F3F3; color:#000000; font-size:9px">Fac</td>
	 			</tr>';
				$c=new ReportesHistoricoFac();
				$registrosG=$c->PeriodosInmueble($perini,$inmueble,$canper);
				while (oci_fetch($registrosG)) {
					$periodo = oci_result($registrosG, 'PERIODO');
					$fechaexp = oci_result($registrosG, 'FECHA');
					$lectura = oci_result($registrosG, 'LECTURA_ACTUAL');
					$lote = oci_result($registrosG, 'ID_RUTA');
					$cons = oci_result($registrosG, 'CONSUMO');
					$metodo = oci_result($registrosG, 'METODO');
					$lector = oci_result($registrosG, 'COD_LECTOR');
					$observacion = oci_result($registrosG, 'OBSERVACION');
					$total = oci_result($registrosG, 'TOTAL');
					
					if($metodo == 'P') {
						$cons = $consumomin;
						$ajuste = 0;
						$facturar = $ajuste + $cons;
					}
					if ($metodo == 'D'){
						if ($cons < $consumomin){
						$ajuste = $consumomin - $cons;
						$facturar = $ajuste + $cons;
						}
						else{
							$ajuste = 0;
							$facturar = $cons;
						}
				 	}
					$codigoHTML.='
					<tr>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $periodo.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $fechaexp.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $lectura.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $lote.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $cons.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $ajuste.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $facturar.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $metodo.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px" colspan="3">'. $lector.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. $observacion.'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. number_format($total,2,'.',',').'</td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px">'. number_format(round($total),2,'.',',').'</td>
					</tr>';
					
				}oci_free_statement($registrosG);
			}oci_free_statement($registrosF);
		}oci_free_statement($registrosC);
		
$codigoHTML.='</table>
	';
//$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->set_paper('letter', 'landscape');
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","2048M");
$dompdf->render();
$dompdf->stream("Reporte_Historico_Facturas.pdf");
?>