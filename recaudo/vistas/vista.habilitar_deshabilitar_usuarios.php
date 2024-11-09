<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 8/17/2018
 * Time: 9:10 AM
 */

include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset="UTF-8" />
        <title>Habilitar y deshabilitar usuarios </title>

        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <!--Datatable-->
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
        <!-- pagina -->
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/repPagDetxCon.css?20" rel="stylesheet" />
        <script type="text/javascript" src="../js/habilitarDeshabilitarUsuarios.js?<?php echo time()?>"></script>

        </head>
    <body>

    <header>
        <div class="cabecera">
            Habilitar y deshabilitar usuarios
    </header>

    <section>
        <article>
            <input title="text" hidden id="usuario">
            <input title="text" hidden id="estado">
            <input title="text" hidden id="nombre">

    <div class="container-fluid">
        <div class="row">
            <table id="dataTable" width="98%" class="row-border hover stripe">
            </table>
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

