<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 16/09/2019
 * Time: 02:32 PM
 */
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Calendario de Facturación</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        <script type="text/javascript" src="../js/calenfact.js?<?php echo time();?>"></script>
        <link href="../../css/general.css?<?php echo time();?> " rel="stylesheet" type="text/css" />
    </head>

    </head>
    <body>
    <header class="cabeceraTit fondFac">
        Control Procesos de Facturación
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genCalFacForm" >

                <span class="tituloTabla">Calendario Facturación CAASD</span>
                <table class="contenedor-tabla" id="tableCAASD">
                    <thead>
                        <tr>
                            <th class="tituloTablaB" rowspan="2">Zona</th>
                            <th class="tituloTablaB" colspan="2">Apertura</th>
                            <th class="tituloTablaB" colspan="2">Lecturas</th>
                            <th class="tituloTablaB" colspan="2">Mora</th>
                            <th class="tituloTablaB" colspan="2">Cierre</th>
                            <th class="tituloTablaB" colspan="2">Distribución</th>
                            <th class="tituloTablaB" colspan="2">Vencimiento</th>
                            <th class="tituloTablaB" colspan="2">Corte</th>
                        </tr>
                        <tr>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                            <th class="tituloTablaB">Prog.</th>
                            <th class="tituloTablaB">Real</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <span class="tituloTabla">Calendario Facturación CORAABO</span>
                <table class="contenedor-tabla" id="tableCORAABO">
                    <thead>
                    <tr>
                        <th class="tituloTablaB" rowspan="2">Zona</th>
                        <th class="tituloTablaB" colspan="2">Apertura</th>
                        <th class="tituloTablaB" colspan="2">Lecturas</th>
                        <th class="tituloTablaB" colspan="2">Mora</th>
                        <th class="tituloTablaB" colspan="2">Cierre</th>
                        <th class="tituloTablaB" colspan="2">Distribución</th>
                        <th class="tituloTablaB" colspan="2">Vencimiento</th>
                        <th class="tituloTablaB" colspan="2">Corte</th>
                    </tr>
                    <tr>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                        <th class="tituloTablaB">Prog.</th>
                        <th class="tituloTablaB">Real</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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

