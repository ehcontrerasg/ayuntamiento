<?php
session_start();
include '../../clases/class.facturas.php';
$coduser = $_SESSION['codigo'];
if($_GET['codinmueble']){
	$codinmueble = $_GET["codinmueble"];
	$valfinancia = $_GET["valfinancia"];
	$codfacturacion = $_GET["codfacturacion"];
	$numcuotas = $_GET["numcuotas"];
	$coddiferido = $_GET["coddiferido"];
	$codacueducto = $_GET["proyecto"];
}
if($_POST['codinmueble']){
	$codinmueble = $_POST["codinmueble"];
	$valfinancia = $_POST["valfinancia"];
	$codfacturacion = $_POST["codfacturacion"];
	$numcuotas = $_POST["numcuotas"];
	$coddiferido = $_POST["coddiferido"];
	$codacueducto = $_POST["proyecto"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<script src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
	 <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
	<link href="../../css/tabber_procesofac.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../js/datInmueblesDif.js"></script>
	<link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
	<script type="text/javascript" src="../../js/sweetalert.min.js "></script>
	<style type="text/css">
	.table{
		border:1px solid #ccc;
		border-left:0px solid #ccc;
	}
	.th{
		background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
		height:24px;
		border:0px solid #ccc;
		border-left:1px solid #ccc;
		font-size:11px;
		font-weight:normal;
		font-family: Arial, Helvetica, sans-serif;
	}
	.tda{
		
		height:24px;
		border:0px solid #ccc;
		border-left:1px solid #ccc;
		padding:0px;
		font-size:11px;
		font-weight:normal;
		font-family: Arial, Helvetica, sans-serif;
	}
	.input{
		border:1px solid #ccc;
		font-family: Arial, Helvetica, sans-serif;
		font-size:11px;
		font-weight:normal;
	}
	.number{
		border:1px solid #ccc;
		font-family: Arial, Helvetica, sans-serif;
		font-size:11px;
		font-weight:normal;
		width:70px;
	}
	.select{
		border:0px solid #ccc;
		font-family: Arial, Helvetica, sans-serif;
		font-size:11px;
		font-weight:normal;
	}
	.btn-info{
		color:#fff;
		background-color:#A77B24;
		border-color:#A77B24;
	}
	.btn{
		display:inline-block;
		padding:6px 12px;
		margin-bottom:0;
		font-size:14px;
		font-weight:400;
		line-height:1.42857143;
		text-align:center;
		white-space:nowrap;
		vertical-align:middle;
		cursor:pointer;
		-webkit-user-select:none;
		-moz-user-select:none;
		-ms-user-select:none;
		user-select:none;
		background-image:none;
		border:1px solid transparent;
		border-radius:4px
	}
    </style>
	<script language="javascript">
	$(function() {
  		$("#coddiferido").autocomplete({
        	source: "./../datos/datos.coddiferido.php",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
            	$(".ui-autocomplete").css("z-index", 100);
            }
		});
	});
	
	function calculacuota(){
		var numero1,numero2, numero3;
		numero1 = document.getElementById('valfinancia').value;
	  	numero2 = document.getElementById('numcuotas').value;
	  	numero3 = parseFloat(numero1) / parseFloat(numero2);
	  	document.getElementById('valcuotas').value = Math.round(numero3);
	}
	
	function recarga() {
        document.formdif2.submit();
   	}
	</script>
</head>
<body style="margin-top:-25px" >
	<div id="content" style="padding:0px; width:470px; margin-left:0px; color:">
		<h3 class="panel-heading" style=" background-color:#A77B24; color:#FFFFFF; font-size:18px; width:470px; font-family:'Times New Roman', Times, serif" align="center">Crear Diferidos</h3>
	</div>
<?php
if (isset($_REQUEST["procesar"])){
	
	$f=new facturas();
	$respdc=$f->aplicaDiferido2($codinmueble,$valfinancia,$numcuotas,$coduser,$codfacturacion,$coddiferido);
	if($respdc==true)
	{
		?>
		<script type="text/javascript">
		swal({   
			title: "Diferido Generado Satisfactoriamente",   
			type: "success",   
			showConfirmButton: true,
			confirmButtonText: "Aceptar", 
		 	loseOnConfirm: false
		},
		function(){
			window.close(this);
		});
		opener.document.location.reload();
		</script>
		<?php 
	}
	else{
		?>
		<script type="text/javascript">
		/*swal({
			title: "Error Generando Diferido",
			text: "No se generó el diferido para el inmueble No <?php echo $codinmueble?>.\n Por favor comuniquese con el area de facturación.",   
			type: "error",   
			showConfirmButton: true,
		 	confirmButtonColor: "#DD6B55",  
			confirmButtonText: "Aceptar", 
		 	loseOnConfirm: false
		},
		function(){   
			window.close(this); 
		});*/
		</script>
		<?php
		
	}
}
else{
$l=new facturas();
$registros=$l->obtieneProyecto($codinmueble);
while (oci_fetch($registros)) {
	$codacueducto = oci_result($registros, 'ID_PROYECTO');
}
if($codacueducto == 'SD') $desacueducto = 'Santo Domingo';
if($codacueducto == 'BC') $desacueducto = 'Boca Chica';

?>
 <form name="formdif2" action="" method="get">
	<div style="display: block;float: left">
		<table id="flex1" style="display:none">
		</table>
		<table cellpadding="0" cellspacing="0" align="left" width="40%">
			<tr>
				<td>
					<fieldset class="fieldset_diferido" style="color: #A77B24;width:450px">
						<table cellpadding="0" cellspacing="0" align="center" style="width:270px">
							<tr>
								<td width="110" align="right"><label>Acueducto:</label></td>
								<td width="45" align="right">
									<input class="input" type="text" name="codacueducto" value="<?php echo $codacueducto?>" size="2" readonly/></td>
								<td width="120">
									<input class="input" type="text" name="desacueducto" value="<?php echo $desacueducto?>" size="15" readonly/></td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>	
					<fieldset class="fieldset_diferido" style="color: #A77B24;width:450px">
						<legend><b>Conceptos</b></legend>
						<table align="center" cellpadding="0" cellspacing="0" >
							<tr>
						  	  <td width="110" align="right"><label>Diferido:</label></td>
							  	<td width="45" align="right">
									<input class="input" type="text" id="coddiferido" name="coddiferido" value="<?php echo $coddiferido?>" size="2" required onblur="recarga();"/>
								</td>
								<?php
								if($coddiferido != ''){
								$a=new facturas();
									$valores=$a->datosConceptos($coddiferido);
									while (oci_fetch($valores)) {
										$desdiferido = oci_result($valores, 'DESCRIPCION');
										$codfacturacion = oci_result($valores, 'COD_SERVICIO');
										$desfacturacion = oci_result($valores, 'DESC_SERVICIO');
										$maxcuotas = oci_result($valores, 'LIMITE_MAX_CUOTAS');
									}
								}
								?>
								<td align="right">
									<input class="input" type="text" id="desdiferido" name="desdiferido" value="<?php echo $desdiferido?>" size="15" readonly />
								</td>
							</tr>
							<tr>
								<td align="right"><label>Facturaci&oacute;n:</label></td>
								<td align="right">
									<input class="input" type="text" id="codfacturacion" name="codfacturacion" value="<?php echo $codfacturacion?>" size="2" readonly/></td>
								<td align="right">
									<input class="input" type="text" id="desfacturacion" name="desfacturacion" value="<?php echo $desfacturacion?>" size="15" readonly />
								</td>
							</tr>
		  				</table>
					</fieldset>	
				</td>
			</tr>
			<tr>
				<td>
					<fieldset class="fieldset_diferido" style="color: #A77B24;width:450px">
						<legend><b>Datos Financiaci&oacute;n</b></legend>
						<table align="center" cellpadding="0" cellspacing="0" >
							<tr>
						  	  	<td width="110" align="right"><label>Valor a Financiar:</label></td>
								<td width="45" align="right">
							  	<input class="input" type="text" value="RD$" size="2" style="border:none; color:#FF0000; font-weight:bold" readonly/></td>
								<td width="120" align="left">
								<input class="input"type="text" name="valfinancia" id="valfinancia" value="<?php echo $valfinancia?>" size="15" onblur="calculacuota();" required style="color:#FF0000; font-weight:bold"/>
						  	  	</td>
							</tr>
							
							<tr>
								<td align="right"><label>Cuotas:</label></td>
							  	<td  align="right"><input class="input" type="text" size="2" style="border:none" readonly/></td> 
								<td align="left">
								<input class="number"  min="1" max="<?php echo $maxcuotas?>" type="number" name="numcuotas" id="numcuotas" value="<?php echo $numcuotas?>" onblur="calculacuota();" style="color:#FF0000; font-weight:bold"  required/>
			  				  	</td>
							</tr>
							<tr>
								<td align="right"><label>Valor Cuota:</label></td>
								<td  align="right">
							  	<input class="input" type="text" value="RD$" size="2" style="border:none; color:#FF0000; font-weight:bold" readonly/></td>
								<td align="left">
								<input class="input" type="text" name="valcuotas" id="valcuotas"  value="<?php echo $valcuotas?>" size="15" readonly style="color:#FF0000; font-weight:bold"/>
								</td>
								
							</tr>
	    				</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>	
					<fieldset class="fieldset_diferido" style="color: #A77B24; width:450px">
						<legend><b>Procesar Datos</b></legend>
						<table align="center" cellpadding="0" cellspacing="0" >
							<tr>
							<?php
							if($coddiferido == '') $disabled = 'disabled';
							?>
							  	<td align="center">
									<button  <?php echo $disabled?> style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#A77B24; color:#A77B24" type="submit" name="procesar"
									id="procesar"class="btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Generar Diferido</b>
									</button>
								</td>
							</tr>
		  				</table>
					</fieldset>	
				</td>
			</tr>
		</table>
	</div>
	<?php  }?>
<input type="hidden" name="codinmueble" value="<?php echo $codinmueble?>" />
</form>
</body>
</html>
