
<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reporte Facturaci&oacute;n y Recaudo Detallado Por Uso</title>
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
        <script type="text/javascript" src="../js/repFacRecDetUso.js?<?echo time()?>"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Facturaci&oacute;n y Recaudo Detallado Por Uso
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepFacRecDetUsoForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Facturaci&oacute;n y Recaudo Detallado Por Uso
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyFacRecDetUso" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <label>Periodo: </label>
                        <input type="number" id="inpPerFacRecDetUso" name="periodo">
                    </div>
                    <div class="contenedor">
                        <button id="butGenFacRecDetUso">Generar Reporte</button>
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

