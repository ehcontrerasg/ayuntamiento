<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset=UTF-8" />
        <title>Reporte inmuebles con corte y movimiento medidor</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/repCortConLect.js?8"></script>
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />


    </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Reporte inmuebles con corte y movimiento medidor
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genRepMedCorForm">

                <div class="subCabecera fondCorte"> Filtros de busqueda</div>
                <span class="datoForm col1">
                <span class="titDato numCont2">Proyecto:</span>
                <span class="inpDatp numCont2"><select tabindex="2" name="proyecto" required select id="genRepMedCorSelPro" ></select></span>

                <span class="titDato numCont1">prceso inicial:</span>
                <span class="inpDatp numCont1"><input  id="genRepMedCorInpProIni" name="ProcesoIni" required  type="number"   ></span>
                <span class="titDato numCont1">proceso final:</span>
                <span class="inpDatp numCont1"><input  id="genRepMedCorInpProFin" name="ProcesoFin" required  type="number"   ></span>
                <span class="titDato numCont1">Contratista:</span>
                <span class="inpDatp numCont1"> <select tabindex="2" name="selCon"  select id="selCon"></select></span>
                <span class="titDato numCont1">Formato:</span>
                <span class="inpDatp numCont1"><select tabindex="2" name="formato" required select id="genRepMedCorSelFor" >
                        <option value="pdf">PDF</option>
                        <option value="xls">EXCEL</option>
                    </select></span>

            </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormCorte" tabindex="4">
            </span>

            </form>

            <div>
                <object id="PdfRepGenMedCorInpProIni" class="conPdf" type="application/pdf"></object>
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

