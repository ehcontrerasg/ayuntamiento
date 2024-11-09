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

if($tipo=='UniGerUsoCon'){
	$periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
    $des_uso = $_POST['des_uso'];
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=Unidades_Gerencia_Uso_Concepto_".$proyecto."_".$periodo. time(). ".xls");
	//header("Pragma: no-cache");
	//header("Expires: 0");
	////////////////////TABLA UNIDADES POR GERENCIA USO CONCEPTO ESTE
	echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
	echo "<tr>";
	echo "<td colspan='5'style='border:1px solid #DEDEDE; align='center'><b>TOTAL DE UNIDADES USUARIOS ACTIVOS POR GERENCIA, USO Y CONCEPTO $periodo</b></td>";
	echo "</tr>";
	echo "</table>";
	echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
		echo "<tr>";
		echo "<td colspan='1'>";
			echo "<table  border='1' bordercolor='#CCCCCC'>";
			echo "<tr>";
			echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Zona</th>";
			echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Uso</th>";
			echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Agua</th>";
            echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Agua Pozo</th>";
            echo "<th style='border-right:1px solid #DEDEDE;  text-align:center' >Alcantarillado</th>";
			echo "</tr>";
            echo "<tr>";
            if($proyecto=='BC'){
                echo "<th rowspan='8' style='border:none; border-right:1px solid #DEDEDE;  text-align:center'>BOCA CHICA</th>";
            }
            else{
                echo "<th rowspan='8' style='border:none; border-right:1px solid #DEDEDE;  text-align:center'>Este</th>";
            }


            $h=new ReportesGerencia();
            $registrosh=$h->UnidadesGerenciaUsoConceptoPeriodoUsos($proyecto, $periodo);
            while (oci_fetch($registrosh)) {
                $des_uso = utf8_decode(oci_result($registrosh, "USO"));
                echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$des_uso</td>";

                $c = new ReportesGerencia();
                $registros = $c->UnidadesGerenciaUsoConceptoPeriodoEsteAgua($proyecto, $periodo, $des_uso);
                while (oci_fetch($registros)) {
                    $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
                    if($cantidad == null){
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>0</td>";
                    }
                    echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
                    $totalaguaeste += $cantidad;
                }oci_free_statement($registros);

                $c = new ReportesGerencia();
                $registros = $c->UnidadesGerenciaUsoConceptoPeriodoEstePozo($proyecto, $periodo, $des_uso);
                while (oci_fetch($registros)) {
                    $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
                    if($cantidad == null){
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>0</td>";
                    }
                    echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
                    $totalpozoeste += $cantidad;
                }oci_free_statement($registros);

                $c = new ReportesGerencia();
                $registros = $c->UnidadesGerenciaUsoConceptoPeriodoEsteAlc($proyecto, $periodo, $des_uso);
                while (oci_fetch($registros)) {
                    $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
                    if($cantidad == null){
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>0</td>";
                    }
                    echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
                    $totalalceste += $cantidad;
                }oci_free_statement($registros);
                echo "</tr>";
            }oci_free_statement($registrosh);
                echo "</table>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "<table  border='1' bordercolor='#CCCCCC'>";
                echo "<tr>";
                echo "<td colspan='2' style='border-right:1px solid #DEDEDE;  text-align:center'><b>Totales</b></td>";
                echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'><b>$totalaguaeste</b></td>";
                echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'><b>$totalpozoeste</b></td>";
                echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'><b>$totalalceste</b></td>";
                echo "</tr>";
                echo "</table>";

                if($periodo=='SD'){
                    ////////////////////TABLA UNIDADES POR GERENCIA USO CONCEPTO NORTE
                    echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
                    echo "<tr>";
                    echo "<td colspan='1'>";
                    echo "<table  border='1' bordercolor='#CCCCCC'>";
                    echo "<tr>";
                    echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Zona</th>";
                    echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Uso</th>";
                    echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Agua</th>";
                    echo "</tr>";
                    $c = new ReportesGerencia();
                    $registros = $c->UnidadesGerenciaUsoConceptoPeriodoNorteAgua($proyecto, $periodo);
                    echo "<tr>";
                    echo "<th rowspan='8' style='border:none; border-right:1px solid #DEDEDE;  text-align:center'>Norte</th>";
                    while (oci_fetch($registros)) {
                        $des_uso = utf8_decode(oci_result($registros, "USO"));
                        $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$des_uso</td>";
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
                        echo "</tr>";
                        $totalaguanorte += $cantidad;
                    }
                    oci_free_statement($registros);
                    echo "</table>";
                    echo "</td>";
                    echo "<td colspan='1'>";
                    echo "<table border='1' bordercolor='#CCCCCC'>";
                    echo "<tr>";
                    echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Agua Pozo</th>";
                    echo "</tr>";
                    $c = new ReportesGerencia();
                    $registros = $c->UnidadesGerenciaUsoConceptoPeriodoNortePozo($proyecto, $periodo);
                    while (oci_fetch($registros)) {
                        echo "<tr>";
                        $des_uso = utf8_decode(oci_result($registros, "USO"));
                        $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
                        echo "</tr>";
                        $totalpozonorte += $cantidad;
                    }
                    oci_free_statement($registros);
                    echo "</table>";
                    echo "</td>";
                    echo "<td colspan='1'>";
                    echo "<table border='1' bordercolor='#CCCCCC'>";
                    echo "<tr>";
                    echo "<th style='border-right:1px solid #DEDEDE;  text-align:center' >Alcantarillado</th>";
                    $c = new ReportesGerencia();
                    $registros = $c->UnidadesGerenciaUsoConceptoPeriodoNorteAlc($proyecto, $periodo);
                    while (oci_fetch($registros)) {
                        echo "<tr>";
                        $des_uso = utf8_decode(oci_result($registros, "USO"));
                        $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
                        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
                        echo "</tr>";
                        $totalalcnorte += $cantidad;
                    }
                    oci_free_statement($registros);
                    echo "</table>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                    echo "<table  border='1' bordercolor='#CCCCCC'>";
                    echo "<tr>";
                    echo "<td colspan='2' style='border-right:1px solid #DEDEDE; text-align:center'>Totales</td>";
                    echo "<td style='border-right:1px solid #DEDEDE; text-align:center'>$totalaguanorte</td>";
                    echo "<td style='border-right:1px solid #DEDEDE; text-align:center'>$totalpozonorte</td>";
                    echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$totalalcnorte</td>";
                    echo "</tr>";
                    echo "</table>";
                }


}
?>