<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Reporte_Diferidos.xls");
include '../clases/class.admindiferidos.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "CODIGO";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="CODIGO";
$tname="SGC_TP_CONCEPTO_DIF";
$where= "ACTIVO = 'S'";
		
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
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digoo</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripci&oacute;n</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Concepto</th>
        </tr>
	</thead>
	<tbody>
		<tr>
		<?php
		$cont = 0;
		$l=new diferidos();
		$registros=$l->obtenerdiferidos($where,$sort,$start,$end, $tname);
		while (oci_fetch($registros)) {
			$cont++;
			$id_diferido = oci_result($registros, 'CODIGO');
			$desc_diferido = oci_result($registros, 'DESCRIPCION');
			$cod_concepto = oci_result($registros, 'COD_CONCEPTO');
			?>
			<td align="center"><b><?php echo $cont?></b></td>
			<td align="center"><?php echo $id_diferido?></td>
			<td align="center"><?php echo $desc_diferido?></td>
			<td align="center"><?php echo $cod_concepto?></td>		
			</tr>
			<?php	
		}
		?>
		</tbody>
    </table>
</body>
</html>