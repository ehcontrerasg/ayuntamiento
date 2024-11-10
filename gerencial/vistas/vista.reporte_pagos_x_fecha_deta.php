<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset="UTF-8" />
        <title>Reporte Pagos Por Fecha Detallado</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/repPagoFechaDet.js"></script>
        <link href="../../css/general.css?4" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondGer">
        Generacion Reporte Pagos Por Fecha Detallado
    </header>
    <section class="contenido">
        <article>
            <form onSubmit="return false" id="repPagPorFecDetForm">
                <span class="datoForm col1">
                    <span class="titDato numCont1">Acueducto:</span>
                    <span class="inpDatp numCont2"><select name="proyecto" tabindex="1" select id="repPagPorFecDetPro" required ></select></span>
                    <span class="titDato numCont2">Entidad:</span>
                    <span class="inpDatp numCont2"><input type="text" name="entini" tabindex="2" id="repPagPorFecDetEntIni" maxlength="3"></span>
                    <span class="inpDatp numCont2"><input type="text" name="entfin" tabindex="3" id="repPagPorFecDetEntFin"  maxlength="3"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont2">Punto Pago:</span>
                    <span class="inpDatp numCont2"><input type="text" name="punini" tabindex="4" id="repPagPorFecDetPunIni" maxlength="3"></span>
                    <span class="inpDatp numCont2"><input type="text" name="punfin" tabindex="5" id="repPagPorFecDetPunFin" maxlength="3"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont2">Caja Pago:</span>
                    <span class="inpDatp numCont2"><input type="text" name="cajini" tabindex="6" id="repPagPorFecDetCajIni" maxlength="3"></span>
                    <span class="inpDatp numCont2"><input type="text" name="cajfin" tabindex="7" id="repPagPorFecDetCajFin" maxlength="3"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont2">Fecha Pago:</span>
                    <span class="inpDatp numCont2"><input type="date" name="fecini" tabindex="8" id="repPagPorFecDetFecIni" required ></span>
                    <span class="inpDatp numCont2"><input type="date" name="fecfin" tabindex="9+++++" id="repPagPorFecDetFecFin" required ></span>
                </span>
                <span class="datoForm col1">
                    <input type="submit" value="Generar" class="botonFormulario botFormGer" tabindex="9">
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

