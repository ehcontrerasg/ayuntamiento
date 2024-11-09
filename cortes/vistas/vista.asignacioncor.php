<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <meta  charset=UTF-8" />
    <title>Asignacion de cortes</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/asignacionCor.js?<?echo time();?>"></script>
  <!--  <script type="text/javascript" src="../js/llenarSpinnerContratista.js?3"></script>-->
    <link href="../../css/general.css?" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <head>
        <header class="cabeceraTit fondCorte">
            Asignacion y reasignacion de cortes
        </header>

        <section class="contenido">
            <article>
                <form onsubmit="return false" id="asigCorForm">

                    <div class="subCabecera fondCorte"> Filtros de busqueda</div>
                    <span class="datoForm col1">
                <span class="titDato numCont1">Proyecto:</span>
                <span class="inpDatp numCont2"><select autofocus="true" tabindex="10" name="proyecto" required select id="asiCorSelPro" ></select></span>
                <span class="titDato numCont1">Zona:</span>
                <span class="inpDatp numCont2"><select tabindex="30" name="zona" required select id="asiCorSelZon" ></select></span>
                  <!-- <span class="titDato numCont1">Diámetro:</span>
                <span class="inpDatp numCont2"><select tabindex="40" name="diametro"  select id="asiCorSeldiametro" ></select></span>-->
                <span class="titDato numCont1">Uso:</span>
                <span class="inpDatp numCont2"><select tabindex="40" name="uso"  select id="asiCorSelUso" ></select></span>
                <span class="titDato numCont2">Categoria:</span>
                <span class="inpDatp numCont1"> <select tabindex="2" name="selCat"  select id="selCat"></select></span>
                <span class="titDato numCont1">Fecha Planificacion:</span>
                <span class="inpDatp numCont2"><input  id="asiCoInpFechPla" tabindex="45" required name="fechPal"  title="fecha de asignacion" type="date" ></span>
            </span>
                    <span class="datoForm col1">
                <span class="titDato numCont2">Facturas Vencidas:</span>
                <span class="inpDatp numCont1"><input  id="asiCoInpFacMin" tabindex="50" name="facturasIni"   placeholder="Facturas vencidad minimo" type="number" ></span>
                <span class="inpDatp numCont1"><input  id="asiCoInpFacMax" tabindex="60" name="facturasFin"   placeholder="Facturas vencidad maxino" type="number" ></span>
                <span class="titDato numCont2">Deuda:</span>
                <span class="inpDatp numCont1"><input  id="asiCoInpDeuMin" value="50" tabindex="70" name="deudaIni"   placeholder="deuda minima" type="number" ></span>
                <span class="inpDatp numCont1"><input  id="asiCoInpDeuMax" tabindex="80" value="999999999" name="deudaFin"   placeholder="deuda maxima" type="number" ></span>
                <span class="titDato numCont2">Unidades:</span>
                <span class="inpDatp numCont1"><input  id="asiCoInpUniMin" tabindex="90" name="unidadesIni"   placeholder="Unidades minimas" type="number" ></span>
                <span class="inpDatp numCont1"><input  id="asiCoInpUniMax" tabindex="100" name="unidadesFin"   placeholder="Unidades maximas" type="number" ></span>

            </span>

                    <span class="datoForm col1">
                <input type="submit" value="Generar"  tabindex="110"class="botonFormulario botFormCorte" tabindex="4">
            </span>

                </form>

                <form onsubmit="return false" id="asigCorFormAsig" ></form>

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




