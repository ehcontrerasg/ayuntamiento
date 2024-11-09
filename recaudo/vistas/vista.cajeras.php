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

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script language="JavaScript" type="text/javascript" src="../../js/funciones2.js"></script>
        <script language="JavaScript" type="text/javascript" src="../../js/xp_progress.js"></script>
        <script language="javascript" type="text/javascript" src="../../js/ajax2.js"></script>
        <script language="javascript" type="text/javascript" src="../../js/jquery.js"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
        <script type="text/javascript" src="../../js/facturaspendientes.js"></script>
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Administraci&oacute;n Cajeras</h3>
        <form name="cambiocajeras" action="vista.cajeras.php" method="post" >
            <div style="padding:0px; width:1120px; margin-left:0px" id="divflex">
                <table id="flex1" style="display:none">
                </table>
            </div>
            <script type="text/javascript">
                <!--
                $('.flexme1').flexigrid();
                $('.flexme2').flexigrid({height:'auto',striped:false});
                $("#flex1").flexigrid	(
                    {
                        url: './../datos/datos.cajeras.php',
                        dataType: 'json',
                        colModel : [
                            {display: 'Id Caja', name: 'ID_CAJA', width: 50, sortable: true, align: 'center'},
                            {display: 'Entidad', name: 'COD_ENTIDAD', width: 50, sortable: true, align: 'center'},
                            {display: 'Punto', name: 'COD_VIEJO', width: 50, sortable: true, align: 'center'},
                            {display: 'Estafeta', name: 'DESCRIPCION', width: 160, sortable: true, align: 'center'},
                            {display: 'Caja', name: 'NUM_CAJA', width: 50, sortable: true, align: 'center'},
                            {display: 'Area', name: 'AREA', width: 70, sortable: true, align: 'center'},
                            {display: 'Usuario', name: 'NOMBRE', width: 250, sortable: true, align: 'center'}
                        ],
                        buttons: [
                            {name:'Intercambiar Cajeras', bclass:'add', onpress: test},
                            {separator: true}
                        ],
                        searchitems : [
                            {display: 'Usuario', name: 'NOMBRE', isdefault: true}
                        ],

                        sortname: "EP.COD_ENTIDAD, PP.ID_PUNTO_PAGO, CP.NUM_CAJA",
                        sortorder: "ASC",
                        usepager: true,
                        title: 'Listado de Cajas y Cajeras',
                        useRp: true,
                        rp: 30,
                        page: 1,
                        showTableToggleBtn: true,
                        width: 1120,
                        height: 310
                    }
                );

                //-->
                /*function edita(id2) { // Traer la fila seleccionada
                 popup("vista.actualizarcliente.php?cod_cliente="+id2,800,600,'yes');
                 }

                 function elimina(id2) { // Traer la fila seleccionada
                 popup("vista.eliminarcliente.php?cod_cliente="+id2,400,300,'yes');
                 }*/

                function traslada_cajera() { // Traer la fila seleccionada
                    popup("vista.traslada_cajera.php",1200,610,'yes');
                }

                function test(com,grid){

                    if (com=='Intercambiar Cajeras')
                    {
                        //alert('Add New Item Action');
                        traslada_cajera();
                        $("#flex1").flexReload();

                    }
                }


                //-->
            </script>
        </form>
    </div>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

