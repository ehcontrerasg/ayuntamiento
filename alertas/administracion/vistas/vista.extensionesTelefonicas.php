<!doctype html>
<html>
    <head></head>
    <header>
        <div id="cabeceraListaExtensiones"><center><h4>Listado de extensiones</h4></center></div>
    </header>
    <body>
        <div id="parametrosListadoExtensionesTelefonicas">
            <form onsubmit="return false" id="frmInsertarExtension">
                <div class="form-group col-sm-1">
                    <label  for="extension">Extensión</label>
                    <input type="text" name="extension" id="txtExtension" class="form-control" maxlength="4"" required>
                </div>
                <div class="form-group col-sm-3"">
                    <label  for="area">Área</label>
                    <select id="selectArea" name="area" class="form-control" required></select>
                </div>
                <div class="form-group col-sm-3">
                    <label  for="usuario">Usuario</label>
                    <select id="selectUsuario" name="usuario" class="form-control" required></select>
                </div>
                <div class="form-group col-sm-3">
                    <label  for="descripcion" name="descripcion">Descripción</label>
                    <textarea name="descripcion" id="txtDescripcion" class="form-control" required></textarea>
                </div>
                <div class="form-group col-sm-2">
                    <label></label>
                    <input type="submit" value="Insertar extensión" class="btn btn-success">
                </div>
            </form>
        </div>

        <div id="dvDetalleExtensiones" class="container-fluid">
            <table id="dtDetalleExtensiones"></table>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="exampleModalLabel">Edición de extensiones</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form onsubmit="return false" id="frmActualizarExtension">
                        <div class="modal-body">
                            <input type="hidden" name="id_extension" id="txtIdExtension">
                            <div class="form-group">
                                <label for="extension">Extensión</label>
                                <input type="text" name="extension" id="dttxtExtension" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="area">Área</label>
                                <select id="dtselectArea" class="form-control" name="area"></select>
                            </div>
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <select class="form-control" id="dtselectUsuario" name="usuario"></select>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea name="descripcion" id="dttxtDescripcion" class="form-control" ></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" value="Editar extensión" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </body>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- alertas -->
<link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
<script type="text/javascript" src="../../js/sweetalert.min.js "></script>
<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
<!--<link rel="stylesheet" type="text/css" href="../../css/font-awesome.min.css">-->
<link rel="stylesheet" type="text/css" href="../../css/style.css?<?echo time();?>">
<!--<link rel="stylesheet" type="text/css" href="../../css/reportes.css" />-->
<link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
<script src="../../js/jquery-3.2.1.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
<script src="../js/extensionesTelefonicas.js?<?echo time();?>"></script>
</html>