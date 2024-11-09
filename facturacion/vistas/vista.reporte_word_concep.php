<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Reporte_Conceptos.doc");
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
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
 <table border="1">
	<thead>
		<tr>
			<th align="center" style="color:#FFFFFF; background-color:#336699">N&deg;</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripción</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Orden</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Tipo</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Mora</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Cuotas</th>
        </tr>
	</thead>
	<tbody>
		<tr>
		<?php
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
			?>
			<td align="center"><b><?php echo $cont?></b></td>
			<td align="center"><?php echo $id_concepto?></td>
			<td align="center"><?php echo $desc_concepto?></td>
			<td align="center"><?php echo $orden?></td>
			<td align="center"><?php echo $tipo_concepto?></td>
			<td align="center"><?php echo $mora?></td>
			<td align="center"><?php echo $num_cuotas?></td>	
			</tr>
			<?php	
		}
		?>
		</tbody>
    </table>
</body>
</html>