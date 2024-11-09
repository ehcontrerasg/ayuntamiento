<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Informe Mensual Grandes Clientes</title>
        <!--JQUERY -->
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gclientes.css " />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repInfGraCli.js?<?echo time();?>"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Informe Mensual Grandes Clientes
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepInfGraCliForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Informe Mensual Grandes Clientes
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyInfGraCli" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <label>Período: </label>
                        <input type="number" name="periodo" id="inpPerInfGraCli">
                    </div>
                    <div class="contenedor">
                        <label>Estado: </label>
                        <select name="estado" id="slcEstado">
                            <option></option>
                            <option >Deshabitados y cancelados</option>
                            <option >Gestion Legal</option>
                        </select>
                    </div>
                    <div class="contenedor">
                        <button id="butGenInfGraCli">Generar Reporte</button>
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
        repInfGraCliInicio();
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

