<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <!--Librería JQUERY-->
            <script src="../../js/jquery.min.js"></script>
            <!--LIBRERÍA BOOTSTRAP-->
            <script src="../../js/bootstrap.min.js"></script>
            <link href="../../css/bootstrap.min.css" rel="stylesheet">
            <!--Libreria CSS-->
            <link rel="stylesheet" type="text/css" href="../css/facturadoAdeudadoPorSector.css?5">
            <!--Librería Datatable-->
            <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
            <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
            <!--Botones de Datatable-->
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
            <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
            <!-- alertas -->
            <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
            <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
            <!--lógica de la página-->
            <script src="../js/FactAdeSectorRuta.js?9"></script>
        </head>
        <header>
            <div class="cabecera">
               <h4 align="center">Reporte de inmuebles facturados, Recaudado y adeudado</h4>
            </div>
        </header>
        <body>
            <form onsubmit="return false" action="return false" id="frmFactAdeSectRut" method="POST">
                <div class="row">
                    <div class="form-group col-sm-1" id="dvProyecto">
                        <label for="cmbProyecto">Proyecto:</label>
                        <select id="cmbProyecto" name="proyecto" required></select>
                    </div>
                    <div class="form-group col-sm-2" id="dvSector">
                        <center>
                            <label for="txtSector" >Sector:</label>
                            <input type="text" id="txtSector" name="sector" required>
                        </center>
                    </div>
                    <div class="form-group col-sm-2" id="dvRuta">
                        <center>
                            <label for="txtRuta" >Ruta:</label>
                            <input type="text" id="txtRuta" name="ruta" required>
                        </center>
                    </div>
                    <div class="form-group col-sm-2" id="dvPeriodo" >
                        <center>
                            <label for="txtPeriodo">Período</label>
                            <input type="number" id="txtPeriodo" name="periodo" required>
                        </center>

                    </div>
                    <div class=" form-group col-sm-2 " id="dvGenerarReporte">
                        <input type="submit" id="btnGenerarReporte" value="Generar Reporte" class="btn btn-success" >
                    </div>
                </div>
            </form>
            <div class="container-fluid">
                <div class="row">
                    <table id="dataTable" width="98%" class="row-border hover stripe"></table>
                </div>
            </div>
        </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

