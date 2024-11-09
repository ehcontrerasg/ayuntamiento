<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset="UTF-8" />
        <title>Reporte pago detallado por conceptos </title>

        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <!--Datatable-->
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/repPagDetxCon.css?3" rel="stylesheet" />
        <link href="../css/botonesDataTableRecaudo.css" rel="stylesheet" />
        <script type="text/javascript" src="../js/repPagoDetxConcepto.js?23"></script>

        </head>
    <body>

    <header>
        <div class="cabecera">
            Reporte Pagos Por Acuerducto y Fecha
        </div>
    </header>

    <section>
        <article>
<form id="repPagDetCon" onsubmit="return false">
            <div class="subCabecera">
                Filtros de Búsqueda
            </div>

            <div class="contenedorBusqVal">
                <div class="titFiltro">Acueducto</div>
                <select name="selRepPagConcAcu" id="selRepPagConcAcu"></select>
            </div>
            <!--<div class="contenedorBusqVal">
                <div class="titFiltro">Concepto</div>
                <select name="selRepPagConcConc" id="selRepPagConcConc"></select>
            </div>-->
            <div class="contenedorBusqFech">
                <div class="titFiltro">Fecha</div>
                <label >Desde:</label>
                <input class="inputFecha" name="inpRepPagConcIniFec" type="date" id="inpRepPagConcIniFec"/>
                <label>Hasta:</label>
                <input class="inputFecha" name="inpRepPagConcFinFec" type="date" id="inpRepPagConcFinFec"/>
            </div>

            <div class="contenedorBut">
                <button class="buttonRep" id="butRepPagConcFinGenRep">Generar Reporte</button>
            </div>

    <div class="container-fluid">
        <div class="row">
            <table id="dataTable" width="98%" class="row-border hover stripe">
            </table>
        </div>
    </div>
</form>

        </article>

    </section>

    <footer>

    </footer>


    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

