<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Reporte_Parametros.doc");
include '../clases/class.adminparametros.php';

$inmueble=$_GET['cod_inmueble'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = "COD_PARAMETRO";
if (!$sortorder) $sortorder = "ASC";
$sort = "ORDER BY $sortname $sortorder";
$fname="COD_PARAMETRO";
$tname="SGC_TP_PARAMETROS";
		
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
            <th align="center" style="color:#FFFFFF; background-color:#336699">P&aacute;rametro</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Valor</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Descripci&oacute;n</th>
        </tr>
	</thead>
	<tbody>
		<tr >
		<?php
		//$cont = 0;
		$l=new Parametros();
		$registros=$l->obtenerParametros($sort,$tname);
		while (oci_fetch($registros)) {
			//$cont++;
			$cod_param = oci_result($registros, 'COD_PARAMETRO');
			$nom_param = oci_result($registros, 'NOM_PARAMETRO');
			$val_param = oci_result($registros, 'VAL_PARAMETRO');
			$des_param = oci_result($registros, 'DES_PARAMETRO');
			?>
			<td align="center"><b><?php echo $cod_param?></b></td>
			<td align="center"><?php echo $nom_param?></td>
			<td align="center"><?php echo $val_param?></td>
			<td align="center"><?php echo $des_param?></td>		
			</tr>
			<?php	
		}
		?>
		</tbody>
    </table>
</body>
</html>