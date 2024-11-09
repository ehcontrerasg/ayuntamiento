<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    require_once("../../dompdf/dompdf_config.inc.php");
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

    $codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t�tulo</title>
</head>
<body>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
            <th align="center" style="color:#FFFFFF; background-color:#336699">N&deg;</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">Proyecto</th>
            <th align="center" style="color:#FFFFFF; background-color:#336699">C&oacute;digo</th>
			<th align="center" style="color:#FFFFFF; background-color:#336699">Urbanizaci&oacute;n</th>
        </tr>
	</thead>
	<tbody>
		';

    $cont = 0;
    $l=new Administracion();
    $registros=$l->obtenerurbanizacion();
    while (oci_fetch($registros)) {
        $cont++;
        $id_proyecto = oci_result($registros, 'ID_PROYECTO');
        $id_urba = oci_result($registros, 'CONSEC_URB');
        $des_urba = oci_result($registros, 'DESC_URBANIZACION');
        $codigoHTML.='
			<tr>
			<td align="center"><b>'.utf8_decode($cont).'</b></td>
			<td align="center">'.utf8_decode($id_proyecto).'</td>
			<td align="center">'.utf8_decode($id_urba).'</td>
			<td align="center">'.utf8_decode($des_urba).'</td>		
			</tr>';

    }
    $codigoHTML.='
	</tbody>
</table>
</body>
</html>';
    $codigoHTML=utf8_encode($codigoHTML);
    $dompdf=new DOMPDF();
    $dompdf->set_paper('A4', 'landscape');
    $dompdf->load_html($codigoHTML);
    ini_set("memory_limit","256M");
    $dompdf->render();
    $dompdf->stream("Reporte_Urbanizaciones.pdf");
    ?>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

