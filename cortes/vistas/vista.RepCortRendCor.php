<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset=UTF-8" />
        <title>Reporte Rendimiento Corte</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/repCortRendCor.js?5"></script>
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />


    </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Rendimiento de corte
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genRepRendCort">

                <div class="subCabecera fondCorte"> Filtros de busqueda</div>

                <span class="datoForm col1">
                    <span class="titDato numCont1">Proyecto:</span>
                    <span class="inpDatp numCont1"><select tabindex="2" name="proyecto" required select id="proyecto" ></select></span>
                    <span class="titDato numCont1">Contratista:</span>
                    <span class="inpDatp numCont1"><select tabindex="2" name="contratista" required select id="contratista" ></select></span>
                    <span class="titDato numCont1">Fecha inicial:</span>
                    <span class="inpDatp numCont1"><input  id="fechaIni" name="FecIni" required  type="date"   ></span>
                    <span class="titDato numCont1">Fecha final:</span>
                    <span class="inpDatp numCont1"><input  id="fechaFin" name="FecFin" required  type="date"   ></span>
                    <span class="titDato numCont1">Formato:</span>
                    <span class="inpDatp numCont1"><select tabindex="2" name="formato" required select id="formato" >
                             <!--<option value="pdf">PDF</option>-->
                            <option value="xls">EXCEL</option>
                    </select></span>
                </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormCorte" tabindex="4">
            </span>

            </form>

            <div>
                <object id="reporte" class="conPdf" type="application/pdf"></object>
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

