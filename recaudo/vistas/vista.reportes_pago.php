<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
//$codsecure = md5('ehdj65$kd*@';

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <title>Reportes Lecturas</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <link href="../../css/bootstrap.min.css" rel="stylesheet" />
        <script type="text/javascript" src="../js/repPagos.js?<?php echo time()?>"></script>
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />

    </head>
    <body style="margin-top:-25px">
    <header>
        <h3 class="panel-heading" style="  margin-top: 40px; box-shadow: 0 3px 0 #00a5bb; background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1200px" align="center">
            Reportes de Pagos
        </h3>
    </header>

    <section>
        <article>
            <form id="formRepPagos">

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

