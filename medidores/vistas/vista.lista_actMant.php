<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Lista de actividades</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/ingresa_resultados_man.js"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />

    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Lista Actividades Mantenimiento
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="lisActManFomr">
            </form>
        </article>
    </section>

    <footer>
    </footer>

    <script type="text/javascript">
        listActInicio();
    </script>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

