<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head xmlns="http://www.w3.org/1999/html">

        <meta  charset=UTF-8" />
        <title>Genaracion de ordenes de mantenimiento</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        <script type="text/javascript" src="../js/asigna_orden_man.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>

    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Asignación mantenimiento Preventivo de medidor
    </header>

    <section class="contenido">
        <article>

            <form onsubmit="return false" id="asiOrdManForm" >
             <span class="datoForm col1">
                <span class="titDato numCont3">Acueducto:</span>
                <span class="inpDatp numCont3"><select required select id="asiOrdManSelPro" ></select></span>

                <span class="titDato numCont3">Sector:</span>
                <span class="inpDatp numCont3"><select required select id="asiOrdManSelSec" ></select></span>
            </span>
                <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormMed" tabindex="9" id="asiOrdManButPro">
            </span>
            </form>

            <form onsubmit="return false" id="asiOrdManRutForm" >

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

