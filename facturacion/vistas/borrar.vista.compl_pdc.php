<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>


    <?php
    include '../clases/class.deuda_cero.php';
    $codinmueble=$_GET['codinmueble'];
    $nom_cli=$_GET['nom_cli'];
    $nom_cli2=$_POST['nom_cli2'];
    $codinmueble2=$_POST['codinmueble2'];
    $servicios2 = $_POST["servicios2"];

    if (isset($_REQUEST["procesar"])){
        echo $codinmueble2.' '.$nom_cli2.' '.$servicios2;
        $f=new deuda_cero();
        $respdc=$f->aplicaPDC($codinmueble,$servicios,$mora,$cuotas);
        if($respdc==true)
        {
            echo "
		<script type='text/javascript'>
		showDialog('PDC Generado Satisfactoriamente','Se ha generado el PDC para el inmueble $codinmueble','success');
		</script>";
        }
        else{
            $mserror=$i->getmsgresult();
            echo "
		<script type='text/javascript'>
		showDialog('Error Generando PDC','El inmueble no cumple con los requisitos para aplicar el PDC.<br> $mserror.<br> Por favor verifique.<br>','error');
		</script>";
        }
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet" />
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <style type="text/css">
            table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            td{

                height:24px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            input{
                border:0px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
        </style>
    </head>
    <body leftmargin="0">
    <form name="formcompl_pdc" action="vista.compl_pdc.php" method="post">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th>Nombre</th>
                <th>C&eacute;dula</th>
                <th>En calidad de</th>
                <th>Tel&eacute;fono</th>
            </tr>
            <tr>
                <td align="center"><input type="text" name="nompdc" value="<?php echo $nompdc?>" size="25" maxlength="70" placeholder="Ingrese Nombre"/></td>
                <td align="center"><input type="text" name="cedpdc" value="<?php echo $cedpdc?>" size="15" maxlength="13" placeholder="Ingrese C&eacute;dula"/></td>
                <td align="center">
                    <select name='calpdc' >
                        <option value="" style="color:#CCCCCC;" disabled selected>Seleccione En Calidad...</option>
                        <option value="I">Inquilino</option>
                        <option value="R">Representante</option>
                    </select>
                </td>
                <td align="center"><input type="text" name="telpdc" value="<?php echo $telpdc?>" size="12" maxlength="12" placeholder="Ingrese Tel&eacute;fono"/></td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" colspan="4" style="color:#FF0000"><b>NOTA: Diligencie estos campos, solo si el cliente que firma el PDC, es una persona diferente a
                        <?php echo $nom_cli?>.</b>
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" colspan="4">
                    <button type="submit" name="procesar" id="procesar" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO"><i class="fa fa-upload"></i><b>&nbsp;&nbsp;Generar PDC</b></button>
                </td>
            </tr>
        </table>
        <input type="hidden" name="nom_cli2" value="<?php echo $nom_cli?>" />
        <input type="hidden" name="codinmueble2" value="<?php echo $codinmueble?>" />
        <input type="hidden" name="servicios2" value="<?php echo $servicios?>" />
    </form>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>


