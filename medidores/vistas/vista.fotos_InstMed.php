<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include_once ('../../include.php');
    include '../../clases/class.medidor.php';
    $coduser = $_SESSION['codigo'];
    $orden = ($_GET['orden']);


    $fecini = substr($fecini,0,10);
    $fecfin = substr($fecfin,0,10);

    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>
    <body bgcolor="#E0E0E0">
    <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#000000">
        <tr class="titulo" height="20" bgcolor="#CCCCCC">
            <th>FOTOGRAFIAS INSTALACION DE MEDIDORES<?php echo $cod_sistema?></th>
        </tr>
        <?php
        /*consulta de fotos del predio en particular*/
        $i=new Medidor();
        $result=$i->getUrlFotInsMedByOrden($orden);
        while (oci_fetch($result)) {
            $url_foto = oci_result($result, 'URL_FOTO');
            $fecha_foto = oci_result($result, 'FECHA_REEALIZACION');
            $usuario_cm = oci_result($result, 'LOGIN');
            $url_foto = substr($url_foto, 3);
            // echo($url_foto);
            ?>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td>
                    <h4 class="panel-heading" style="background-color:#336699;border-color:#336699"><center> <?php echo $cod_sistema.' /-/ '.$fecha_foto.' /-/ '.$usuario_cm ?></center></h4>
                </td>
            </tr>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td align="center" ><img src="../../../webservice/<? echo $url_foto; ?>"></td>
            </tr>
            <?
        } oci_free_statement($result);
        ?>
    </table>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
