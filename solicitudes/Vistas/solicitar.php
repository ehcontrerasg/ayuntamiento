
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link type="image/ico" href="../images/favicon.ico" rel="icon">
    <title>Aprobacion de Solicitudes</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css?<? echo time();?>">
    <link rel="stylesheet" type="text/css" href="../css/reportes.css?<? echo time();?>" />
    <link rel="stylesheet" href="../css/sweetalert.css">
</head>
<body>

	<!-- Modal de sesión -->
	<?php include_once('./modal-sesion.php'); ?>
    <!-- Fin de modal de sesión -->

	<div class="container tab-pane fade in active" id="ctnSolicitudesTI">
	<div class="row">

	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
			<section id="sctReclamar">
    			<form class="form-horizontal" id="genSolicitudTI" enctype="multipart/form-data">
	    			<div class="form-group" align="center">
	    				<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
	    					<div class="form-group">
			    				<label for="sltMenu" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Menu:</label>
			    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			    					<select class="form-control" id="sltMenu" name="sltMenu" required>
			    						<option>Seleccionar</option>
			    					</select>
			    				</div>
			    			</div>
	    				</div>
	    				<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
	    					<div class="form-group">
			    				<label for="sltPantalla" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Pantalla</label>
			    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			    					<select class="form-control" id="sltPantalla" name="sltPantalla">
			    					</select>
			    				</div>
			    			</div>
	    				</div>
	    			</div>
	    			<div class="form-group">
	    				<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3">Tipo de requerimiento:</label>
	    				<div class="col-sm-offset-1 col-xs-11 col-sm-11 col-md-11 col-lg-11">
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="1" required> Falla Proceso
									</label>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="2"> Falla Reporte
									</label>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="3"> Falla Pantalla
									</label>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="5" > Mejoras de Proceso
									</label>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="6"> Mejoramiento de Reporte
									</label>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="7"> Mejoramiento de Pantalla
									</label>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="8"> Nuevo Reporte
									</label>
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label class="radio-inline">
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="4"> Nueva Pantalla
									</label>
								</div>
							</div>
	    				</div>
	    			</div>
	    			<br>
	    			<div class="form-group">
	    				<label for="obsRequerimiento" class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3">Observación del requerimiento:</label>
	    				<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
	    					<textarea name="obsRequerimiento" id="obsRequerimiento" rows="12" ></textarea>
	    				</div>
	    			</div>
	    			<br>
					<div class="form-group row" id="dvArchivos">
						<label class="col-sm-12" for="fleAdjunto">Archivo</label>
						<input class="col-sm-12" type="file" name="archivos[]" id="fleAdjunto" multiple accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.jpg, .jpeg, .png,application/pdf,application/vnd.ms-excel.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/plain">
					</div>
	    			<div class="form-group" align="center">
    					<button type="submit" class="btn btn-success" id="btnEnvReclamo">Enviar</button>
    					<button type="button" class="btn btn-danger" id="btnCanReclamo">Cancelar</button>
	    			</div>
	    		</form>
	    	</section>
		</div>
	</div>
</div>

</body>

    <script src="../js/lib/jquery/jquery-3.1.1.min.js"></script>
    <script src="../js/lib/jquery/bootstrap.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../../js/fnLogin.js"></script>

	<script src="../js/solicitarJs.js?<?echo time();?>"></script>

</html>
