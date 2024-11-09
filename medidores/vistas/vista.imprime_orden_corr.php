<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>


        <title>Impresión de ordenes de mantenimiento</title>
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>

        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
        <link href="../css/imprime_orden_corr.css?<?php echo time();?>" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Impresión de Ordenes de Mantenimiento Correctivo
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genHojMedCorForm">
            <span class="datoForm col1">
                <span class="titDato numCont1">Acueducto:</span>
                <span class="inpDatp numCont1"><select name="proyecto" tabindex="1" select id="genHojMedCorSelPro" required ></select></span>
                <span class="titDato numCont2">Proceso Inicial:</span>
                <span class="inpDatp numCont1"><input name="proIni" tabindex="2" id="genHojMedCorInpProIni" placeholder="Inicial" type="text"></span>
                <span class="titDato numCont2">Proceso Final:</span>
                <span class="inpDatp numCont1"><input name="proFin" tabindex="3"id="genHojMedCorInpProFin" placeholder="Final" type="text"></span>
                <span class="titDato numCont1">Periodo</span>
                <span class="inpDatp numCont2"><input name="periodo" tabindex="3"id="genHojMedCorInpPer" placeholder="periodo" type="number"></span>
            </span>

                <span class="datoForm col1">

                <span class="titDato numCont1">Cod.Sistema:</span>
                <span class="inpDatp numCont1"><input  name="codSis" tabindex="1" id="genHojMedCorInpCodSis" placeholder="Cod. Sistema"  type="text"></span>
                <span class="titDato numCont2">Manzana Inicial:</span>
                <span class="inpDatp numCont1"><input name="manIni" tabindex="2" id="genHojMedCorInpManIni" placeholder="Inicial" ></span>
                <span class="titDato numCont2">Manzana Final:</span>
                <span class="inpDatp numCont1"><input name="manFin" tabindex="3" id="genHojMedCorInpManFin" placeholder="Final"  ></span>
                <span class="titDato numCont1">Operario:</span>
                <span class="inpDatp numCont2"><select name="operario" tabindex="4" select id="genHojMedCorSelOpe" ></select></span>
            </span>


                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormMed" tabindex="9" id="genHojMedCorButPro">
            </span>

            </form>

            <button id="btnExcel">Exportar a Excel</button>
            <div>
                <object id="genHojMedCorObjHoj" class="conPdf" type="application/pdf"></object>
            </div>
            <div id="dvExcel"></div>
        </article>
    </section>

    <footer>
    </footer>
    </body>
    <!--pag-->
    <script type="text/javascript" src="../js/imprime_orden_corr.js?<?php echo time();?>"></script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

