<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8"/>
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../js/spinner.js"></script>
        <script type="text/javascript" src="../js/RepCorMens.js?<?echo time();?>"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    </head>
    <body>

    <div id="divForm">
        <br><label>Periodo</label>
        <select id="selPeriodo"></select>
        <select id="SelProyecto"></select>
        <span class="titDato numCont2">Contratista:</span>
        <span class="inpDatp numCont1"> <select tabindex="2"  name="selCon" required select id="selCon"></select></span>
        <input type="button" id="butRepResMen" value="Generar Reporte" >
    </div>
    <iframe id="ifRepResMen" src=""  width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="500" style="background-color: #E0E0E0;"></iframe>

    <script>
        inicio();
    </script>
    </body>

    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

