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
    <header id="aplSfCab" class="cabeceraTit">
        Aplicar saldo A favor
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="aplSfForm">
                <div class="subCabecera"> datos de factura</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Valor.</span>
                <span class="inpDatp numCont1"><input  tabindex="1" required id="aplSfInpVal" readonly type="number" min="0"></span>
                <span class="titDato numCont1">Periodo.</span>
                <span class="inpDatp numCont3"><input  id="aplSfInpPer" required  readonly type="text"></span>
                <span class="titDato numCont1">Fecha Pago.</span>
                <span class="inpDatp numCont3"><input  id="aplSfInpFec" required  readonly type="text"></span>
                <span class="titDato numCont1">Num Fac.</span>
                <span class="inpDatp numCont1"><input  id="aplSfInpNumFac" required  readonly type="text"></span>
            </span>

                <div class="subCabecera">Informacion Saldo a favor</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">Valor Aplicado.</span>
                <span class="inpDatp numCont4"><input autofocus="true" tabindex="1" required id="aplSfInpValAp" placeholder="Valor Aplicado" type="number" min="1" ></span>
                <span class="titDato numCont2">Valor Restante.</span>
                <span class="inpDatp numCont4"><input  id="aplSfInpValRes" tabindex="2" required placeholder="Valor Restante" min="0" type="text"></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Observaciones</span>
                <span class="inpDatp numCont6"><textarea  id="aplSfTexIObs" readonly placeholder="Observaciones de Saldo a favor"></textarea></span>
                <span class="inpDatp numCont4"><textarea tabindex="3" id="aplSfTexIComp" required  placeholder="Complemento"></textarea></span>
            </span>




                <span class="datoForm col1">
                <input type="submit" tabindex="4" value="Generar" class="botonFormulario" tabindex="9" id="aplSfButGen">
            </span>
            </form>
        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        aplSfInicio();
    </script>

    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
