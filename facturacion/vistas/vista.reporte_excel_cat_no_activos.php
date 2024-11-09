<?php
$proyecto = $_GET['proyecto'];
//$zonini = $_GET['zonini'];
//$perini = $_GET['perini'];

/*if($secini != '' && $secfin == '') $secfin = $secini;
if($secini == '' && $secfin != '') $secini = $secfin;
if($secini != '' && $secfin != '') $nomrepo = '_Sector_'.$secini.'_a_'.$secfin;*/

//if($canper == '') $canper = 3;
//else $canper = $canper;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Facturas_Digitales_".$proyecto."_".$periodo.".xls");
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_no_activos.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Uso</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad</th>
		</tr>
		<?php
		$item = 0;
		$totalfac = 0;
		$c=new Reportes_Cat_No_Activo();
		$registros=$c->obtieneCantidadInmuebles($proyecto);
		while (oci_fetch($registros)) {
			$item ++;
			$uso = oci_result($registros, 'ID_USO');
			$cantidad = oci_result($registros, 'CANTIDAD');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$uso</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$cantidad</td>";
			echo "</tr>";
			$total += $cantidad;
		}oci_free_statement($registros);
		echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$total</b></td>";
		echo "</tr>";
		?>
</table>
</body>
</html>