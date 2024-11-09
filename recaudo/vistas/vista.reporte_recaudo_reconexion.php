<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset="UTF-8" />
        <title>Reporte recaudo reconexion </title>

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
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/b-2.0.1/b-html5-2.0.1/datatables.min.js"></script>        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
        <!-- Excel -->
        <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.templates.min.js"></script>
        <!-- pagina -->
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/botonesDataTableRecaudo.css" rel="stylesheet" />
        <link href="../css/repPagDetxCon.css?20" rel="stylesheet" />
        <script type="text/javascript" src="../js/repPagoDetxConcepto.js?<?PHP echo time()?>"></script>

        </head>
    <body>

    <header>
        <div class="cabecera">
            Reporte Recaudo Reconexion
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

