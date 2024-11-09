<?php session_start();
require_once '../Clases/clase.solicitudes.php';
$user = $_SESSION['codigo'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link type="image/ico" href="../images/favicon.ico" rel="icon">
    <title>Aprobacion de Solicitudes</title>
    <link rel="stylesheet" type="text/css" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css?<?= time();?>">
    <link rel="stylesheet" type="text/css" href="../css/reportes.css?<? echo time();?>" />
    <link href="../css/solicitudes_no_encontradas.css?<? echo time();?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/sweetalert.css">
</head>
<body>
    <!-- Modal de sesión -->
    <?php include_once('./modal-sesion.php'); ?>
    <!-- Fin de modal de sesión -->
    
    <div class="row" id="dvEstado">
        <div class="col-sm-2 form-group">
            <label for="txtCodigoSolicitud">Código de solicitud:</label>
            <input type="number" id="txtCodigoSolicitud" class="form-control">

        </div>
        <div class="col-sm-3 form-group">
            <label for="slcEstado" id="lblEstadoSCMS">Estado:</label>
            <select class="form-control" id="slcEstado"><option value="">ESTADO</option><option value="ANU">Anulado</option><option value="ESP">Espera</option><option value="FIN">Finalizado</option><option value="PRO">Procesando</option></select>
        </div>
        <div class="col-sm-4 form-group row">
            <div class="col-sm-6">
                <label>Fecha desde: </label>
                <input type="date" name="fechaDesde" class="form-control">
            </div>
            <div class="col-sm-6">
                <label>Fecha hasta: </label>
                <input type="date" name="fechaHasta" class="form-control">
            </div>
        </div>
        <div class="col-sm-1 form-group">
            <button class="btn btn-primary" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-sm-1 form-group">
            <button class="btn btn-warning" id="btnLimpiar" style="margin-top: 23%;">Limpiar</button>
        </div>
    </div>
    <div id="dvPaginacion">
        <button id="btnAtrás" class="btn btnPaginacion" title="Atrás"><</button>
        <button id="btnAdelante" class="btn btnPaginacion" title="Adelante"> > </button>
    </div>
<div>
    <div id="dvSolicitudes">
	<br>
</div>


</body>
   <script src="../js/lib/jquery/jquery-3.1.1.min.js"></script>
    <script src="../js/lib/jquery/bootstrap.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../../js/fnLogin.js"></script>
	<script src="../js/solicitud.js?<?= time();?>"></script>
	<script src="../js/solicitudes.js?<?= time();?>"></script>
</html>
