<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Generación de corte</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/gencorte.js?<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />
        </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Generación de corte
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genCorForm">

            <span class="datoForm col1">
                <span class="titDato numCont1">Proyecto:</span>
                <span class="inpDatp numCont2"><select  id="genCorSelPro" required="" name="proyecto"></select></span>

                <span class="titDato numCont1">Inmueble:</span>
                <span class="inpDatp numCont2"><input  id="genCorInpInm" name="inmueble" required placeholder="Código de sistema" type="number"   ></span>
                <span class="titDato numCont1">Motivo:</span>
                <span class="inpDatp numCont2"><input  id="genCorInpMot" name="motivo" required placeholder="Motivo" type="text"   ></span>
                <span class="titDato numCont1">Operario:</span>
                <span class="inpDatp numCont2"><select  id="genCorSelOpe" required="" name="operario"></select></span>
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

