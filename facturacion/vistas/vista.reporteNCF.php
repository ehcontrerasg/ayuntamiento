<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 28/10/2019
 * Time: 01:58 PM
 */

include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html>
    <head>

        <meta>
        <title>Reporte NCF Por Proyecto Y Zona</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css " />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        <script type="text/javascript" src="../js/reporteNCF.js?<?php echo time() ?>"></script>
        <link href="../css/facturacion.css " rel="stylesheet" type="text/css" />
    </head>
    <body >
    <header class="cabeceraTit">
        Reporte NCF Por Proyecto Y Zona
    </header>
    <section class="contenido">
        <article>
            <span class="datoForm col1">
                <span class="titDato numCont3">Periodo:</span>
                <span class="inpDatp numCont3"><select id="apeLotInpPer"></select></span>
                <span class="inpDatp numCont3"><input id="apeLotInpPerDesc" readonly ></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">Acueducto:</span>
                <span class="inpDatp numCont3"><select id="apeLotSelPro" ></select></span>
                <span class="inpDatp numCont3"><input id="apeLotInpProDsc" readonly ></span>
            </span>


            <span class="datoForm col1">
                <span class="titDato numCont3">Zona:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpZon" type="text"></span>
                <span class="inpDatp numCont3"><input id="apeLotInpZonDesc" readonly type="text"></span>
            </span>



            <span class="datoForm col1">
                <span class="titDato numCont3">B01:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpB01" readonly></span>
                <span class="inpDatp numCont3"><input id="apeLotInpB01Tot" readonly></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">B02:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpB02" readonly></span>
                <span class="inpDatp numCont3"><input id="apeLotInpB02Tot" readonly></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">B14:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpB14" readonly></span>
                <span class="inpDatp numCont3"><input id="apeLotInpB14Tot" readonly></span>
            </span>

            <span class="datoForm col1">
                <span class="titDato numCont3">B15:</span>
                <span class="inpDatp numCont3"><input id="apeLotInpB15" readonly></span>
                <span class="inpDatp numCont3"><input id="apeLotInpB15Tot" readonly></span>
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
