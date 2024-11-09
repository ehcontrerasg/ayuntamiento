<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Entrada de resultados de cambio</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/ingresa_resultados_man.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Entrada de resultados de Mantenimiento
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genHojMedForm">

                <div class="subCabecera fondMed"> Datos del imueble</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Cod. sis</span>
                <span class="inpDatp numCont2"><input autofocus="true" tabindex="1" required id="ingResManInpCodSis" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont1">Proyecto</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpProy" required placeholder="Proyecto" readonly type="text"></span>
                <span class="titDato numCont1">Acurducto</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpAcu" required placeholder="Acueducto" readonly type="text"></span>
                <span class="titDato numCont1"></span>
                <span class="inpDatp numCont2"></span>
            </span>

                <div class="subCabecera fondMed"> Información de la solicitud y planificación</div>
                <span class="datoForm col1">

                <span class="titDato numCont1">Empleado</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpEmpPla" required placeholder="Empleado" readonly type="text"></span>
                <span class="titDato numCont1">Fecha</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpFechPla" required placeholder="Fecha planificacion" readonly type="text"></span>
                <span class="titDato numCont1">Motivo</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpMot" required placeholder="Motivo"  readonly type="text"></span>
                <span class="titDato numCont1">Orden</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpOrd" required placeholder="Orden" readonly type="text"></span>

            </span>

                <div class="subCabecera fondMed"> Datos Generales</div>

                <span class="datoForm col1">
                <span class="titDato numCont1">Cliente</span>
                <span class="inpDatp numCont1"><input  id="ingResManInpCodCli" required placeholder="Cod. Cliente" readonly type="text"></span>
                <span class="inpDatp numCont3"><input  id="ingResManInpCli" required placeholder="Cliente" readonly type="text"></span>
                <span class="titDato numCont1">Dirección</span>
                <span class="inpDatp numCont4"><input  id="ingResManInpDir" required placeholder="Dirección" readonly type="text"></span>
                <span class="titDato numCont1">Zona</span>
                <span class="inpDatp numCont1"><input  id="ingResManInpZon" required placeholder="Zona" readonly type="text"></span>

            </span>
                <span class="datoForm col1">
                <span class="titDato numCont1">Medidor</span>
                <span class="inpDatp numCont1"><input  id="ingResManInpCodMedRet" required placeholder="Cod. Med." readonly type="text"></span>
                <span class="inpDatp numCont2"><input  id="ingResManInpMedRet" required placeholder="Medidor" readonly type="text"></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><input  id="ingResManInpCalRet" required placeholder="Calibre" readonly type="text"></span>
                <span class="titDato numCont2">Emplazamiento</span>
                <span class="inpDatp numCont2"><input  id="ingResManInpEmplaRet" placeholder="Emplazamiento" readonly type="text"></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont1"><input  id="ingResManInpSerRet" required placeholder="Serial" readonly  type="text"></span>
            </span>



                <div class="subCabecera fondMed">Datos medidor Mantenimiento</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Marca</span>
                <span class="inpDatp numCont2"><select  tabindex="6" id="ingResManSelMarcMedIns"></select></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><select  tabindex="7" id="ingResManSelCalIns"></select></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont2"><input  tabindex="8" id="ingResManInpSerIns" placeholder="Serial"  type="text"></span>
                <span class="titDato numCont2">Emplazamiento.</span>
                <span class="inpDatp numCont2"><select  tabindex="10" id="ingResManSelEmpIns"></select></span>


            </span>

                <span class="datoForm col1">
                <span class="titDato numCont1">Lectura</span>
                <span class="inpDatp numCont2"><input required tabindex="9" id="ingResManInpLecIns" placeholder="Lectura"  type="number" min="0" ></span>
                <span class="titDato numCont1">Fecha Mantenimiento</span>
                <span class="inpDatp numCont1"><input required tabindex="9" id="ingResManInpFechMan" required  type="date"></span>
                <span class="titDato numCont1"></span>
                <span class="inpDatp numCont2"></span>
                <span class="titDato numCont2"></span>
                <span class="inpDatp numCont2"></span>
            </span>

                <div class="subCabecera fondMed"> Actividades y materiales del mantenimiento</div>

                <span class="datoForm col2">
                <span class="titDato numCont8">Agregar Actividades</span>
                <span class="inpDatp numCont4"><input type="button" value="Agregar" id="ingResManButAgrAct" class="botonFormulario" ></span>

            </span>

                <span class="datoForm col2">
                <span class="titDato numCont8">Agregar Materiales</span>
                <span class="inpDatp numCont4"><input type="button" value="Agregar" id="ingResManButAgrMant" class="botonFormulario" ></span>
            </span>

                <div class="subCabecera fondMed">Observaciones</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">Mantenimiento</span>
                <span class="inpDatp numCont4"><textarea tabindex="15" id="ingResManTexAObsIns" placeholder="Observaciones de Mantenimiento"></textarea></span>
                <span class="titDato numCont2">Generales</span>
                <span class="inpDatp numCont4"><textarea tabindex="16" id="ingResManTexAObsLec" placeholder="Observaciones Generales"></textarea></span>

            </span>




                <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormMed" tabindex="9" id="ingResManButIng">
            </span>

            </form>
        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        ingResManInicio();
    </script>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

