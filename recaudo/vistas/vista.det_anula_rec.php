<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../../destruye_sesion.php';
    $_SESSION['tiempo']=time();
    $_POST['codigo']=$_SESSION['codigo'];
    $cod_inmueble=$_POST['cod_inmueble'];
    $id_pago=$_GET['id_pago'];
    $cod_rec = $_POST['cod_rec'];
    $observacion=$_POST['observacion'];
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <style type="text/css">
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
        </style>

    </head>
    <body style="padding: 1px">
    <div style="display:block; float:left; margin-top:-20px; margin-left:-20px" class="flexigrid" id="content">
        <form name="anulapagos" action="vista.anularecaudos.php" method="post" target="jobFrame">
            <table align="right" style="margin-top:-19px; margin-left:38px">
                <tr>
                    <td>
                        <div class="flexigrid" style="width:743px; margin-top:19px; margin-left:-38px;" align="right">
                            <div class="mDiv">
                                <div align="left"><b>Observaci&oacute;n de Anulaci&oacute;n Otro Recaudo N&deg; <?php echo $id_pago;?></b></div>
                            </div>
                        </div>
                        <textarea id="observacion" name="observacion" cols="120" rows="6" class="input" style="margin-left:-38px" required><?php echo $observacion;?></textarea>
                    </td>
                    <td >

                        <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#FF8000; color:#FF8000; display:block; margin-top:60px; margin-left:100px" type="submit" name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Anular Recaudo</b>
                        </button>
                        <input type="hidden" name="cod_rec" value="<?php echo $id_pago;?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

