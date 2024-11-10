<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <html lang="es">
        <meta  charset="UTF-8" />
        <title>Entrada de documentos de Archivo</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap/css/bootstrap.css " rel="stylesheet" type="text/css" />
        </head>
    <body>
    <header class="cabeceraTit fondArc">
        Modificación de documentos de Archivo
    </header>

    <section class="contenido">
        <form id="ArcDocArcForm" name="ActDocArcForm" enctype="multipart/form-data" class="form-horizontal">
            <div class="" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="form-group">
                    <label for="codigo-sistema" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Código de sistema</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <input class="form-control" type="number" value="" id="ingCodDoc" name="ingCodDoc" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="codigo-archivo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Código del archivo</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <input class="form-control" type="text" value="" id="codArc" name="codArc" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="departamento" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Departamento</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <select class="form-control form-control-lg" id="departamento" name="departamento" required>
                            <option>Departamentos</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="documento" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Documento</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <select class="form-control form-control-lg" id="documento" name="documento" required>
                            <option>Documentos</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="proyecto" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Proyecto</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <select class="form-control form-control-lg" id="proyecto" name="proyecto" required>
                            <option>Proyecto</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fecha_doc" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Fecha del documento</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <input class="form-control" type="date" value="<?php //echo date("Y-m-d");?>" id="fechaDoc" name="fechaDoc" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="archivo" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Archivo</label>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <input  type="file" name="archivo_fls" id="archivo_fls" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="observacion" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">Obervación</label>
                    <div class="col-10">
                        <textarea class="form-control" id="obs" rows="3" id="observacion" name="observacion"></textarea>
                    </div>
                </div>

                <span class="datoForm col1">
		                <input type="submit" tabindex="5" value="Guardar" class="botonFormulario botFormArc" >
		            </span>
            </div>
        </form>
    </section>

    <footer>
    </footer>


    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/ingresa_doc_arc.js?1"></script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

