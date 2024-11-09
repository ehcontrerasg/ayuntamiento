<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    /**
     * Created by PhpStorm.
     * User: PC
     * Date: 6/14/2016
     * Time: 11:05 AM
     */
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title></title>
        <script type="text/javascript" src="../../alertas/dialog_box.js "></script>
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js "></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js "></script>
        <link href="../../alertas/dialog_box.css " rel="stylesheet" type="text/css" />
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <link rel="stylesheet" href="../../flexigrid/style.css " />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css ">
        <script type="text/javascript" src="../js/datTot.js "></script>
    </head>

    <body>

    <div class="bloque3">
        <table id="flexTot" style="display:none">
        </table>

    </div>
    <script type="text/javascript">
        inicioDatTot();
        flexyTotalizadores();
    </script>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

