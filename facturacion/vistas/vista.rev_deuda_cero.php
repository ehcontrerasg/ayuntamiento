<?php

$codinmueble = $_GET["codinmueble"];
$servicios = $_GET["servicios"];
$total = $_GET["total"];
$mora = $_GET["mora"];
$cuotas = $_GET["cuotas"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
   	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
        iframe{
            border-color: #666666;
            border:solid;
            border-width:0px ;
            display: block;
            float: left;
            width: 530px;
            height: 60px;
        }
		
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
		border:0px solid #ccc;
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
<body>
<script language="javascript">
	function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
</script>
<div id="content" class="flexigrid" >
<?php
if (isset($_REQUEST["reversar"])){
	include '../clases/class.deuda_cero.php';
		
	$f=new deuda_cero();
	$verpdc=$f->verifica_PDC($codinmueble);
	if($verpdc=='')
	{
		?>
		<meta http-equiv="Refresh" content="5 ; URL=vista.consulta.php">
		<?php
		$mserror .= 'El inmueble no cuenta con un PDC activo.<br>';
		echo "
		<script type='text/javascript'>
		showDialog('Error Reversando PDC','El inmueble N&deg; $codinmueble no cumple con los requisitos para reversar el PDC.<br> $mserror<br><br> Por favor comuniquese con el &aacute;rea de facturaci&oacute;n.<br>','error',6);
		</script>";
	}
	else{
		$respdc=$f->reversa_PDC($codinmueble);
		if($respdc==true)
		{
			?>
			<meta http-equiv="Refresh" content="5 ; URL=vista.consulta.php">
			<?php echo "
			<script type='text/javascript'>
			showDialog('PDC Reversado Satisfactoriamente','Se ha reversado el PDC para el inmueble $codinmueble','success',6);
			</script>";
		}
		else{
			if($codinmueble == '') $mserror .= 'El codigo del inmueble no puede ser vacio.<br>';
			if($mora == '') $mserror .= 'El valor de la mora no puede ser vacio.<br>';
			if($servicios == '') $mserror .= 'El valor de los servicios no puede ser vacio.';
			?>
			<meta http-equiv="Refresh" content="5 ; URL=vista.consulta.php">
			<?php
			echo "
			<script type='text/javascript'>
			showDialog('Error Reversando PDC','El inmueble N&deg; $codinmueble no cumple con los requisitos para reversar el PDC.<br> $mserror<br><br> Por favor comuniquese con el &aacute;rea de facturaci&oacute;n.<br>','error',6);
			</script>";
		}
	}
}
	?>
	<table cellpadding="0" cellspacing="0" width="54.8%" align="center">
		<tr>
			<td align="center" colspan="4">
			<button type="submit" name="reversar" id="reversar" 
			style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO">
			<i class="fa fa-download"></i><b>&nbsp;&nbsp;Reversar PDC</b></button></td>
		</tr>
	</table>
	<input type="hidden" name="codinmueble" value="<?php echo $codinmueble?>" />
	<input type="hidden" name="servicios" value="<?php echo $servicios?>" />
	<input type="hidden" name="mora" value="<?php echo $mora?>" />
	<input type="hidden" name="total" value="<?php echo $total?>" />
	<input type="hidden" name="cuotas" value="<?php echo $cuotas?>" />
	<?php
//}
?>
</div>
</body>
</html>