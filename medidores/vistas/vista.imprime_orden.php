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
        <!--pag-->
        <script type="text/javascript" src="../js/imprime_orden.js?1"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Impresión de Ordenes de Cambio de Medidores
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genHojMedForm">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select tabindex="1" select id="genHojMedSelPro" required ></select></span>
                <span class="titDato numCont2">Proceso Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="2" id="genHojMedInpProIni" placeholder="Inicial" type="text"></span>
                <span class="titDato numCont2">Proceso Final:</span>
                <span class="inpDatp numCont2"><input  tabindex="3"id="genHojMedInpProFin" placeholder="Final" type="text"></span>
            </span>

                <span class="datoForm col1">

                <span class="titDato numCont2">Cod.Sistema:</span>
                <span class="inpDatp numCont2"><input tabindex="4" id="genHojMedInpCodSis" placeholder="Cod. Sistema"  type="text"></span>
                <span class="titDato numCont2">Manzana Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="5" id="genHojMedInpManIni" placeholder="Inicial" ></span>
                <span class="titDato numCont2">Manzana Final:</span>
                <span class="inpDatp numCont2"><input tabindex="6" id="genHojMedInpManFin" placeholder="Final"  ></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Medidor:</span>
                <span class="inpDatp numCont2"><select tabindex="7" select id="genHojMedSelMed" >
                        <option></option>
                        <option value="S">MEDIDO</option>
                        <option value="N">NO MEDIDO</option>
                    </select>
                </span>
                <span class="titDato numCont2">Estado Inmueble:</span>
                <span class="inpDatp numCont2"><select tabindex="8" select id="genHojMedSelEstInm" >
                        <option></option>
                        <option value="A">ACTIVO</option>
                        <option value="I">NO ACTIVO</option>
                    </select>
                </span>
                <span class="titDato numCont2">Operario:</span>
                <span class="inpDatp numCont2"><select tabindex="11" select id="genHojMedSelOpe" ></select></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Fecha Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="12" id="genHojMedInpFecIni" placeholder="Cod. Sistema"  type="date"></span>
                <span class="titDato numCont2">Fecha Final:</span>
                <span class="inpDatp numCont2"><input tabindex=13 id="genHojMedInpFecFin" type="date"></span>
                <span class="titDato numCont2"></span>
                <span class="inpDatp numCont2"></span>
            </span>


                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormMed" tabindex="9" id="genHojMedButPro">
            </span>

            </form>

            <div id="contenedorPdf">
                <object id="genHojMedObjHoj" class="conPdf" type="application/pdf"></object>
            </div>

        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        genHojMedInicio();
    </script>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

