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
    $monto = $_GET['monto'];
    $importe1=$_GET['importe1'];
    $importe2=$_GET['importe2'];
    $importe3=$_GET['importe3'];
    $importe4=$_GET['importe4'];
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

                url: './../datos/datos.facturasPendientes.php?inmueble=<?php echo $inmueble;?>&monto=<?php echo $monto;?>&importe1=<?php echo $importe1;?>&importe2=<?php echo $importe2;?>&importe3=<?php echo $importe3;?>&importe4=<?php echo $importe4;?>',

                dataType: 'json',
                colModel : [
                    {display: 'N&deg;', name: 'rnum', width:30,  align: 'center'},
                    {display: 'Consecutivo<br/>Factura', name: 'CONSEC_FACTURA', width: 80, sortable: true, align: 'center'},
                    {display: 'Periodo', name: 'Periodo', width: 50, sortable: true, align: 'center'},
                    {display: 'Fecha<br/>Expedicion', name: 'FEC_EXPEDICION', width: 80, sortable: true, align: 'center'},
                    {display: 'Fecha<br/>Vencimiento', name: 'FEC_EXPEDICION', width: 80, sortable: true, align: 'center'},
                    {display: 'Nfc', name: 'NCF', width: 130, sortable: true, align: 'center'},
                    {display: 'Valor<br/>Factura', name: 'TOTAL', width: 70, sortable: true, align: 'center'},
                    {display: 'Pendiente', name: 'PENDIENTE', width: 70, sortable: true, align: 'center'},
                    {display: 'Abono', name: 'ABONO', width: 70, sortable: true, align: 'center'},
                    {display: 'Saldo', name: 'SALDO', width: 70, sortable: true, align: 'center'}
                ],

                sortname: "PERIODO",
                sortorder: "ASC",
                usepager: false,
                title: 'Listado Facturas Pendientes Inmueble <?php echo $inmueble?>',
                useRp: false,
                rp: 1000,
                page: 1,
                showTableToggleBtn: false,
                width: 1120,
                height: 130
            }
        );
    </script>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

