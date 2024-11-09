<div class="container tab-pane fade in active" id="ctnSolicitudesTI" ng-if="showPtlRealSolTI">
	<div class="row">
		<div class="col-xs-12 col-sm-11 col-md-11 col-lg-11"
			 ng-if="msgSuccess">
			<div class="alert alert-success alert-dismissible fade in" role="alert">
				 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p>La <b>solicitud</b> se genero correctamente!</b></p>
			</div>
		</div>
		<div class="col-xs-12 col-sm-11 col-md-11 col-lg-11"
			 ng-if="msgDanger">
			<div class="alert alert-danger alert-dismissible fade in" role="alert">
				 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p>La <b>solicitud</b> no se pudo generar por la siguiente razon: {{msgErrSOLTI}} </b></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
			<section id="sctReclamar">
    			<form class="form-horizontal" id="genSolicitudTI">
	    			<div class="form-group" align="center">
	    				<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
	    					<div class="form-group">
			    				<label for="sltMenu" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Menu:</label>
			    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			    					<select class="form-control" id="sltMenu" name="sltMenu" required>
			    					</select>
			    				</div>
			    			</div>
	    				</div>
	    				<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
	    					<div class="form-group">
			    				<label for="sltPantalla" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Pantalla</label>
			    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			    					<select class="form-control" id="sltPantalla" name="sltPantalla" required>
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
									  	<input type="radio" class="optTipRequermiento" name="optTipRequermiento" value="3" nto"> Falla Pantalla
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
	    				<label for="obsRequerimiento" class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3">Observacion del requerimiento:</label>
	    				<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
	    					<textarea name="obsRequerimiento" id="obsRequerimiento" rows="12" ></textarea>
	    				</div>
	    			</div>
	    			<br>
	    			<div class="form-group" align="center">
    					<button type="submit" class="btn btn-success" id="btnEnvReclamo">Enviar</button>
    					<button class="btn btn-danger" id="btnCanReclamo">Cancelar</button>
	    			</div>
	    		</form>
	    	</section>
		</div>
	</div>
</div>
