<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 15/10/2019
 * Time: 01:58 PM
 */

include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html>
    <head>

        <meta>
        <title>Modifica Fechas Facturacion</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css " />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        <script type="text/javascript" src="../js/modificafecha.js?0"></script>
        <link href="../css/facturacion.css " rel="stylesheet" type="text/css" />
    </head>
    <body >
    <header class="cabeceraTit">
        Modificación Fechas Facturación
    </header>
    <section class="contenido">
        <article>
            <span class="datoForm col1">
                <span class="titDato numCont3">Acueducto:</span>
                <span class="inpDatp numCont3"><select select id="apeLotSelPro" ></select></span>
                <span class="inpDatp numCont3"><input id="apeLotInpProDsc" readonly ></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">Zona:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpZon" type="text"></span>
                <span class="inpDatp numCont3"><input id="apeLotInpZonDesc" readonly type="text"></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">Periodo:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpPer" readonly></span>
                <span class="inpDatp numCont3"><input id="apeLotInpPerDesc" readonly ></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">Fecha Expedición:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpFecExp" readonly></span>
                <span class="inpDatp numCont3"><input type="date" id="apeLotInpFecExpDesc"></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">Fecha Vencimiento:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpFecVen" readonly></span>
                <span class="inpDatp numCont3"><input type="date" id="apeLotInpFecVenDesc"></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">Fecha Corte:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpFecCor" readonly></span>
                <span class="inpDatp numCont3"><input type="date" id="apeLotInpFecCorDesc"></span>
            </span>

            <span class="datoForm col1">
            <button class="botonFormulario" id="apeLotButPerDesc">Modificar</button>
        </span>

        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        apeLotInicio();
    </script>

    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
