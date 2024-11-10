<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    $coduser = $_SESSION['codigo'];
    if($_GET['cod_inmueble']){
        $cod_sistema = ($_GET['cod_inmueble']);
    }else{
        $cod_sistema=$codinmueble;
    }

// Establecemos la conexión
    $Cnn = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8 />
        <title>Fotos Mantenimiento </title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?<?php echo time();?>" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?<?php echo time();?>"></script>
        <script type="text/javascript" src="../js/fotos.js?<?php echo time();?>"></script>
        </head>


    <body bgcolor="#E0E0E0">
    <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#000000">

        <?php
        /*consulta de fotos del predio en particular*/
        $sql = "SELECT URL_FOTO, TO_CHAR(FECHA,'DD/MM/YYYY') FECHA FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod_sistema AND ELIMINADA = 'N'" ;
        $stid = oci_parse($link, $sql);
        oci_execute($stid, OCI_DEFAULT);
        //echo $sql."<br>";
        while (oci_fetch($stid)) {
            $url_foto = oci_result($stid, 'URL_FOTO');
            $url_foto = substr($url_foto, 3);
            $fecha= oci_result($stid, 'FECHA');
            // echo($url_foto);
            ?>
            <tr class="titulo" height="20" bgcolor="#CCCCCC">
                <th>FOTOGRAFIAS INMUEBLE MANTENIMIENTOS <?php echo $cod_sistema."  FECHA: ".$fecha?></th>
            </tr>
            <tr class="titulo" bgcolor="#DCDCDA">
                <td align="center" height="670" ><img onLoad="redimension('<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>')" align="center" onClick="rotate(90,<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>,'<? echo /*'../../../webservice/fotos_sgc/'.*/$url_foto;?>');"   id="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" name="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" src="../../webservice/fotos_sgc/<? echo $url_foto; ?>">
                    <div><button onClick="eliminafoto('<? echo $url_foto ?>')" id="<? echo $url_foto ?>">Eliminar foto</button></div>
                </td>

            </tr>
            <?
        } oci_free_statement($stid);
        ?>
    </table>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

