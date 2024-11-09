<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta>
        <title>Supervision de Facturas</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag--> <!--pag-->
        <script type="text/javascript" src="../js/supervisionFac.js?10 "></script>
        <link href="../css/facturacion.css " rel="stylesheet" type="text/css" />
    </head>
    <body >
    <header class="cabeceraTit">
        Asignacion de Supervision de entrega de facturas
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="formularioSup">
         <span class="datoForm col1">
            <span class="titDato numCont3">Acueducto:</span>
            <span class="inpDatp numCont3"><select select id="selProy" ></select></span>
            <span class="inpDatp numCont3"><input id="inpProy" readonly ></span>
        </span>


            <span class="datoForm col1">
            <span class="titDato numCont3">Zona:</span>
            <span class="inpDatp numCont3"><select name="zon" select id="selZon" ></select></span>
            <span class="inpDatp numCont3"><input id="inpZon" readonly ></span>
        </span>

            <span class="datoForm col1">
            <span class="titDato numCont3">Periodo:</span>
            <span class="inpDatp numCont3"><select name="per" id="selPer" ></select></span>
            <span class="inpDatp numCont3"><input id="inpPer" readonly ></span>
        </span>

         

            <span class="datoForm col1">
            <span class="titDato numCont3">Ruta:</span>
                <span class="inpDatp numCont3"><select name="rut" id="selRuta" ></select></span>
            <span class="inpDatp numCont3"><input id="inpZRuta" readonly ></span>
        </span>


     <span class="datoForm col1">
            <span class="titDato numCont3">% de muestra:</span>
            <span class="inpDatp numCont3"><input name="muestra" id="inpmuestra" min="5" max="100"  type="number"></span>
            <span class="inpDatp numCont3"><input id="apeLotInpZonDesc" readonly type="text"></span>
        </span>


                <span class="datoForm col1">
            <span class="titDato numCont3">Operario:</span>
            <span class="inpDatp numCont3"><select name="oper" id="selOper" ></select></span>
            <span class="inpDatp numCont3"><input id="inpoper" readonly ></span>
        </span>


            <span class="datoForm col1">
            <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormFac" tabindex="9"">
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
