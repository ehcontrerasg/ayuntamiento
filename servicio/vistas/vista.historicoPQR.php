<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <!--Librería JQUERY-->
            <script src="../../js/jquery-3.2.1.min.js" type="text/javascript"></script>
            <!--Librería FlexiGrid-->
            <!--<link rel="stylesheet" href="../../flexigrid/style.css" />-->
            <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
            <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
            <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
            <!--Librería Bootstrap-->
            <!--<script src="../../js/bootstrap.min.js" type="text/javascript"></script>-->
            <link href="../../css/bootstrap.min.css" type="text/css" rel="stylesheet">
            <!--Estilos-->
            <link href="../css/historicoPQR.css?"<?echo time()?> type="text/css" rel="stylesheet">
            <!--Lógica página-->
            <script src="../js/historicoPQR.js?"<?echo time()?> type="text/javascript"></script>
        </head>
        <body >
            <div id="formulario" onSubmit="return false">
                <div class="container" style="margin-top: 20px;" >
                    <div class="row">
                        <form  id="frmCodInmuble" action="#">
                            <label class="col-sm-1">Inmueble</label>
                            <input type="text" name="inmueble" id="txtInmueble" class="col-sm-2"/>
                            <input type="submit" value="Buscar" class="btn btn-success col-sm-2" id="btnBuscarReclamos"/>
                        </form>
                    </div>
                </div>
            </div>
            <div id="historico_reclamos"></div>
        </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

