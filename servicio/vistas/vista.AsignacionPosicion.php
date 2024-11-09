<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!doctype html>
    <html>
        <head>
            <!--ESTILOS-->
            <link href="../../css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
            <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
            <link rel="stylesheet" type="text/css" href="../css/AsignacionPosicion.css?<?php echo time();?>" />
        </head>
        <body>
            <div>
                <div><h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px; text-align:center; margin:27px; width: 96%;">Asignación de posición</h3></div>
                <div id="dvPosiciones">
                    <table id="dtPosiciones" class="stripe" style="width: 100%;"></table>
                </div>
            </div>
        </body>
        <!--SCRIPTS-->
        <script src="../../js/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../../js/sweetalert.min.js" type="text/javascript"></script>
        <script src="../../js/session.js?<?echo time();?>"></script>
        <script src="../js/AsignacionPosicion.js?<?php echo time();?>" type="text/javascript"></script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>