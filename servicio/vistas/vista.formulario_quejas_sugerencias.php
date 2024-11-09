<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>form quejas sugerencias</title>
        <!--JQUERY-->
        <script type="text/javascript"  src="../../js/jquery-3.3.1.min.js"></script>
        <!--Datatable-->
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <script src="../../js/Datatables/RowGroup-1.0.0/dataTables.rowsGroup.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/botonesDataTableServicios.css?<?echo time()?>" rel="stylesheet" media="all" />
        <!--logica pag    -->
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js" ></script>
        <script src=
                "https://html2canvas.hertzen.com/dist/html2canvas.js">
        </script>
        <script type="text/javascript" src="../js/formQuejasSugerencias.js?<?echo time()?>"></script>
        <style type="text/css" media="print">
            body {
                zoom:30%; /*or whatever percentage you need, play around with this number*/
            }

        </style>
    </head>

    <body >
    <header>
        <div class="cabecera">
             Formularios de quejas y sugerencias
        </div>
    </header>

    <section class="contenido" style="background-color: #d6d5d3">
        <article>
            <form onSubmit="return false" id="formQuejasSugerencias">
                <div class="container" id="moviCuenta" style="padding-top: 10px">
                    <div class="container">
                        <div class="row align-items-end">
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label>Proyecto:</label>
                                <select  tabindex="10"   id="proyecto"   name="proyecto"    class="arrow-togglable  form-control form-control-lg" ></select>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label>Fecha inicial:</label>
                                <input type="date" tabindex="10"  id="idFechaIn"   name="fechaIn"  class="arrow-togglable  form-control form-control-lg" >
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label>Fecha final:</label>
                                <input type="date" tabindex="20"  id="idFechaFn"   name="fechaFn"    class="arrow-togglable  form-control form-control-lg" >
                            </div>
                            <!--<div class=" col-lg-1">
                                <label for="cmbNivel">Nivel:</label>
                                <select id="cmbNivel" name="nivel" class="form-control-lg"></select>
                            </div>-->
                            <div class="col-md-3 col-sm-12 col-xs-12 my-6" style="margin-top: 20px">
                                <label></label>
                                <button  id="btnGenerar" tabindex="30" onclick="repQuejasSugerencias()" class="btn btn-info" style="background-color: #000040; border-color: #000040; margin-bottom: 10px">Generar </button>

                            </div>
                        </div>
                    </div>
                </div>

            </form>


        </article>


    </section>
    <div class="container-fluid" style="margin-top: 15px" >
        <table id="dataTable" width="100%" class="display" style="margin-top: 2px" >
        </table>
    </div>



    <!-- Modal -->

    <div id="miModal" class="modal fade " role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div id="vistaFormulario2" style="width: 100%; padding-top: 0; margin-top: 0">
                        <center>
                            <div id="vistaFormulario" style="padding-top: 0; margin-top: 0" >


                            </div>
                        </center>
                    </div>

                    <div class="modal-footer">
                        <button  id="btnPrint" tabindex="30" class="btn btn-info" onclick="PrintElem('vistaFormulario2')" >Imprimir </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="vistaFormularioPrint"></div>
    <div id="previewImage"></div>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

