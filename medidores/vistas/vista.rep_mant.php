<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset="UTF-8" />
        <title>Reporte 3K</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/rep_mant.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Generacion Reporte Mantenimientos
    </header>

    <section class="contenido">
        <article>
            <form onSubmit="return false" id="repManMedForm">
            <span class="datoForm col1">
                <span class="titDato numCont1">Acueducto:</span>
                <span class="inpDatp numCont2"><select name="proyecto" tabindex="1" select id="repManMedSelPro" required ></select></span>
                <span class="titDato numCont1">Tipo:</span>
                <span class="inpDatp numCont2">
                    <select name="tipo" tabindex="2" select id="repManMedSelTip" required >
                        <option value=""></option>
                        <option value="preventivo">Preventivo</option>
                        <option value="correctivo">Correctivo</option>
                    </select>
                </span>

                <span class="titDato numCont2">Fecha:</span>
                <span class="inpDatp numCont2"><input type="date" name="fecini" tabindex="1" id="repManMedInpFecIni" required ></span>
                <span class="inpDatp numCont2"><input type="date" name="fecfin" tabindex="1" id="repManMedInpFecFin" required ></span>

            </span>
                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormMed" tabindex="9">
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

