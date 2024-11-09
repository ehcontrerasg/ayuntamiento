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
    $cod_pago = $_POST['cod_pago'];
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
        <form name="anulapagos" action="vista.anulapagos.php" method="post" target="jobFrame">


            <table id="flex1" style="display:block;float: left">
            </table>
            <table align="right" style="margin-top:-220px; margin-left:600px">
                <tr>
                    <td>
                        <div class="flexigrid" style="width:538px; margin-top:19px; margin-left:-38px;" align="right">
                            <div class="mDiv">
                                <div align="left"><b>Observaci&oacute;n de Anulaci&oacute;n Pago <?php echo $id_pago;?></b></div>
                            </div>
                        </div>
                        <textarea id="observacion" name="observacion" cols="60" rows="14" class="input" style="margin-left:-38px" required><?php echo $observacion;?></textarea>
                    </td>
                    <td >

                        <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#FF8000; color:#FF8000; display:block; margin-top:60px; margin-left:-140px" type="submit" name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Anular Pago</b>
                        </button>
                        <input type="hidden" name="cod_pago" value="<?php echo $id_pago;?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {

                url: './../datos/datos.FacPagas.php?id_pago=<?php echo $id_pago;?>',

                dataType: 'json',
                colModel : [
                    {display: 'N&deg;', name: 'rnum', width:25,  align: 'center'},
                    {display: 'Consecutivo<br/>Factura', name: 'CONSEC_FACTURA', width:70,  sortable: true, align: 'center'},
                    {display: 'Periodo', name: 'periodo', width: 55, sortable: true, align: 'center'},
                    {display: 'Fecha<br/>Pago', name: 'FECHA_PAGO', width: 70, sortable: true, align: 'center'},
                    {display: 'Valor<br/>Factura', name: 'TOTAL', width: 75, sortable: true, align: 'center'},
                    {display: 'Importe', name: 'IMPORTE', width: 75, sortable: true, align: 'center'},
                    {display: 'Saldo', name: 'SALDO', width: 75, sortable: true, align: 'center'},
                    {display: 'Comprobante', name: 'COMPROBANTE', width: 75, sortable: true, align: 'center'}
                ],

                sortname: "PERIODO",
                sortorder: "ASC",
                usepager: false,
                title: 'Listado Facturas Pagas Con Id Pago <?php echo $id_pago?>',
                useRp: false,
                rp: 1000,
                page: 1,
                showTableToggleBtn: false,
                width: 560,
                height: 128
            }
        );
    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

