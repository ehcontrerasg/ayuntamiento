<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Aprobacion de Solicitudes</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/sidebar.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="http://172.16.1.214:3000/socket.io/socket.io.js"></script>
	<script src="js/angular.min.js"></script>
</head>
<body>
	<header class="navbar-fixed-top">
	  	<div class="container">
	   		<h4>Menu</h4>
	 	</div>
	</header >
	<br>
	<br>
	<nav class="navbar navbar-default sidebar" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>      
            </div>
            <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active" id="inscribirEst">
                		<a href="#ctnSolicitudesTI" aria-controls="ctnSolicitudesTI" role="tab" data-toggle="tab"><strong>Realizar Solicitud TI</strong><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-pencil"></span></a>
            		</li>
                    <li id="buscarEst"><a href="#ctnSolTIRealizadas" aria-controls="ctnSolTIRealizadas" role="tab" data-toggle="tab"><strong>Solicitudes TI</strong><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-search"></span></a></li>
                    <!-- li class="dropdown">
                        <a href="#" class="dropdown-toggle" id="menPrestamos" data-toggle="dropdown">Prestamos<span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-usd"></span></a>
                        <ul class="dropdown-menu forAnimate" role="menu">
                             <li><a href="#">Vigentes</a></li> 
                            <li class="divider"></li>
                            <li id="solReenganche"><a class="" href="#">Reenganche</a></li>
                        </ul>
                    </li>
                    <!-- <li class="dropdown">
                        <a href="#" class="dropdown-toggle" id="menPerDatos" data-toggle="dropdown">Mis Datos<span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a>
                        <ul class="dropdown-menu forAnimate" role="menu">
                            <li><a class="hola" href="#DatosPersonales" id="one">Datos Personales</a></li>
                            <li class="divider"></li>
                            <li><a class="" href="#DireccionCasa" id="two">Direccion Domicilio</a></li>
                            <li class="divider"></li>
                            <li><a class="" href="#RefLaborales" id="four">Referencia Laborales</a></li>
                        </ul>
                    </li>     
                    <li id="logout"><a href="php/logout.php">Cerrar Sesion<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li> 
						ng-model="idMenu" ng-change="lstPantallas(this)"
                    -->
                </ul>
            </div>
        </div>
    </nav>
    <div ng-app="appCalidadTI" ng-controller="ctrlCalidadTI" class="main tab-content">
	    <style type="text/css">
	    	.iconSOl{
	    		color: #65b333;
	    		margin-top: 50%;
	    		font-size: 19px;
	    		opacity: 0.5;
	    	}
	    	.lstSolicitudes {
	    		background-color: rgba(234, 234, 234, 0.56);
	    		color: #008017;
	    		font-size: 16px;
	    		font-weight: bold;
	    		padding: 2%;
	    		border-radius: 5px 5px 5px 5px;
				-moz-border-radius: 5px 5px 5px 5px;
				-webkit-border-radius: 5px 5px 5px 5px;
				border: 0.1px solid #65b333;

	    	}
	    	#ctnSolTIRealizadas .form-control[disabled], #ctnSolTIRealizadas .form-control[readonly], #ctnSolTIRealizadas fieldset[disabled] .form-control {
			    background-color: transparent;
			    opacity: 1;
			    font-size: 14px;
			    color: #555;
			}
			/*.mostrar{
				transition:0.5s linear all;
				display: block;
			}*/
			.ocultar{
				display: none;
			}
			.fadeOUT{
				transition:0.5s linear all;
  				opacity:0;
			}
			
	    </style>
	    <div class="container-fluid tab-pane fade" role="tabpanel" id="ctnSolTIRealizadas">
	    	<div ng-repeat="solicitud in solicitudes track by $index" class="" id="{{'ctn'+solicitud.ID_SCMS}}">
	    		<div class="form-horizontal lstSolicitudes">
		    		<div class="row">
		    			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
		    				<span class="glyphicon glyphicon-option-vertical iconSOl"></span>
		    			</div>
		    			<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
		    				<div class="row" align="right" style="margin-right: 3%;">
		    					<p class="lead" style="font-size: 14px; color: #555;font-style: italic; font-weight: bold;">{{solicitud.ID_SCMS}}</p>
		    				</div>
		    				<div class="row">
			    				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				    				<div class="row">
				    					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				    						<div class="form-group">
				    							<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="">Estado: </label>
							    				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							    					<input type="text" class="form-control input-sm" value="{{solicitud.ESTADO}}" readonly>
							    				</div>
				    						</div>
				    					</div>
				    					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				    						<div class="form-group">
				    							<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="">Prioridad: </label>
							    				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							    					<input type="text" class="form-control input-sm" value="{{solicitud.DESC_PRIORIDAD}}" readonly>
							    				</div>
				    						</div>
				    					</div>
				    				</div>
				    			</div>
				    			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				    				<div class="row">
				    					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				    						<div class="form-group">
						    					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="">Fecha Compromiso: </label>
							    				<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5">
							    					<input type="text" class="form-control input-sm" value="{{solicitud.FECHA_COMPROMISO | date: 'dd/MM/y'}}" readonly>
							    				</div>
						    				</div>
			    						</div>
			    						<!-- div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				    						<div class="form-group">
						    					<label class="label-control col-xs-3 col-sm-3 col-md-3 col-lg-3" for="">Fecha Solicitud: </label>
							    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
							    					{{solicitud.FECHA_SOLICITUD}}
							    				</div>
						    				</div>
			    						</div-->
		    						</div>
				    			</div>
			    			</div>
			    			<div class="row">
			    				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		    						<div class="form-group">
		    							<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3" for="">Desarrollador: </label>
					    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
					    					<input type="text" class="form-control input-sm" value="{{solicitud.DESARROLLADOR}}" readonly>
					    				</div>
		    						</div>
				    			</div>
				    			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		    						<div class="form-group">
				    					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="">Modulo/Pantalla: </label>
					    				<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" sty>
					    					<p style="font-size: 14px;color: #555;">{{solicitud.MODULO+' / '+solicitud.PANTALLA}}</p>
					    					<!--input type="text" class="form-control input-sm" value="{{solicitud.PANTALLA}}" readonly -->
					    				</div>
				    				</div>
				    			</div>
			    			</div>
			    			<div class="row">
				    				<center id="{{'btns'+solicitud.ID_SCMS}}">
				    					<button class="btn btn-success btn-aceptar" style="margin-right: 3%;"><span class="glyphicon glyphicon-ok"></span> Aceptar</button>
				    					<button class="btn btn-danger btn-rechazar" ng-click="btnRechazar(solicitud.ID_SCMS)"><span class="glyphicon glyphicon-remove"></span> Rechazar</button>
				    				</center>
			    					<div id="{{'alrt'+solicitud.ID_SCMS}}" class="alert alert-danger alert-dismissible fade in ocultar" role="alert" style="width: 95%; margin-top: 12px;">
			    						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									  		<span aria-hidden="true">&times;</span>
								  		</button>
								  		<div class="row">
									  		<center>
									  			<h3 style="font-weight: bold; margin-bottom: 8px" align="center">¿Esta seguro de rechazar esta solicitud?</h3>
							    				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 1%;">
					    							<textarea name="{{'cmt'+solicitud.ID_SCMS}}" id="{{'cmt'+solicitud.ID_SCMS}}" rows="8" maxlength="2500" placeholder="Expliquenos las razon." cols="40"></textarea>
					    						</div>
									  			<button id="{{'btn-acept'+solicitud.ID_SCMS}}" class="btn btn-success">Si</button>
										  		<button id="{{'btn-rech'+solicitud.ID_SCMS}}" class="btn btn-danger">No</button>	
									  		</center>	
								  		</div>
								  		
									</div>
			    			</div>
		    			</div>
		    		</div>
	    		</div>
	    		<br>
	    	</div>
	    </div>
    </div>
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/socket.min.js"></script>
	<script src="js/ngRoute.js"></script>
	<!-- script src="js/angular-animate.js"></script -->
	<script src="js/ctrlCalidadTI.js?1"></script>
</body>
</html>