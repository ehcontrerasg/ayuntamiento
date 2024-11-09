<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    $coduser = $_SESSION['codigo'];
    $proyecto = $_GET['proyecto'];
    $secini = $_GET['secini'];
    $secfin = $_GET['secfin'];
    $fecini = $_GET['fecini'];
    $fecfin = $_GET['fecfin'];

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Reporte_Inmuebles_Incorporados_Sectores_del_'$secini'_al_'$secfin'.xls");
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
            <th style="border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">SECTOR</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">C&Oacute;DIGO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">DIRECCI&Oacute;N</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ID PROCESO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">SECTOR</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">RUTA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">NOMBRE</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">ESTADO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">FECHA SOLICITUD</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">USO</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">GERENCIA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">TARIFA</th>
            <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#FFFFFF">CODIGO TARIFA</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $cont = 1;
        $s=new Reportes();
        $registros=$s->obtenerInmueblesInc ($proyecto, $secini, $secfin, $fecini, $fecfin);
        while (oci_fetch($registros)) {
            $codigo = oci_result($registros, 'CODIGO_INM');
            $direccion = oci_result($registros, 'DIRECCION');
            $proceso = oci_result($registros, 'ID_PROCESO');
            $sector = oci_result($registros, 'SECTOR');
            $ruta = oci_result($registros, 'RUTA');
            $nombre = oci_result($registros, 'ALIAS');
            $estado = oci_result($registros, 'ID_ESTADO');
            $fecha = oci_result($registros, 'FECHA_INICIO');
            $sector = oci_result($registros, 'ID_SECTOR');
            $uso = oci_result($registros, 'ID_USO');
            $gerencia = oci_result($registros, 'ID_GERENCIA');
            $tarifa = oci_result($registros, 'TARIFA');
            $codtar = oci_result($registros, 'CODIGO_TARIFA');

            echo "<tr>";
            if($sector != $secini){
                echo "<td align='left' style='border:none; border-top:1px solid #DEDEDE'><b>$sector</b></td>";
                $secini = $sector;
            }
            else if ($sector == $secini && $cont == 1) echo "<td align='left' style='border:none; border-top:1px solid #DEDEDE'><b>$sector</b></td>";
            else echo "<td align='left' style='border:none; border-right:1px solid #DEDEDE'></td>";
            echo "<td align='center'>$codigo</td>";
            echo "<td align='center'>$direccion</td>";
            echo "<td align='center'>$proceso</td>";
            echo "<td align='center'>$sector</td>";
            echo "<td align='center'>$ruta</td>";
            echo "<td align='center'>$nombre</td>";
            echo "<td align='center'>$estado</td>";
            echo "<td align='center'>$fecha</td>";
            echo "<td align='center'>$uso</td>";
            echo "<td align='center'>$gerencia</td>";
            echo "<td align='center'>$tarifa</td>";
            echo "<td align='center'>$codtar</td>";

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

