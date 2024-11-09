<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Inmuebles Con Deuda</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?0" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?0"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css?0" rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/servicio.css " />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repInmDeuda.js?0"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Inmuebles Con Deuda
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepInmDeudaForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Inmuebles Con Deuda
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyInmDeuda" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <label> Sector: </label>
                        <input  id="secInmDeuIni" name="secIni"  placeholder="Inicial" type="number" class="inputNumber">
                        <input  id="secInmDeuFin" name="secFin"  placeholder="Final" type="number" class="inputNumber">
                    </div>
                    <div class="contenedor">
                        <label> Ruta: </label>
                        <input  id="rutInmDeuIni" name="rutIni"  placeholder="Inicial" type="number" class="inputNumber">
                        <input  id="rutInmDeuFin" name="rutFin"  placeholder="Final" type="number" class="inputNumber">
                    </div>
                    <div class="contenedor">
                        <label> Unidades: </label>
                        <input  id="uniInmDeuIni" name="uniIni"  placeholder="Inicial" type="number" class="inputNumber">
                        <input  id="uniInmDeuFin" name="uniFin"  placeholder="Final" type="number" class="inputNumber">
                    </div>
                    <div class="contenedor">
                        <label> Facturas Pendientes: </label>
                        <input  id="facInmDeuIni" name="facIni" required placeholder="Desde" type="number" class="inputNumber">
                        <input  id="facInmDeuFin" name="facFin" required placeholder="Hasta" type="number" class="inputNumber">
                    </div>

                    <div class="contenedor">
                        <label> Periodos a comparar: </label>
                        <input  id="facInmDeuPer1" name="periodo1"  placeholder="Desde" type="number" class="inputNumber">
                        <input  id="facInmDeuPer1" name="periodo2"  placeholder="Hasta" type="number" class="inputNumber">
                    </div>
                    <div class="contenedor">
                        <button id="butGenInmDeuda">Generar Reporte</button>
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

