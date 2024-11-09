<?php
$proyecto = $_GET['proyecto'];
//$zonini = $_GET['zonini'];
$periodo = $_GET['periodo'];

/*if($secini != '' && $secfin == '') $secfin = $secini;
if($secini == '' && $secfin != '') $secini = $secfin;
if($secini != '' && $secfin != '') $nomrepo = '_Sector_'.$secini.'_a_'.$secfin;*/

//if($canper == '') $canper = 3;
//else $canper = $canper;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Facturas_Digitales_".$proyecto."_".$periodo.".xls");
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_fac_digital.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
    <tr>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Inmueble</th>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Zona</th>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Factura</th>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Email</th>
        <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Fecha Envio</th>
    </tr>
    <?php
    $item = 0;
    $totalfac = 0;
    $c=new Reportes_Fac_Digital();
    $registros=$c->obtieneFacturas($proyecto,$periodo);
    while (oci_fetch($registros)) {
        //$item ++;
        $codinm = oci_result($registros, 'INMUEBLE');
        $zona = oci_result($registros, 'ID_ZONA');
        $numfac = oci_result($registros, 'CONSEC_FACTURA');
        $email = oci_result($registros, 'EMAIL');
        $fecha = oci_result($registros, 'FECHA');
        echo "<tr>";
        //echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
        echo "<td align='center' style='border-color:#DEDEDE'>$codinm</td>";
        echo "<td align='center' style='border-color:#DEDEDE'>$zona</td>";
        echo "<td align='center' style='border-color:#DEDEDE'>$numfac</td>";
        echo "<td align='left' style='border-color:#DEDEDE'>$email</td>";
        echo "<td align='left' style='border-color:#DEDEDE'>$fecha</td>";
        echo "</tr>";
        //$total += $cantidad;
    }oci_free_statement($registros);
    echo "<tr>";
   // echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
    //echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
   // echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$total</b></td>";
    echo "</tr>";
    ?>
</table>
</body>
</html>