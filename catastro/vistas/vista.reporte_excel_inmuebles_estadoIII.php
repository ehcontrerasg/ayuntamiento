<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    $coduser   = $_SESSION['codigo'];
    $proyecto  = $_GET['proyecto'];
    $proIni    = $_GET['proIni'];
    $proFin    = $_GET['proFin'];
    $manIni    = $_GET['manIni'];
    $manFin    = $_GET['manFin'];
    $estIni    = $_GET['estIni'];
    $estFin    = $_GET['estFin'];
    $usoIni    = $_GET['usoIni'];
    $actividad = $_GET['actividad'];
    $servicio = $_GET['servicio'];

    $nomrepo = 'Reporte_Inmuebles_Por_Estado_III';

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$nomrepo.xls");
    include '../clases/class.reportes_catastro.php';
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:280px">
        <thead>
        <tr>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">C&Oacute;DIGO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">URBANIZACI&Oacute;N</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">DIRECCI&Oacute;N</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CLIENTE</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">PROCESO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CATASTRO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">USO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ACT</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">UNID</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ESTADO</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count      = 1;
        $s          = new Reportes();
        $registrosS = $s->obtenerInmEstado($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $usoIni, $actividad,$servicio);
        while (oci_fetch($registrosS)) {
            $codigo     = oci_result($registrosS, 'CODIGO_INM');
            $urbaniza   = oci_result($registrosS, 'DESC_URBANIZACION');
            $direccion  = oci_result($registrosS, 'DIRECCION');
            $cliente    = oci_result($registrosS, 'ALIAS');
            $proceso    = oci_result($registrosS, 'ID_PROCESO');
            $catastro   = oci_result($registrosS, 'CATASTRO');
            $uso        = oci_result($registrosS, 'ID_USO');
            $actividad  = oci_result($registrosS, 'DESC_ACTIVIDAD');
            $unidades   = oci_result($registrosS, 'TOTAL_UNIDADES');
            $estado     = oci_result($registrosS, 'ID_ESTADO');

            echo "<tr>";
            echo "<td align='center'> $codigo </td>";
            echo "<td align='center'> $urbaniza </td>";
            echo "<td align='center'> $direccion </td>";
            echo "<td align='center'> $cliente </td>";
            echo "<td align='center'> $proceso </td>";
            echo "<td align='center'> $catastro </td>";
            echo "<td align='center'> $uso </td>";
            echo "<td align='center'> $actividad </td>";
            echo "<td align='center'> $unidades </td>";
            echo "<td align='center'> $estado </td>";
            echo "</tr>";
            $count++;
        }
        oci_free_statement($registrosS);
        ?>
        </tr>
        </tbody>
    </table>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

