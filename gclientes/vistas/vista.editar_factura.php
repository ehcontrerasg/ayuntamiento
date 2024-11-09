<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

<!doctype html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
        <link  rel="stylesheet" href="../css/editar_factura.css?"<?echo time()?>>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    </head>
    <body>
        <section>
            <article >
                <div class="cabecera">
                    Edición de facturas
                </div>
                <div id="ingresoFactura" class="form-group col-sm-12">
                        <label class="col-sm-1">Factura:</label>
                        <input class="col-sm-5" type="number"  id="txtFactura" class="form-control"/>
                        <button class="btn btn-primary col-lg-2" id="btnBuscarFactura">Buscar</button>
                </div>
            </article>
            <article id="datosFactura">
                <div id="datosEditar">
                    <form onsubmit="return false" action="#" method="post" id="frmDatosFactura">
                        <div class="form-group">
                            <label>Tipo de documento</label><br/>
                            <!--<input type="text" class="form-control" id="tipo_documento" name="tipo_documento" placeholder="Tipo de documento">-->
                            <select class="form-control" id="tipoDoc" name="tipo_documento"></select>
                        </div>
                        <div class="form-group">
                            <label>Tipo de documento</label><br/>
                            <input type="text" class="form-control" id="numero_documento" name="numero_documento" placeholder="Número de documento">
                        </div>
                        <div class="form-group">
                            <label>Nombre de usuario</label><br/>
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre de usuario">
                        </div>
                        <div class="form-group">
                            <label>NCF</label><br/>
                            <input type="text" class="form-control" id="ncf" name="ncf" placeholder="NCF">
                        </div>
                        <div class="form-group">
                            <label>Fecha de vencimiento de NCF</label><br/>
                            <input type="date" name="fecha_vencimiento_ncf" id="dtFechaVencimientoNCF" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Título de comprobante</label><br/>
                            <input type="text" class="form-control" id="titulo_comprobante" name="titulo_comprobante" placeholder="Título de comprobante">
                        </div>
                        <button id="btnValidar" class="btn btn-success">Validar</button>
                    </form>
                </div>
                <div id="mostrarPDF">
                    <object id="divRepImpFac" class="conPdf" type="application/pdf" width="100%" height="500px" internalinstanceid="83" data=""></object>
                </div>
            </article>
        </section
    </body>
    <!--JQUERY -->
    <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <!--Lógica JS-->
    <script src="../js/editar_factura.js?<?echo time()?>"></script>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>