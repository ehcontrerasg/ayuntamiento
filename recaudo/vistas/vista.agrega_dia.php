<?php
session_start();
include '../clases/classPagos.php';
include '../../destruye_sesion.php';
$coduser = $_SESSION['codigo'];
$fecha=$_POST['fecha'];
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 		<script src="../../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
 		<link href="../../css/bootstrap.min.css" rel="stylesheet">
		<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
		<title>ACEASOFT</title>
	</head>
	
	
	<body>
		<div id="content">
		<?php
		if (isset($_REQUEST["procesar"])){
			$feriado = explode('-',$fecha);
			$agno = $feriado[0];
			$mes = $feriado[1];
			$dia = $feriado[2];
			$c = new Pagos();
			$bandera = $c->IngresaDiaFeriado($agno, $mes, $dia, $fecha, $coduser);
			if($bandera == false){
				$error=$c->getmsgresult();
				$coderror=$c->getcodresult();
				echo"
				<script type='text/javascript'>
					showDialog('Error Registrando el D&iacute;a','C&oacute;digo de error: $coderror <br>Mensaje: $error','error');
				</script>";
			}
			else if($bandera == true){
				echo "
				<script type='text/javascript'>
					showDialog('D&iacute; Registrado','Se Registro el dia feriado $fecha','success');
				</script>";
			}
		}
		?>
		<p>
		<form  id='agregadia' action="vista.agrega_dia.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary" style="border-color:#FF8000">
				<h3 class="panel-heading" style="background-color:#FF8000;border-color:#FF8000;font-size:18px;"><center>Agregar D&iacute;a Feriado</center> </h3>
				<div class="panel_mody" ><center></center></div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
						<p>	
						<div class="input-group input-group-sm">
			  				<span class="input-group-addon" width="200" >Seleccione D&iacute;a Feriado</span>
							<span class="input-group-addon">
							<input size='300' style='text-transform:uppercase' id='fecha' name='fecha' required type='date' class='form-control' value='<?php echo $fecha?>'  width='14' height='10'>
							</span>
						</div>
						</p>
					</div>
					<p>
				    	<center>
				    		<a>
				    		<input type="submit" id="procesar" name="procesar" value="Agregar" class="btn btn-primary btn-lg" style="background-color:#FF8000;border-color:#FF8000">
				    		</a>&nbsp;&nbsp; 
				    		<a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:#FF8000;border-color:#FF8000">
				    		Cancelar
				    		</a>
				    	</center>		
				    </p>		
				</div>
	    	</div>
    	</form>
		</p>
		</div>
	</body>
</html>
<script typ="text/javascript">
opener.top.jobFrame.location.reload()
</script>

 

