<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <html lang="es">
        <meta  charset="UTF-8" />
        <title>Observaciones</title>
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <link rel="stylesheet" type="text/css"  href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="../css/obs_style.css">
        </head>
    <body>
    <header>
        <div class="container-fluid">
            <center>
                <h4>Observaciones de Inmuebles</h4>
            </center>
        </div>
    </header>
    <div class="container">
        <div class="main">
            <form action="#" id="frmCodInm" class="form-inline">
                <div class="form-group">
                    <label for="cod_inm" class="label-control">Codigo: </label>
                    <input type="text" class="form-control " id="cod_inm" required>
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <hr>
            <form class="form-horizontal" id="frmObservacion">

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="txtAsunto" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Asunto:</label>
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <input type="text" name="txtAsunto" id="txtAsunto" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="sltCodigo" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Codigo</label>
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <select name="sltCodigo" id="sltCodigo" class="form-control">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="txtObservacion" class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Descripcion:</label>
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <textarea name="txtObservacion" id="txtObservacion" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <center>
                        <button type="button" class="btn btn-warning">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </center>
                </div>
            </form>
            <br>
            <div class="datagrid table-responsive">
                <table id="tblObservaciones" class="table table-striped table-condensed table-bordered table-hove">
                    <thead>
                    <th>CONSECUTIVO</th>
                    <th>CODIGO_OBS</th>
                    <th>ASUNTO</th>
                    <th>DESCRIPCION</th>
                    <th>FECHA</th>
                    <th>LOGIN</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script src="../../js/sweetalert.min.js"></script>
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.min.js"></script>
    <!--script src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
    <script src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
    <script src="../../js/numeral-js/src/numeral.js"></script -->
    <script src="../js/fnObservaciones.js?<? echo time();?>"></script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

