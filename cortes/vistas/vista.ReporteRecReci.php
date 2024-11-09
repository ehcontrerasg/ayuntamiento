<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Trabajo deiario de reconexiones</title>
        <!-- alertas -->

        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/recReci.js?2<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />
        <!--flexy grid-->
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?4">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js "></script>
        <!--mapa google-->
        <!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k&signed_in=true"></script>
        </body>
    </head>


    </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Trabajo deiario de reconexiones
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="recReciForm" >
              <span class="datoForm col1">
                  <span class="titDato numCont2">Acueducto:</span>
                  <span class="inpDatp numCont2"><select required name="proyecto" select id="proyecto" ></select></span>

                  <span class="titDato numCont2">Periodo:</span>
                  <span class="inpDatp numCont2"><input required id="periodo" name="periodo" type="number"  ></span>
              </span>
                <span class="datoForm col1">
                  <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormCorte" tabindex="9">
              </span>
            </form>

            <div>
                <object id="repRecReci" class="conPdf" type="application/pdf"></object>
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

