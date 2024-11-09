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
    $codigo=$_SESSION['codigo'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>

        <script language="JavaScript" type="text/javascript" src="../../js/funciones2.js?1"></script>
        <script language="JavaScript" type="text/javascript" src="../../js/xp_progress.js?1"></script>
        <script language="javascript" type="text/javascript" src="../../js/ajax2.js?1"></script>
        <script language="javascript" type="text/javascript" src="../../js/jquery.js?1"></script>
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?1"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?1"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../css/pagosgen.css" rel="stylesheet" />
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Aplica Pagos En Transito</h3>
        <form name="aplicapago" action="vista.aplica_pagos.php" method="post" onsubmit="return rend();">
            <div style="padding:0px; width:1120px; margin-left:0px">
                <table style="display:block;">
                    <td style="width:100%; text-align:center; font-size:11px; color:#333333"><b>Fecha Aplicaci&oacute;n Pagos</b>
                        <input type="date" id="fecha_aplpago" value="<?php echo $fecha_aplpago?>" class="input" style="height:20px"/>
                        <input type="hidden" id="coduser" value="<?php echo $codigo?>" />
                    </td>
                </table>
                <table id="flex1" style="display:none">
                </table>
            </div>
            <script type="text/javascript">
                $('.flexme1').flexigrid();
                $('.flexme2').flexigrid({height:'auto',striped:false});
                $("#flex1").flexigrid	(
                    {
                        url: './../datos/datos.pagos_transito.php',
                        dataType: 'json',
                        colModel : [
                            {display: 'No', name: 'rnum', width: 50,  align: 'center'},
                            {display: 'Codigo / Factura', name: 'id_inmueble', width: 90, sortable: true, align: 'center'},
                            {display: 'Inmueble', name: 'id_inmueble', width: 70, sortable: true, align: 'center'},
                            {display: 'Cliente', name: 'id_cliente', width: 230, sortable: true, align: 'center'},
                            {display: 'Importe', name: 'valor_importe', width: 65, sortable: true, align: 'center'},
                            {display: 'Fecha<br>Pago', name: 'fecha_pago', width: 75, sortable: true, align: 'center'},
                            {display: 'Fecha<br>Registro', name: 'fecha_registro', width: 75, sortable: true, align: 'center'},
                            {display: 'Entidad', name: 'desc_entidad', width: 180, sortable: true, align: 'center'},
                            {display: 'Punto<br>Pago', name: 'id_ptopago', width: 180, sortable: true, align: 'center'},
                            {display: 'Caja', name: 'id_caja', width: 40, sortable: true, align: 'center'},
                            {display: 'Observacion', name: 'observacion', width: 200, sortable: true, align: 'center'}
                        ],

                        buttons: [
                            {name:'Aplicar Pago', bclass:'add', onpress: test},
                            {separator: true},
                            {name:'Anular Pago', bclass:'delete', onpress: test},
                            {separator: true}
                        ],
                        searchitems : [
                            {display: 'Inmueble', name: 'id_inmueble', isdefault: true},
                            {display: 'Cliente', name: 'id_cliente'},
                            {display: 'Fecha Pago', name: 'fecha_pago'},
                            {display: 'Fecha Registro', name: 'fecha_registro'},
                            {display: 'Entidad', name: 'id_entpago'},
                            {display: 'Punto Pago', name: 'id_ptopago'}
                        ],

                        sortname: "P.ID_PAGO",
                        sortorder: "DESC",
                        usepager: true,
                        title: 'Listado de Pagos en Transito',
                        useRp: true,
                        rp: 30,
                        page: 1,
                        showTableToggleBtn: true,
                        width: 1120,
                        height: 310
                    }
                );

                function test(com,grid){
                    if (com=='Anular Pago'){
                        if($('.trSelected',grid).length>0){
                            if($('.trSelected',grid).length == 1){
                                var leyenda = 'Desea anular el pago seleccionado?';
                            }
                            if($('.trSelected',grid).length > 1){
                                var leyenda = 'Desea anular los '+ $('.trSelected',grid).length +' pagos seleccionados?';
                            }
                            swal({
                                    title: "Advertencia!!",
                                    text: leyenda,
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Si!",
                                    cancelButtonText: "No!",
                                    showLoaderOnConfirm: true,
                                    closeOnConfirm: false,
                                    closeOnCancel: true
                                },
                                function(isConfirm){
                                    if (isConfirm) {
                                        var items = $('.trSelected',grid);
                                        var itemlist ='';
                                        for(i=0;i<items.length;i++){
                                            itemlist+= items[i].id.substr(3)+",";
                                        }
                                        $.ajax({
                                            type: "POST",
                                            dataType: "json",
                                            url: '../datos/datos.anulapagotrans.php',
                                            data: "items="+itemlist,
                                            success: function(data){
                                                if (data.total == 1){
                                                    swal({
                                                        title: "Pago Anulado",
                                                        text: "Se Anul\u00f3 el pago seleccionado",
                                                        type: "success",
                                                        showCancelButton: false,
                                                        confirmButtonColor: "#66CCFF",
                                                        confirmButtonText: "OK!",
                                                        cancelButtonText: "No!",
                                                        showLoaderOnConfirm: true,
                                                        closeOnConfirm: false,
                                                        closeOnCancel: false,
                                                        html: true
                                                    });
                                                }
                                                else if (data.total > 1){
                                                    swal({
                                                        title: "Pagos Anulados",
                                                        text: "Se Anularon los pagos seleccionados",
                                                        type: "success",
                                                        showCancelButton: false,
                                                        confirmButtonColor: "#66CCFF",
                                                        confirmButtonText: "OK!",
                                                        cancelButtonText: "No!",
                                                        showLoaderOnConfirm: true,
                                                        closeOnConfirm: false,
                                                        closeOnCancel: false,
                                                        html: true
                                                    });
                                                }
                                                else{
                                                    swal("Error", "No se anularon los pagos seleccionados", "error");
                                                }
                                                //alert("Has Anulado "+data.total+" pago(s)");
                                                $("#flex1").flexReload();
                                            }
                                        });
                                    }
                                    else {
                                        return false;
                                    }
                                });
                        }
                    }

                    if (com=='Aplicar Pago'){
                        if($('.trSelected',grid).length>0){
                            if($('.trSelected',grid).length == 1){
                                var leyenda = 'Desea aplicar el pago seleccionado?';
                            }
                            if($('.trSelected',grid).length > 1){
                                var leyenda = 'Desea aplicar los '+ $('.trSelected',grid).length +' pagos seleccionados?';
                            }
                            swal({
                                    title: "Mensaje!!",
                                    text: leyenda,
                                    type: "info",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Si!",
                                    cancelButtonText: "No!",
                                    showLoaderOnConfirm: true,
                                    closeOnConfirm: false,
                                    closeOnCancel: true
                                },
                                function(isConfirm){
                                    if (isConfirm) {
                                        var items = $('.trSelected',grid);
                                        var fec_aplpago = document.getElementById("fecha_aplpago").value;
                                        var coduser = document.getElementById("coduser").value;
                                        var itemlist ='';
                                        for(i=0;i<items.length;i++){
                                            itemlist+= items[i].id.substr(3)+",";
                                        }
                                        $.ajax({
                                            type: "POST",
                                            dataType: "json",
                                            url: '../datos/datos.aplicapagotrans2.php?fec_aplpago='+fec_aplpago+'&codigo='+coduser,
                                            data: "items="+itemlist,
                                            success: function(data){
                                                if (data.buenos > 1){
                                                    swal({
                                                        title: "Pagos Aplicados",
                                                        text: "Se Aplicaron "+data.buenos+" pagos de un total de "+data.total,
                                                        type: "success",
                                                        showCancelButton: false,
                                                        confirmButtonColor: "#66CCFF",
                                                        confirmButtonText: "OK!",
                                                        cancelButtonText: "No!",
                                                        showLoaderOnConfirm: true,
                                                        closeOnConfirm: false,
                                                        closeOnCancel: false,
                                                        html: true
                                                    });
                                                }
                                                else if (data.buenos == 1){
                                                    swal({
                                                        title: "Pago Aplicado",
                                                        text: "Se Aplic\u00f3 "+data.buenos+" pago de un total de "+data.total,
                                                        type: "success",
                                                        showCancelButton: false,
                                                        confirmButtonColor: "#66CCFF",
                                                        confirmButtonText: "OK!",
                                                        cancelButtonText: "No!",
                                                        showLoaderOnConfirm: true,
                                                        closeOnConfirm: false,
                                                        closeOnCancel: false,
                                                        html: true
                                                    });
                                                }
                                                else{
                                                    swal("Error", "No se aplicaron los pagos seleccionados", "error");
                                                }

                                                $("#flex1").flexReload();
                                            }
                                        });
                                    }
                                    else {
                                        return false;
                                    }
                                });
                        }
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

