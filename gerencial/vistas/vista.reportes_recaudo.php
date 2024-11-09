<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Reportes mensuales</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/reportes_recaudo.js?2"></script>
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />

        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />


    </head>
    <body>
    <header>
        <div class="cabeceraTit fondGer">
            Reportes Gerenciales de recaudo
        </div>
    </header>

    <section>
        <article>
            <form id="formRepRec">

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

