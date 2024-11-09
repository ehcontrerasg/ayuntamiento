<!doctype html>
<html>
    <head>
        <!--Meta-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--Meta-->

        <!--CSS-->
        <link rel="stylesheet" type="text/css" href="../css/EncuestaSatisfaccionUsuarios.css?<?php echo time();?>"/>
        <!--Bootstrap-->
        <link rel="stylesheet" type="text/css" href="../../librerias/bootstrap-4.5.3-dist/css/bootstrap.min.css"/>
        <!--Datatable-->
        <link rel="stylesheet" type="text/css" href="../../js/DataTables-1.10.19/css/buttons.dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="../../js/DataTables-1.10.19/css/jquery.dataTables.min.css"/>
        <!--Font Awesome-->
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>
        <!--Alertas-->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <!--CSS-->
    </head>
    <body>
        <div class="container-fluid">
            <div class="row" id="content">
                <div id="tituloFormulario" class="col-sm-12">
                    <p>REPORTE ENCUESTA SATISFACCION USUARIOS</p>
                </div>
                <div id="dvFormulario" class="col-sm-12">
                    <form method="post" id="frmEncuestaSatisfaccionUsuarios" class="container">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-3">
                                <label for="proyecto">Proyecto</label>
                                <select name="proyecto" id="proyecto" class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-12 col-md-3">
                                <label for="fechaDesde">Fecha desde</label>
                                <input type="date" id="fechaDesde" class="form-control" name="fecha_desde"/>
                            </div>
                            <div class="form-group col-sm-12 col-md-3">
                                <label for="fechaHasta">Fecha hasta</label>
                                <input type="date" id="fechaHasta" class="form-control" name="fecha_hasta"/>
                            </div>
                            <div class="form-group col-sm-12 col-md-1">
                                <label>&nbsp;</label>
                                <input type="submit" value="Generar" class="btn btn-success" id="btnGenerarReporte"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="dvReporte" class="col-sm-12">
                    <p id="pNoExistenDatos">Aún no hay datos para mostrar.</p>
                    <img id='btnExportarExcelResumen' src="../../images/excel/Excel.ico" title="Exportar a Excel" height="10%"/>
                    <table id="tblSatisfaccionUsuario">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ENCUESTADOR</th>
                                <th>CLIENTE</th>
                                <th>OFICINA</th>
                                <th>FECHA</th>
                                <th>VISUALIZAR</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="dvResumenSatisfaccionUsuarios">
                        <table id="tblResumenSatisfaccionUsuarios">
                            <thead>
                                <tr style="border: 1px solid black; background: #316dbb; color: white">
                                    <th><strong>No. Fila</strong></th>
                                    <th><strong>Fecha</strong></th>
                                    <th><strong>Oficina</strong></th>
                                    <th><strong>Cliente</strong></th>
                                    <th><strong>Encuestador</strong></th>
                                    <th><strong>ITEM 1</strong></th>
                                    <th><strong>ITEM 2</strong></th>
                                    <th><strong>ITEM 3</strong></th>
                                    <th><strong>ITEM 4</strong></th>
                                    <th><strong>ITEM 5</strong></th>
                                    <th><strong>ITEM 6</strong></th>
                                    <th><strong>ITEM 7</strong></th>
                                    <th><strong>ITEM 8</strong></th>
                                    <th><strong>ITEM 9</strong></th>
                                    <th><strong>ITEM 10</strong></th>
                                    <th><strong>ITEM 11</strong></th>
                                    <th><strong>ITEM 12</strong></th>
                                    <th><strong>ITEM 13</strong></th>
                                    <th><strong>ITEM 14</strong></th>
                                    <th><strong>ITEM 15</strong></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div id="detalleEncuesta"></div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Encuesta de satisfacción de usuarios</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!--JS-->
    <!--JQUERY-->
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <!--Bootstrap-->
    <script src="../../librerias/bootstrap-4.5.3-dist/js/bootstrap.min.js"></script>
    <!--Datatable-->
    <script src="../../js/DataTables-1.10.19/dataTables.min.js"></script>
    <!-- alertas -->
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!--Moment JS-->
    <script type="text/javascript" src="../../js/momentJs/moment.min.js"></script>
    <!--Print div-->
    <!--<script type="text/javascript" src="../../js/printJS/printJS.js"></script>
    <script type="text/javascript" src="../../js/printThis/printThis.js"></script>
    <script type="text/javascript" src="../../js/jqueryPrintElement/jquery.printElement.min.js"></script>-->
    <!--PDF-->
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <!--HTML 2 CANVAS-->
    <script> src = "../../js/html2Canvas/html2canvas.min.js"</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-alpha1/html2canvas.js"></script>
    <!--Lógica-->
    <script src="../js/EncuestaSatisfaccionUsuarios.js?<?php echo time();?>" type="text/javascript"></script>
    <!--JS-->
</html>