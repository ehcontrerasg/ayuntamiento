<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
<!DOCTYPE html>
<head>
    <meta  "charset=UTF-8" />
    <title>Entrada de documentos Contables</title>
    <!-- alertas -->
    <link href="../js/datatables/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="../../font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
</head>

<header class="cabeceraTit fondArc">
    Consulta De Documentos Contables
</header>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar archivo</h4>
            </div>
            <form id="modDocArcForm" name="modDocArcForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <!--div>
                        <input class="form-control" type="hidden" value="" id="IdRegistro" name="IdRegistro"  readonly>
                    </div-->

                    <!--div class="">
                        <label for="codigo-sistema" class="col-2 col-form-label">Código de sistema</label>
                        <div class="col-10">
                            <input class="form-control" type="number" value="" id="ingCodDoc" name="ingCodDoc" readonly>
                        </div>
                    </div>

                    <div class="">
                        <label for="codigo-archivo" class="col-2 col-form-label">Código del archivo</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value="" id="codArc" name="codArc" readonly>
                        </div>
                    </div>

                    <!--<div class="">
                          <label for="departamento" class="col-2 col-form-label">Departamento</label>
                          <div class="col-10">
                                <select class="form-control form-control-lg" id="departamento" name="departamento" required>
                                  <option>Departamentos</option>
                                </select>
                          </div>
                    </div>-->

                    <!--div class="">
                        <label for="documento" class="col-2 col-form-label">Documento</label>
                        <div class="col-10">
                            <select class="form-control form-control-lg" id="documento" name="documento" required>
                                <option>Documentos</option>
                            </select>
                        </div>
                    </div>

                    <!--<div class="">
                          <label for="proyecto" class="col-2 col-form-label">Proyecto</label>
                          <div class="col-10">
                                <select class="form-control form-control-lg" id="proyecto" name="proyecto" required>
                                  <option>Proyecto</option>
                                </select>
                          </div>
                    </div>-->

                    <!--div class="">
                        <label for="fecha_doc" class="col-2 col-form-label">Fecha del documento</label>
                        <div class="col-10">
                            <input class="form-control" type="date" value="<?php echo date("Y-m-d");?>" id="fechaDoc" name="fechaDoc" required>
                        </div>
                    </div>

                    <div class="">
                        <label for="archivo" class="col-2 col-form-label">Archivo</label>
                        <div class="col-10">
                            <input  type="file" name="archivo_fls" id="archivo_fls" class="form-control">
                        </div>
                    </div>

                    <div class="">
                        <label for="observacion" class="col-2 col-form-label">Obervación</label>
                        <div class="col-10">
                            <input  type="text" name="observacion" id="observacion" class="form-control" required>
                        </div>
                    </div-->
                </div>
                <!--div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="modificar" class="btn btn-primary">Guardar cambios</button>
                </div-->
            </form>
        </div>
    </div>
</div>
<!-- ModalCheque -->
<div class="modal fade" id="infoModalCheque" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información Cheques</h4>
            </div>
            <div class="modal-body">
                <table id="dataTableInfoCheque" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="50%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Emisión</th>
                        <th>Beneficiario</th>
                        <th>Banco</th>
                        <th>Empresa</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Comunicaciones-->
<div class="modal fade" id="infoModalComunicaciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información Comunicaciones</h4>
            </div>
            <div class="modal-body">
                <table id="dataTableInfoComunicaciones" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="50%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Emisión</th>
                        <th>Beneficiario</th>
                        <th>Empresa</th>
                        <th>Fecha Recepción</th>
                        <th>Asunto</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Entradas-->
<div class="modal fade" id="infoModalEntradas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información Entradas Diario</h4>
            </div>
            <div class="modal-body">
                <table id="dataTableInfoEntradas" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="50%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Periodo</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Facturas-->
<div class="modal fade" id="infoModalFacturas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información Facturas</h4>
            </div>
            <div class="modal-body">
                <table id="dataTableInfoFacturas" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="50%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Emisión</th>
                        <th>Beneficiario</th>
                        <th>Empresa</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Notas-->
<div class="modal fade" id="infoModalNotas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información Notas</h4>
            </div>
            <div class="modal-body">
                <table id="dataTableInfoNotas" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="50%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Emisión</th>
                        <th>Empresa</th>
                        <th>Código Factura</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Pagos-->
<div class="modal fade" id="infoModalPagos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información Pagos y Depositos</h4>
            </div>
            <div class="modal-body">
                <table id="dataTableInfoPagos" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="45%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha Emisión</th>
                        <th>Beneficiario</th>
                        <th>Banco</th>
                        <th>Empresa</th>
                        <th>Cuenta</th>
                        <th>Número Aprobación</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>

<!-- Modal listar pdf-->
<div class="modal fade" id="listarPDFModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Ver Documento PDF</h4>
            </div>
            <div class="modal-body">
                <table id="dataTablePDF" class="display table table-responsive table-bordered table-hove table-condensed table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Clase</th>
                        <th>Tipo</th>
                        <th>Ver</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!-- button type="button" class="btn btn-primary">Save changes</button -->
            </div>
        </div>
    </div>
</div>
<div id="ctnTblConsultaArch">
    <table id="dataTable" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Clase</th>
            <th>Tipo</th>
            <th>Código</th>
            <th>Segmento</th>
            <th>Fecha Cargue</th>
            <th>Usuario Cargue</th>
            <th>Opciones</th>
        </tr>
        </thead>
    </table>
</div>


<footer>
</footer>
<script type="text/javascript" src="../js/datatables/media/js/jquery.js"></script>
<script type="text/javascript" src="../css/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="../js/datatables/media/js/jquery.dataTables.js"></script>
<!--script type="text/javascript" src="../js/ingresa_doc_arc.js?20"></script-->
<script type="text/javascript" src="../js/consulta_doc_arc_contable.js?25"></script>
<script type="text/javascript" src="../../js/sweetalert.min.js"></script>
</script>
</body>
</html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

