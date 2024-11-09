<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <html lang="es">
        <meta  charset=UTF-8" />
        <title>Reporte Reconexiones Diarias</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/ruta_pdc.js"></script>
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />
        </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Rutas PDC
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genFacCaaForm">

                <div class="subCabecera"> Filtros de busuqeda</div>
                <span class="datoForm col1">
                <span class="titDato numCont2">Proyecto:</span>
                <span class="inpDatp numCont1"><select tabindex="2" name="proyecto" required select id="rutPdcSelPro" ></select></span>
                <span class="titDato numCont1">Proceso:</span>
                <span class="inpDatp numCont1"><input  id="rutPdcInpProIni" name="proIni" required placeholder="Proceso Inicial" type="number"   ></span>
                <span class="inpDatp numCont1"><input  id="rutPdcInpProFin" name="proFin" required  placeholder="Proceso Final" type="number" ></span>
            </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormCorte" tabindex="4">
            </span>

            </form>

            <div>
                <object id="rutPdcPdf" class="conPdf" type="application/pdf"></object>
            </div>

        </article>
    </section>

    <footer>
    </footer>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

