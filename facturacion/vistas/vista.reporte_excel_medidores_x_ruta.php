<?php
$proyecto = $_GET['proyecto'];
$secini = $_GET['secini'];
$perini = $_GET['perini'];

/*if($secini != '' && $secfin == '') $secfin = $secini;
if($secini == '' && $secfin != '') $secini = $secfin;
if($secini != '' && $secfin != '') $nomrepo = '_Sector_'.$secini.'_a_'.$secfin;*/

//if($canper == '') $canper = 3;
//else $canper = $canper;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Total_Medidores_Por_Ruta_".$proyecto."_Periodo_".$perini."_Sector_".$secini.".xls");
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_fac_ruta.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
    <tr>
        <th colspan="3" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Sector <?php echo $secini;?> Periodo <?php echo $perini?></th>
    </tr>
    <tr>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ruta</th>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad Medidores</th>
    </tr>
    <?php
    $item = 0;
    $totalfac = 0;
    $c=new Reportes_Fac_Ruta();
    $registros=$c->obtieneRutasCantidadMed($proyecto,$secini,$perini);
    while (oci_fetch($registros)) {
        $item ++;
        $ruta = oci_result($registros, 'ID_RUTA');
        $cantidad_fac = oci_result($registros, 'CANTIDAD');
        echo "<tr>";
        echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
        echo "<td align='left' style='border-color:#DEDEDE'>$ruta</td>";
        echo "<td align='left' style='border-color:#DEDEDE'>$cantidad_fac</td>";
        echo "</tr>";
        $totalfac += $cantidad_fac;
    }oci_free_statement($registros);
    echo "<tr>";
    echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
    echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
    echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalfac</b></td>";
    echo "</tr>";
    ?>
</table>
</body>
</html>