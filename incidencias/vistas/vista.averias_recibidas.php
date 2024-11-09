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
        <script src="../../js/DataTables-1.10.19/dataTable.js"></script>
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
        <link href="../css/general_averias.css?<?PHP echo time()?>" rel="stylesheet" />

        <link href="../css/botonesDaTableAverRecib.css" rel="stylesheet" />

    <!--   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k&signed_in=true"></script>-->
        <script type="text/javascript" src="../js/AveriasRecividas.js?<?PHP echo time()?>"></script>



    </head>
    <body>

    <header>
        <div class="cabecera">
            Monitoreo de averías
    </header>

    <section>
        <article>
            <form id="repPagDetCon" onsubmit="return false">
                <div class="subCabecera">
                    Filtros de Búsqueda
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

                <div class="contenedorBut">
                    <button class="buttonRep" id="butRepPagConcFinGenRep">Buscar</button>
                </div>

            </form>
            </div>
            <div class="modal fade" id="miModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" id="detalles" role="document">
                    <form class="modal-content" onsubmit="return false" id="actCampos">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Detallles</h4>
                        </div>

                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-2 ">
                                        <label> ID:</label><br>
                                        <span id="id"></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Fecha:</label><br>
                                        <span id="fecha"></span>
                                    </div>
                                <div class="col-sm-3 ">
                                    <label>Motivo:</label>
                                    <br>
                                    <span id="motivo"></span>
                                </div>
                                    <div class="col-sm-5 ">
                                        <label> Observacion:</label>
                                        <br>
                                        <span id="observacion"></span>
                                    </div>
                                </div>

                                    <div class="row">
                                    <div class="col-sm-2 ">
                                        <label>Nombre:</label>
                                        <br>
                                        <span id="Nombre"></span>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <label>Telefono:</label>
                                        <br>
                                        <span id="Telefono"></span>
                                    </div>
                                    <div class="col-sm-3 ">
                                        <label>Direcion:</label>
                                        <br>
                                        <span id="Direcion"></span>
                                    </div>
                                        <div class="col-sm-5 ">
                                        <label>Email:</label>
                                        <br>
                                        <span id="email"></span>
                                    </div>
                                </div>

                            <div class="row"   style="height: 15px">
                                <div class="col-sm-12  ">
                                    <label>Fotos:</label>
                                    <br>
                                 <span id="foto1"></span>
                                </div>

                            </div>
                                <div class="row" style="height: 15px">
                                <div class="col-sm-12  ">
                                    <div id="coordenadas"></div>
                                    <div id="map">
                                        <div id="map"></div>

                                    </div>
                                </div>
                                </div>

                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="modificar" class="btn btn-primary">Imprimir detalles</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                        </div>

                </div>
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

