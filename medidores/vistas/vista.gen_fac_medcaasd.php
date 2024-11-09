<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Impresión de ordenes de medidores</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js?3"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/gen_fac_medcaasd.js?<?echo time();?>"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Impresión de facturas De instalación
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genFacCaaForm">

                <div class="subCabecera fondMed"> Generar factura existente</div>
                <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select autofocus="true" tabindex="1" select id="genFacCaaSelAcu" required ></select></span>
                <span class="titDato numCont2">Factura:</span>
                <span class="inpDatp numCont2"><select tabindex="2" select id="genFacCaaSelFac" name="fac" required ></select></span>
                <span class="titDato numCont2">Numero Inmuebles:</span>
                <span class="inpDatp numCont2"><input  id="genFacCaaInpNumInmExi" readonly placeholder="Cantidad" type="text"></span>
            </span>


                <div class="subCabecera fondMed"> Generar una nueva factura</div>
                <span class="datoForm col1">
                    <span class="titDato numCont2">Cantidad Instalaciones</span>
                    <span class="inpDatp numCont2"><input tabindex="3" required min="1" id="genFacCaaInpCantInsNue"  type="number"></span>
                    <span class="titDato numCont2">Cantidad Pendiente:</span>
                    <span class="inpDatp numCont2"><input   id="genFacCaaInpPend" type="number" readonly ></span>
                    <span class="titDato numCont2">Periodo:</span>
                    <span class="inpDatp numCont2"><select tabindex="2"  id="genFacCaaSelPer" name="per"  ></select></span>
            </span>



                <span class="datoForm col1">
                <input type="submit" value="Generar PDF" class="botonFormulario botFormMed" tabindex="4" id="genFacCaaButGen">
                <input type="button" value="Generar XLS" class="botonFormulario botFormMed" tabindex="5" id="genFacCaaButGenX">
                    <!--button type="submit" name="genxls" id="genxls" class="botonFormulario botFormMed">Generar XLS</button-->
            </span>

            </form>

            <div id = "contenedorPdf">
                <object id="genFacCaaObjFac" class="conPdf" type="application/pdf"></object>
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

