<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html>
    <head>

        <meta>
        <title>Apertura Ciclo Facturacion</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css " />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        <script type="text/javascript" src="../js/apertura.js?3 "></script>
        <link href="../css/facturacion.css?1 " rel="stylesheet" type="text/css" />
    </head>
    <body >
    <header class="cabeceraTit">
        Apertura Nuevo Período de Facturación
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
            <button class="botonFormulario" id="apeLotButPerDesc">Procesar</button>
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
