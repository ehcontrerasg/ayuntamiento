<?php
session_start();
include '../clases/class.deuda_cero.php';
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$codinmueble = $_GET['codinmueble'];
$proyecto = $_GET['proyecto'];
$nom_cli = $_GET['nom_cli'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Proceso de Facturación</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
	<link href="../../css/tabber_procesofac.css" rel="stylesheet" type="text/css">
	
</head>
<body leftmargin="0">
	<div id="content">
   
	<?php
	$l=new deuda_cero();
	if($codinmueble != ""){
		//$where = " AND I.CODIGO_INM = '$codinmueble'";
		$registros = $l->datosInmueble($codinmueble);
		while (oci_fetch($registros)) {
    		$zona_inm=oci_result($registros, 'ID_ZONA');
			$urba_inm=oci_result($registros, 'DESC_URBANIZACION');
			$dir_inm=oci_result($registros, 'DIRECCION');
			$fecalta_inm=oci_result($registros, 'FEC_ALTA');
			$estado_inm=oci_result($registros, 'ID_ESTADO');
			$catastro_inm=oci_result($registros, 'CATASTRO');
			$proceso_inm=oci_result($registros, 'ID_PROCESO');
			$cod_cli=oci_result($registros, 'CODIGO_CLI');
			$con_cli=oci_result($registros, 'ID_CONTRATO');
			$nom_cli=oci_result($registros, 'ALIAS');
			$doc_cli=oci_result($registros, 'DOCUMENTO');
			$tel_cli=oci_result($registros, 'TELEFONO');
			$serial=oci_result($registros, 'SERIAL');
		}
	?>
	<form name="datinm" action="vista.creadeudacero.php" method="get">
	 
		<fieldset class="fieldset_diferido">
		<legend class="legend_fact"><b>Inmueble N&deg;: <?php echo $codinmueble;?></b></legend>
		<table>
			<tr>
				<td align="right" class="td">Zona:&nbsp;</td>
				<td align="left" class="td">
	  		  <input class="input_diferido" type="text" readonly size="3" value="<?php echo $zona_inm?>" /></td>
				<td align="right" class="td">Direcci&oacute;n:&nbsp;</td>
				<td align="left" class="td">
	  		  <input class="input_diferido" type="text" readonly size="35" value="<?php echo $urba_inm.' ' .$dir_inm?>" /></td>
				<td align="right" class="td">Fecha Alta:&nbsp;</td>
				<td align="left" class="td">
	  		  <input class="input_diferido" type="text" readonly size="15" value="<?php echo $fecalta_inm?>" /></td>
				<td align="right" class="td">Catastro:&nbsp;</td>
				<td align="left" class="td">
	  		  <input class="input_diferido" type="text" readonly size="20" value="<?php echo $catastro_inm?>" /></td>
				<td align="right" class="td">Proceso:&nbsp;</td>
				<td align="left" class="td">
	  		  <input class="input_diferido" type="text" readonly size="15" value="<?php echo $proceso_inm?>" /></td>
			</tr>
			<tr>
				<td class="td" align="right">Cliente:&nbsp;</td>
				<td class="td" align="left">
					<input class="input_diferido" type="text" readonly size="8" value="<?php echo $cod_cli?>" /></td>
				<td class="td" align="right">Nombre:&nbsp;</td>
				<td class="td" align="left">
					<input class="input_diferido" type="text" readonly size="35" value="<?php echo $nom_cli?>" /></td>
				<td class="td" align="right">Documento:&nbsp;</td>
				<td class="td" align="left">
					<input class="input_diferido" type="text" readonly size="15" value="<?php echo $doc_cli?>" /></td>
				<td class="td" align="right">Tel&eacute;fono:&nbsp;</td>
				<td class="td" align="left">
					<input class="input_diferido" type="text" readonly size="15" value="<?php echo $tel_cli?>" /></td>
				<td align="right" class="td">Estado:&nbsp;</td>
				<td align="left" class="td">
	  		  		<input class="input_diferido" type="text" readonly size="3" value="<?php echo $estado_inm?>" /></td>
			</tr>
		</table>	
		</fieldset>
		</form>
		
		<div class="tabber" id="tab" style="width:1070px;">
			<div class="tabbertab" title="Deuda Cero">
				<form name="formrev_pdc" action="vista.rev_deuda_cero.php" method="get">
				<?php
				include('vista.rev_deuda_cero.php');
				?>
				</form>
			</div>
			<!--div class="tabbertab" title="Diferido">
				<?php 
				//include('vista.DatosLectura.php'); 
				?>
			</div>
			<div class="tabbertab" title="Mora">
			<?php 
				//include('vista.DatosServicios.php'); 
			?>
			</div>
			<div class="tabbertab" title="Reliquidaci&oacute;n">
			<?php 
				//include('vista.facturas.php'); 
			?>
			</div-->
		</div>
		
    <?php
	}
	?>
	</div>
</body>
</html>