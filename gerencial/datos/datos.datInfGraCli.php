<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include_once '../clases/class.reportes_gerenciales.php';
session_start();
$cod=$_SESSION['codigo'];

if($tipo=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        $_SESSION['tiempo']=time();
        echo "true";
    }
}

if($tipo=='selPro'){
    $l=new ReportesGerencia();
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='InfGraCli'){

    $periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
    $cont = 0;
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Informe_Mensual_Grandes_Clientes_".$proyecto."_".$periodo.".xls");
    //header("Pragma: no-cache");
    //header("Expires: 0");
    ////////////////////TABLA USUARIOS ALCANTARILLADO POR GERENCIA USO CONCEPTO ESTE
    echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
    echo "<tr>";
    echo "<td colspan='5'style='border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF' align='center'><b>INFORME GRANDES USUARIOS PERIODO $periodo</b></td>";
    echo "</tr>";
    echo "</table>";

    echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
    echo "<tr>";
    echo "<td colspan='1'>";
    echo "<table  border='1' bordercolor='#CCCCCC'>";
    echo "<tr>";
    echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Codigo</th>";
    echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Nombre</th>";
    echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>M3 Facturados</th>";
    echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>RD$ Facturado</th>";
    echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>RD$ Recaudado</th>";
    echo "</tr>";
    $c=new ReportesGerencia();
    $registros=$c->RepGrandesClientes($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cont++;
        echo "<tr>";
        $cod_inm=utf8_decode(oci_result($registros,"CODIGO_INM"));
        $alias=utf8_decode(oci_result($registros,"ALIAS"));
        $nom_cli=utf8_decode(oci_result($registros,"NOMBRE_CLI"));
        $metros=utf8_decode(oci_result($registros,"METROS"));
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        if($alias == '') $alias = $nom_cli;
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$cod_inm</td>";
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$alias</td>";
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$metros</td>";
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$facturado</td>";
        $d=new ReportesGerencia();
        $registrosR=$d->RepGrandesClientesRecaudo($cod_inm, $periodo);
        while (oci_fetch($registrosR)) {
            $recaudado=utf8_decode(oci_result($registrosR,"IMPORTE"));
            echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$recaudado</td>";
            echo "</tr>";
        }
        $totalmetros += $metros;
        $totalfacturado += $facturado;
        $totalrecaudado += $recaudado;
    }oci_free_statement($registros);
    echo "</table>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "<table  border='1' bordercolor='#CCCCCC'>";
    echo "<tr>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006666; text-align:center'>Totales</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$cont</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$totalmetros</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$totalfacturado</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$totalrecaudado</td>";
    echo "</tr>";
    echo "</table>";
}


if($tipo=='InfGraCliResumen'){
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Informe_Mensual_Grandes_Clientes_".$proyecto."_".$periodo.".xls");
    $periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
    $cont = 0;
    //header("Pragma: no-cache");
    //header("Expires: 0");
    ////////////////////TABLA USUARIOS ALCANTARILLADO POR GERENCIA USO CONCEPTO ESTE
    echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
        echo "<tr>";
            echo "<td colspan='5'style='border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF' align='center'><b>INFORME GRANDES USUARIOS PERIODO $periodo</b></td>";
        echo "</tr>";
    echo "</table>";

    echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
        /*echo "<tr>";
            echo "<td colspan='1'>";*/
   // echo "<table  border='1' bordercolor='#CCCCCC'>";
        echo "<tr>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Codigo</th>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Nombre</th>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>M3 Facturados</th>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>RD$ Facturado</th>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>RD$ Recaudado</th>";
        echo "</tr>";
    $c=new ReportesGerencia();
    $registros=$c->RepGrandesClientes($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cont++;
        echo "<tr>";
        $cod_inm=utf8_decode(oci_result($registros,"CODIGO_INM"));
        $alias=utf8_decode(oci_result($registros,"ALIAS"));
        $nom_cli=utf8_decode(oci_result($registros,"NOMBRE_CLI"));
        $metros=utf8_decode(oci_result($registros,"METROS"));
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        if($alias == '') $alias = $nom_cli;
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$cod_inm</td>";
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$alias</td>";
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$metros</td>";
        echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$facturado</td>";
        $d=new ReportesGerencia();
        $registrosR=$d->RepGrandesClientesRecaudo($cod_inm, $periodo);
        while (oci_fetch($registrosR)) {
            $recaudado=utf8_decode(oci_result($registrosR,"IMPORTE"));
            echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$recaudado</td>";

        }
        echo "</tr>";
        $totalmetros += $metros;
        $totalfacturado += $facturado;
        $totalrecaudado += $recaudado;
    }oci_free_statement($registros);
    echo "</table>";
    /*echo "</td>";
    echo "</tr>";
    echo "</table>";/**/
    echo "<table  border='1' bordercolor='#CCCCCC'>";
    echo "<tr>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006666; text-align:center'>Totales</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$cont</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$totalmetros</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$totalfacturado</td>";
    echo "<td style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#990000; text-align:center'>$totalrecaudado</td>";
    echo "</tr>";
    echo "</table>";
}
?>