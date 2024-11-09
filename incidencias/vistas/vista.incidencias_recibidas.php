<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset="UTF-8" />
        <title>  Monitoreo de incidencias </title>

        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <!--Datatable-->
        <link href="../css/botonesDaTableAverRecib.css" rel="stylesheet" />
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
        <!-- pagina -->
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/general_incidencias.css?<?PHP echo time()?>" rel="stylesheet" />

        <link href="../css/botonesDaTableAverRecib.css" rel="stylesheet" />

    <!--   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k&signed_in=true"></script>-->
        <script type="text/javascript" src="../js/AveriasRecividas.js?<?PHP echo time()?>"></script>



    </head>

    <body>

    <header>
        <div class="cabecera">
            Monitoreo de incidencias
    </header>

    <section>
        <article>

            <form id="repPagDetCon" onsubmit="return false">
                <div class="subCabecera">
                    Filtros de Búsqueda
                </div>

                <div class="contenedorBusqVal">
                    <div class="titFiltro">Motivo</div>
                    <select name="motivo" id="idMotivo"></select>
                </div>

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
                    <button class="buttonRep" id="butRepPagConcFinGenRep">Buscar</button>
                </div>

            </form>

            <div class="container-fluid" style="margin-top: 20px">
                <div class="row">
                    <table id="dataTable" width="95%" class="row-border hover stripe">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Observación</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Direccion</th>
                            <th>Email</th>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>Descripcion</th>
                            <th>Id</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </article>

    </section>

    </body>

    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

