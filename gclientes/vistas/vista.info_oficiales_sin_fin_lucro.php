<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):
    error_reporting(-1);?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Informe Mensual Oficiales e Instituciones sin fines de lucro</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/gclientes.css " />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/repInfOfiSinF.js?<? echo time();?>"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Informe Mensual Oficiales e Instituciones sin fines de lucro
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepInfOfiSinForm">
                <div class="subCabecera">
                    Filtros de Búsqueda Informe Mensual Oficiales e Instituciones sin fines de lucro
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <label> Proyecto: </label>
                        <select name="proyecto" id="selProyInfOfiSinF" ></select>
                    </div>
                    <div class="contenedor">
                        <label>Zona: </label>
                        <input name="zona" type="text" id="inpZonOfiSinF" >
                    </div>
                    <div class="contenedor">
                        <label> Grupo: </label>
                        <select name="grupo" id="selGruInfOfiSinF"></select>
                    </div>
                    <div class="contenedor">
                        <label>Período Inicial: </label>
                        <input name="periodo" type="number" id="inpPerInfOfiSinF" >
                    </div>
                    <div class="contenedor">
                        <label>Período Final: </label>
                        <input name="perfin" type="number" id="inpPerFinInfOfiSinF">
                    </div>
                    <div class="contenedor">
                        <label>Documento: </label>
                        <input name="documento" type="number" id="inpDocumentoSinF">
                    </div>
                    <div class="contenedor">
                        <button id="butGenInfOfiSinF">Generar Reporte</button>
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

