<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <?php
    session_start();
    include '../clases/class.admintarifas.php';
    include '../clases/class.reportes_lectura.php';
    include '../../destruye_sesion.php';

    $inmueble=$_GET['cod_inmueble'];
    $sortname = $_POST['sortname'];
    $sortorder = $_POST['sortorder'];
    $proyecto = $_POST['proyecto'];
    $uso = $_POST['uso'];
    $concepto = $_POST['concepto'];

    if (!$sortname) $sortname = "CONSEC_TARIFA";
    if (!$sortorder) $sortorder = "ASC";
    $sort = "ORDER BY $sortname $sortorder";
    $fname="CONSEC_TARIFA";
    $tname="SGC_TP_TARIFAS";
    $where= "VISIBLE IS NOT NULL AND COD_PROYECTO = '$proyecto' AND COD_USO = '$uso' AND COD_SERVICIO = $concepto";
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
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
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
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
        </style>
    </head>
    <body style="margin-top:-25px" >
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Administrar Tarifas</h3>
        <div style="text-align:center; width:1120px;">
            <form name="FMTarifas" action="vista.admintarifas.php" method="post">
                <div class="flexigrid" style="width:2000px">
                    <div class="mDiv">
                        <div align="left"><b>Administraci&oacute;n de Tarifas</b></div>
                    </div>
                </div>
                <div class="flexigrid" style="width:2000px">
                    <div class="mDiv">
                        <div align="left">
                            Acueducto:
                            <select name="proyecto" required><option></option>
                                <?php
                                $l=new Reportes();
                                $registros=$l->seleccionaAcueducto();
                                while (oci_fetch($registros)) {
                                    $cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;
                                    $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;
                                    if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                    else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                            Uso:
                            <select name="uso" required><option></option>
                                <?php
                                $l=new Reportes();
                                $registros=$l->obtieneUsos();
                                while (oci_fetch($registros)) {
                                    $cod_uso = oci_result($registros, 'ID_USO') ;
                                    $des_uso = oci_result($registros, 'DESC_USO') ;
                                    if($cod_uso == $uso) echo "<option value='$cod_uso' selected>$des_uso</option>\n";
                                    else echo "<option value='$cod_uso'>$des_uso</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                            Concepto:
                            <select name="concepto" required><option></option>
                                <?php
                                $l=new Reportes();
                                $registros=$l->obtieneConceptos();
                                while (oci_fetch($registros)) {
                                    $cod_conc = oci_result($registros, 'COD_SERVICIO') ;
                                    $des_conc = oci_result($registros, 'DESC_SERVICIO') ;
                                    if($cod_conc == $concepto) echo "<option value='$cod_conc' selected>$des_conc</option>\n";
                                    else echo "<option value='$cod_conc'>$des_conc</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                            <input type="submit" name="enviar" value="Consultar" />
                        </div>
                    </div>
                </div>
            </form>
            <!--div class="flexigrid" style="width:2000px">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">Exportar&nbsp;&nbsp;
                        <a href="vista.reporte_excel_tarifas.php">
                            <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                        <a href="vista.reporte_word_tarifas.php">
                            <img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
                        <a href="vista.reporte_pdf_tarifas.php">
                            <img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
                    </div>
                </div>
            </div></div></div-->
            <?php
            if(isset($_REQUEST["enviar"])){
                ?>
                <form id="ficha" method="post">
                    <div>
                        <table id="flex1" style="display:block; width:2500">
                            <thead>
                            <tr>
                                <th align="center" class="th" style="width:30px">N&deg;</th>
                                <th align="center" class="th" style="width:30px">AC</th>
                                <th align="center" class="th" style="width:324px">Descripción</th>
                                <th align="center" class="th" style="width:70px">Código<br/>Servicio</th>
                                <th align="center" class="th" style="width:70px">Código<br/>Uso</th>
                                <th align="center" class="th" style="width:70px">Código<br/>Tarifa</th>
                                <th align="center" class="th" style="width:70px">Consumo<br/>M&iacute;nimo</th>
                                <th align="center" class="th" style="width:80px">Limite Minimo<br/>Rango 1</th>
                                <th align="center" class="th" style="width:80px">Limite Maximo<br/>Rango 1</th>
                                <th align="center" class="th" style="width:80px">Valor Metro<br/>Rango 1</th>
                                <th align="center" class="th" style="width:80px">Limite Minimo<br/>Rango 2</th>
                                <th align="center" class="th" style="width:80px">Limite Maximo<br/>Rango 2</th>
                                <th align="center" class="th" style="width:80px">Valor Metro<br/>Rango 2</th>
                                <th align="center" class="th" style="width:80px">Limite Minimo<br/>Rango 3</th>
                                <th align="center" class="th" style="width:80px">Limite Maximo<br/>Rango 3</th>
                                <th align="center" class="th" style="width:80px">Valor Metro<br/>Rango 3</th>
                                <th align="center" class="th" style="width:80px">Limite Minimo<br/>Rango 4</th>
                                <th align="center" class="th" style="width:80px">Limite Maximo<br/>Rango 4</th>
                                <th align="center" class="th" style="width:80px">Valor Metro<br/>Rango 4</th>
                                <th align="center" class="th" style="width:80px">Limite Minimo<br/>Rango 5</th>
                                <th align="center" class="th" style="width:80px">Limite Maximo<br/>Rango 5</th>
                                <th align="center" class="th" style="width:80px">Valor Metro<br/>Rango 5</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php
                                $l=new tarifas();
                                $registros=$l->obtenertarifas($where,$sort,$start,$end, $tname);
                                while (oci_fetch($registros)) {
                                $id_tarifa = oci_result($registros, 'CONSEC_TARIFA');
                                $acueducto = oci_result($registros, 'COD_PROYECTO');
                                $desc_tarifa = oci_result($registros, 'DESC_TARIFA');
                                $cod_servicio = oci_result($registros, 'COD_SERVICIO');
                                $uso = oci_result($registros, 'COD_USO');
                                $cod_tarifa = oci_result($registros, 'CODIGO_TARIFA');
                                $consumo = oci_result($registros, 'CONSUMO_MIN');
                                $lim_min_0 = oci_result($registros, 'LMIN_0');
                                $lim_max_0 = oci_result($registros, 'LMAX_0');
                                $valor_0 = oci_result($registros, 'VALOR_0');
                                $lim_min_1 = oci_result($registros, 'LMIN_1');
                                $lim_max_1 = oci_result($registros, 'LMAX_1');
                                $valor_1 = oci_result($registros, 'VALOR_1');
                                $lim_min_2 = oci_result($registros, 'LMIN_2');
                                $lim_max_2 = oci_result($registros, 'LMAX_2');
                                $valor_2 = oci_result($registros, 'VALOR_2');
                                $lim_min_3 = oci_result($registros, 'LMIN_3');
                                $lim_max_3 = oci_result($registros, 'LMAX_3');
                                $valor_3 = oci_result($registros, 'VALOR_3');
                                $lim_min_4 = oci_result($registros, 'LMIN_4');
                                $lim_max_4 = oci_result($registros, 'LMAX_4');
                                $valor_4 = oci_result($registros, 'VALOR_4');
                                $lim_min_5 = oci_result($registros, 'LMIN_5');
                                $lim_max_5 = oci_result($registros, 'LMAX_5');
                                $valor_5 = oci_result($registros, 'VALOR_5');
                                ?>
                                <td class="tda" style="width:30px; text-align:center"><?php echo $id_tarifa?></td>
                                <td class="tda" style="width:30px; text-align:center"><?php echo $acueducto?></td>
                                <td class="tda" style="width:324px; text-align:center">
                                    <div style="padding:0px" align="center" id="content_desc_tarifa_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="desc_tarifa <?php echo $id_tarifa?>" name="desc_tarifa <?php echo $id_tarifa?>" value="<?php echo $desc_tarifa?>" size="45"/>
                                    </div>
                                </td>
                                <td class="tda" style="width:70px; text-align:center">
                                    <div style="padding:0px" align="center" id="content_cod_servicio_<?php echo $id_tarifa?>">
                                        <input class="input" type="text" id="cod_servicio <?php echo $id_tarifa?>" name="cod_servicio <?php echo $id_tarifa?>" value="<?php echo $cod_servicio?>" size="2"/>
                                    </div>
                                </td>
                                <td class="tda" style="width:70px; text-align:center">
                                    <div style="padding:0px" align="center" id="content_cod_uso_<?php echo $id_tarifa?>">
                                        <input class="input" type="text" id="cod_uso <?php echo $id_tarifa?>" name="cod_uso <?php echo $id_tarifa?>" value="<?php echo $uso?>" size="2"/>
                                    </div>
                                </td>
                                <td class="tda" style="width:70px; text-align:center">
                                    <div style="padding:0px" align="center" id="content_codigo_tarifa_<?php echo $id_tarifa?>">
                                        <input class="input" type="text" id="codigo_tarifa <?php echo $id_tarifa?>" name="codigo_tarifa <?php echo $id_tarifa?>" value="<?php echo $cod_tarifa?>" size="2"/>
                                    </div>
                                </td>
                                <td class="tda" style="width:70px;">
                                    <div style="padding:0px" align="center" id="content_consumo_min_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="consumo_min <?php echo $id_tarifa?>" name="consumo_min <?php echo $id_tarifa?>" value="<?php echo $consumo?>"size="2"  />
                                    </div>
                                </td>

                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_limite_min_1_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_min_1 <?php echo $id_tarifa?>" name="limite_min_1 <?php echo $id_tarifa?>" value="<?php echo $lim_min_0?>"size="2" />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_limite_max_1_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_max_1 <?php echo $id_tarifa?>" name="limite_max_1 <?php echo $id_tarifa?>" value="<?php echo $lim_max_0?>"size="2"/>
                                    </div>
                                </td>
                                <td  class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_valor_1_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="valor_1 <?php echo $id_tarifa?>" name="valor_1 <?php echo $id_tarifa?>" value="<?php echo $valor_0?>"size="2"/>
                                    </div>
                                </td>


                                <td class="tda" style="width:80px;">
                                    <div style="padding:0px" align="center" id="content_limite_min_2_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_min_2 <?php echo $id_tarifa?>" name="limite_min_2 <?php echo $id_tarifa?>" value="<?php echo $lim_min_1?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px;">
                                    <div style="padding:0px" align="center" id="content_limite_max_2_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_max_2 <?php echo $id_tarifa?>" name="limite_max_2 <?php echo $id_tarifa?>" value="<?php echo $lim_max_1?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px;">
                                    <div style="padding:0px" align="center" id="content_valor_2_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="valor_2 <?php echo $id_tarifa?>" name="valor_2 <?php echo $id_tarifa?>" value="<?php echo $valor_1?>"size="2"  />
                                    </div>
                                </td>


                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_limite_min_3_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_min_3 <?php echo $id_tarifa?>" name="limite_min_3 <?php echo $id_tarifa?>" value="<?php echo $lim_min_2?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_limite_max_3_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_max_3 <?php echo $id_tarifa?>" name="limite_max_3 <?php echo $id_tarifa?>" value="<?php echo $lim_max_2?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_valor_3_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="valor_3 <?php echo $id_tarifa?>" name="valor_3 <?php echo $id_tarifa?>" value="<?php echo $valor_2?>"size="2"  />
                                    </div>
                                </td>


                                <td class="tda" style="width:80px;">
                                    <div style="padding:0px" align="center" id="content_limite_min_4_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_min_4 <?php echo $id_tarifa?>" name="limite_min_4 <?php echo $id_tarifa?>" value="<?php echo $lim_min_3?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px;">
                                    <div style="padding:0px" align="center" id="content_limite_max_4_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_max_4 <?php echo $id_tarifa?>" name="limite_max_4 <?php echo $id_tarifa?>" value="<?php echo $lim_max_3?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px;">
                                    <div style="padding:0px" align="center" id="content_valor_4_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="valor_4 <?php echo $id_tarifa?>" name="valor_4 <?php echo $id_tarifa?>" value="<?php echo $valor_3?>"size="2"  />
                                    </div>
                                </td>


                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_limite_min_5_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_min_5 <?php echo $id_tarifa?>" name="limite_min_5 <?php echo $id_tarifa?>" value="<?php echo $lim_min_4?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_limite_max_5_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="limite_max_5 <?php echo $id_tarifa?>" name="limite_max_5 <?php echo $id_tarifa?>" value="<?php echo $lim_max_4?>"size="2"  />
                                    </div>
                                </td>
                                <td class="tda" style="width:80px; background-color:#FFFFE6">
                                    <div style="padding:0px" align="center" id="content_valor_5_<?php echo $id_tarifa?>">
                                        <input class="input" type="text"  id="valor_5 <?php echo $id_tarifa?>" name="valor_5 <?php echo $id_tarifa?>" value="<?php echo $valor_4?>"size="2"  />
                                    </div>
                                </td>
                            </tr>
                            <?php
                            }oci_free_statement ($registros);
                            ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php
            }
            ?>
            <script type="text/javascript">
                <!--


                // $('.flexme1').flexigrid();
                //$('.flexme2').flexigrid({height:'auto',striped:false});
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
                        //{name:'Agregar', bclass:'add', onpress: test},
                        //{separator: true}
                        //{name:'Eliminar', bclass:'delete', onpress: test},
                        //{separator: true}
                        //{name:'Guardar', bclass:'save', onpress: test},
                        //{separator: true}
                    ],
                    /* searchitems : [
                         {display: 'C\xF3digo', name: 'COD_SERVICIO', isdefault: true},
                         {display: 'Tipo', name: 'DIFERIDO'},
                         {display: 'Mora', name: 'MORA'}
                     ],*/
                    //sortname: "COD_SERVICIO",
                    //sortorder: "ASC",
                    //usepager: true,
                    title: '<?php echo '<div align="left" style="margin-left:-7px; height:20px; margin-top:-4px">Listado de Tarifas</div>';?>',
                    //useRp: true,
                    //rp: 100,
                    //page: 1,
                    //showTableToggleBtn: true,
                    width: 2000,
                    height: 350
                });


                $(document).ready(function(){
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
                            url: "../datos/datos.admintarifas.php",
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
                    popup("vista.agregatarifa.php",770,610,'yes');
                }

                /*function guarda() { // Traer la fila seleccionada
                    popup("vista.guardaconcepto.php?num_cuotas=<?php//echo $num_cuotas?>",770,610,'yes');
    }*/

                function elimina(id) { // Traer la fila seleccionada
                    popup("vista.eliminatarifa.php?id_concepto="+id,800,600,'yes');
                }

                function test(com,grid)
                {

                    if (com=='Agregar')
                    {
                        //alert('Add New Item Action');
                        agrega();

                    }

                    /*  if (com=='Eliminar')
                      {
                          if($('.trSelected',grid).length>0){

                              var items = $('.trSelected',grid);
                              var itemlis ='';
                              for(i=0;i<items.length;i++){
                                  itemlis+= items[i].id.substr(3)+",";
                              }
                              itemlis=itemlis.substr(0,itemlis.length-1);
                              return elimina(itemlis);


                          } else {
                              return false;
                          }
                      }*/

                }
                //-->
            </script>
    </body>
    </html>



<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

