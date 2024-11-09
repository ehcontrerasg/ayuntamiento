<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Hojas De Corte</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/hojasCorte.js?<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Hojas De Corte
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="hojCorForm">

            <span class="datoForm col1">
                <span class="titDato numCont1">Proyecto:</span>
                <span class="inpDatp numCont1"><select tabindex="10" name="proyecto" required select id="hojCorSelPro" ></select></span>
                <span class="titDato numCont1">Sector:</span>
                <span class="inpDatp numCont1"><select tabindex="20" name="sector"  select id="hojCorSelSec" ></select></span>
                <span class="titDato numCont1">Zona:</span>
                <span class="inpDatp numCont1"><select tabindex="30" name="zona"  select id="hojCorSelZon" ></select></span>
                <span class="titDato numCont1">Inmueble:</span>
                <span class="inpDatp numCont1"><input id="LotCorInpInm" name="inmueble" type="text"> </span>
                <span class="titDato numCont1">Operario:</span>
                <span class="inpDatp numCont1"><select tabindex="40" name="operario"  select id="hojCorSelOpe" ></select></span>
                <span class="titDato numCont1">Tipo:</span>
                <span class="inpDatp numCont1"><select tabindex="50" name="tipo" required select  id="hojCorSelTip" >
                        <option></option>
                        <option value="M">Movil</option>
                        <option value="P">Papel</option>
                    </select></span>
            </span>

                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormCorte" tabindex="60">
            </span>

            </form>

            <div id="contenedorPdf">
                <object id="objHojaCor" class="conPdf" type="application/pdf"></object>
            </div>

        </article>
    </section>

    <footer>
    </footer>
    </body>
    </html>

    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

