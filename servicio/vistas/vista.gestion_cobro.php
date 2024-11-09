<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Gesti&oacute;n de Cobro</title>
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
        <script type="text/javascript" src="../js/repGesCobro.js?0"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Reporte de Gesti&oacute;n de Cobro
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepGesCobroForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Gesti&oacute;n de Cobro
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select id="selProyGesCobro" name="proyecto" required></select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Gestion Inicial: </label>
                        <input type="date" id="inpFecIniGesCobro" name="fecGestionIni" class="inputDate" required>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Gestion Final: </label>
                        <input type="date" id="inpFecFinGesCobro" name="fecGestionFin" class="inputDate" required>
                    </div>
                    <div class="contenedor">
                        <label for="sltUsrGestion">Usuario: </label>
                        <select id="sltUsrGestion" name="sltUsrGestion">
                            <option></option>
                        </select>
                    </div>
                    <div class="contenedor">
                        <button id="butGenGesCobro">Generar Reporte</button>
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

