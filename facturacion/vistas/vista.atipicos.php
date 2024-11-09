<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>


    <?php
    session_start();
    include '../clases/class.atipicos.php';
    include '../../destruye_sesion.php';
//$_SESSION['tiempo']=time();
//$_POST['codigo']=$_SESSION['codigo'];
    $inmueble=$_GET['cod_inmueble'];

    $sortname = $_POST['sortname'];
    $sortorder = $_POST['sortorder'];

    if (!$sortname) $sortname = "COD_SERVICIO";
    if (!$sortorder) $sortorder = "ASC";

    $sort = "ORDER BY $sortname $sortorder";

    $fname="COD_SERVICIO";
    $tname="SGC_TP_SERVICIOS";
    $where= "CALCULO IS NOT NULL";

    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $periodo = $_POST['periodo'];
    $sector = $_POST['sector'];
    $atipico = $_POST['atipico'];
//$fecini = $_POST['fecini'];
//$fecfin = $_POST['fecfin'];

//Conectamos con la base de datos
//$Cnn = new OracleConn(UserGeneral, PassGeneral);
//$link = $Cnn->link;
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!--<meta http-equiv="refresh" content="5; URL=rendimiento.php?periodo=<?php echo $periodo1;?>&ruta=<?php echo $ruta1;?>&proc=1" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../font-awesome/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">

        <script language="javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
        <style type="text/css">
            iframe{
                margin-top:-10px;
                border-color: #666666;
                border:1px solid #ccc;
                display:none;
                width: 560px;
                height: 400px;
                float:left;
            }
            .th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:0px solid #ccc;
                border-left:0px solid #ccc;
                border-right:1px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
                text-align:center;
            }
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
        </style>
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px; width:1120px;">
        <form name="rendimiento" action="vista.atipicos.php" method="post" onsubmit="return rend();">
            <?php
            if($proyecto == '' && $fecini != '' && $fecfin != ''){
                echo"
	<script type='text/javascript'>
	showDialog('Advertencia','Seleccione el Acueducto','warning',3);
	</script>";
            }
            ?>

            <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Consumos At&iacute;picos</h3>
            <div style="text-align:center; width:1120px; margin-left:0px">
                <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
                    <tr>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Acueducto:&nbsp;</td>
                        <td align="left" width="15%" bgcolor="#EBEBEB">
                            <select name="proyecto" class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();' required>
                                <option value="" selected>Seleccione acueducto...</option>
                                <?php
                                $l=new Atipicos();
                                $registros=$l->seleccionaProyecto($coduser);
                                while (oci_fetch($registros)) {
                                    $cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;
                                    $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;
                                    if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                    else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                        </td>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Periodo:&nbsp;</td>
                        <td align="left" width="15%" bgcolor="#EBEBEB">
                            <select id="periodo" name='periodo' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
                                <option value="" selected>Seleccione periodo...</option>
                                <?php
                                $l=new Atipicos();
                                $registros=$l->seleccionaPeriodo($proyecto);
                                while (oci_fetch($registros)) {
                                    $cod_periodo = oci_result($registros, 'PERIODO') ;
                                    if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                                    else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                        </td>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Zona:&nbsp;</td>
                        <td align="left" width="15%" bgcolor="#EBEBEB">
                            <select name='sector' id='sector' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
                                <option value="" selected>Seleccione zona...</option>
                                <?php
                                $l=new Atipicos();
                                $registros=$l->seleccionaSector($periodo, $proyecto);
                                while (oci_fetch($registros)) {
                                    $cod_sector = oci_result($registros, 'ID_ZONA') ;
                                    if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                                    else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                        </td>
                        <!--td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">At&iacute;pico:&nbsp;</td>
				<td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='atipico' id='atipico' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
						<option value="" selected>Seleccione atipico...</option>
						<?php
                        /* $l=new Atipicos();
                         $registros=$l->seleccionaAtipico();
                         while (oci_fetch($registros)) {
                             $cod_atipico = oci_result($registros, 'ID_ATIPICO') ;
                             $des_atipico = oci_result($registros, 'DESC_ATIPICO') ;
                             if($cod_atipico == $atipico) echo "<option value='$cod_atipico' selected>$des_atipico</option>\n";
                             else echo "<option value='$cod_atipico'>$des_atipico</option>\n";
                         }oci_free_statement($registros);*/
                        ?>
	                </select>
            	</td-->
                    </tr>
                </table>
            </div>

            <?php
            if (($proyecto != "" && $periodo != "" && $sector != "") ){
                /*$agno = substr($periodo,0,4);
                $mes = substr($periodo,4,2);
                if($mes == '01'){$mes = Enero;} if($mes == '02'){$mes = Febrero;} if($mes == '03'){$mes = Marzo;} if($mes == '04'){$mes = Abril;}
                if($mes == '05'){$mes = Mayo;} if($mes == '06'){$mes = Junio;} if($mes == '07'){$mes = Julio;} if($mes == '08'){$mes = Agosto;}
                if($mes == '09'){$mes = Septiembre;} if($mes == '10'){$mes = Octubre;} if($mes == '11'){$mes = Noviembre;} if($mes == '12'){$mes = Diciembre;}
                $nomrepo = 'Rendimiento Operarios Factura - ';*/
                if($atipico == 1) $desatipico = 'Todos';
                if($atipico == 2) $desatipico = 'Alto Consumo';
                if($atipico == 3) $desatipico = 'Bajo Consumo';
                if($atipico == 4) $desatipico = 'Consumo Cero';
                if($atipico == 5) $desatipico = 'Consumo Negativo';
                ?>
                <!--form action="../../funciones/ficheroExcel.php?agno=<?// echo $agno;?>&mes=<?// echo $mes;?>&nomrepo=<?//echo $nomrepo;?>" method="post" target="_blank"  id="FormularioExportacion">
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:100%">
		<div class="mDiv">
    		<div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
            	<img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="Excel"/>
                <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div>
    	</div>
	</div>
</form-->
                <br />
                <div style="margin-top:-10px">
                    <table id="flex1" style="display:block">
                        <thead>
                        <tr>
                            <th align="center" class="th" style="width:30px;">N&deg;</th>
                            <th align="center" class="th" style="width:80px">C&oacute;digo<br/>Inmueble</th>
                            <th align="center" class="th" style="width:80px">Lectura<br/>Anterior</th>
                            <th align="center" class="th" style="width:80px">Observaci&oacute;n<br/>Anterior</th>
                            <th align="center" class="th" style="width:80px">Lectura<br/>Actual</th>
                            <th align="center" class="th" style="width:80px">Observaci&oacute;n<br/>Actual</th>
                            <th align="center" class="th" style="width:80px">Lectura<br/>M&iacute;nima</th>
                            <th align="center" class="th" style="width:80px">Lectura<br/>M&aacute;xima</th>
                            <th align="center" class="th" style="width:80px">Consumo<br/>Mes</th>
                            <th align="center" class="th" style="width:80px">Consumo<br/>Promedio</th>
                            <th align="center" class="th" style="width:120px">Observaci&oacute;n<br/>Desviaci&oacute;n</th>
                            <th align="center" class="th" style="width:70px">Fotos<br/>Lectura</th>
                            <th align="center" class="th" style="width:70px">Hist&oacute;rico<br/>Lecturas</th>
                            <th align="center" class="th" style="width:70px">Instalacion<br/>Medidor</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            $cont = 0;
                            $l=new Atipicos();
                            $registros=$l->obtenerAtipicos($proyecto, $periodo, $sector);
                            while (oci_fetch($registros)) {
                            $cont++;
                            $cod_inmueble = oci_result($registros, 'COD_INMUEBLE');
                            $lec_minima = oci_result($registros, 'CONSUMO_MINIMO');
                            $lec_maxima = oci_result($registros, 'CONSUMO_MAXIMO');
                            $promedio = oci_result($registros, 'PROMEDIO');
                            $lec_actual = oci_result($registros, 'LECTURA_ACTUAL');
                            $obs_actual = oci_result($registros, 'OBSERVACION_ACTUAL');
                            $consumo = oci_result($registros, 'CONSUMO');
                            $fecIns = oci_result($registros, 'FECHA_INSTALACION');
                            $f=new Atipicos();
                            $valores=$f->obtenerLecAnterior($proyecto, $periodo, $sector, $cod_inmueble);
                            while (oci_fetch($valores)) {
                                $lec_anterior = oci_result($valores, 'LECTURA_ACTUAL');
                                $obs_anterior = oci_result($valores, 'OBSERVACION');
                            }oci_free_statement($valores);
                            if($lec_actual > $lec_maxima) $obs_desvio = 'Alto Consumo';
                            if($lec_actual < $lec_minima && $lec_actual > $lec_anterior) $obs_desvio = 'Bajo Consumo';
                            if($lec_actual == $lec_anterior) $obs_desvio = 'Consumo Cero';
                            if($lec_actual < $lec_minima && $lec_actual < $lec_anterior) $obs_desvio = 'Consumo Negativo';

                            if($consumo <= $promedio) $consumo = $promedio;

                            ?>
                            <td class="tda" style="width:30px; text-align:center"><b><?php echo $cont?></b></td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $cod_inmueble?></td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $lec_anterior?></td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $obs_anterior?></td>
                            <td class="tda" style="width:80px; text-align:center">
                                <div style="padding:0px" align="center" id="content_lec_<?php echo $cod_inmueble?>">
                                    <input class="input" type="text" id="lectura_actual <?php echo $cod_inmueble.' '.$periodo?>" name="lectura_actual <?php echo $cod_inmueble.' '.$periodo?>" value="<?php echo $lec_actual?>" size="3"/>
                                </div>
                            </td>
                            <td class="tda" style="width:80px; text-align:center">
                                <div style="padding:0px" align="center" id="content_observa_<?php echo $cod_inmueble?>">
                                    <input class="input" type="text" id="observacion_actual <?php echo $cod_inmueble.' '.$periodo?>" name="observacion_actual <?php echo $cod_inmueble.' '.$periodo?>" value="<?php echo $obs_actual?>" size="3"/>
                                </div>
                            </td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $lec_minima?></td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $lec_maxima?></td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $consumo?>
                                <!--div style="padding:0px" align="center" id="content_consumo_<?php echo $cod_inmueble?>">
						<input class="input" type="text" id="consumo <?php// echo $cod_inmueble.' '.$periodo?>" name="consumo <?php// echo $cod_inmueble.' '.$periodo?>" value="<?php// echo $consumo?>" size="3"/>
					</div-->
                            </td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $promedio?></td>
                            <td class="tda" style="width:120px; text-align:center"><?php echo $obs_desvio?></td>
                            <?php
                            $h=new Atipicos();
                            $total_fotos=$h->existefotolec($cod_inmueble,$periodo);
                            $i=new Atipicos();
                            $total_fotos=$i->existefotolec($cod_inmueble,$periodo);

                            if($total_fotos == true){
                                ?>
                                <td class="tda" style="width:70px; text-align:center"><a href="JAVASCRIPT:fotosperiodo(<?php echo $cod_inmueble?>,<?php echo $periodo?>);">
                                        <i class="fa fa-camera fa-lg" style="color:#000000"></i></a></td>
                                <?php

                            }
                            else{
                                ?>
                                <td class="tda" style="width:70px; text-align:center"></td>
                                <?php
                            }
                            ?>
                            <td class="tda" style="width:70px; text-align:center"><a href="JAVASCRIPT:hislec(<?php echo $cod_inmueble?>);">
                                    <i class="fa fa-book fa-lg" style="color:#000000"></i></a></td>
                            <td class="tda" style="width:80px; text-align:center"><?php echo $fecIns?></td>
                        </tr>
                        <?php
                        unset($obs_desvio);
                        }oci_free_statement($registros);
                        ?>
                        </tbody>
                    </table>
                </div>

                <script type="text/javascript">
                    <!--
                    $('.flexme1').flexigrid();
                    $('.flexme2').flexigrid({height:'auto',striped:false});
                    $("#flex1").flexigrid	({

                            title: 'Listado Consumos At&iacute;picos - Periodo <?php echo $periodo?> - Zona <?php echo $sector?> ',
                            useRp: true,
                            rp: 1000,
                            page: 1,
                            //showTableToggleBtn: true,
                            width: 1120,
                            height: 350
                        }
                    );
                    //FUNCION PARA ABRIR UN POPUP
                    var popped = null;

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
                                url: "../datos/datos.modifica.php",
                                data: dataString,

                                success: function(data) {
                                    field.val(data);
                                    $('#imagen_'+parent).remove();

                                    $('#'+parent).append('<img id="im1'+parent+'" src="ok.png"/>').fadeIn('slow');
                                }
                            });
                        });
                    });

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

                    function fotosperiodo(id2,periodo) { // Traer la fila seleccionada
                        popup("vista.fotos_lec.php?cod_sistema="+id2+"&periodo="+periodo,750,600,'yes','pop1');
                    }
                    function hislec(inm) { // Traer la fila seleccionada
                        popup("vista.hist_lec.php?inmueble="+inm,500,310,'yes','pop3');
                    }
                    //-->
                </script>

                <iframe id="detallerutaopefac" style="border-left:0px solid #ccc; border-right:0px solid #ccc"></iframe>
                <iframe id="rutageofac" style="border-left:0px solid #ccc; border-left:0px solid #ccc"></iframe>
                <?php
            }
            ?>
        </form>
    </div>
    </body>
    </html>
    <script type="text/javascript" language="javascript">
        function recarga() {
            document.rendimiento.submit();
        }
    </script>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
