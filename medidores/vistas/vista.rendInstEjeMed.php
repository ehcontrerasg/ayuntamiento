<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Rendimiento de Instalacion</title>
        <!-- alertas -->

        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/rendInstEjeMed.js?4"></script>
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />

        <!--flexy grid-->
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?8">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js "></script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k&signed_in=true"></script>
        </body>
    </head>


    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Rendimiento de Instalacion Realizado por dia por fecha de ejecucion
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="rendInstEjeMedForm" >
              <span class="datoForm col1">
                  <span class="titDato numCont1">Acueducto:</span>
                  <span class="inpDatp numCont2"><select required name="proyecto" select id="rendInstEjeMedSelPro" ></select></span>
                  <span class="titDato numCont1">Sector:</span>
                  <span class="inpDatp numCont2"><select   name="sector"  id="rendInstEjeMedSelSec" ></select></span>
                  <span class="titDato numCont1">Fecha Ini:</span>
                  <span class="inpDatp numCont1"><input  id="rendInstEjeMedFecIni" name="FecIni" type="date" ></span>
                  <span class="titDato numCont1">Fecha Fin:</span>
                  <span class="inpDatp numCont1"><input  id="rendInstEjeMedFecFin" name="FecFin" type="date"  ></span>
              </span>
                <span class="datoForm col1">
                  <input type="submit" value="Generar" class="botonFormulario botFormMed" >
                  <input type="button" value="Generar Excel" class="botonFormulario botFormMed" id="genRepXls">
              </span>
            </form>

            <div class="contenedor-flexy" >
                <table id="flexRendInstEjeMed">
                </table>
            </div>
        </article>

        <article class="detalle_ruta">
            <div class="contenedor-flexy2" >
                <table id="flexRendInstEjeMedDet">
                </table>
            </div>
        </article>

        <article class="mapa_ruta">
            <div id="map1" class="contenedor-mapa" >

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

