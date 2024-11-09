<?php
$proyecto = $_GET['proyecto'];
$perini = $_GET['perini'];

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Reporte_Distribucion_Facturas_Por_Zonas_".$proyecto."_Periodo_".$perini.".xls");
include '../clases/class.entrega_factura.php';
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
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Zona</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Inicio</th>			
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Fin</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad</th>
		</tr>
		<?php
		$item = 0;
		$c=new Entrega_Factura();
		$registros=$c->Rendimiento_entregafac($proyecto,$perini);
		while (oci_fetch($registros)) {
			$item ++;
			$zona = oci_result($registros, 'ID_ZONA');
			$inicio = oci_result($registros, 'INICIO');
            $fin = oci_result($registros, 'FIN');
			$cand = oci_result($registros, 'TOTAL');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$zona</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$inicio</td>";
                echo "<td align='left' style='border-color:#DEDEDE'>$fin</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$cand</td>";
			echo "</tr>";
			$totalcand += $cand;
		}oci_free_statement($registros);
		echo "<tr>";
        echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'></td>";
        echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'></td>";
        echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'></td>";
        echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalcand</b></td>";
		echo "</tr>";
		?>
</table>
</body>
</html>