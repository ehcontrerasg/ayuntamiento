<?php
session_start();
include '../clases/class.notas_creditos.php';
include '../../destruye_sesion_cierra.php';

$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];
$fechain = $_POST['fechain'];
$fechafn = $_POST['fechafn'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
<link rel="stylesheet" href="../../flexigrid/style.css" />
<link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
<link rel="stylesheet" href="../../css/tablas_catastro.css">
<link href="../../css/bootstrap.min.css" rel="stylesheet">
<link href="../../css/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
<script src="../../js/jquery.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
<style type="text/css">
.input{
	border:1px solid #ccc;
    font-family: Arial, Helvetica, sans-serif;
    font-size:11px;
	height:16px;
    font-weight:normal;
}
</style>

</head>
<body>
<form name="notascreditos" action="vista.reporte_conceptosNC.php" method="post" >
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Conceptos de Notas Creditos</h3>
<div class="flexigrid" style="width:1120px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Notas Creditos</div>
        <div style="background-color:rgb(255,255,255)">
        	<table width="100%">
    			<tr>
    				<td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
						<select name="proyecto" class="input" required><option></option>
						<?php
						$l=new Notas_Credito();
						$registros=$l->seleccionaAcueducto();
						while (oci_fetch($registros)) {
							$cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;	
							$sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;	
							if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
							else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
						}oci_free_statement($registros);
						?>									
						</select>
       		  	  	</td>
					<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Inicial<br />
						<input type="date" name="fechain" id="fechain" value="<?php echo $fechain;?>" class="input" style="width:95px" required min="190001" max="205012"/>
       		  	  	</td>
                           <td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Final<br />
						<input type="date" name="fechafn" id="fechafn" value="<?php echo $fechafn;?>" class="input" style="width:95px" required min="190001" max="205012"/>
       		  	  	</td>
				  	<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">
                       	<input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#336699; color:#336699;">
                   	</td>
				</tr>
        	</table>
        </div>
    </div>
</div>
</form>
<?php
if(isset($_REQUEST["Generar"])){
?>
<div class="flexigrid" style="width:1120px">
	<div class="mDiv">
    	<div>Total De Tipos De NCF</div>
    </div>
</div>
<div class="datagrid" style="width:auto; height:125px; border:none; float:left">
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:125px">
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Tipo de NFC</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Valor</th>
		</tr>
		<?php
		$item = 0;
		$c=new Notas_Credito();
		$registros=$c->totalNc($proyecto, $fechain, $fechafn);
		while (oci_fetch($registros)) {
			$item ++;
			$ncf = oci_result($registros, 'NCF');
			$valor = oci_result($registros, 'VALOR');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$ncf</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$valor</td>";
			echo "</tr>";
			$totalval += $valor;
		}oci_free_statement($registros);
		echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalval</b></td>";
		echo "</tr>";
		?>
</table>
</div>
<div class="datagrid" style="width:auto; height:125px; border:none; float:right">
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:125px">
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Tipo de NFC</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Valor</th>
		</tr>
		<?php
		$item = 0;
		$c=new Notas_Credito();
		$registros=$c->totalNd($proyecto, $fechain, $fechafn);
		while (oci_fetch($registros)) {
			$item ++;
			$ncf = oci_result($registros, 'NCF');
			$valor = oci_result($registros, 'VALOR');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$ncf</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$valor</td>";
			echo "</tr>";
			$totalvalss += $valor;
		}oci_free_statement($registros);
		echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalvalss</b></td>";
		echo "</tr>";
		?>
</table>
</div>
<div class="flexigrid" style="width:1120px">
	<div class="mDiv">
    	<div>Conceptos De Notas Creditos</div>
    </div>
</div>
<div class="datagrid" style="width:auto; height:350px; border:none; float:left">
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Conceptos</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Valor</th>
		</tr>
		<?php
		$item = 0;
		$c=new Notas_Credito();
		$registros=$c->ConceptosNc($proyecto, $fechain, $fechafn);
		while (oci_fetch($registros)) {
			$item ++;
			$concepto = oci_result($registros, 'CONCEPTO');
			$valores = oci_result($registros, 'VALOR');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$concepto</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$valores</td>";
			echo "</tr>";
			$totalvals += $valores;
		}oci_free_statement($registros);
		echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalvals</b></td>";
		echo "</tr>";
		?>
</table>
</div>
<div class="datagrid" style="width:auto; height:350px; border:none; float:right">
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Conceptos</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Valor</th>
		</tr>
		<?php
		$item = 0;
		$c=new Notas_Credito();
		$registros=$c->ConceptosNd($proyecto, $fechain, $fechafn);
		while (oci_fetch($registros)) {
			$item ++;
			$concepto = oci_result($registros, 'CONCEPTO');
			$valores = oci_result($registros, 'VALOR');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$concepto</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$valores</td>";
			echo "</tr>";
			$totalvalsx += $valores;
		}oci_free_statement($registros);
		echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalvalsx</b></td>";
		echo "</tr>";
		?>
</table>
</div>
<?php
}
?>
</body>
</html>

