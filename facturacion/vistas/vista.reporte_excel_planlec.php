<?php
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_plan_lec.php';
$proyecto = $_GET['proyecto'];
$perini = $_GET['perini'];
$secini = $_GET['secini'];
$secfin = $_GET['secfin'];
$fecini = $_GET['fecini'];
if($secini != '' && $secfin == '') $secfin = $secini;
if($secini == '' && $secfin != '') $secini = $secfin;
if($secini != '' && $secfin != '') $nomrepo = '_Sector_'.$secini.'_a_'.$secfin;
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Planificacion_Toma_Lecturas_".$proyecto."_".$perini.".xls");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<?php
		echo "<table class='scroll' border='1' bordercolor='#CCCCCC' style='height:350px'>";
		echo "<tr>";
		echo "<td colspan='5' style='border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF' align='center'><b>PLANIFICACI&Oacute;N TOMA DE LECTURAS</b></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center' >N&deg;</th>";
		echo "<th style='border:none; border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center' >DISTRIBUIDOR</th>";
		echo "<th style='border:none; border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center' >ZONA</th>";
		echo "<th style='border:none; border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center' >RUTAS PLANIFICADAS</th>";
		echo "<th style='border:none; border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center' >CANTIDAD</th>";
        echo "</tr>";
		$c=new Reportes_Plan_Lec();
		$registros=$c-> ObtienePlanificacionRuta($perini,$proyecto,$secini,$secfin,$fecini);
		$item = 0;
		$totallec = 0;
		while (oci_fetch($registros)) {
			$canrutas = '';
			$cod_lector = oci_result($registros, 'COD_LECTOR');
			$lector = oci_result($registros, 'USUARIO');
			$zona = oci_result($registros, 'ID_ZONA');
			$cantidad = oci_result($registros, 'CANTIDAD');
			$h=new Reportes_Plan_Lec();
			$registrosC=$h->ObtieneDetalleRutas($cod_lector, $zona, $perini);
			while (oci_fetch($registrosC)) {
				$ruta = oci_result($registrosC, 'RUTA');
				$canrutas .= $ruta.', ';
			}oci_free_statement($registrosC);
			$canrutas = substr($canrutas,0,strlen($canrutas)-2);
			$item++;
			echo "<tr>";
			echo "<td align='center' style='border-color:#DEDEDE' <b>$item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE'>$lector</td>";
			echo "<td align='left' style='border-color:#DEDEDE'>$zona</td>";
			echo "<td align='left' style='border-color:#DEDEDE'>$canrutas</td>";
			echo "<td align='left' style='border-color:#DEDEDE'>$cantidad</td>";
			echo "</tr>";
			
			$totallec += $cantidad;
		}oci_free_statement($registros);
		echo "<tr>";
		echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;' colspan='2'><b>Total Distribuidores: $item</b></td>";
		echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;' colspan='2'><b>Total lecturas</b></td>";
		echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totallec</b></td>";
		echo "</tr>";
		echo "</table>";
	?>
</body>
</html>
<?php

?>