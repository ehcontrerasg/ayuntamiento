<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html>
    <head>
        <!--<html lang="es">-->
        <meta  charset="UTF-8" />
        <title>Reporte Asignacion de Inspecciones diaria</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>

        <!--pag-->
        <script type="text/javascript" src="../js/RepAsigDiarInsp.js?<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />


        </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Reporte asignacion diaria de rutas Inspecciones
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genRepAsigDiarInsp">

                <div class="subCabecera fondCorte"> Filtros de busqueda</div>
                <span class="datoForm col1">
                <span class="titDato">Proyecto:</span>
                <span class="inpDatp"><select tabindex="2" name="proyecto" required select id="repDiarInspSelPro" ></select></span>
                <span class="titDato">Gerencia:</span>
                <span class="inpDatp"><select tabindex="2" name="gerencia"  select id="repDiarInspSelGer" ></select></span>
                <span class="titDato">Contratista:</span>
                <span class="inpDatp"> <select tabindex="2"  name="contratista" required select id="repDiarInspSelContr"></select></span>
                <span class="titDato">fecha:</span>
                <span class="inpDatp"><input  id="inpFechaIni" name="fecha" required  type="date"   ></span>
                <span class="datoForm"><input type="submit" value="Generar" class="botonFormulario botFormInsp" tabindex="4">
            </span>
            </span>

           <!--     <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormInsp" tabindex="4">
            </span>-->

            </form>

            <div id="contenedorPdf">
                <object id="PdfRepAsigInsp" class="conPdf" type="application/pdf"></object>
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

