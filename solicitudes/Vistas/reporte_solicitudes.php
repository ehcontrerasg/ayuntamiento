<!DOCTYPE html>
<html lang="es" ng-app="appSolicitudesTI">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aprobacion de Solicitudes</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/reportes.css" />
    <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
    <script src="../js/lib/jquery/jquery-3.1.1.min.js"></script>
    <script src="../js/lib/jquery/bootstrap.min.js"></script>
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.min.js"></script>

</head>
<body>


		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog center-block" role="document" style="max-width: 400px;color: #0c6abc;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #f0f0f0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title" id="myModalLabel"><b>ACEA SOFT</b></h4></center>
                </div>
                <form id="frmLogin" class="form-horizontal" action="../../webServices/ws.login.php" method="post" caso="2">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username" class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4">Usuario:</label>
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4">Contraseña:</label>
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-key"></span></span>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row" id="msgUser" style="display: none">
                            <div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
                                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <p>El <b>usuario</b> o la <b>contraseña</b> son incorrectos.</p>
                                </div>
                            </div>
                        </div>
                        <center>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="logout()" style="width: 100px"><span class="glyphicon glyphicon-remove"></span> Salir</button>
                            <button type="submit" class="btn btn-primary" style="width: 100px"><span class="glyphicon glyphicon-ok"></span> Entrar</button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<br>
	<header>
		<div class="cabecera">
			Reporte Solicitudes
		</div>
	</header>
<section class="lead">
	<form action="#" name="report_form" class="form" method="POST" id="frmSolicitudes">
	<div class="container-fluid">
		<div class="form-group  col-sm-2">
			<label>Departamento</label>
			<select name="department" id="department" class="form-control">
				<!--<option value="0">DEPARTAMENTO</option>-->
			</select>
		</div>
		<div class="form-group  col-sm-2 ">
			<label>Fecha Inicial</label>
			<input type="date" name="fecha_ini" class="form-control">
		</div>
		<div class="form-group  col-sm-2 ">
			<label>Fecha Final</label>
			<input type="date" name="fecha_fin" class="form-control">
		</div>
        <div class="form-group  col-sm-2 ">
			<label>Estado</label>
            <select name="estado" id="slcEstadoSCMS" class="form-control">
                <option value="">ESTADO</option>
            </select>
            <!--<select id="slcEstado" name="estado">
                <option value="0">Estado</option>
            </select>-->
		</div>
		<div class="form-group col-sm-2 ">
			<label>&nbsp</label>
			<input type="submit" id="report-btn" class="btn btn-success form-control" value="Generar">
		</div>
	</div>
	</form>

</section>
<div class="container-fluid">
    <div id="acciones">
        <button id="btnExpExcel" class="btn btn-success">Excel</button>
    </div>

    <div class="row">
		<table id="dataTable" width="98%" class="row-border hover stripe">
		</table>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-report">
  <div class="modal-dialog" role="document" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Solicitud de Cambios y Mejores del Sistema</h4>
      </div>
      <div class="modal-body">
		<iframe src="" id="reportPDF" frameborder="0" width="100%" style="height: 70vh"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

	<footer>
	</footer>

</body>
<!-- Aqui los scripts para los reportes -->
<script src="../js/report_require.js?<? echo time();?>"></script>
</html>
