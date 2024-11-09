<?php
$proyecto = $_GET['proyecto'];
$zonini = $_GET['zonini'];
$zonfin = $_GET['zonfin'];
$perini = $_GET['perini'];

if($zonini != '' && $zonfin == '') $zonfin = $zonini;
if($zonini == '' && $zonfin != '') $zonini = $zonfin;
if($zonini != '' && $zonfin != '') $nomrepo = '_Zona_'.$zonini.'_a_'.$zonfin;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Reporte_Modificaciones_Lectura_$proyecto_Periodo_$perini$nomrepo.xls");
include '../clases/class.reportes_lectura.php';
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
        <th style="border:solid; text-align:center" rowspan="2">INMUEBLE</th>
        <th style="border:solid; text-align:center" rowspan="2">ZONA</th>
        <th style="border:solid; text-align:center" rowspan="1" colspan="5">DATOS TERRENO</th>
        <th style="border:solid; text-align:center" rowspan="1" colspan="5">DATOS MODIFICACIÓN</th>
    </tr>
    <tr>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">LECTURA</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">OBSERVACION</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">LECTOR</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">FECHA</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">CONSUMO</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">LECTURA</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">OBSERVACION</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">MODIFICADOR</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">FECHA</th>
        <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="1">CONSUMO</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $c=new Reportes();
    $registros=$c->ObtieneModificaciones($perini,$zonini,$zonfin,$proyecto);
    $item = 1;
    // $cantlectores = 0;
    while (oci_fetch($registros)) {
        $inmueble = oci_result($registros, 'COD_INMUEBLE');
        $zona = oci_result($registros, 'ID_ZONA');
        $lecori = oci_result($registros, 'LECTURA_ORIGINAL');
        $obsori = oci_result($registros, 'OBSERVACION');
        $opeori = oci_result($registros, 'LECTOR_ORI');
        $fecori = oci_result($registros, 'FECHA_LECTURA_ORI');
        $conori = oci_result($registros, 'CONSUMO');
        $lecact = oci_result($registros, 'LECTURA_ACTUAL');
        $obsact = oci_result($registros, 'OBSERVACION_ACTUAL');
        $opeact = oci_result($registros, 'MODIFICADOR');
        $fecact = oci_result($registros, 'FECHA_LECTURA');
        $conact = oci_result($registros, 'CONSUMO_ACT');
        echo "<tr>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$inmueble</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'><b>$zona</b></td>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$lecori</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'><b>$obsori</b></td>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$opeori</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'><b>$fecori</b></td>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$conori</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'><b>$lecact</b></td>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$obsact</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'><b>$opeact</b></td>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$fecact</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'><b>$conact</b></td>";
        $item++;
    }oci_free_statement($registros);
    echo "</tr>";
    ?>
    </tbody>
</table>
</body>
</html>