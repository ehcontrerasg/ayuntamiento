<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include_once ('../../include.php');
    include '../clases/classPqrs.php';
    $coduser = $_SESSION['codigo'];
    $cod_sistema = $_GET['cod_sistema'];
//$fecini=$_GET['fecini'];
//$fecfin=$_GET['fecfin'];
//$periodo=$_GET['periodo'];

    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../js/fotos.js?0"></script>
    </head>
    <body style="background-color:#E0E0E0">
    <h3 class="panel-heading" style="background-color:#000040;border-color:#000040; color:#FFFFFF"><center>Fotograf&iacute;as Inmueble <?php echo $cod_sistema?></center></h3>
    <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#E0E0E0">
        <?php
        /*consulta de fotos del predio en particular*/
        $i=new PQRs();
        $result=$i->urlfotomant($cod_sistema);
        while (oci_fetch($result)) {
            $url_foto = oci_result($result, 'URL_FOTO');
            $url_foto = substr($url_foto, 3);
            ?>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td align="center" height="670" >
                    <img onLoad="redimension('<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>')" align="center" onClick="rotate(90,<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>,'<? echo '../../../webservice/fotos_sgc/'.$url_foto;?>');"   id="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" name="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" src="../../../webservice/fotos_sgc/<? echo $url_foto; ?>">
                </td>
            </tr>
            <?
        } oci_free_statement($result);
        ?>
    </table>

    <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#E0E0E0">
        <?php
        /*consulta de fotos del predio en particular*/
        $i=new PQRs();
        $result=$i->urlfotolect($cod_sistema);
        while (oci_fetch($result)) {
            $url_foto = oci_result($result, 'URL_FOTO');
            $url_foto = substr($url_foto, 3);
            ?>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td align="center" height="670" >
                    <img onLoad="redimension('<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>')" align="center" onClick="rotate(90,<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>,'<? echo '../../../webservice/fotos_sgc/'.$url_foto;?>');"   id="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" name="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" src="../../../webservice/fotos_sgc/<? echo $url_foto; ?>">
                </td>
            </tr>
            <?
        } oci_free_statement($result);
        ?>
    </table>

    <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#E0E0E0">
        <?php
        /*consulta de fotos del predio en particular*/
        $i=new PQRs();
        $result=$i->urlfotofact($cod_sistema);
        while (oci_fetch($result)) {
            $url_foto = oci_result($result, 'URL_FOTO');
            $url_foto = substr($url_foto, 3);
            ?>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td align="center" height="670" >
                    <img onLoad="redimension('<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>')" align="center" onClick="rotate(90,<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>,'<? echo '../../../webservice/fotos_sgc/'.$url_foto;?>');"   id="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" name="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" src="../../../webservice/fotos_sgc/<? echo $url_foto; ?>">
                </td>
            </tr>
            <?
        } oci_free_statement($result);
        ?>
    </table>

    <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#E0E0E0">
        <?php
        /*consulta de fotos del predio en particular*/
        $i=new PQRs();
        $result=$i->urlfotocorte($cod_sistema);
        while (oci_fetch($result)) {
            $url_foto = oci_result($result, 'URL_FOTO');
            $url_foto = substr($url_foto, 3);
            ?>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td align="center" height="670" >
                    <img onLoad="redimension('<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>')" align="center" onClick="rotate(90,<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>,'<? echo '../../../webservice/fotos_sgc/'.$url_foto;?>');"   id="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" name="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" src="../../../webservice/fotos_sgc/<? echo $url_foto; ?>">
                </td>
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

