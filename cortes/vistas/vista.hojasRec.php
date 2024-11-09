<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8"/>
        <script type="text/javascript" src="../../js/spinner.js"></script>
        <script type="text/javascript" src="../js/hojasRec.js?<?echo time();?>"></script>
        <title></title>
    </head>
    <body>

    <form id="formHojasRec">
        <label>proyecto</label>
        <select id="selProyecto" required></select>

        <label>Sector</label>
        <select id="selSector" required></select>

        <label>Zona</label>
        <select id="selZona" required></select>

        <label>operario</label>
        <select id="selOperario" required></select>

        <label>Fecha Asignacion</label>
        <input type="date" id="fecha_asig" />

        <input type="radio" name="rbModo" value="M" checked> Movil
        <input type="radio" name="rbModo"  value="P"> Papel


        <input type="button" id="butOrdRec" value="Generar ordenes" required>
    </form>
    <iframe id="ifHojaRec" src="" id="gen_pdf"width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="500" style="background-color: #E0E0E0;"></iframe>

    <script>
        inicio();
    </script>
    </body>

    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

