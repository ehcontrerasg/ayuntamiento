<?
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
        <h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px" align="center">Medios de Recepci&oacute;n</h3>
        <form name="dianolabora" action="vista.dias_no_labora.php" method="post" >
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
                        url: './../datos/datos.mediorecep.php',
                        dataType: 'json',
                        colModel : [
                            {display: 'No', name: 'rnum', width: 40,  align: 'center'},
                            {display: 'C&oacute;digo', name: 'agno', width: 50, sortable: true, align: 'center'},
                            {display: 'Descripci&oacute;n', name: 'mes', width: 100, sortable: true, align: 'center'}
                        ],

                        buttons: [
                            {name:'Agregar Medio', bclass:'add', onpress: test},
                            {separator: true},
                            {name:'Eliminar Medio', bclass:'delete', onpress: test},
                            {separator: true}
                        ],

                        sortname: "ID_MEDIO_REC",
                        sortorder: "ASC",
                        usepager: true,
                        title: 'Listado de Medios de Recepci&oacute;n',
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

                function agrega_medio() { // Traer la fila seleccionada
                    popup("vista.agrega_medio.php",770,610,'yes');
                }

                function test(com,grid){
                    if (com=='Eliminar Medio'){
                        if($('.trSelected',grid).length>0){
                            if($('.trSelected',grid).length == 1){
                                var leyenda = 'Desea eliminar ';
                                var leyenda1 = ' dato seleccionado?'
                            }
                            if($('.trSelected',grid).length > 1){
                                var leyenda = 'Desea eliminar estos ';
                                var leyenda1 = ' datos seleccionados?'
                            }
                            if(confirm(leyenda + $('.trSelected',grid).length + leyenda1)){
                                var items = $('.trSelected',grid);
                                var itemlist ='';
                                for(i=0;i<items.length;i++){
                                    itemlist+= items[i].id.substr(3)+",";
                                }
                                $.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: '../datos/datos.elimina_medio.php',
                                    data: "items="+itemlist,
                                    success: function(data){
                                        alert("Has Eliminado "+data.total+" dia(s)");
                                        $("#flex1").flexReload();
                                    }
                                });
                            }
                        }
                        else {
                            return false;
                        }
                    }

                    if (com=='Agregar Medio')
                    {
                        //alert('Add New Item Action');
                        agrega_medio();
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

