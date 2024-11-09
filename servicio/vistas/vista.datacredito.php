<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Inmuebles Datacredito</title>

        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/servicio.css " />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/datacredito.js?4"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Datacredito de Inmuebles.
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepInmPdcForm">
                <div class="subCabecera">
                    Filtros de Búsqueda De Inmuebles En Datacredito
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyecto"></select>
                    </div>
                    <div class="contenedor">
                        <button id="butGenInmPdc">Generar Reporte</button>
                    </div>
                </div>

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

