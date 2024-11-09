<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Informe resumen gerencial </title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?0" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?0"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css?0" rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gerencia.css?<?echo time();?>" />

    </head>
    <body>
    <header>
        <div class="cabecera">
            Resumen de reportes gerenciales
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepConForm">
                <div class="subCabecera">
                    Filtros de Búsqueda de reportes gerenciales
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyRepCon" name="proyecto" required></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha inicial: </label>
                        <input type="date" id="fecha_inicial" placeholder="Periodo" name="fecha_inicial" required>
                        <label>Fecha final: </label>
                        <input type="date" id="fecha_final" placeholder="Periodo" name="fecha_final" required>
                        <button id="btnGenerarReportes">Generar</button>
                    </div>
                </div>
            </form>
            <div id="dvData"></div>
        </article>
    </section>
    <footer>
    </footer>
    </body>
    <!--logica pag    -->
    <script type="text/javascript" src="../js/resumen_gerencial.js?<?echo time();?>"></script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

