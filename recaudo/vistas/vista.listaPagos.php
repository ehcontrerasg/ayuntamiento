<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Aprobacion de Solicitudes</title>
            <!-- alertas -->
            <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
            <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
            <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
            <!--<link rel="stylesheet" type="text/css" href="../../css/font-awesome.min.css">-->
            <link rel="stylesheet" type="text/css" href="../../css/style.css">
            <!--<link rel="stylesheet" type="text/css" href="../../css/reportes.css" />-->
            <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
            <script src="../../js/jquery-3.2.1.min.js"></script>
            <script src="../../js/bootstrap.min.js"></script>
            <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
            
        </head>
        <header>
            <div id="cabeceraListaPagos"><center><h4>Reporte de lista de pagos</h4></center></div>
        </header>
        <body>
            <div  class="container-fluid" id="parametrosListaPagos">
                <form  name="report_form" class="form" method="POST" id="frmListaPagos">
                    <div class="form-group  col-sm-2 ">
                        <label>Fecha inicial:</label>
                        <input type="date" id="fechaDesde" name="fechaDesde" class="form-control" />
                    </div>
                    <div class="form-group  col-sm-2 ">
                        <label>Fecha final:</label>
                        <input type="date" id="fechaHasta" name="fechaHasta" class="form-control"/>
                    </div>
                    <div class="form-group col-sm-2 ">
                        <label>&nbsp;</label>
                        <input type="submit" value="Generar reporte" id="report-btn" class="btn btn-success form-control ">
                    </div>
                </form>
            </div>
            <div class="container-fluid" id="dvDatatableListaPagos">
                <div class="row" >
                    <table id="dataTable" width="98%" class="row-border hover stripe"></table>
                </div>
            </div>
        </body>
        <!-- Aqui los scripts para los reportes -->
        <script src="../js/listaPagos.js?<?echo time();?>"></script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

