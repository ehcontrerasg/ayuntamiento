<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title> Cambio datos de medidor</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/cambio_datos.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Cambio datos de medidor
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="camDatMedForm">

                <div class="subCabecera fondMed"> Datos del imueble</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Cod. sis</span>
                <span class="inpDatp numCont2"><input name="codSis" autofocus="true" tabindex="1" required id="camDatMedInpCodSis" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont1">Proyecto</span>
                <span class="inpDatp numCont2"><input  id="camDatMedInpProy" required placeholder="Proyecto" readonly type="text"></span>
                <span class="titDato numCont1">Acurducto</span>
                <span class="inpDatp numCont2"><input  id="camDatMedInpAcu" required placeholder="Acueducto" readonly type="text"></span>
                <span class="titDato numCont1"></span>
                <span class="inpDatp numCont2"></span>
            </span>



                <div class="subCabecera fondMed"> Datos Generales</div>

                <span class="datoForm col1">
                <span class="titDato numCont1">Cliente</span>
                <span class="inpDatp numCont1"><input  id="camDatMedInpCodCli" required placeholder="Cod. Cliente" readonly type="text"></span>
                <span class="inpDatp numCont3"><input  id="camDatMedInpCli" required placeholder="Cliente" readonly type="text"></span>
                <span class="titDato numCont1">Dirección</span>
                <span class="inpDatp numCont4"><input  id="camDatMedInpDir" required placeholder="Dirección" readonly type="text"></span>
                <span class="titDato numCont1">Zona</span>
                <span class="inpDatp numCont1"><input  id="camDatMedInpZon" required placeholder="Zona" readonly type="text"></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont1">Medidor</span>
                <span class="inpDatp numCont2"><select name="med" id="camDatMedSelCodMed" required></select></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont2"><select name="cal" id="camDatMedSelCal" required></select></span>
                <span class="titDato numCont1">Emplazamiento</span>
                <span class="inpDatp numCont2"><select name="emp"  id="camDatMedSelEmpla" required></select> </span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont2"><input name="ser"  id="camDatMedInpSer" required ></span>
            </span>





                <div class="subCabecera fondMed">Observaciones</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">Motivo Cambio</span>
                <span class="inpDatp numCont4"><textarea tabindex="15" name="Mot" id="camDatMedTexAMot" required placeholder="Motivo de Cambio"></textarea></span>

            </span>


                <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormMed" tabindex="9"">
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

