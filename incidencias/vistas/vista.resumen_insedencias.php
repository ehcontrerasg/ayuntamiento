<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset="UTF-8" />
        <title>Reporte Incidencias </title>

        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <!--Datatable-->
        <link rel="stylesheet" href="../../js/DataTables-1.10.19/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.19/dataTable.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
        <!-- pagina -->
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../css/general_incidencias.css?<?PHP echo time()?>" rel="stylesheet" />


    <!--   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k&signed_in=true"></script>-->
        <script type="text/javascript" src="../js/ResumenInidencias.js?<?PHP echo time()?>"></script>



    </head>
    <body>

    <header>
        <div class="cabecera">
            Reporte de Incidencias
    </header>

    <section>
        <article>
            <form id="repPagDetCon" onsubmit="return false">
                <div class="subCabecera">
                    Filtros
                </div>
<div >
                <div class="contenedorBusqVal">
                    <div class="titFiltro">Motivo</div>
                    <select name="motivo" id="idMotivo"></select>
                </div>
                <!--<div class="contenedorBusqVal">
                    <div class="titFiltro">Concepto</div>
                    <select name="selRepPagConcConc" id="selRepPagConcConc"></select>
                </div>-->
                <div class="contenedorBusqFech">
                    <div class="titFiltro">Fecha</div>
                    <label >Desde:</label>
                    <input class="inputFecha" name="IniFec" type="date" id="idIniFec"/>
                    <label>Hasta:</label>
                    <input class="inputFecha" name="FinFec" type="date" id="idFinFec"/>
                </div>
    <div class="contenedorBusqVal">
        <div class="titFiltro">Estado</div>
        <select name="Atendida" id="Atendida"></select>

    </div>
    <div class="contenedorBusqVal">
        <button class="buttonRep" id="butRepPagConcFinGenRep">Generar</button>
    </div>



                    </form>
        </article>

            <div class="container-fluid" style="margin-top: 20px">
                <div class="row">
                    <table id="dataTable" width="95%" class="row-border hover stripe">
                    </table>
                </div>
            </div>


    </section>

    <footer>

    </footer>


    </body>

    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

