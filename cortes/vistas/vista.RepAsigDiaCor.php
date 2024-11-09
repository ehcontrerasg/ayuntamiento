<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Reporte Asignacion de reconexion diaria</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/RepAsigDiarCors.js?<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />


        </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Reporte asignacion diaria de rutas corte
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genRepAsigDiarCor">

                <div class="subCabecera fondCorte"> Filtros de búsqueda</div>
                <span class="datoForm col1">
                <span class="titDato">Proyecto:</span>
                <span class="inpDatp"><select tabindex="2" name="proyecto" required select id="repDiarCorSelPro" ></select></span>
                <span class="titDato">Gerencia:</span>
                <span class="inpDatp"><select tabindex="2" name="gerencia"  select id="repDiarCorSelGer" ></select></span>
                <span class="titDato">Contratista:</span>
                <span class="inpDatp"> <select tabindex="2" name="selCon" required select id="selCon"></select></span>
                <span class="titDato">fecha:</span>
                <span class="inpDatp"><input  id="inpFechaIni" name="fecha" required  type="date"></span>
                <span><input type="submit" value="Generar" class="botonFormulario botFormCorte" tabindex="4"></span>

            </span>
            </form>

            <div id="contenedorPdf">
                <object id="PdfRepAsigCor" class="conPdf" type="application/pdf"></object>
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

