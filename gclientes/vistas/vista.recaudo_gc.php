<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reporte Recaudo Grandes Clientes</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gclientes.css " />

    </head>
    <body>
    <header>
        <div class="cabecera">
            Reporte Recaudo Grandes Clientes
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepRecGraCliForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Reporte Recaudo Grandes Clientes
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select name="proyecto" id="selProyRecGraCli"></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Inicial: </label>
                        <input  name="fechaini" type="date" id="inpFecIniRecGraCli">
                    </div>
                    <div class="contenedor">
                        <label>Fecha Final: </label>
                        <input name="fechafin" type="date" id="inpFecFinRecGraCli">
                    </div>
                    <div class="contenedor">
                        <label for="slcUso">Uso: </label>
                        <select name="uso" id="slcUso">
                            <option value="">Todos los usos</option>
                        </select>
                    </div>
                    <div class="contenedor">
                        <button id="butGenRecGraCli">Generar Reporte</button>
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
    </body>
        <script type="text/javascript" src="../../js/jquery-3.3.1.min.js"></script>
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repRecGraCli.js?<? echo time(); ?>"></script>
        <script type="text/javascript">
            repRecGraCliInicio();
        </script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

