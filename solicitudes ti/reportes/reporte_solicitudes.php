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
    <!-- Aqui los scripts para los reportes -->
    <script src="../js/reports/report_require.js"></script>
</head>
<body>
	<header>
		<div class="cabecera">
			Reporte Solicitudes
		</div>
	</header>
<section class="lead">
	<form action="#" name="report_form" class="form" method="POST">
	<div class="container-fluid">
		<div class="form-group  col-sm-5">
			<label>Departamento</label>
			<select name="department" id="department" class="form-control">
				<option value="0">DEPARTAMENTO</option>
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
		<div class="form-group col-sm-2 pull-right">
			<label>&nbsp;</label>
			<input type="submit" id="report-btn" class="btn btn-success form-control">
		</div>
	</div>
	</form>

</section>
<div class="container-fluid">
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
</html>
