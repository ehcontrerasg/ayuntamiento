<?php
require_once("../../dompdf/dompdf_config.inc.php");
include '../clases/class.reportes_lectura.php';

$proyecto = $_GET['proyecto'];
$zonini = $_GET['zonini'];
$zonfin = $_GET['zonfin'];
$lecini = $_GET['lecini'];
$perini = $_GET['perini'];

if($zonini != '' && $zonfin == '') $zonfin = $zonini;
if($zonini == '' && $zonfin != '') $zonini = $zonfin;
if($zonini != '' && $zonfin != '') $nomrepo = '_Zona_$zonini_a_$zonfin';

$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin titulo</title>
</head>
<body>';
	$c=new Reportes();
	$registros=$c->seleccionaCantObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
	while (oci_fetch($registros)) {
		$cantob = oci_result($registros, 'CANTIDAD');
	}oci_free_statement($registros);
	$codigoHTML.='<table>
    	<thead>
        	<tr>
				<th style="border-right:1px solid #DEDEDE; text-align:center; background-color:#336699; color:#FFFFFF" rowspan="2">N&deg;</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#336699; color:#FFFFFF" rowspan="2">ZONA</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#336699; color:#FFFFFF" rowspan="2">LECTOR</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#336699; color:#FFFFFF" colspan="'.$cantob.'">OBSERVACIONES
				</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#336699; color:#FFFFFF" rowspan="2">TOTALES</th>
            </tr>
        	<tr>';
				$c=new Reportes();
				$registros=$c->seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto); 
				while (oci_fetch($registros)) {
					$observacion = oci_result($registros, 'OBSERVACION_ACTUAL');
					$codigoHTML.='<th style="border:none; border-right:1px solid #DEDEDE; border-top:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF">'.$observacion.'</th>';
				} unset($observacion);
				oci_free_statement($registros);
			$codigoHTML.='</tr>
		</thead>
        <tbody>';
			$codigoHTML.='<tr>';
			$c=new Reportes();
			$registros=$c->ObtieneZonasObslec($perini,$zonini,$zonfin,$lecini,$proyecto);
			$item = 1;
			$cantlectores = 0;
			while (oci_fetch($registros)) {
				$zona = oci_result($registros, 'ID_ZONA');
				$h=new Reportes();
				$registrosC=$h->ObtieneCantLector($perini,$proyecto,$zona,$lecini);
				while (oci_fetch($registrosC)) {
					$cantlec = oci_result($registrosC, 'CANTIDAD');
				}oci_free_statement($registrosC);
				
				$codigoHTML.='<td align="center" style="border-color:#DEDEDE" rowspan="'.$cantlec.'"><b>'.$item.'</b></td>';
				$codigoHTML.='<td align="left" style="border-color:#DEDEDE" rowspan="'.$cantlec.'"><b>'.$zona.'</b></td>';
				$d=new Reportes();
				$registrosR=$d->ObtieneLectorObslec($perini,$proyecto,$zona,$lecini);
				while (oci_fetch($registrosR)) {
					$lector = oci_result($registrosR, 'COD_LECTOR_ORI');
					$nomlector = oci_result($registrosR, 'NOM_USR');
					$apelector = oci_result($registrosR, 'APE_USR');
					$cantlectores++;
					$codigoHTML.='<td align="center" style="border-color:#DEDEDE"><b>'.$lector.' - '.$nomlector.' '.$apelector.'</b></td>';
					$p=new Reportes();
					$registrosM=$p->seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
					$cantotope = 0;
					while (oci_fetch($registrosM)) {
						$canope = 0;
						$observacionop = oci_result($registrosM, 'OBSERVACION_ACTUAL');
						$codigoHTML.='<td align="center" style="border-color:#DEDEDE">';
						$f=new Reportes();
						$registrosO=$f->ObtieneObservaciones($perini,$lector,$proyecto,$zona,$observacionop);
						while (oci_fetch($registrosO)) {	
							$canope = oci_result($registrosO, 'CANTIDAD');
							$obsope = oci_result($registrosO, 'OBSERVACION_ACTUAL');
							$codigoHTML.=''.$canope.'</td>';
							$cantotope += $canope;
						}oci_free_statement($registrosO);
					}oci_free_statement($registrosM);
					$codigoHTML.='<td align="center" style="border-color:#DEDEDE; background-color:#F3F3F3; color:#000000"><b>'.$cantotope.'</b></td>';
					$codigoHTML.='</tr>';
				}oci_free_statement($registrosR); unset($obsope,$canope,$cantotope);
				//$codigoHTML.='</tr>';
				$item++;
			}oci_free_statement($registros);
			$codigoHTML.='<tr>';
			$codigoHTML.='<td align="center"style="border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000" colspan="2"><b>TOTALES</b></td>';
			$codigoHTML.='<td align="center" style="border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000"><b>'.$cantlectores.'</b></td>';
			$p=new Reportes();
			$registrosM=$p->seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
			$totalobservaciones = 0;
			while (oci_fetch($registrosM)) {
				$observacionop = oci_result($registrosM, 'OBSERVACION_ACTUAL');
				$f=new Reportes();
				$registrosO=$f->ObtieneTotalesObservacion($perini,$proyecto,$observacionop,$zonini,$zonfin,$lecini);
				while (oci_fetch($registrosO)) {
					$cantobserva = oci_result($registrosO, 'CANTIDAD');
					$codigoHTML.='<td align="center" style="border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">
						<b>'.$cantobserva.'</b>
					</td>';
					$totalobservaciones += $cantobserva;
				}oci_free_statement($registrosO);
			}oci_free_statement($registrosM);
			$codigoHTML.='<td align="center"style="border-color:#DEDEDE;border-bottom:1px solid #DEDEDE;background-color:#800000;color:#FFFFFF"><b>'.$totalobservaciones.'</b></td>
			</tr>';
		$codigoHTML.='</tbody>			
      </table>	
	</body>
</html>';
//$codigoHTML=utf8_encode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->set_paper('letter', 'portrait');
$dompdf->load_html($codigoHTML);
//ini_set("memory_limit","512M");
$dompdf->render();
$dompdf->stream("Reporte_Observaciones_Lecturas.pdf");
?>