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

    $nomrepo = 'Reporte_Inmuebles_Por_Estado';

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
            <th style="border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">SECTOR</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ZONA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">C&Oacute;DIGO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">URBANIZACI&Oacute;N</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">DIRECCI&Oacute;N</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CLIENTE</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">PROCESO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CATASTRO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">MEDIDOR</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">SERIAL</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">EMPL</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">DIAM</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">USO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ACT</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">UNID</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">SUMINISTRO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CONTRATO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ESTADO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">TARIFA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">FAC PEND</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">DEUDA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CUPO BASICO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CONSUMO MINIMO</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count      = 1;
        $s          = new Reportes();
        $registrosS = $s->obtenerInmEstado($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $usoIni, $actividad,$servicio);
        while (oci_fetch($registrosS)) {
            $sector     = oci_result($registrosS, 'ID_SECTOR');
            $zona       = oci_result($registrosS, 'ID_ZONA');
            $codigo     = oci_result($registrosS, 'CODIGO_INM');
            $urbaniza   = oci_result($registrosS, 'DESC_URBANIZACION');
            $direccion  = oci_result($registrosS, 'DIRECCION');
            $cliente    = oci_result($registrosS, 'ALIAS');
            $proceso    = oci_result($registrosS, 'ID_PROCESO');
            $catastro   = oci_result($registrosS, 'CATASTRO');
            $medidor    = oci_result($registrosS, 'MEDIDOR');
            $serial     = oci_result($registrosS, 'SERIAL');
            $emplaza    = oci_result($registrosS, 'COD_EMPLAZAMIENTO');
            $diametro   = oci_result($registrosS, 'DESC_CALIBRE');
            $uso        = oci_result($registrosS, 'ID_USO');
            $actividad  = oci_result($registrosS, 'DESC_ACTIVIDAD');
            $unidades   = oci_result($registrosS, 'TOTAL_UNIDADES');
            $suministro = oci_result($registrosS, 'DESC_SUMINISTRO');
            $contrato   = oci_result($registrosS, 'ID_CONTRATO');
            $estado     = oci_result($registrosS, 'ID_ESTADO');
            $tarifa     = oci_result($registrosS, 'DESC_TARIFA');
            $fac_pend   = oci_result($registrosS, 'FAC_PEND');
            $deuda      = oci_result($registrosS, 'DEUDA');
            $cup_bas    = oci_result($registrosS, 'CUPO_BASICO');
            $cons_min   = oci_result($registrosS, 'CONSUMO_MINIMO');

            echo "<tr>";
            if ($secini != $sector) {
                echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
                $secini = $sector;
            } else if ($secini == $sector && $count == 1) {
                echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
                $secini = $sector;
            } else {
                echo "<td align='center' style='border:none'></td>";
            }
            if ($zonini != $zona) {
                echo "<td align='center' style='border-top:1px solid #DEDEDE;  border-bottom:none; border-right:none'><b> $zona </b></td>";
                $zonini = $zona;
            } else if ($zonini == $zona && $count == 1) {
                echo "<td align='center' style='border-top:1px solid #DEDEDE;  border-bottom:none; border-right:none'><b> $zona </b></td>";
                $zonini = $zona;
            } else {
                echo "<td align='center' style='border:none; border-left:1px solid #DEDEDE'></td>";
            }
            echo "<td align='center'> $codigo </td>";
            echo "<td align='center'> $urbaniza </td>";
            echo "<td align='center'> $direccion </td>";
            echo "<td align='center'> $cliente </td>";
            echo "<td align='center'> $proceso </td>";
            echo "<td align='center'> $catastro </td>";
            echo "<td align='center'> $medidor </td>";
            echo "<td align='center'> $serial </td>";
            echo "<td align='center'> $emplaza </td>";
            echo "<td align='center'> $diametro </td>";
            echo "<td align='center'> $uso </td>";
            echo "<td align='center'> $actividad </td>";
            echo "<td align='center'> $unidades </td>";
            echo "<td align='center'> $suministro </td>";
            echo "<td align='center'> $contrato </td>";
            echo "<td align='center'> $estado </td>";
            echo "<td align='center'> $tarifa </td>";
            echo "<td align='center'> $fac_pend </td>";
            echo "<td align='center'>$deuda </td>";
            echo "<td align='center'>$cup_bas </td>";
            echo "<td align='center'>$cons_min </td>";
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

