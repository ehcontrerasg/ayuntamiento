<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>


    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Aplica Saldo A favor</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/reliquidacion_pag.js?<?echo time();?> "></script>
        <link href="../css/facturacion.css " rel="stylesheet" type="text/css" />

    </head>
    <body>
    <header id="aplSfInmCab" class="cabeceraTit">
        Aplicar saldo A favor a inmueble
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="aplSfInmForm">
                <div class="subCabecera"> datos de Inmueble</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Deuda.</span>
                <span class="inpDatp numCont3"><input  tabindex="1" required id="aplSfInmInpDeu" readonly type="number" min="0"></span>
                <span class="titDato numCont1">Periodo deuda.</span>
                <span class="inpDatp numCont3"><input  id="aplSfInmInpPer" required  readonly type="text"></span>
                <span class="titDato numCont1">Num Fac.</span>
                <span class="inpDatp numCont3"><input  id="aplSfInmInpNumfac" required  readonly type="text"></span>
            </span>

                <div class="subCabecera">Informacion Saldo a favor</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">Valor Aplicado.</span>
                <span class="inpDatp numCont4"><input autofocus="true" tabindex="1" required id="aplSfInmInpAValAp" placeholder="Valor Aplicado" type="number" min="1" ></span>

            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Observaciones</span>
                <span class="inpDatp numCont6"><textarea  id="aplSfInmTexAObs" readonly placeholder="Observaciones de Saldo a favor"></textarea></span>
                <span class="inpDatp numCont4"><textarea tabindex="3" id="aplSfInmTextAObsCom" required  placeholder="Complemento"></textarea></span>
            </span>




                <span class="datoForm col1">
                <input type="submit" tabindex="4" value="Generar" class="botonFormulario" tabindex="9" id="aplSfInmButGen">
            </span>
            </form>
        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        aplSfInmInicio();
    </script>

    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

