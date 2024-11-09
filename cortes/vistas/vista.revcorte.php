<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Reversion de corte</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/revcorte.js?<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />
        </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Reversion de corte
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="revCorForm">

            <span class="datoForm col1">
                <span class="titDato numCont2">Inmueble:</span>
                <span class="inpDatp numCont2"><input  id="revCorInpInm" name="inmueble" required placeholder="Código de sistema" type="number"   ></span>
                <span class="titDato numCont2">Observación:</span>
                <span class="inpDatp numCont2"><input  id="revCorInpObs" name="observacion" required placeholder="Observación" type="text"   ></span>
            </span>

                <span class="datoForm col1">
                    <input type="submit" value="Procesar" class="botonFormulario botFormCorte" tabindex="4">
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

