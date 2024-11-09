<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reporte Historico Facturacion Detallado</title>
        <!--JQUERY -->
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <!--alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones-->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag-->
        <link rel="stylesheet" type="text/css" href="../css/gerencia.css " />
        <!--logica pag-->
        <script type="text/javascript" src="../js/repHisFacDet.js?7"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Reporte Historico Facturacion Detallado
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepHisFacDetForm">
                <div class="subCabecera">
                    Filtros de Búsqueda  Historico Facturacion Detallado
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyHist" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <label>Período inicial: </label>
                        <input type="number" id="inpPerIniHist" name="periodoini">
                    </div>
                    <div class="contenedor">
                        <label>Período final: </label>
                        <input type="number" id="inpPerFinHist" name="periodofin">
                    </div>

                    <div class="contenedor">
                        <button id="butGenHist">Generar Reporte</button>
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
        repDetFactInicio();
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

