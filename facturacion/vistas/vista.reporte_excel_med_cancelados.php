<?php
$proyecto = $_GET['proyecto'];

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Medidores_cancelados_".$proyecto.".xls");
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_med_instalados.php';
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
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Gerencia</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad</th>
		</tr>
		<?php
		$item = 0;
		$totalfac = 0;
		$c=new Reportes_Med_Instalados();
		$registros=$c->obtieneCantidadMedidoresCancelados($proyecto);
		while (oci_fetch($registros)) {
			$item ++;
			$gerencia = oci_result($registros, 'ID_GERENCIA');
			$cantidad = oci_result($registros, 'CANTIDAD');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$gerencia</td>";
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