<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    $coduser = $_SESSION['codigo'];
    $proyecto = $_GET['proyecto'];
    $tipo = $_GET['tipo'];
    $secini = $_GET['secini'];
    $secfin = $_GET['secfin'];
    $fecini = $_GET['fecini'];
    $fecfin = $_GET['fecfin'];
    $usoini = $_GET['usoini'];
    $usofin = $_GET['usofin'];

    if($tipo == 'A') $ab = 'Alta';
    if($tipo == 'B') $ab = 'Baja';

    $nomrepo = 'Reporte_'.$ab.'_Inmuebles';

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
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">DIRECCION</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CLIENTE</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">PROCESO</th>
            <?php
            if($tipo == 'A'){
                echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">PERIODO ALTA</th>';
                echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">FECHA ALTA</th>';
            }
            if($tipo == 'B'){
                echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">PERIODO BAJA</th>';
                echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">FECHA BAJA</th>';
            }
            ?>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ESTADO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">USO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">TARIFA</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $s=new Reportes();
        $registrosS=$s->obtenerAltas ($proyecto, $tipo, $fecini, $fecfin, $secini, $secfin, $usoini, $usofin);
        while(oci_fetch($registrosS)){
            $sector = oci_result($registrosS, 'ID_SECTOR');
            $zona = oci_result($registrosS, 'ID_ZONA');
            $codigo = oci_result($registrosS, 'CODIGO_INM');
            $urbaniza = oci_result($registrosS, 'DESC_URBANIZACION');
            $direccion = oci_result($registrosS, 'DIRECCION');
            $cliente = oci_result($registrosS, 'ALIAS');
            $proceso = oci_result($registrosS, 'ID_PROCESO');
            $peralta = oci_result($registrosS, 'PER_ALTA');
            $fecalta = oci_result($registrosS, 'FEC_ALTA');
            $perbaja = oci_result($registrosS, 'PER_BAJA');
            $fecbaja = oci_result($registrosS, 'FEC_BAJA');
            $estado = oci_result($registrosS, 'ID_ESTADO');
            $uso = oci_result($registrosS, 'ID_USO');
            $tarifa = oci_result($registrosS, 'DESC_TARIFA');

            echo "<tr>";
            if($secini != $sector){
                echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
                $secini = $sector;
            }
            else{
                echo "<td align='center' style='border:none'></td>";
            }
            if($zonini != $zona){
                echo "<td align='center' style='border-top:1px solid #DEDEDE;  border-bottom:none; border-right:none'><b> $zona </b></td>";
                $zonini = $zona;
            }
            else{
                echo "<td align='center' style='border:none; border-left:1px solid #DEDEDE'></td>";
            }
            echo "<td align='center'> $codigo </td>";
            echo "<td align='center'> $urbaniza $direccion </td>";
            echo "<td align='center'> $cliente </td>";
            echo "<td align='center'> $proceso </td>";
            if($tipo == 'A'){
                echo "<td align='center'> $peralta </td>";
                echo "<td align='center'> $fecalta </td>";
            }
            else if($tipo == 'B'){
                echo "<td align='center'> $perbaja </td>";
                echo "<td align='center'> $fecbaja </td>";
            }
            echo "<td align='center'> $estado </td>";
            echo "<td align='center'> $uso </td>";
            echo "<td align='center'> $tarifa </td>";
            echo "</tr>";
        }oci_free_statement($registrosS);
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

