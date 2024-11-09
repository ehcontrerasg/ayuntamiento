<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Genaracion de ordenes de medidores</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        <script type="text/javascript" src="../js/genera_orden.js?2"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
        <!--flexy grid-->
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js "></script>
    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Generación Ordenes de Cambio de Medidores
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genOrdFor">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select tabindex="1" name="proyecto" required select id="genOrdSelPro" ></select></span>
                <span class="titDato numCont2">Proceso Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="2" name="proini" id="genOrdInpProIni" placeholder="Inicial" type="text"></span>
                <span class="titDato numCont2">Proceso Final:</span>
                <span class="inpDatp numCont2"><input  tabindex="3"id="genOrdInpProFin" name="profin" placeholder="Final" type="text"></span>
            </span>

                <span class="datoForm col1">

                <span class="titDato numCont2">Cod.Sistema:</span>
                <span class="inpDatp numCont2"><input tabindex="4" id="genOrdInpCodSis" name="codsis" placeholder="Cod. Sistema" type="text"></span>
                <span class="titDato numCont2">Manzana Inicial:</span>
                <span class="inpDatp numCont2"><input tabindex="5" id="genOrdInpManIni" name="manini" placeholder="Inicial" ></span>
                <span class="titDato numCont2">Manzana Final:</span>
                <span class="inpDatp numCont2"><input tabindex="6" id="genOrdInpManFin" name="manfin" placeholder="Final"  ></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Medidor:</span>
                <span class="inpDatp numCont2"><select tabindex="7" name="medido" select id="genOrdSelMed" >
                        <option></option>
                        <option value="S">MEDIDO</option>
                        <option value="N">NO MEDIDO</option>
                    </select>
                </span>
                <span class="titDato numCont2">Estado Inmueble:</span>
                <span class="inpDatp numCont2"><select tabindex="8" name="estado" select id="genOrdSelEstInm" >
                        <option></option>
                        <option value="A">ACTIVO</option>
                        <option value="I">NO ACTIVO</option>
                    </select>
                </span>
                <span class="titDato numCont2">Motivo:</span>
                <span class="inpDatp numCont2"><select tabindex="9" name="motivo" required id="genOrdSelMot" ></select></span>
            </span>

                <span class="datoForm col1">
                <span class="titDato numCont2">Contratista:</span>
                <span class="inpDatp numCont2"><select tabindex="10" required id="genOrdSelCon" ></select></span>
                <span class="titDato numCont2">Operario:</span>
                <span class="inpDatp numCont2"><select tabindex="11" name="usr_asignado" required id="genOrdSelOpe" ></select></span>
                <span class="titDato numCont2">Descripción:</span>
                <span class="inpDatp numCont2"><input tabindex="6" name="desc" id="genOrdInpDes" placeholder="Descripcion" ></span>
            </span>

                <span class="datoForm col1">
                <span class="titDatp numCont2">Factura Instalacion: <select tabindex="12" name="aplFact" required id="ingResCambSelFac">
                       <option selected></option>
                        <option  value="S">SI</option>
                        <option value="N">NO</option>
                    </select>
                </span>
                </span>


                </span>





                <span class="datoForm col1">
                <button class="botonFormulario botFormMed" tabindex="9" id="genOrdButPro">Procesar</button>
            </span>

            </form>


            <div class="contenedor-flexy" >
                <table id="flexGeneraOrd">
                </table>
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

