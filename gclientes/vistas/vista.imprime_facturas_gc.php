<?
include_once('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Facturas Grandes Clientes</title>
            <!--Bootstrap-->
            <link href="../../librerias/bootstrap-4.5.3-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet">
            <!-- alertas -->
            <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
            <link href="../../css/css.css" rel="stylesheet" type="text/css" />
            <!-- iconos botones   -->
            <link href="../../font-awesome/css/font-awesome.min.css?<?php echo time()?>" rel="stylesheet" />
            <!--estilo pag    -->
            <link rel="stylesheet" type="text/css" href="../css/gclientes.css?<?php echo time()?>" />

        </head>
        <body class="container-fluid">
            <div class="row">
                <header class="col-sm-12">
                    <div class="cabecera">
                        Impresión Facturas Grandes Clientes
                    </div>
                </header>
                <section class="col-sm-12">
                    <article class="row">
                        <form onSubmit="return false" id="genImpFacForm" class="col-sm-12">
                            <div class="subCabecera col-sm-12">
                                Filtros de B&uacute;squeda Impresi&oacute;n Facturas Grandes Clientes
                            </div>
                            <div class="divfiltros col-sm-12">
                                <div class="row">
                                    <div class="col-sm-2 form-group">
                                        <label for="impFacSelPro">Proyecto</label>
                                        <select id="impFacSelPro" class="form-control"></select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label for="impFacSelGru">Grupo</label>
                                        <select id="impFacSelGru" class="form-control"></select>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label for="impFacTxtRnc">RNC</label>
                                        <input type="text" id="impFacTxtRnc" class="form-control">
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label for="impFacTxtPeriodoInicial">Periodo inicial:</label>
                                        <input type="number" id="impFacTxtPeriodoInicial" class="form-control">
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label for="impFacTxtPeriodoFinal">Periodo final</label>
                                        <input type="number" id="impFacTxtPeriodoFinal" class="form-control">
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>&nbsp;</label>
                                        <button id="impFacButCon" class="btn btn-success">Generar Facturas</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </article>
                </section>
                <section class="col-sm-12" id="secPdf">
                    <object id="divRepImpFac" class="conPdf" type="application/pdf" width="100%" height="400px"></object>
                </section>
            </div>
        </body>
        <!--Jquery -->
        <script src="../../js/jquery.min.js" type="text/javascript"></script>
        <!--Alertas-->
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <!--logica pag    -->
        <script type="text/javascript" src="../js/imprimefac.js?<?php echo time();?>"></script>
        <script type="text/javascript">
            impFacInicio();
        </script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

