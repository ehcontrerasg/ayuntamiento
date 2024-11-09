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
    $inmueble=$_GET['inmueble'];
//$importe1=$_GET['importe1'];
//$importe2=$_GET['importe2'];
//$importe3=$_GET['importe3'];
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <style type="text/css">
            b { color: red }
            .iframe{
                margin-top:-10px;
                margin-left:-1px;
                border-color: #666666;
                border:0px solid #ccc;
                display:none;
                width: 561px;
                height: 265px;
                float:right;
            }
        </style>
    </head>
    <body style="padding: 1px">
    <div style="display:block;float: left">

        <table id="flex1" style="display:block;float: left">
        </table>
    </div>
    <script type="text/javascript">
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {

                url: './../datos/datos.PagosInmueble.php?inmueble=<?php echo $inmueble;?>',

                dataType: 'json',
                colModel : [
                    {display: 'N&deg;', name: 'rnum', width:70,  align: 'center'},
                    {display: 'Id Pago', name: 'id_pago', width:70,  sortable: true, align: 'center'},
                    {display: 'Fecha<br/>Pago', name: 'FECHA_PAGO', width: 80, sortable: true, align: 'center'},
                    {display: 'Valor<br/>Pagado', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                    {display: 'Cajero', name: 'ID_USUARIO', width: 180, sortable: true, align: 'center'}
                ],

                sortname: "ID_PAGO",
                sortorder: "DESC",
                usepager: false,
                title: 'Listado Pagos Realizados Inmueble <?php echo $inmueble?>',
                useRp: false,
                rp: 1000,
                page: 1,
                showTableToggleBtn: false,
                width: 560,
                height: 195
            }
        );
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

