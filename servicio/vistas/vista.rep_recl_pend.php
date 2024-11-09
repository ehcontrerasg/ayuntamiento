<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <html lang="es">
        <meta  charset="UTF-8" />
        <title>Reporte reclamos pendientes</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/rep_recl_pend.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
        </head>
    <body>
    <header class="cabeceraTit fondSerCli">
        Reporte reclamos pendientes
    </header>

    <section class="contenido">
        <article>
            <form onSubmit="return false" id="repRecPendForm">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont1"><select name="proyecto" tabindex="1" select id="repRecPendSelPro" required ></select></span>

                <span class="titDato numCont2">Motivo:</span>
                <span class="inpDatp numCont1"><select name="motivo" tabindex="1" select id="repRecPendSelMot" required ></select></span>

                <span class="titDato numCont2">Fecha Ini:</span>
                <span class="inpDatp numCont1"><input required name="fecIni" tabindex="3"id="repRecPendInpFecFin"  type="date"></span>

                <span class="titDato numCont2">Fecha Fin:</span>
                <span class="inpDatp numCont1"><input required name="fecFin" tabindex="3"id="repRecPendInpFecFin"  type="date"></span>
            </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormSerCli" tabindex="9">
            </span>

            </form>


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

