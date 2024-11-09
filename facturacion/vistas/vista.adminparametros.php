
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--Estilos-->
    <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
   <!--Librerías JS-->
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <script src="../css/bootstrap/js/bootstrap.js" ></script>
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
    <!--Botones de Datatable-->
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>


    <script type="javascript" src="//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"></script>
    <script src="../js/parametros.js?1"></script>




</head>
    <section class="contenido container-fluid">
        <body style="margin-top:-25px" >
        <div id="content" >
            <h3 class="col-lg-12" style=" background-color:#336699; color:#FFFFFF; font-size:18px; height:32px; padding-top: 6px; margin-top: 50px; " align="center">Administrar Parametros</h3>

            <div class="modal fade" id="frmAdministrarParametros" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Editar valores de parametros</h4>
                        </div>
                        <div class="modal-body">
                            <form onsubmit="return false" id="frmEditarParametro">
                                <div>
                                    <label for="nombre-parametro" class="col-2 col-form-label">Nombre del parámetro</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" id="txtNombreParametro" name="descripcionParametro" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label for="valor-parametro" class="col-2 col-form-label">Valor del parámetro</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" id="txtValorParametro" name="valorParametro" required >
                                    </div>

                                </div>




                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <!-- button type="button" class="btn btn-primary">Save changes</button -->
                            <!--<button id=type="button" class="btn btn-primary" data-dismiss="modal" id="btnGuardarCambios" >Guardar cambios</button>-->
                            <input type="submit" class="btn btn-primary" value="Guardar cambios"/>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row" style="margin-left: auto;" width="95%">
                    <table id="dataTable" width="98%" class="row-border hover stripe"></table>
                </div>
            </div>


        </div>


         </section>



        </body>


</html>
