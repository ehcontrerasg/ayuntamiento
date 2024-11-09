<?php
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

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Reporte_Historico_Facturas_".$proyecto."_Periodo_".$perini."".$nomrepo.".doc");
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_hisfac.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
 <table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
			<td colspan="14" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b>HIST&Oacute;RICO DE FACTURAS</b></td>
		</tr>
		<?php
		$c=new ReportesHistoricoFac();
		$registrosC=$c->CantidadZonas($perini,$zonini,$zonfin,$canper,$proyecto);
		while (oci_fetch($registrosC)) {
			$zona = oci_result($registrosC, 'ID_ZONA');
			?>
			<tr>
				<td colspan="14" style="border:1px solid #DEDEDE; background-color:#0080C0; color:#FFFFFF"><b>ZONA&nbsp;&nbsp;<?php echo $zona?></b></td>
			</tr>
			<?php
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
				?>
				<tr>
					<td colspan="2" rowspan="2" style="border:1px solid #DEDEDE; background-color:#83B9FC; color:#FFFFFF; font-size:11px">
						<b>Inmueble&nbsp;&nbsp;<?php echo $inmueble?></b>
					</td>
					<td colspan="5" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; font-size:11px">
						<b>Cliente:&nbsp;</b><?php echo substr($cliente,0,50)?>
					</td>
					<td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; border-left:none; font-size:11px" colspan="4">
						<b>Proceso:&nbsp;</b><?php echo $proceso?>
					</td>
					<td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; border-left:none; font-size:11px" colspan="3">
						<b>Catastro:&nbsp;</b><?php echo $catastro?>
					</td>
				</tr>
				<tr>
					<td colspan="5" style="border:1px solid #DEDEDE;background-color:#FFFFFF;color:#000000; font-size:11px"><b>Direcci&oacute;n:&nbsp;</b>
					<?php echo substr($direccion.' '.$urbaniza,0,50)?></td>
					<td style="border:1px solid #DEDEDE;background-color:#FFFFFF;color:#000000;vertical-align:top;text-align:center;font-size:9px">
					<b>Estado</b><br /><?php echo $estado?></td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Unidad</b><br /><?php echo $unidades?></td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Uso</b><br /><?php echo $uso?></td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Tarifa</b><br /><?php echo $tarifa?></td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Cons.Min</b><br /><?php echo $consumomin?></td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Medidor</b><br /><?php echo $medidor?></td>
					<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
					<b>Serial</b><br /><?php echo $serial?></td>
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
	 			</tr>
				<?php
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
					//$fecpago = oci_result($registrosG, 'FECPAGO');
					//$pagado = oci_result($registrosG, 'TOTAL_PAGADO');
					
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
					?>
					<tr>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $periodo?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $fechaexp?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $lectura?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $lote?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $cons?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $ajuste?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $facturar?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $metodo?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px" colspan="3"><?php echo $lector?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $observacion?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo number_format($total,2,'.',',')?></td>
						<td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo number_format(round($total),2,'.',',')?></td>
					</tr>
					<?php
				}oci_free_statement($registrosG);
				?>
				
			<?php	
			}oci_free_statement($registrosF);
		}oci_free_statement($registrosC);
		?>
</table>
</body>
</html>