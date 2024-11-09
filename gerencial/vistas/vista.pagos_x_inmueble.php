<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reporte Pagos Por Inmueble</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?2" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?2"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js?2"></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css?2" rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gerencia.css?3" />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repComPagInm.js?1"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Comparativo Pagos Inmueble Entre dos Periodos
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepComPagInmForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Comparativo Pagos Inmueble Entre dos Periodos
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyComPagInm" name="proyecto" required></select>
                    </div>
                    <div class="contenedor">
                        <label>Periodo uno: </label>
                        <input type="number" id="inpPerUnoComPagInm" name="periodouno" required>
                    </div>
                    <div class="contenedor">
                        <label>Periodo dos: </label>
                        <input type="number" id="inpPerDosComPagInm" name="periododos" required>
                    </div>
                    <div class="contenedor">
                        <button id="butGenComPagInm">Generar Reporte</button>
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

