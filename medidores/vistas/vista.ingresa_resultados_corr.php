<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Entrada de resultados de Mantenimiento correctivo</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/ingresa_resultados_corr.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Entrada de resultados de Mantenimiento Correctivo
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="ingResManCorForm">

                <div class="subCabecera fondMed"> Datos del imueble</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Cod. sis</span>
                <span class="inpDatp numCont2"><input name="codSis" autofocus="true" tabindex="1" required id="ingResManInpCodSis" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont1">Proyecto</span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpProy" required placeholder="Proyecto" readonly type="text"></span>
                <span class="titDato numCont1">Acurducto</span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpAcu" required placeholder="Acueducto" readonly type="text"></span>
                <span class="titDato numCont1"></span>
                <span class="inpDatp numCont2"></span>
            </span>

                <div class="subCabecera fondMed"> Información de la solicitud y planificación</div>
                <span class="datoForm col1">

                <span class="titDato numCont1">Empleado</span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpEmpPla" required placeholder="Empleado" readonly type="text"></span>
                <span class="titDato numCont1">Fecha</span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpFechPla" required placeholder="Fecha planificacion" readonly type="text"></span>
                <span class="titDato numCont1">Motivo</span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpMot" required placeholder="Motivo"  readonly type="text"></span>
                <span class="titDato numCont1">Orden</span>
                <span class="inpDatp numCont2"><input name="orden" id="ingResManCorInpOrd" required placeholder="Orden" readonly type="text"></span>

            </span>

                <div class="subCabecera fondMed"> Datos Generales</div>

                <span class="datoForm col1">
                <span class="titDato numCont1">Cliente</span>
                <span class="inpDatp numCont1"><input  id="ingResManCorInpCodCli" required placeholder="Cod. Cliente" readonly type="text"></span>
                <span class="inpDatp numCont3"><input  id="ingResManCorInpCli" required placeholder="Cliente" readonly type="text"></span>
                <span class="titDato numCont1">Dirección</span>
                <span class="inpDatp numCont4"><input  id="ingResManCorInpDir" required placeholder="Dirección" readonly type="text"></span>
                <span class="titDato numCont1">Zona</span>
                <span class="inpDatp numCont1"><input  id="ingResManCorInpZon" required placeholder="Zona" readonly type="text"></span>

            </span>
                <span class="datoForm col1">
                <span class="titDato numCont1">Medidor</span>
                <span class="inpDatp numCont1"><input  id="ingResManCorInpCodMedRet" required placeholder="Cod. Med." readonly type="text"></span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpMedRet" required placeholder="Medidor" readonly type="text"></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><input  id="ingResManCorInpCalRet" required placeholder="Calibre" readonly type="text"></span>
                <span class="titDato numCont2">Emplazamiento</span>
                <span class="inpDatp numCont2"><input  id="ingResManCorInpEmplaRet" placeholder="Emplazamiento" readonly type="text"></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont1"><input  id="ingResManCorInpSerRet" required placeholder="Serial" readonly  type="text"></span>
            </span>



                <div class="subCabecera fondMed">Datos medidor Mantenimiento</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Marca</span>
                <span class="inpDatp numCont2"><select  tabindex="6" name="marcMed" id="ingResManCorSelMarcMedIns"></select></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><select name="calMed"  tabindex="7" id="ingResManCorSelCalIns"></select></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont2"><input name="serMed"  tabindex="8" id="ingResManCorInpSerIns" placeholder="Serial"  type="text"></span>
                <span class="titDato numCont2">Emplazamiento.</span>
                <span class="inpDatp numCont2"><select  name="empMed" tabindex="10" id="ingResManCorSelEmpIns"></select></span>


            </span>

                <span class="datoForm col1">
                <span class="titDato numCont1">Lectura</span>
                <span class="inpDatp numCont2"><input name="lect" required tabindex="9" id="ingResManCorInpLecIns" placeholder="Lectura"  type="number" min="0" ></span>
                <span class="titDato numCont1">Fecha Mantenimiento</span>
                <span class="inpDatp numCont1"><input name="fecMant" required tabindex="9" id="ingResManCorInpFechMan" required  type="date"></span>
                <span class="titDato numCont1"></span>
                <span class="inpDatp numCont2"></span>
                <span class="titDato numCont2"></span>
                <span class="inpDatp numCont2"></span>
            </span>

                <div class="subCabecera fondMed"> Actividades y materiales del mantenimiento</div>

                <span class="datoForm col2">
                <span class="titDato numCont8">Agregar Actividades</span>
                <span class="inpDatp numCont4"><input type="button" value="Agregar" id="ingResManCorButAgrAct" class="botonFormulario" ></span>

            </span>

                <span class="datoForm col2">
                <span class="titDato numCont8">Agregar Materiales</span>
                <span class="inpDatp numCont4"><input type="button" value="Agregar" id="ingResManCorButAgrMant" class="botonFormulario" ></span>
            </span>

                <div class="subCabecera fondMed">Observaciones</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">Mantenimiento</span>
                <span class="inpDatp numCont4"><textarea tabindex="15" name="ObsMan" id="ingResManCorTexAObsIns" placeholder="Observaciones de Mantenimiento"></textarea></span>
                <span class="titDato numCont2">Generales</span>
                <span class="inpDatp numCont4"><textarea tabindex="16" name="ObsGen" id="ingResManCorTexAObsLec" placeholder="Observaciones Generales"></textarea></span>

            </span>




                <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormMed" tabindex="9" id="ingResManCorButIng">
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

