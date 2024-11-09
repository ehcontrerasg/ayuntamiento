<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Facturas Grandes Clientes</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css?<?php echo time()?>" rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gclientes.css?<?php echo time()?>" />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/imprimefac2.js?<?php echo time()?>"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Impresi&oacute;n Facturas Grandes Clientes
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genImpFacForm">
                <div class="subCabecera">
                    Filtros de B&uacute;squeda Impresi&oacute;n Facturas Grandes Clientes
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label>Proyecto: </label>
                        <select id="impFacSelPro"></select>
                    </div>
                    <div class="contenedor">
                        <label>Grupo: </label>
                        <select id="impFacSelGru"></select>
                    </div>
                    <div class="contenedor">
                        <label>Perido: </label>
                        <input type="number" id="impFacInpPer">
                    </div>
                    <div class="contenedor">
                        <label>Zona: </label>
                        <span class="inpDatp numCont3"><input id="apeLotInpZon" type="text"></span>
                        <span class="inpDatp numCont3"><input id="apeLotInpZonDesc" readonly type="text"></span>
                    </div>
                    <div class="contenedor">
                        <button id="impFacButCon">Generar Facturas</button>
                    </div>
                </div>
                <div  >
                    <object id="divRepImpFac" class="conPdf" type="application/pdf" width="100%" height="400px"></object>
                </div>
            </form>
        </article>
    </section>
    <footer>
    </footer>
    <script type="text/javascript">
        impFacInicio();
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

