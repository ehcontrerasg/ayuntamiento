<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Dar de baja Medidor</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/baja_medidor.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>


    <header class="cabeceraTit fondMed">
        Eliminacion de medidor
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="bajMedForm">

                <div class="subCabecera fondMed"> Datos del imueble</div>
                <span class="datoForm col1">
                <span class="titDato numCont1">Cod. sis</span>
                <span class="inpDatp numCont2"><input name="codSis" autofocus="true" tabindex="1" required id="bajMedInpCodSis" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont1">Proyecto</span>
                <span class="inpDatp numCont2"><input  id="bajMedInpProy" required placeholder="Proyecto" readonly type="text"></span>
                <span class="titDato numCont1">Acurducto</span>
                <span class="inpDatp numCont2"><input  id="bajMedInpAcu" required placeholder="Acueducto" readonly type="text"></span>
                <span class="titDato numCont1"></span>
                <span class="inpDatp numCont2"></span>
            </span>



                <div class="subCabecera fondMed"> Datos Generales</div>

                <span class="datoForm col1">
                <span class="titDato numCont1">Cliente</span>
                <span class="inpDatp numCont1"><input  id="bajMedInpCodCli" required placeholder="Cod. Cliente" readonly type="text"></span>
                <span class="inpDatp numCont3"><input  id="bajMedInpCli" required placeholder="Cliente" readonly type="text"></span>
                <span class="titDato numCont1">Dirección</span>
                <span class="inpDatp numCont4"><input  id="bajMedInpDir" required placeholder="Dirección" readonly type="text"></span>
                <span class="titDato numCont1">Zona</span>
                <span class="inpDatp numCont1"><input  id="bajMedInpZon" required placeholder="Zona" readonly type="text"></span>

            </span>
                <span class="datoForm col1">
                <span class="titDato numCont1">Medidor</span>
                <span class="inpDatp numCont1"><input  id="bajMedInpCodMedRet" required placeholder="Cod. Med." readonly type="text"></span>
                <span class="inpDatp numCont2"><input  id="bajMedInpMedRet" required placeholder="Medidor" readonly type="text"></span>
                <span class="titDato numCont1">Calibre</span>
                <span class="inpDatp numCont1"><input  id="bajMedInpCalRet" required placeholder="Calibre" readonly type="text"></span>
                <span class="titDato numCont2">Emplazamiento</span>
                <span class="inpDatp numCont2"><input  id="bajMedInpEmplaRet" placeholder="Emplazamiento" readonly type="text"></span>
                <span class="titDato numCont1">Serial</span>
                <span class="inpDatp numCont1"><input  id="bajMedInpSerRet" required placeholder="Serial" readonly  type="text"></span>
            </span>


                <div class="subCabecera fondMed">Observaciones</div>

                <span class="datoForm col1">
                <span class="titDato numCont2">Motivo</span>
                <span class="inpDatp numCont4"><textarea tabindex="15" name="Mot" id="bajMedTexAMot" required placeholder="Motivo de Eliminación"></textarea></span>

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

