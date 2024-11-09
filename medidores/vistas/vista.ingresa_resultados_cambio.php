<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
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
            <script type="text/javascript" src="../js/ingresa_resultados_cambio.js"></script>
            <link href="../../css/general.css " rel="stylesheet" type="text/css" />
        </head>
        <body>
        <header class="cabeceraTit fondMed">
            Entrada de resultados de cambio
        </header>

        <section class="contenido">
            <article>
                <form onsubmit="return false" id="genHojMedForm">

                    <div class="subCabecera fondMed"> Datos del imueble</div>
                    <span class="datoForm col1">
                <span class="titDato numCont1">Cod. sis</span>
                <span class="inpDatp numCont2"><input autofocus="true" tabindex="1" required id="ingResCambInpCodSis" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont1">Proyecto</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpProy" required placeholder="Proyecto" readonly type="text"></span>
                <span class="titDato numCont1">Acurducto</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpAcu" required placeholder="Acueducto" readonly type="text"></span>
                <span class="titDato numCont1">Factura Instalacion.</span>
                <span class="inpDatp numCont2">
                    <select tabindex="2" required id="ingResCambSelFac">
                        <option selected></option>
                        <option  value="S">SI</option>
                        <option value="N">NO</option>
                    </select>
                </span>
            </span>

                    <div class="subCabecera fondMed"> Información de la solicitud y planificación</div>
                    <span class="datoForm col1">

                <span class="titDato numCont1">Empleado</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpEmpPla" required placeholder="Empleado" readonly type="text"></span>
                <span class="titDato numCont1">Fecha</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpFechPla" required placeholder="Fecha planificacion" readonly type="text"></span>
                <span class="titDato numCont1">Motivo</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpMot" required placeholder="Motivo"  readonly type="text"></span>
                <span class="titDato numCont1">Orden</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpOrd" required placeholder="Orden" readonly type="text"></span>

            </span>

                    <div class="subCabecera fondMed"> Datos Generales</div>

                    <span class="datoForm col1">
                <span class="titDato numCont1">Cliente</span>
                <span class="inpDatp numCont1"><input  id="ingResCambInpCodCli" required placeholder="Cod. Cliente" readonly type="text"></span>
                <span class="inpDatp numCont3"><input  id="ingResCambInpCli" required placeholder="Cliente" readonly type="text"></span>
                <span class="titDato numCont1">Dirección</span>
                <span class="inpDatp numCont4"><input  id="ingResCambInpDir" required placeholder="Dirección" readonly type="text"></span>
                <span class="titDato numCont1">Zona</span>
                <span class="inpDatp numCont1"><input  id="ingResCambInpZon" required placeholder="Zona" readonly type="text"></span>

            </span>
                    <span class="datoForm col1">
                <span class="titDato numCont1">Medidor</span>
                <span class="inpDatp numCont1"><input  id="ingResCambInpCodMedRet" required placeholder="Cod. Med." readonly type="text"></span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpMedRet" required placeholder="Medidor" readonly type="text"></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><input  id="ingResCambInpCalRet" required placeholder="Calibre" readonly type="text"></span>
                <span class="titDato numCont2">Emplazamiento</span>
                <span class="inpDatp numCont2"><input  id="ingResCambInpEmplaRet" placeholder="Emplazamiento" readonly type="text"></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont1"><input  id="ingResCambInpSerRet" required placeholder="Serial" readonly  type="text"></span>
            </span>

                    <div class="subCabecera fondMed">Medidor Retirado</div>
                    <span class="datoForm col1">
                <span class="titDato numCont1">Lectura</span>
                <span class="inpDatp numCont2"><input tabindex="3"  required id="ingResCambInpLecRet" placeholder="Lectura" type="number" min="0"  ></span>
                <span class="inpDatp numCont2"><select tabindex="4" required id="ingResCambSelObsLec"></select></span>
                <span class="titDato numCont3">Motivo no realización</span>
                <span class="inpDatp numCont4"><select tabindex="5" id="ingResCambSelMotNoRea"></select></span>
            </span>

                    <div class="subCabecera fondMed">Medidor Instalado</div>
                    <span class="datoForm col1">
                <span class="titDato numCont1">Fec. Ins.</span>
                <span class="inpDatp numCont2"><input  required tabindex="6" id="ingResCambInpFecIns" type="date"></span>
                <span class="titDato numCont1">Marca</span>
                <span class="inpDatp numCont2"><select required tabindex="7" id="ingResCambSelMarcMedIns"></select></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><select required tabindex="8" id="ingResCambSelCalIns"></select></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont1"><input required tabindex="9" id="ingResCambInpSerIns" placeholder="Serial"  type="text"></span>
                <span class="titDato numCont1">Lectura</span>
                <span class="inpDatp numCont1"><input required tabindex="10" id="ingResCambInpLecIns" placeholder="Lectura"  type="number" min="0"  ></span>
            </span>

                    <span class="datoForm col1">
                <span class="titDato numCont1">Emplazamiento.</span>
                <span class="inpDatp numCont2"><select required tabindex="11" id="ingResCambSelEmpIns"></select></span>
                <span class="titDato numCont2">Entrega al usuario</span>
                <span class="inpDatp numCont1">
                    <select tabindex="12" required id="ingResCambSelEntUsu">
                        <option></option>
                        <option value="S">SI</option>
                        <option value="N">NO</option>
                    </select>
                </span>
                <span class="titDato numCont1">Fecha Garant.</span>
                <span class="inpDatp numCont2"><input tabindex="13" id="ingResCambInpFecGar"  type="date"></span>
                <span class="titDato numCont1">Fecha Venc.</span>
                <span class="inpDatp numCont2"><input tabindex="14" id="ingResCambInpFecVenc"  type="date"></span>
            </span>

                    <div class="subCabecera fondMed">Obsrvaciones</div>

                    <span class="datoForm col1">
                <span class="titDato numCont2">Instalacion</span>
                <span class="inpDatp numCont4"><textarea tabindex="15" id="ingResCambTexAObsIns" placeholder="Observaciones de Instalación"></textarea></span>
                <span class="titDato numCont2">Lectura</span>
                <span class="inpDatp numCont4"><textarea tabindex="16" id="ingResCambTexAObsLec" placeholder="Observaciones de Lectura"></textarea></span>

            </span>




                    <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormMed" tabindex="9" id="ingResCambButIng">
            </span>

                </form>
            </article>
        </section>

        <footer>
        </footer>

        <script type="text/javascript">
            ingResCambInicio();
        </script>

        </body>
        </html>
    <?php endif;
    if ($verificarPermisos==false):
        include "../../general/vistas/vista.PlantillaError.php";
    endif; ?>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

