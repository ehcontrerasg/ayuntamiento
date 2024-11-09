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
        <title>Pagos Anulados</title>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css"  href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../../js/DataTables-1.10.15/ext/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../css/pagAnulados.css">
        <!-- link rel="stylesheet" type="text/css" href="../css/table.css"-->
    </head>
    <body>
    <header>
        <div class="container"><center><h4>Reporte de Pagos Anulados</h4></center></div>
    </header>
    <div style="width: 95%; margin: 3%;margin-top: 0px;">`
        <div class="datagrid table-responsive">
            <table id="dataTable" class="table table-striped table-condensed table-bordered table-hove">
                <thead>
                <th>ID_Pago</th>
                <th>Cod. Sistema</th>
                <th>Importe</th>
                <th>Motivo Anulacion</th>
                <th>Fech. Pago</th>
                <th>Usuario Pago</th>
                <th>Fech Anulacion</th>
                <th>Usuario Anulo</th>
                </thead>
                <tfoot>
                <th><input type="text" class="form-control" id="txtIdPago" placeholder="ID Pago" data-index="0" /></th>
                <th><input type="text" class="form-control" id="txtCodSistema" placeholder="Cod Sist" data-index="1" /></th>
                <th>Importe</th>
                <th>Motivo Anulacion</th>
                <th><input type="date" class="form-control" id="txtFechPago" data-index="4" /></th>
                <th><input type="text" class="form-control" id="txtUsrPago" placeholder="Usuario Pago" data-index="5" /></th>
                <th><input type="date" class="form-control" id="txtFechAnulacion" data-index="6" /></th>
                <th><input type="text" class="form-control" id="txtUsrAnulacion" placeholder="Usuario Anulo" data-index="7" /></th>
                </tfoot>
            </table>
        </div>
    </div>
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
    <script src="../../js/numeral-js/src/numeral.js"></script>
    <script src="../js/pagAnulados.js"></script>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

