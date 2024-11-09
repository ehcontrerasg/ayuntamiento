<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Impresión de ordenes de medidores</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/imprime_orden_man.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Impresión de Ordenes de Mnatenimiento de Medidores
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genHojMedManForm">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select autofocus="true" tabindex="1" select id="genHojMedManSelPro" required ></select></span>
                <span class="titDato numCont2">Proceso Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="2" id="genHojMedManInpProIni" placeholder="Inicial" type="text"></span>
                <span class="titDato numCont2">Proceso Final:</span>
                <span class="inpDatp numCont2"><input  tabindex="3"id="genHojMedManInpProFin" placeholder="Final" type="text"></span>
            </span>

                <span class="datoForm col1">

                <span class="titDato numCont2">Cod.Sistema:</span>
                <span class="inpDatp numCont2"><input tabindex="4" id="genHojMedManInpCodSis" placeholder="Cod. Sistema"  type="text"></span>
                <span class="titDato numCont2">Manzana Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="5" id="genHojMedManInpManIni" placeholder="Inicial" ></span>
                <span class="titDato numCont2">Manzana Final:</span>
                <span class="inpDatp numCont2"><input tabindex="6" id="genHojMedManInpManFin" placeholder="Final"  ></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Operario:</span>
                <span class="inpDatp numCont2"><select tabindex="7" select id="genHojMedManSelOpe" ></select></span>
                <span class="titDato numCont2">Fecha Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="8" id="genHojMedManInpFecIni" placeholder="Cod. Sistema"  type="date"></span>
                <span class="titDato numCont2">Fecha Final:</span>
                <span class="inpDatp numCont2"><input tabindex="9" id="genHojMedManInpFecFin" type="date"></span>
            </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormMed" tabindex="8" id="genHojMedManButPro">
            </span>

            </form>

            <div id="contenedorPdf">
                <object id="genHojMedManObjHoj" class="conPdf" type="application/pdf"></object>
            </div>

        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        genHojMedManInicio();
    </script>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

