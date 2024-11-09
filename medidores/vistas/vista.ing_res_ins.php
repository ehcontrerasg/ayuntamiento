<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Entrada de resultados de Inspeccion</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/ingresa_res_ins.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Entrada de resultados de Inspeccion
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="ingResInsMedForm">

                <div class="subCabecera fondMed"> Datos del imueble</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Cod. sis</span>
                <span class="inpDatp numCont2"><input autofocus="true"id="ingResInsInpCodSis"  tabindex="1" required name="inm" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont1">Proyecto</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpPro" required placeholder="Proyecto" readonly type="text"></span>
                <span class="titDato numCont1">Acueducto</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpAcu" required placeholder="Acueducto" readonly type="text"></span>
                <span class="titDato numCont1">PQR</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpPqr" name="pqr" required placeholder="PQR" readonly type="text"></span>
            </span>

                <div class="subCabecera fondMed"> Información de la solicitud de inspeccion</div>
                <span class="datoForm col1">

                <span class="titDato numCont1">Empleado</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpOpe" required placeholder="Empleado" readonly type="text"></span>
                <span class="titDato numCont1">Fecha</span>
                <span class="inpDatp numCont1"><input  id="ingResInsInpFecPla" required placeholder="Fecha planificacion" readonly type="text"></span>
                <span class="titDato numCont1">Motivo</span>
                <span class="inpDatp numCont3"><input  id="ingResInsInpMot" required placeholder="Motivo"  readonly type="text"></span>
                <span class="titDato numCont1">Orden</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpOrd" name="orden" required placeholder="Orden" readonly type="text"></span>

            </span>
                <span class="datoForm col1">

                <span class="titDato numCont1">Descripcion</span>
                <span class="inpDatp numCont11"><textarea readonly  required id="ingResInsTexAreDescIns" placeholder="Descripción"></textarea></span>

            </span>
                <div class="subCabecera fondMed"> Datos Generales</div>

                <span class="datoForm col1">
                <span class="titDato numCont1">Cliente</span>
                <span class="inpDatp numCont1"><input  id="ingResInsInpCodCli" required placeholder="Cod. Cliente" readonly type="text"></span>
                <span class="inpDatp numCont3"><input  id="ingResInsInpNomCli" required placeholder="Cliente" readonly type="text"></span>
                <span class="titDato numCont1">Dirección</span>
                <span class="inpDatp numCont4"><input  id="ingResInsInpDir" required placeholder="Dirección" readonly type="text"></span>
                <span class="titDato numCont1">Zona</span>
                <span class="inpDatp numCont1"><input  id="ingResInsInpZon" required placeholder="Zona" readonly type="text"></span>

            </span>
                <span class="datoForm col1">
                <span class="titDato numCont1">Medidor</span>
                <span class="inpDatp numCont1"><input  id="ingResInsInpCodMed" required placeholder="Cod. Med." readonly type="text"></span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpDesMed" required placeholder="Medidor" readonly type="text"></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><input  id="ingResInsInpCal" required placeholder="Calibre" readonly type="text"></span>
                <span class="titDato numCont2">Emplazamiento</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpEmp" placeholder="Emplazamiento" readonly type="text"></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont1"><input  id="ingResInsInpSer" required placeholder="Serial" readonly  type="text"></span>
            </span>

                <div class="subCabecera fondMed">Resultado de Inspección</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">lectura</span>
                <span class="inpDatp numCont2"><input  id="ingResInsInpLect" tabindex="1"  name="lectura" required placeholder="lectura"  type="text"></span>
                <span class="titDato numCont2">Pasar A:</span>
                <span class="inpDatp numCont2"><select  id="ingResInsSelInp" tabindex="3"  name="dpto" required ></select> </span>
                <span class="inpDatp numCont4"></span>
                <span class="inpDatp numCont4"></span>

            </span>

                <span class="datoForm col1">
                <span class="titDato numCont1">Inspeccion</span>
                <span class="inpDatp numCont11"><textarea tabindex="4" name="resultado" required id="ingResInsTexAreObsIns" placeholder="Observaciones de Inspección"></textarea></span>
                    <!--                <span class="titDato numCont2">Generales</span>-->
                    <!--                <span class="inpDatp numCont4"><textarea tabindex="4" name="obs" required id="ingResInsTexAreObsGen" placeholder="Observaciones Generales"></textarea></span>-->
            </span>


                <span class="datoForm col1">
                <input type="submit" tabindex="5" value="Generar" class="botonFormulario botFormMed"  id="ingResInsButIng">
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

