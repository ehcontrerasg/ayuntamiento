<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    $inmueble=$_GET['cod_inmueble']?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/style_aseo.css"/>
        <script type="text/javascript" src="../../js/cambiarPestanna.js"></script>
        <script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>

        <title></title>
    </head>
    <body>
    <div class="contenedor">
        <div id="pestanas">
            <ul id=lista>
                <li id="pestana6"><a href='javascript:cambiarPestanna(pestanas,pestana6);'>GENERAL</a></li>
                <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>SERVICIOS</a></li>
                <!--li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>MEDIDOR</a></li-->
                <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>OBSERVACIONES</a></li>
                <li id="pestana4"><a href='javascript:cambiarPestanna(pestanas,pestana4);'>FOTOS</a></li>
                <li id="pestana5"><a href='javascript:cambiarPestanna(pestanas,pestana5);'>CONTRATOS</a></li>
                <!--li id="pestana7"><a href='javascript:cambiarPestanna(pestanas,pestana7);'>CORTES</a></li-->
            </ul>
        </div>

        <body onload="javascript:cambiarPestanna(pestanas,pestana6);">

        <div id="contenidopestanas">


            <div id="cpestana6">
                <iframe width="1000" height="700"  src="vista.actualizarinmueble.php?cod_sistema=<?php echo $inmueble;?>"></iframe>
            </div>
            <div id="cpestana1">
                <iframe width="1000" height="700"  src="vista.servicios.php?cod_inmueble=<?php echo $inmueble;?>"></iframe>

            </div>
            <!--div id="cpestana2">
                <iframe width="1000" height="700"  src="vista.medidor.php?cod_inmueble=<?php echo $inmueble;?>"></iframe>
            </div-->
            <div id="cpestana3">
                <iframe width="1000" height="700"  src="vista.observaciones.php?cod_inmueble=<?php echo $inmueble;?>"></iframe>
            </div>
            <div id="cpestana4">
                <iframe width="1000" height="700"  src="vista.fotos.php?cod_inmueble=<?php echo $inmueble;?>"></iframe>
            </div>
            <div id="cpestana5">
                <iframe width="1000" height="700"  src="vista.contrato.php?cod_inmueble=<?php echo $inmueble;?>"></iframe>
            </div>
            <!--div id="cpestana7">
                <iframe width="1000" height="700"  src="vista.cortes.php?cod_inmueble=<?php echo $inmueble;?>"></iframe>
            </div-->

        </div>
        </body>
    </html>
    </pre>



<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

