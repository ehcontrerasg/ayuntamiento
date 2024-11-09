<!doctype html>
<html>
    <head>
    <!--CSS-->
        <!--Fuentes-->
        <link href="../../librerias/fontAwesome/css/all.css" rel="stylesheet" type="text/css">
        <!--Alertas-->
        <link href="../../css/sweetalert.css" rel="stylesheet" type="text/css">
        <!--BOOTSTRAP-->
        <link href="../../librerias/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!--Datatable-->
        <link href="../../librerias/DataTables/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

        <link href="../css/consulta_archivo_gerencia.css?<?php echo time();?>" rel="stylesheet" type="text/css">
    <!--CSS-->
    </head>
    <body class="container">
        <div class="row">
            <p class="col-sm-12" id="screenTitle">Consulta de archivos de gerencia</p>
            <div id="dvArchivosGerencia" class="col-sm-12">
                <table id="tblArchivosGerencia" class="display compact">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número de protocolo</th>
                            <th>Asunto</th>
                            <th>Empresa</th>
                            <th>Tipo de correspondencia</th>
                            <th>Tipo de documento</th>
                            <th>Fecha de documento</th>
                            <th>Ruta Archivo</th>
                            <th>Usuario carga</th>
                            <th>Fecha de carga</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!--Modal de histórico de archivo-->
        <div class="modal" tabindex="-1" id="modalHistorico" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Histórico de archivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body container">
                        <div class="row">
                            <table id="tblHistorico" class="table table-striped col-sm-12 table-bordered">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="col">No.</th>
                                    <th class="col">No. de protocolo</th>
                                    <th class="col">Asunto</th>
                                    <th class="col">Tipo de correspondencia</th>
                                    <th class="col">Empresa</th>
                                    <th class="col">Documento</th>
                                    <th class="col">Fecha de documento</th>
                                    <th class="col">Usuario</th>
                                    <th class="col">Fecha de carga</th>
                                    <th class="col">Archivo cambia</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal de actualización de archivos-->
        <div class="modal" tabindex="-1" id="modalActualizacion" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Actualización de archivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body container">
                        <form class="col-sm-12" enctype="multipart/form-data">
                            <input type="hidden" value="" id="hdnArchivo">
                            <input type="hidden" value="" id="hdnIdArchivo">
                            <fieldset>
                                <legend>Información del documento: </legend>
                                <div class="row justify-content-around">
                                    <div class="form-group col-sm-12 col-md-4">
                                        <label for="nmbProtocolo">Número de protocolo</label>
                                        <input type="number" placeholder="Ingrese el número de protocolo" class="form-control" id="nmbProtocolo" name="numero_protocolo" disabled/>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-4">
                                        <label for="slcEmpresas">Empresa</label>
                                        <select id="slcEmpresas" class="form-control" name="empresa" required>
                                            <option value="">Seleccione una empresa</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-4">
                                        <label for="slcTipoCorrespondencia">Correspondencia</label>
                                        <select id="slcTipoCorrespondencia" class="form-control" name="tipo_correspondencia" required>
                                            <option value="">Seleccione un tipo de correspondencia</option>
                                            <option value="Enviado">Enviado</option>
                                            <option value="Recibido">Recibido</option>
                                            <option value="Otros">Otros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row justify-content-around">
                                    <div class="form-group col-sm-12 col-md-12">
                                        <label for="slcTipoDocumento">Asunto</label>
                                        <input type="text" placeholder="Ingrese el asunto" class="form-control" id="txtAsunto" name="asunto" maxlength="299" required>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="slcTipoDocumento">Tipo de documento</label>
                                        <select id="slcTipoDocumento" class="form-control" name="tipo_documento" required>
                                            <option value="">Seleccione un tipo de documento</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="dtFechaDocumento">Tipo de documento</label>
                                        <input type="date" class="form-control"  id="dtFechaDocumento" name="fecha_documento">
                                    </div>
                                </div>
                            </fieldset>
                            <hr>
                            <fieldset>
                                <legend>¿Dónde se guardará el documento?</legend>
                                <div class="row justify-content-around">
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="slcCarpeta">Carpeta</label>
                                        <select id="slcCarpeta" class="form-control" name="carpeta" required>
                                            <option value="">Seleccione una carpeta</option>
                                            <option value="ACTAS">Actas</option>
                                            <option value="DOUMENTOS BASE">Documentos base</option>
                                            <option value="CAASD">CAASD</option>
                                            <option value="CORAABO">CORAABO</option>
                                            <option value="BANCO DE RESERVAS">Banco de reservas</option>
                                            <option value="DGII">DGII</option>
                                            <option value="ORDENES DE COMPRAS">Órdenes de compras</option>
                                            <option value="SOLICITUD DE CLIENTE">Solicitud de cliente</option>
                                            <option value="ABOGADOS">Abogados</option>
                                            <option value="AUDITORES">Auditores</option>
                                            <option value="CONTABILIDAD">Contabilidad</option>
                                            <option value="FAX">Fax</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6" id="groupSubcarpeta">
                                        <label for="slcSubcarpeta">Subcarpeta</label>
                                        <select id="slcSubcarpeta" class="form-control" name="subcarpeta" required>
                                            <option value=''>Seleccione una subcarpeta</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-12">
                                        <div class="row">
                                            <label for="inputArchivo" class="col-sm-12">Archivo</label>
                                            <button class="btn btn-primary col-sm-6" id="btnVerDocumento"><i class='fas fa-eye'></i> Documento anterior</button>
                                            <input type="file" id="inputArchivo"
                                                   class="form-control col-sm-12"
                                                   name="archivo"
                                                   accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf, application/msword, application/vnd.ms-powerpoint, application/vnd.ms-excel"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <input type="submit" value="Guardar cambios" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
<!--JavaScript-->
    <!--Alertas-->
    <script src="../../js/sweetalert.min.js" rel="script" type="text/javascript"></script>
    <!--Jquery-->
    <script src="../../js/jquery-3.3.1.min.js" rel="script" type="text/javascript"></script>
    <!--Data table-->
    <script src="../../librerias/DataTables/js/jquery.dataTables.min.js" rel="script" type="text/javascript"></script>
    <!--Bootstrap-->
    <script src="../../librerias/bootstrap-4.5.3-dist/js/bootstrap.min.js" rel="script" type="text/javascript"></script>
    <!--Consulta archivos gerencia-->
    <script src="../js/consulta_archivo_gerencia.js?<? echo time();?>" rel="script" type="text/javascript"></script>
<!--JavaScript-->
</html>