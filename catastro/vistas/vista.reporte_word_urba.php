<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Reporte_Urbanizaciones.doc");
include '../clases/class.administracion.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "CONSEC_URB";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="CONSEC_URB";
$tname="SGC_TP_URBANIZACIONES";
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
            <th align="center" style="color:#FFFFFF; background-color:#336699">Proyecto</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Urbanizaci&oacute;n</th>
        </tr>
	</thead>
	<tbody>
		<tr>
		<?php
		$cont = 0;
		$l=new Administracion();
		$registros=$l->obtenerurbanizacion();
		while (oci_fetch($registros)) {
			$cont++;
			$id_proyecto = oci_result($registros, 'ID_PROYECTO');
			$id_urba = oci_result($registros, 'CONSEC_URB');
			$des_urba = oci_result($registros, 'DESC_URBANIZACION');
			?>
			<td align="center"><b><?php echo $cont?></b></td>
			<td align="center"><?php echo $id_proyecto?></td>
			<td align="center"><?php echo $id_urba?></td>
			<td align="center"><?php echo $des_urba?></td>		
			</tr>
			<?php	
		}
		?>
		</tbody>
    </table>
</body>
</html>