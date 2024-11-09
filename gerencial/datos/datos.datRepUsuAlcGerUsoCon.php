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

if($tipo=='UsuAlcGerUsoCon'){
	$periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=Usuarios_Alcantarillado_Gerencia_Uso_Concepto_".$proyecto."_".$periodo.".xls");
	//header("Pragma: no-cache");
	//header("Expires: 0");
	////////////////////TABLA USUARIOS ALCANTARILLADO POR GERENCIA USO CONCEPTO ESTE
	echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
	echo "<tr>";
	echo "<td colspan='3'style='border:1px solid #DEDEDE; align='center'><b>USUARIOS ALCANTARILLADO POR ZONA Y USO $periodo</b></td>";
	echo "</tr>";
	echo "</table>";
	echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
		echo "<tr>";
		echo "<td colspan='1'>";
			echo "<table  border='1' bordercolor='#CCCCCC'>";
			echo "<tr>";
			echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Zona</th>";
			echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Uso</th>";
			echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Usuarios</th>";
			echo "</tr>";
			$c=new ReportesGerencia();
			$registros=$c->UsuariosAlcGerenciaUsoConceptoPeriodoEste($proyecto, $periodo);
			echo "<tr>";
            if($proyecto=='SD'){
                echo "<th rowspan='8' style='border:none; border-right:1px solid #DEDEDE;  text-align:center;'>Este</th>";
            }else{
                echo "<th rowspan='8' style='border:none; border-right:1px solid #DEDEDE;  text-align:center;'><B>BOCA CHICA</B></th>";
            }

			while (oci_fetch($registros)) {
				$des_uso=utf8_decode(oci_result($registros,"USO"));
				$cantidad=utf8_decode(oci_result($registros,"CANTIDAD"));
				echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$des_uso</td>";
				echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
				echo "</tr>";
				$totalusuarioseste += $cantidad;
			}oci_free_statement($registros);
			echo "</table>";
		echo "</td>";	
		echo "</tr>";
	echo "</table>";

    if($proyecto=='SD'){
        echo "<table  border='1' bordercolor='#CCCCCC'>";
        echo "<tr>";
        echo "<td colspan='2' style='border-right:1px solid #DEDEDE;  text-align:center'>Total Este</td>";
        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$totalusuarioseste</td>";
        echo "</tr>";
        echo "</table>";
    }


	////////////////////TABLA USUARIOS ALCANTARILLADO POR GERENCIA USO CONCEPTO NORTE
	if($proyecto=='SD'){
        echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
        echo "<tr>";
        echo "<td colspan='1'>";
        echo "<table  border='1' bordercolor='#CCCCCC'>";
        echo "<tr>";
        echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Zona</th>";
        echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Uso</th>";
        echo "<th style='border-right:1px solid #DEDEDE;  text-align:center'>Usuarios</th>";
        echo "</tr>";
        $c=new ReportesGerencia();
        $registros=$c->UsuariosAlcGerenciaUsoConceptoPeriodoNorte($proyecto, $periodo);
        echo "<tr>";
        echo "<th rowspan='8' style='border:none; border-right:1px solid #DEDEDE;  text-align:center'>Norte</th>";
        while (oci_fetch($registros)) {
            $des_uso=utf8_decode(oci_result($registros,"USO"));
            $cantidad=utf8_decode(oci_result($registros,"CANTIDAD"));
            echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$des_uso</td>";
            echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$cantidad</td>";
            echo "</tr>";
            $totalusuariosnorte += $cantidad;
        }oci_free_statement($registros);
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

        echo "<table  border='1' bordercolor='#CCCCCC'>";
        echo "<tr>";
        echo "<td colspan='2' style='border-right:1px solid #DEDEDE;  text-align:center'>Total Norte</td>";
        echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'>$totalusuariosnorte</td>";
        echo "</tr>";
        echo "</table>";
    }
    $totalgeneral = $totalusuariosnorte + $totalusuarioseste;

	echo "<table  border='1' bordercolor='#CCCCCC'>";
		echo "<tr>";
			echo "<td colspan='2' style='border-right:1px solid #DEDEDE;  text-align:center'><b>Total General<b/></td>";
			echo "<td style='border-right:1px solid #DEDEDE;  text-align:center'><b>$totalgeneral<b/></td>";
		echo "</tr>";
	echo "</table>";
}
?>