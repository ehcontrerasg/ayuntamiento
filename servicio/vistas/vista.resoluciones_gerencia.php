<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Resumen Resolucion y Gerencia</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!--JQUERY -->
    <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/servicio.css " />
        <!--logica pag    -->
    <script type="text/javascript" src="../js/repResolGer.js?4"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Resumen Por Tipo de Resoluci&oacute;n y Gerencia
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepResolGerForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Resumen Por Tipo de Resoluci&oacute;n y Gerencia
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
						<select id="selProyResolGer" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Resoluci&oacute;n Inicial: </label>
						<input type="date" id="inpFecIniResolGer" name="fecini" class="inputDate">
                    </div>
                    <div class="contenedor">
                        <label>Fecha Resoluci&oacute;n Final: </label>
						<input type="date" id="inpFecFinResolGer" name="fecfin" class="inputDate">
                    </div>
                    <div class="contenedor">
                        <button id="butGenResolGer">Generar Reporte</button>
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
        repResolGerInicio();
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

