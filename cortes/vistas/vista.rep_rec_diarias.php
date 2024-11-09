<?

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Reporte Reconexiones Diarias</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/rep_rec_diarias.js?5"></script>
        <link href="../../css/general.css?1" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Reporte reconexiones diarias
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="repRecDiarForm">

                <div class="subCabecera fondCorte"> Filtros de busuqeda</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Proyecto:</span>
                <span class="inpDatp numCont1"><select tabindex="2" name="proyecto" required select id="repRecDiarSelPro" ></select></span>
                <span class="titDato numCont1">Fecha:</span>
                <span class="inpDatp numCont1"><input  id="repRecDiarInpFecIni" name="fecIni" required autofocus="true" type="date"></span>
                <span class="inpDatp numCont1"><input  id="repRecDiarInpFecFin" name="fecFin" required autofocus="true" type="date"></span>
                <span class="titDato numCont1">Contratista:</span>
                <span class="inpDatp numCont1"><select tabindex="2" name="contratista"  select id="repRecDiarSelCon" ></select></span>
                <span class="titDato numCont1">Inspector:</span>
                <span class="inpDatp numCont1"><select tabindex="2" name="usuario" select id="repRecDiarSelIns" ></select></span>
                <span class="titDato numCont1">Proceso:</span>
                <span class="inpDatp numCont1"><input  id="repRecDiarInpProIni" name="proIni"  required  placeholder="Proceso Inicial" type="number"></span>
                <span class="inpDatp numCont1"><input  id="repRecDiarInpProFin" name="proFin" required  placeholder="Proceso Final" type="number"></span>
                <span class="titDato numCont1">Tipo:</span>
                <span class="inpDatp numCont2"><select required tabindex="5" name="arch" select id="repRecDiarSelArc" >
                        <option></option>
                        <option value="pdf">PDF</option>
                        <option value="xls">XLS</option>
                        <!--                        <option></option>-->
                    </select></span>
            </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormCorte" tabindex="6" id="repRecDiarButGen">
            </span>

            </form>

            <div>
                <object id="repRecDiarFormPdf" class="conPdf" type="application/pdf"></object>
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

