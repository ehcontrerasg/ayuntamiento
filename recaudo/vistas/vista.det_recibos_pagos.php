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
    $cod_inmueble=$_GET['cod_inmueble'];
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
        <script type="text/javascript" src="../../js/facturaspendientes.js"></script>
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
        <form name="detallerecpagos" action="vista.det_recibos_pagos.php" method="post" target="jobFrame">


            <table id="flex1" style="display:block;float: left">
            </table>


        </form>
        <script type="text/javascript">
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {

                    url: './../datos/datos.RecibosPagos.php?cod_inmueble=<?php echo $cod_inmueble;?>',

                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width:25,  align: 'center'},
                        {display: 'Id Pago', name: 'ID_PAGO', width:70,  sortable: true, align: 'center'},
                        {display: 'Fecha<br/>Pago', name: 'FECHA_PAGO', width: 70, sortable: true, align: 'center'},
                        {display: 'Importe', name: 'IMPORTE', width: 75, sortable: true, align: 'center'},
                        {display: 'Cajera', name: 'CAJA', width: 75, sortable: true, align: 'center'},
                        {display: 'Periodo', name: 'PERIODO', width: 55, sortable: true, align: 'center'}
                    ],

                    sortname: "FECIND",
                    sortorder: "DESC",
                    usepager: false,
                    title: 'Listado Pagos Inmueble <?php echo $cod_inmueble?>',
                    useRp: false,
                    rp: 1000,
                    page: 1,
                    showTableToggleBtn: false,
                    width: 559,
                    height: 270
                }
            );
        </script>
    </div>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

