<!doctype html>
<html>
    <head>
        <!--CSS-->
        <link href="../css/ingresa_archivo_gerencia.css?<?php echo time();?>" rel="stylesheet" type="text/css">
        <!--Alertas-->
        <link href="../../css/sweetalert.css" rel="stylesheet" type="text/css">
        <!--BOOTSTRAP-->
        <link href="../../librerias/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!--CSS-->
    </head>
    <body>
        <div class="container" id="content">
            <div class="row">
                <p class="col-sm-12" id="screenTitle">Ingreso de archivos de gerencia</p>
                <form class="col-sm-12" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Información del documento: </legend>
                        <div class="row justify-content-around">
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="nmbProtocolo">Número de protocolo</label>
                                <input type="number" placeholder="Ingrese el número de protocolo" class="form-control" id="nmbProtocolo" name="numero_protocolo"/>
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
                                <label for="txtAsunto">Asunto</label>
                                <input type="text" placeholder="Ingrese el asunto" class="form-control" id="txtAsunto" name="asunto" maxlength="299" required>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="slcTipoDocumento">Tipo de documento</label>
                                <select id="slcTipoDocumento" class="form-control" name="tipo_documento" required>
                                    <option value="">Seleccione un tipo de documento</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="dtFechaDocumento">Fecha del documento</label>
                                <input type="date" id="dtFechaDocumento" name="fecha_documento" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <legend>¿Dónde se guardará el documento?</legend>
                        <div class="row justify-content-around">
                            <div class="form-group col-sm-12 col-md-4">
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
                            <div class="form-group col-sm-12 col-md-4" id="groupSubcarpeta">
                                <label for="slcSubcarpeta">Subcarpeta</label>
                                <select id="slcSubcarpeta" class="form-control" name="subcarpeta">
                                    <option value="">Seleccione una subcarpeta</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="inputArchivo">Archivo</label>
                                <input
                                        type="file"
                                        id="inputArchivo"
                                        class="form-control"
                                        name="archivo"
                                        accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf, application/msword, application/vnd.ms-powerpoint, application/vnd.ms-excel" required>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row justify-content-around">
                        <input type="submit" value="Guardar archivo" class="btn btn-success col-sm-6">
                    </div>
                </form>
            </div>
        </div>
    </body>
    <!--Javascript-->
    <!--Alertas-->
    <script src="../../js/sweetalert.min.js" rel="script" type="text/javascript"></script>
    <!--Jquery-->
    <script src="../../js/jquery-3.3.1.min.js" rel="script" type="text/javascript"></script>
    <!--Data table-->
    <script src="../../js/DataTables-1.10.19/dataTables.min.js" rel="script" type="text/javascript"></script>
    <!--Bootstrap-->
    <script src="../../librerias/bootstrap-4.5.3-dist/js/bootstrap.min.js" rel="script" type="text/javascript"></script>
    <!--Ingreso Archivos gerencia-->
    <script src="../js/ingresa_archivo_gerencia.js?<?php echo time();?>" rel="script" type="text/javascript"></script>
    <!--Javascript-->
</html>