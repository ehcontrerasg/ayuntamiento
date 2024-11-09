<?php
session_start();
include '../clases/class.inmuebles.php';
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$codinmueble = $_GET['codinmueble'];
$proyecto = $_GET['proyecto'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Consulta General de Inmuebles</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
	<link href="../../css/tabber_catastro.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php
	if($codinmueble != ""){
		$where = " AND I.CODIGO_INM = '$codinmueble'";
		$l=new inmuebles();
		$registros = $l->datosInmueble($where);
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
	<form name="datinm" action="vista.datos_inmueble.php" method="get">
		<div>
		<fieldset class="fieldset_catastro">
		<legend class="legend_catastro"><b>Inmueble N&deg;: <?php echo $codinmueble;?></b></legend>
		<table width="100%">
			<tr>
				<td width="5%" align="right" class="td_catastro">Zona:&nbsp;</td>
				<td width="4%" align="left" class="td_catastro">
	  		  <input class="input" type="text" readonly size="3" value="<?php echo $zona_inm?>" /></td>
				<td width="8%" align="right" class="td_catastro">Direcci&oacute;n:&nbsp;</td>
				<td width="19%" align="left" class="td_catastro">
	  		  <input class="input" type="text" readonly size="35" value="<?php echo $urba_inm.' ' .$dir_inm?>" /></td>
				<td width="9%" align="right" class="td_catastro">Fecha Alta:&nbsp;</td>
				<td width="11%" align="left" class="td_catastro">
	  		  <input class="input" type="text" readonly size="15" value="<?php echo $fecalta_inm?>" /></td>
				<td width="7%" align="right" class="td_catastro">Catastro:&nbsp;</td>
				<td width="13%" align="left" class="td_catastro">
	  		  <input class="input" type="text" readonly size="20" value="<?php echo $catastro_inm?>" /></td>
				<td width="6%" align="right" class="td_catastro">Proceso:&nbsp;</td>
				<td width="8%" align="left" class="td_catastro">
	  		  <input class="input" type="text" readonly size="15" value="<?php echo $proceso_inm?>" /></td>
				<td width="5%" align="right" class="td_catastro">Estado:&nbsp;</td>
				<td width="5%" align="left" class="td_catastro">
	  		  <input class="input" type="text" readonly size="3" value="<?php echo $estado_inm?>" /></td>
			</tr>
			<tr>
				<td class="td_catastro" align="right">Cliente:&nbsp;</td>
				<td class="td_catastro" align="left">
					<input class="input" type="text" readonly size="8" value="<?php echo $cod_cli?>" /></td>
				<td class="td_catastro" align="right">Nombre:&nbsp;</td>
				<td class="td_catastro" align="left">
					<input class="input" type="text" readonly size="25" value="<?php echo $nom_cli?>" /></td>
				<td class="td_catastro" align="right">Contrato:&nbsp;</td>
				<td class="td_catastro" align="left">
					<input class="input" type="text" readonly size="15" value="<?php echo $con_cli?>" /></td>
				<td class="td_catastro" align="right">Documento:&nbsp;</td>
				<td class="td_catastro" align="left">
					<input class="input" type="text" readonly size="15" value="<?php echo $doc_cli?>" /></td>
				<td class="td_catastro" align="right">Tel&eacute;fono:&nbsp;</td>
				<td class="td_catastro" align="left">
					<input class="input" type="text" readonly size="15" value="<?php echo $tel_cli?>" /></td>
			</tr>
		</table>	
		</fieldset>
		</div>
		<div class="tabber" id="tab" style="width:1252px;">
		<?php
			if($serial != ""){
			?>
			<div class="tabbertab" title="Medidor">
				<?php
				include('vista.DatosMedidor.php');
				?>
			</div>
			<div class="tabbertab" title="Lecturas">
				<?php 
				include('vista.DatosLectura.php'); 
				?>
			</div>
			<?php
			}
			?>
			<div class="tabbertab" title="Contratos">
			<?php 
				include('vista.DatosContratos.php'); 
			?>
			</div>
			<div class="tabbertab" title="Servicios">
			<?php 
				include('vista.DatosServicios.php'); 
			?>
			</div>
			<div class="tabbertab" title="Facturas">
			<?php

				include('vista.facturas.php');
			?>
			</div>
			<div class="tabbertab" title="Cortes">
			<?php

				//include('vista.facturas.php');
			?>
			</div>

            <div class="tabbertab" title="Fotos">
                <?php

                 include_once('vista.fotos.php');
                ?>
            </div>
		</div>
		</form>
    <?php
	}
	?>
	
</body>
</html>