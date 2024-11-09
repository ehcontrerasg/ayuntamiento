<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();

    include '../../destruye_sesion_cierra.php';

    $_SESSION['tiempo']=time();
    $_POST['codigo']=$_SESSION['codigo'];
    $inmueble=$_GET['inmueble'];


    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script language="javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
    </head>
    <body style="width: 92%">
    <form name="FMTarifas" action="vista.hist_fac_ent.php" method="post" onsubmit="return rend();">
        <div class="flexigrid" style="width:700px">
            <table id="flex1" style="display:none">
            </table>
        </div>
    </form>
    <script type="text/javascript">
        <!--
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {

                url: './../datos/datos.histfactent.php?inmueble=<?php echo $inmueble;?>',

                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'rnum', width:25,  align: 'center'},
                    {display: 'Periodo', name: 'PERIODO', width: 60, sortable: true, align: 'center'},
                    {display: 'Factura', name: 'CONSEC_FACTURA', width: 100, sortable: true, align: 'center'},
                    {display: 'Valor', name: 'TOTAL', width: 90, sortable: true, align: 'center'},
                    {display: 'Fecha Pago', name: 'FECHA_PAGO', width: 90, sortable: true, align: 'center'},
                    {display: 'Entregó', name: 'OPERARIO', width: 130, sortable: true, align: 'center'}
                ],


                sortname: "PERIODO",
                sortorder: "DESC",

                title: 'Listado Facturas Entregadas',

                rp: 1000,
                page: 1,

                width: 660,
                height: 200
            }
        );




        //-->
    </script>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

