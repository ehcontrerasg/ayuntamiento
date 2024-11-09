<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8 />
        <title>Genaracion de ordenes de mantenimiento</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/genera_orden_man.js?<?php echo time();?>"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
        <style>
            #dvPdf{
                height: 500px;
            }
        </style>
        <!--flexy grid-->
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?1 "></script>
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Generación mantenimiento de medidor
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genOrdManFor">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select required autofocus="true" tabindex="1" select id="genOrdManSelPro" ></select></span>
                <span class="titDato numCont2">Proceso Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="2" id="genOrdManInpProIni" placeholder="Inicial" type="text"></span>
                <span class="titDato numCont2">Proceso Final:</span>
                <span class="inpDatp numCont2"><input  tabindex="3"id="genOrdManInpProFin" placeholder="Final" type="text"></span>
            </span>

                <span class="datoForm col1">

                <span class="titDato numCont2">Cod.Sistema:</span>
                <span class="inpDatp numCont2"><input tabindex="4" id="genOrdManInpCodSis" placeholder="Cod. Sistema" type="text"></span>
                <span class="titDato numCont2">Manzana Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="5" id="genOrdManInpManIni" placeholder="Inicial" ></span>
                <span class="titDato numCont2">Manzana Final:</span>
                <span class="inpDatp numCont2"><input tabindex="6" id="genOrdManInpManFin" placeholder="Final"  ></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Motivo:</span>
                <span class="inpDatp numCont2"><select required tabindex="7" select id="genOrdManSelMot" ></select></span>
                <span class="titDato numCont2">Contratista:</span>
                <span class="inpDatp numCont2"><select required tabindex="8" select id="genOrdManSelCon" ></select></span>
                <span class="titDato numCont2">Operario:</span>
                <span class="inpDatp numCont2"><select required tabindex="9" select id="genOrdManSelOpe" ></select></span>

            </span>

                <span class="datoForm col1">
                <span class="titDato numCont4">Descripción:</span>
                <span class="inpDatp numCont8"><input required tabindex="10" id="genOrdManInpDes" placeholder="Descripcion" ></span>
            </span>



                <span class="datoForm col1">
                <button class="botonFormulario botFormMed" tabindex="11" id="genOrdManButPro">Procesar</button>
            </span>

            </form>


            <div class="contenedor-flexy" >
                <table id="flexGeneraOrdMan">
                </table>
            </div>

            <div id="dvPdf">
                <object id="genHojMedManObjHoj" class="conPdf" type="application/pdf"></object>
            </div>

        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        genOrdManInicio();
    </script>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

