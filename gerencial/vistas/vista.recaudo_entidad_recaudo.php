<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Resumen De Recaudo Por Entidad</title>
        <!--JQUERY -->
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gerencia_recaudo.css?<?echo time()?>" />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repResRecEnt.js"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Resumen De Recaudo Por Entidad
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepResRecEntForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Resumen De Recaudo Por Entidad
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyResRecEnt" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Pago Inicial: </label>
                        <input type="date" id="inpFecIniResRecEnt" name="fecini">
                    </div>
                    <div class="contenedor">
                        <label>Fecha Pago Final: </label>
                        <input type="date" id="inpFecFinResRecEnt" name="fecfin">
                    </div>
                    <div class="contenedor">
                        <button id="butGenResRecEnt">Generar Reporte</button>
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
        repResRecEntInicio();
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
