<?php
session_start();
include '../clases/class.facturas.php';
$coduser = $_SESSION['codigo'];

	$codinmueble = $_GET["codinmueble"];
	

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
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<link href="../../css/tabber_procesofac.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../js/datInmueblesDif.js"></script>
	<link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
	<script type="text/javascript" src="../../js/sweetalert.min.js "></script>
	<style type="text/css">
		#compl_pdc{
            width: 540px;
            height: 170px;
			float:right;
        }
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
		.select{
			border:0px solid #ccc;
			font-family: Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:normal;
		}
		.btn-info{
			color:#fff;
			background-color:#5bc0de;
			border-color:#46b8da;
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
</head>
<body style="margin-top:-25px" >
	<div class="flexigrid" >
		<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:800px; font-family:'Times New Roman', Times, serif" align="center">
			Crear Acuerdo de Pago
		</h3>
		<?php
		if (isset($_REQUEST["procesar"])){
			$inmueble = $_POST["inmueble"];
			$valfinancia = $_POST["valfinancia"];
			$codfacturacion = $_POST["codfacturacion"];
			$numcuotas = $_POST["numcuotas"];
            $pagini= $_POST["pagIni"];
			//$codinmueble = $_POST["inmueble"];
			$f=new facturas();
			$respdc=$f->aplicaDiferido($inmueble,$valfinancia,$numcuotas,$coduser,$codfacturacion,$pagini);
			if($respdc==true)
			{
				?>
				<script type="text/javascript">
				swal({   
					title: "Acuerdo Generado",  
					text: "Se ha generado el Acuerdo de Pago para el inmueble No <?php echo $codinmueble?>.",   
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
				if($codinmueble == '') $mserror .= 'El codigo del inmueble no puede ser vacio.<br>';
				if($coduser == '') $mserror .= 'El usuario de sistema no puede ser vacio.<br>';
				if($valfinancia == '') $mserror .= 'El valor de financiacion no puede ser vacio.';
				?>
				<script type="text/javascript">
				swal({   
					title: "Error Generando Acuerdo",
					text: "No se generó el acuerdo de pago para el inmueble No <?php echo $codinmueble?>.\n Por favor comuniquese con el area de facturación.",   
					type: "error",   
					showConfirmButton: true,
					confirmButtonColor: "#DD6B55",  
					confirmButtonText: "Aceptar", 
					loseOnConfirm: false
				},
				function(){   
					window.close(this); 
				});
				</script>
				<?php
			}
		}
		else{
		if($codinmueble != '') $where = " AND F.INMUEBLE = '$codinmueble' GROUP BY I.ID_PROYECTO";
		if($codinmueble == '') $where = " AND F.INMUEBLE = '$inmueble' GROUP BY I.ID_PROYECTO";
		$l=new facturas();
		$registros=$l->totalfacven($where);
		while (oci_fetch($registros)) {
			$valfinancia = oci_result($registros, 'DEUDA');
			$codacueducto = oci_result($registros, 'ID_PROYECTO');
		}
		if($codacueducto == 'SD') $desacueducto = 'Santo Domingo';
		if($codacueducto == 'BC') $desacueducto = 'Boca Chica';
		if($codacueducto == ''){
			?>
			<script type="text/javascript">
				swal({   
					title: "Inmueble Sin Deuda",
					text: "El inmueble No <?php echo $codinmueble?> no tiene deuda pendiente para generar un acuerdo de pago.",   
					type: "warning",   
					showConfirmButton: true,
					confirmButtonColor: "#DD6B55",  
					confirmButtonText: "Aceptar", 
					loseOnConfirm: false
				},
				function(){   
					window.close(this); 
				});
			</script>
			<?php
		}
		?>
		<form  method="post">
		<div style="display: block;float: left">
			<table id="flexDeudaTotal" style="display:none">
			</table>
		
			<table cellpadding="0" cellspacing="0" align="center" width="40%">
				<tr>
					<td>
						<fieldset class="fieldset_diferido" style="width:290px"> 
							<table cellpadding="0" cellspacing="0" align="left" style="width:270px">
								<tr>
									<td width="110" align="right"><label>Acueducto:</label></td>
									<td width="45" align="right"><input class="input" type="text" name="codacueducto" value="<?php echo $codacueducto?>" size="2"/></td>
									<td width="120"><input class="input" type="text" name="desacueducto" value="<?php echo $desacueducto?>" size="15"/></td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>	
						<fieldset class="fieldset_diferido" style="width:290px">
							<legend><b>Conceptos</b></legend>
							<table align="left" cellpadding="0" cellspacing="0" >
								<tr>
								  <td width="110" align="right"><label>Diferido:</label></td>
									<td width="45" align="right"><input class="input" type="text" name="coddiferido" value="RD" size="2"/></td>
									<td width="120"><input class="input" type="text" name="desdiferido" value="Refinanciaci&oacute;n Deuda" size="15"/></td>
								</tr>
								<tr>
									<?php
									$codfacturacion='50';
									?>
									<td align="right"><label>Facturaci&oacute;n:</label></td>
									<td align="right"><input class="input" type="text" name="codfacturacion" value="<?php echo $codfacturacion?>" size="2"/></td>
									<td><input class="input" type="text" name="desfacturacion" value="Cargos Diferidos" size="15"/></td>
								</tr>
							</table>
						</fieldset>	
					</td>
				</tr>
				<tr>
					<td>
						<fieldset class="fieldset_diferido" style="width:290px">
							<legend><b>Datos Financiaci&oacute;n</b></legend>
							<table align="left" cellpadding="0" cellspacing="0" >
								<tr>
									<td width="110" align="right"><label>Valor a Financiar:</label></td>
									<td width="45" align="right">
									<input class="input" type="text" value="RD$" size="2" style="border:none; color:#FF0000; font-weight:bold" readonly/></td>
									<td width="120" align="left">
									<input class="input"type="text" name="valfinancia" id="valfinancia" value="<?php echo $valfinancia?>" size="15" onblur="calculacuota();" readonly style="border:none; color:#FF0000; font-weight:bold"/>
									</td>
								</tr>
								
								<tr>
									<td align="right"><label>Cuotas:</label></td>
									<td  align="right"><input class="input" type="text" size="2" style="border:none" readonly/></td> 
									<td align="left">
									<input class="input"  min="1" max="54" type="number" name="numcuotas" id="numcuotas" value="<?php echo $numcuotas?>" onblur="calculacuota();" style="border:none; color:#FF0000; font-weight:bold"  required/>
									</td>
								</tr>
								<tr>
									<td align="right"><label>Valor Cuota:</label></td>
									<td  align="right">
									<input class="input" type="text" value="RD$" size="2" style="border:none; color:#FF0000; font-weight:bold" readonly/></td>
									<td align="left">
									<input class="input" type="text" name="valcuotas" id="valcuotas"  value="<?php echo $valcuotas?>" size="15" readonly style="border:none; color:#FF0000; font-weight:bold"/>
									</td>
									
								</tr>

                                <tr>
                                    <td align="right"><label>Pago Inicial:</label></td>
                                    <td  align="right"><input class="input" value="RD$"  type="text" size="2" style="border:none" readonly/></td>
                                    <td align="left">
                                        <input class="input"  min="0"  type="number" name="pagIni" id="pagIni" value="<?php echo $pagini?>"  style="border:none; color:#FF0000; font-weight:bold" required />
                                    </td>
                                </tr>


							</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>	
						<fieldset class="fieldset_diferido" style="width:290px">
							<legend><b>Procesar Datos</b></legend>
							<table align="center" cellpadding="0" cellspacing="0" >
								<tr>
									<td align="center">
										<button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" type="submit" name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Generar Acuerdo de Pago</b>
										</button>
									</td>
								</tr>
							</table>
						</fieldset>	
					</td>
				</tr>
			</table>
		</div>	
		<input class="input"type="hidden" name="inmueble" id="inmueble" value="<?php echo $codinmueble?>" />
		<div style="display: block;float: left">
			<table id="flexyfacpendiente" style="display:none">
			</table>
		</div>	
	</div>
	<input type="hidden" id="inpdatfacinm" value="<?php echo $codinmueble;?>" />
	
	</form>
	<?php } ?>
	<script type="text/javascript">
		inicioInmueblesDiferido();
		flexydeudatotal()
		facpenddeuda();
	</script>
</body>
</html>