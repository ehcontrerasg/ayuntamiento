<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Reporte_Sector_Ruta_Manzana.xls");
    include '../clases/class.reportes_catastro.php';

    $coduser = $_SESSION['codigo'];
    $proyecto = $_GET['proyecto'];
    $secini = $_GET['secini'];
    $secfin = $_GET['secfin'];
    $rutini = $_GET['rutini'];
    $rutfin = $_GET['rutfin'];

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
        <thead>
        <tr>
            <th style="border-right:1px solid #DEDEDE; text-align:center; background-color:#585858; color:#FFFFFF">SECTOR</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#585858; color:#FFFFFF">RUTA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#585858; color:#FFFFFF">MANZANA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#585858; color:#FFFFFF">N&deg; INMUEBLES</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $contsec = 1;
        $countsec = 1;
        $s=new Reportes();
        $registrosS=$s->obtenerSectores ($proyecto, $secini, $secfin);
        while (oci_fetch($registrosS)) {
            $sector = oci_result($registrosS, 'ID_SECTOR');
            echo "<tr>";
            echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
            $r=new Reportes();
            $registrosR=$r->obtenerRutas ($proyecto, $sector, $rutini, $rutfin);
            $cantmzsec = 0;
            $cantinmsec= 0;
            $cantrutas=0;
            while (oci_fetch($registrosR)) {
                $ruta = oci_result($registrosR, 'ID_RUTA');
                if($countsec != $contsec) echo "<td align='left' style='border:none'></td>";
                echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none;'><b> $ruta </b></td>";
                $m=new Reportes();
                $registrosM=$m->obtenerRegistros ($proyecto, $sector, $ruta);
                $countsec = 1;
                $cantmzrut = 0;
                $cantinmrut= 0;
                while (oci_fetch($registrosM)) {
                    $manzana = oci_result($registrosM, 'MANZANA');
                    $inmuebles = oci_result($registrosM, 'CANTIDAD');
                    if($countsec != $contsec) echo "<td align='left' style='border:none; border-right:1px solid #DEDEDE'></td> <td align='left' style='border:none'></td>";
                    echo "<td align='center'> $manzana </td>";
                    echo "<td align='center'> $inmuebles </td>";
                    echo "</tr>";
                    $countsec++;
                    $cantmzrut++;
                    $cantinmrut += $inmuebles;
                    $cantmzsec++;
                    $cantinmsec += $inmuebles;
                }oci_free_statement($registrosM);
                echo "<tr>";
                echo "<td align='center' style='border:none;'><b></b></td>";
                echo "<td align='center' style='background-color:#DEDEDE; color:#000000'><b>TOTAL RUTA $ruta</b></td>";
                echo "<td align='center' style='background-color:#DEDEDE; color:#000000'><b>$cantmzrut</b></td>";
                echo "<td align='center' style='background-color:#DEDEDE; color:#000000'<b>$cantinmrut</b></td>";
                echo "</tr>";
                $cantrutas++;
            }oci_free_statement($registrosR);
            echo "</tr>";
            //$contsec++;
            $contsec = 1;
            $countsec = 1;
            echo "<tr>";
            echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>TOTAL SECTOR $sector</b></td>";
            echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>$cantrutas</b></td>";
            echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>$cantmzsec</b></td>";
            echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>$cantinmsec</b></td>";
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

