<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta>
        <title>Reporte de Supervision de Facturas</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag--> <!--pag-->
        <script type="text/javascript" src="../js/repSupervisionFac.js?11 "></script>
        <link href="../css/facturacion.css " rel="stylesheet" type="text/css" />

        <!--Estilos-->
        <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <!--Librerías JS-->
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <script src="../css/bootstrap/js/bootstrap.js" ></script>
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>

    </head>
    <body >
    <header class="cabeceraTit">
        Reporte de supervision de facturas
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="formularioRepSup">
                <span class="datoForm col1">
                    <span class="titDato numCont3">Acueducto:</span>
                    <span class="inpDatp numCont3"><select select id="selProy" ></select></span>
                    <span class="titDato numCont3">Zona:</span>
                    <span class="inpDatp numCont3"><select name="zon" select id="selZon" ></select></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont3">fecha planificacion:</span>
                    <span class="inpDatp numCont3"><input type="date" name="fecha" id="inpFecha"></span>
                    <span class="titDato numCont3">Operario:</span>
                    <span class="inpDatp numCont3"><select name="oper" id="selOper" ></select></span>
                </span>


            <span class="datoForm col1">
            <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormFac" tabindex="9"">
        </span>
            </form>

            <div class="container-fluid">
                <div class="row" style="margin-left: auto;" width="95%">
                    <table id="dataTableRepSup" width="98%" class="row-border hover stripe"></table>
                </div>
            </div>

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
