<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    $coduser = $_SESSION['codigo'];
    $proyecto = $_GET['proyecto'];
    $fecini = $_GET['fecini'];
    $fecfin = $_GET['fecfin'];

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Reporte_Reclamos_por_Zonas_y_Rutas.xls");
    include '../clases/class.reportes_catastro.php';
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
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">SECTOR</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">RUTA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">RECLAMOS</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $cont = 1;
        $s=new Reportes();
        $registros=$s->reclamosPorRutas($proyecto, $fecini, $fecfin); 
            while (oci_fetch($registros)) {
                $zona = oci_result($registros, 'ID_SECTOR');
                $ruta = oci_result($registros, 'ID_RUTA');
                $reclamos = oci_result($registros, 'CANTIDAD_PQR');
                echo "<td align='center'>$zona</td>";
                echo "<td align='center'>$ruta</td>";
                echo "<td align='center'>$reclamos</td>";
                echo "</tr>";
            $cont++;
        }oci_free_statement($registros);
        ?>
        </tbody>
    </table>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

