
<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!doctype html>
    <head>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--Alertas-->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--Acciones-->
        <script type="text/javascript" src="../js/EditaFormaPago.js?8"></script>
        <!--BOOTSTRAP-->
        <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <form onsubmit="return false" id="frmEdicionFormaPago">
        <div class="container">
            <div class="row">
                <h1 align="center" style="color:#638bcc;"> Editar forma de pago</h1>
            </div>
            <div class="row">
                <div id="mensaje" style="margin-left:20%;margin-top:5%;">
                    <div class="form-group  col-sm-6">
                        <label>Forma de pago actual: <span id="formaPago"></span></label>
                    </div>
                    <div class="form-group  col-sm-6">
                        <label>Nueva forma de pago:&nbsp</label><select id="selTipoPago"></select>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div id="controles" class="col-sm-6" style="margin-left:25%; background: linear-gradient(#F7F7F7,#F7F7F7);"></div>
            </div>
            <div class="row">
                <div class="form-group  col-lg-12" style="margin-left:45%" >
                    <input type="submit" name="procesar" id="procesar" value="Aplicar cambio" class="btn btn-success">
                </div>
            </div>
        </div>

    </form>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError2.php";
endif; ?>

