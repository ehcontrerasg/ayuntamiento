<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset=UTF-8" />
        <title>Migración Inmuebles</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/migra_inmuebles.js?<?php echo time();?>"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondAseo">
        Migración De Inmuebles
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="busMigInmForm">
                <!--div class="subCabecera fondSerCli"> Datos del imueble</div-->
                <span class="datoForm col1">
                    <span class="titDato numCont1">Acueducto</span>
                    <span class="inpDatp numCont1"><select  select id="migInmSelPro" required></select></span>
                    <span class="titDato numCont1">Sector</span>
                    <span class="inpDatp numCont1"><select   select id="migInmSelSec" required></select></span>
                    <span class="titDato numCont1">Ruta</span>
                    <span class="inpDatp numCont1"><select   select id="migInmSelRut" required></select></span>
                    <span class="titDato numCont1">Manzana</span>
                    <span class="inpDatp numCont1"><input  id="migInmMan" name="mza" placeholder="Manzana" type="text"></span>
                    <span class="titDato numCont1">Cod. Sistema</span>
                    <span class="inpDatp numCont1"><input  id="migInmCod" name="codsis" placeholder="Código Sistema" type="text"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Migrar A:</span>
                    <span class="titDato numCont1">Ciclo</span>
                    <span class="inpDatp numCont1"><select  select id="migInmSelCic" required></select></span>
                    <span class="titDato numCont1">Sector</span>
                    <span class="inpDatp numCont1"><select   select id="migInmSelNueSec" required></select></span>
                    <span class="titDato numCont1">Ruta</span>
                    <span class="inpDatp numCont1"><select   select id="migInmSelNueRut" required></select></span>
                    <span class="titDato numCont1">Manzana</span>
                    <span class="inpDatp numCont1"><input  id="migInmNueMan" name="mza" placeholder="Manzana" type="text"></span>
                </span>
                <span class="datoForm col1">
                    <input type="submit"  value="Buscar Inmuebles" class="botonFormulario botFormAseo"  id="busMigInmBut">
                    <input type="button"  id="valProBut" hidden>
                    <input type="button"  id="valCatBut" hidden>
                </span>
            </form>
            <form onsubmit="return false" id="actMigInmForm" >
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

