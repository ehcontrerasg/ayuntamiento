<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Inmuebles Data Credito</title>
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/servicio.css " />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repDataCredito.js?<?echo time()?>"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Inmuebles Data Credito
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepDataCreditoForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Inmuebles Data Credito
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyDataCredito"></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Pago Inicial: </label>
                        <input type="date" id="inpFecIniDataCredito" class="inputDate">
                    </div>
                    <div class="contenedor">
                        <label>Fecha Pago Final: </label>
                        <input type="date" id="inpFecFinDataCredito" class="inputDate">
                    </div>
                    <div class="contenedor">
                        <button id="butGenDataCredito">Generar Reporte</button>
                    </div>
                </div>
                <div>
                    <input type="hidden" id="inpHidValRes">
                </div>
            </form>
            <div id="table"></div>
        </article>
    </section>
    <footer>
    </footer>
    <script type="text/javascript">
        repDataCreditoInicio();
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

