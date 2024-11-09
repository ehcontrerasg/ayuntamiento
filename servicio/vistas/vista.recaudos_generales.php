<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reporte Recaudo</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/servicio.css?1 " />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repRecCli.js"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Reporte Recaudo
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepRecGraCliForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Reporte Recaudo
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyRecGraCli"></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Inicial: </label>
                        <input type="date" id="inpFecIniRecGraCli" style="width: auto;">
                    </div>
                    <div class="contenedor">
                        <label>Fecha Final: </label>
                        <input type="date" id="inpFecFinRecGraCli" style="width: auto;">
                    </div>
                    <div class="contenedor">
                        <label>Inmueble: </label>
                        <input type="number" id="inpInmueble" style="width: auto;">
                    </div>
                    <div class="contenedor">
                        <button id="butGenRecGraCli" class="btnserv">Generar Reporte</button>
                    </div>
                </div>
                <div>
                    <input type="hidden" id="inpHidValRes">
                </div>
            </form>
        </article>
    </section>
    <footer>
    </footer>
    <script type="text/javascript">
        repRecGraCliInicio();
    </script>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

