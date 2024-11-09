<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.admin_pagos.php';
    include '../../destruye_sesion.php';

    $cod_inmueble=$_POST['cod_inmueble'];
    $sortname = $_POST['sortname'];
    $sortorder = $_POST['sortorder'];

    if (!$sortname) $sortname = "COD_PARAMETRO";
    if (!$sortorder) $sortorder = "DESC";
    $sort = "ORDER BY $sortname $sortorder";
    $fname="COD_PARAMETRO";
    $tname="SGC_TP_PARAMETROS";
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <script type="text/JavaScript" src="../js/bootstrap.min.js"></script>
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
        <style type="text/css">
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
                height:16px;
            }
            .table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            .th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:1px solid #ccc;
                border-left:0px solid #ccc;
                border-bottom:0px solid #ccc;
                border-top:0px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            .tda{

                height:24px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            .font{
                float:right;
            }
            .select{
                border:0px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .btn-info{
                color:#fff;
                background-color:#5bc0de;
                border-color:#46b8da;
            }
            .btn{
                display:inline-block;
                padding:6px 12px;
                margin-bottom:0;
                font-size:14px;
                font-weight:400;
                line-height:1.42857143;
                text-align:center;
                white-space:nowrap;
                vertical-align:middle;
                cursor:pointer;
                -webkit-user-select:none;
                -moz-user-select:none;
                -ms-user-select:none;
                user-select:none;
                background-image:none;
                border:1px solid transparent;
                border-radius:4px
            }

            #listaCajas{
                max-height: 452px;
                overflow-y: scroll;
            }


        </style>
        <!--Lógica de javascript-->
        <script type="javascript" src="../js/EditaFormaPago.js?2"></script>
    </head>
    <body style="margin-top:-25px" >
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Correcci&oacute;n de Pagos</h3>
        <div style="text-align:center; width:1120px;">
            <form name="pagos" action="vista.adminpagos.php" method="post" >
                <div class="flexigrid" style="width:1120px">
                    <div class="mDiv">
                        <div align="left">Correcci&oacute;n de pagos <font class="font">C&oacute;digo Inmueble:&nbsp;<input class="input" type="number" min="0" value="<?php echo $cod_inmueble ?>"  required name="cod_inmueble"  style="width: 150px" placeholder="Ingrese el Inmueble"/>&nbsp;<i class="fa fa-search"></i></font></div>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_REQUEST["obslec"])){
                ?>
                <script type="text/javascript">
                    window.open('vista.listacajas.php','Listado de cajas','width=1255, height=600, top=30px, left=40px');
                </script>
                <?php
            }
            ?>
           <!-- <form id="ficha" method="post">-->
                <div>
                    <table id="flex1" style="display:block;">
                        <thead>
                        <tr>
                            <th align="center" class="th" style="width:80px">N&deg; Pago</th>
                            <th align="center" class="th" style="width:150px">Entidad - Punto - Caja</th>
                            <th align="center" class="th" style="width:180px">Fecha Pago</th>
                            <th align="center" class="th" style="width:180px">Fecha Registro</th>
                            <th align="center" class="th" style="width:150px">Nuevo Id Caja&nbsp;&nbsp;</th>
                            <th align="center" class="th" style="width:300px">Observacion</th>
                            <th align="center" class="th" style="width:300px">Editar forma de pago</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr >
                            <?php
                            //$cont = 0;
                            $l=new AdminstraPagos();
                            $registros=$l->obtienePagosInmueble($cod_inmueble);
                            while (oci_fetch($registros)) {
                            //$cont++;
                            $id_pago = oci_result($registros, 'ID_PAGO');
                            $fecha_pago = oci_result($registros, 'FECHA_PAGO');
                            $fecha_registro = oci_result($registros, 'FECHA_REGISTRO');
                            $entidad= oci_result($registros, 'ENTIDAD_VIEJO');
                            $punto = oci_result($registros, 'PUNTO_VIEJO');
                            $caja = oci_result($registros, 'NUM_CAJA');
                            $id_caja = oci_result($registros, 'ID_CAJA');
                            $form_pago = oci_result($registros, 'ID_FORM_PAGO');
                            $obs_pago = oci_result($registros, 'OBSERVACION');
                            $tipo = oci_result($registros, 'TIPO');
                            $rec = oci_result($registros, 'REC');
                            ?>
                            <td class="tda" style="width:80px; text-align:center"><input type="hidden" value=<?php echo $rec?>></td>
                            <td class="tda" style="width:80px; text-align:center"><b><?php echo $id_pago?></b></td>
                            <td class="tda" style="width:150px; text-align:center">
                                <label class="input" style="border:none"><?php echo $entidad.' - '.$punto.' - '.$caja?></label>
                            </td>
                            <td class="tda" style="width:180px; text-align:center">
                                <div style="padding:0px" align="center" id="content_fecha_pago<?php echo $id_pago?>">
                                    <input class="input" type="date"  id="fecha_pago <?php echo $id_pago.' '.$tipo?>?>" name="fecha_pago <?php echo $id_pago.' '.$tipo?>" value="<?php echo $fecha_pago?>"/>
                                </div>
                            </td>
                            <td class="tda" style="width:180px; text-align:center">
                                <div style="padding:0px" align="center" id="content_fecha_registro<?php echo $id_pago?>">
                                    <input class="input" type="date"  id="fecha_registro <?php echo $id_pago.' '.$tipo?>" name="fecha_registro <?php echo $id_pago.' '.$tipo?>" value="<?php echo $fecha_registro?>"/
                                    >
                                </div>
                            </td>
                            <td class="tda" style="width:150px; text-align:center">
                                <div style="padding:0px" align="center" id="content_id_caja<?php echo $id_pago?>">
                                    <input class="input" type="text"  id="id_caja <?php echo $id_pago.' '.$tipo?>" name="id_caja <?php echo $id_pago.' '.$tipo?>" value="<?php echo $id_caja?>" size="3"/>
                                </div>
                            </td>
                            <td class="tda" style="width:400px; text-align:center">
                                <div style="padding:0px" align="center" id="content_observacion<?php echo $obs_pago?>">
                                    <input style="width:290px" class="input" type="text"  id="observacion <?php echo $id_pago.' '.$tipo?>" name="observacion <?php echo $id_pago.' '.$tipo?>" value="<?php echo $obs_pago?>" />
                                </div>
                            </td>
                            <td class="tda" style="width:300px; text-align:center">
                                <div style="padding:0px" align="center" id="content_editar<?php echo $id_pago?>">
                                    <button id="<?php echo $id_pago?>" class="btn btn-primary">Editar</button>
                                </div>
                            </td>

                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>

            <!--Modal de lista de cajas-->
            <!-- Button trigger modal -->
       <!--     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                Launch demo modal
            </button>
-->
            <!-- Modal -->
            <div class="modal fade" id="modalListaCajas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div id="content" style="padding:0px; width:100%; margin-left:0px">
                                <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:100%; align="center">Lista de Cajas</h3>
                            </div>
                        </div>
                        <div class="modal-body" style="margin-top:-25px;" >
                            <div style="overflow: auto" id="listaCajas">
                                <table id="flex1" style="display:block;">
                                    <thead>
                                    <tr>
                                        <th align="center" class="th" style="width:112px; color:#000000">Id Caja</th>
                                        <th align="center" class="th" style="width:400px; color:#000000">Entidad</th>
                                        <th align="center" class="th" style="width:400px; color:#000000">Punto</th>
                                        <th align="center" class="th" style="width:112px; color:#000000">Caja</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr >
                                        <?php
                                        //$cont = 0;
                                        $l=new AdminstraPagos();
                                        $registros=$l->listadoCajas();
                                        while (oci_fetch($registros)) {
                                        //$cont++;
                                        $id_caja = oci_result($registros, 'ID_CAJA');
                                        $id_entidad = oci_result($registros, 'ID_ENTIDAD');
                                        $desc_entidad = oci_result($registros, 'DESC_ENTIDAD');
                                        $id_punto= oci_result($registros, 'COD_VIEJO');
                                        $desc_punto = oci_result($registros, 'DESC_PUNTO');
                                        $caja = oci_result($registros, 'DESC_CAJA');
                                        ?>
                                        <td class="tda" style="width:112px; text-align:center; color:#000000"><b><?php echo $id_caja?></b></td>
                                        <td class="tda" style="width:400px; text-align:center; color:#000000"><?php echo $id_entidad.' - '.$desc_entidad?></td>
                                        <td class="tda" style="width:400px; text-align:center; color:#000000"><?php echo $id_punto.' - '.$desc_punto?></td>
                                        <td class="tda" style="width:112px; text-align:center; color:#000000"><?php echo $caja?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
          <!--  </form >-->
            <script type="text/javascript">

                <!--
                function test(/*com,grid*/ e)
                {

                    // $(".add").attr({data-toggle:"modal",data-target:"#exampleModalCenter"});
                    $(".add").attr('data-toggle','modal')
                             .attr('data-target','#modalListaCajas');
                   // $(".add").click();
                    /*if (com=='Ver Listado de Cajas')
                    {*/
                    //alert('Add New Item Action');
                    // agrega();

                    //alert("click");
                    //$('#modalListaCajas').modal('show');
                    // }



                }
                $('.flexme1').flexigrid();
                $('.flexme2').flexigrid({height:'auto',striped:false});
                $("#flex1").flexigrid	({
                    // url: './../datos/datos.adminconceptos2.php',
                    // dataType: 'json',
                    /*colModel : [
                     {display: 'No', name: 'rnum', width:15,  align: 'center'},
                     {display: 'C\xF3digo', name: 'COD_SERVICIO', width: 60, sortable: true, align: 'center'},
                     {display: 'Descripci\xF3n', name: 'DESC_SERVICIO', width: 180, sortable: true, align: 'center'},
                     {display: 'Orden', name: 'ORDEN', width: 40, sortable: true, align: 'center'},
                     {display: 'Tipo', name: 'DIFERIDO', width: 50, sortable: true, align: 'center'},
                     {display: 'Mora', name: 'MORA', width: 40, sortable: true, align: 'center'},
                     {display: 'Cuotas', name: 'LIMITE_MAX_CUOTAS', width: 50, sortable: true, align: 'center'}
                     ],*/
                    buttons: [
                        {name:'Ver Listado de Cajas', bclass:'add',onpress:test},
                        {separator: true}
                        //{name:'Eliminar', bclass:'delete', onpress: test},
                        //{separator: true}
                        //{name:'Guardar', bclass:'save', onpress: test},
                        //{separator: true}
                    ],
                    /*  searchitems : [
                     {display: 'C\xF3digo', name: 'COD_SERVICIO', isdefault: true},
                     {display: 'Tipo', name: 'DIFERIDO'},
                     {display: 'Mora', name: 'MORA'}
                     ],*/
                    //sortname: "COD_SERVICIO",
                    //sortorder: "ASC",
                    //usepager: true,
                    title: '<?php echo '<div align="left" style="margin-left:-7px; height:20px; margin-top:-4px">Listado de Pagos Inmueble '.$cod_inmueble.' </div>';?>',
                    //useRp: true,
                    //rp: 100,
                    //page: 1,
                    //showTableToggleBtn: true,
                    //width: 1046,
                    height: 290
                });


                $(document).ready(function(){


                    $("td button").click(function(){
                        var hdn = $(this).parents('tr').find('input[type="hidden"]').val();
                        window.open('vista.edita_forma_pago.php?idPago='+this.id+'&rec='+hdn,'pagename','resizable,height=505,width=1055');

                    })
                    $('input').blur(function(){
                        var field = $(this);

                        var parent = field.parent().attr('id');
                        $('#im1'+parent).remove();
                        $('#imagen_'+parent).remove();
                        if($('#'+parent).find(".ok").length){
                            $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
                        }else{
                            $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
                        }

                        var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name');
                        $.ajax({
                            type: "POST",
                            url: "../datos/datos.adminpagos.php",
                            data: dataString,

                            success: function(data) {
                                field.val(data);
                                $('#imagen_'+parent).remove();

                                $('#'+parent).append('<img id="im1'+parent+'" src="ok.png"/>').fadeIn('slow');
                            }
                        });
                    });


                });

                //FUNCION PARA ABRIR UN POPUP
                var popped = null;
                function popup(uri, awid, ahei, scrollbar) {
                    var params;
                    if (uri != "") {
                        if (popped && !popped.closed) {
                            popped.location.href = uri;
                            popped.focus();
                        }
                        else {
                            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                            popped = window.open(uri, "popup", params);
                        }
                    }
                }
                //-->

                function agrega() { // Traer la fila seleccionada
                    //popup("vista.lista_cajas.php",500,500,'yes');
                }

                function elimina(id) { // Traer la fila seleccionada
                    popup("vista.eliminadiferido.php?id_concepto="+id,800,600,'yes');
                }


                //-->





            </script>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

