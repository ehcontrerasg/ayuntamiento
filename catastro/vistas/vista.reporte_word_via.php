<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Reporte_Calles.doc");
include '../clases/class.administracion.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "CONSEC_NOM_VIA";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";
$fname="CONSEC_NOM_VIA";
$tname="SGC_TP_NOMBRE_VIA";

		
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
            <th align="center" style="color:#FFFFFF; background-color:#336699">Acueducto</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Nombre Calle</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Tipo V&iacute;a</th>
        </tr>
	</thead>
	<tbody>
		<tr>
		<?php
		$cont = 0;
		$l=new Administracion();
		$registros=$l->obtenervias();
		while (oci_fetch($registros)) {
			$cont++;
			$id_proyecto = oci_result($registros, 'ID_PROYECTO');
			$id_via = oci_result($registros, 'CONSEC_NOM_VIA');
			$des_via = oci_result($registros, 'DESC_NOM_VIA');
			$tip_via = oci_result($registros, 'DESC_TIPO_VIA');
			?>
			<td align="center"><b><?php echo $cont?></b></td>
			<td align="center"><?php echo $id_proyecto?></td>
			<td align="center"><?php echo $id_via?></td>
			<td align="center"><?php echo $des_via?></td>
			<td align="center"><?php echo $tip_via?></td>	
			</tr>
			<?php	
		}
		?>
		</tbody>
    </table>
</body>
</html>