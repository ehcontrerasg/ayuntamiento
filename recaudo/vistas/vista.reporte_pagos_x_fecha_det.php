<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.reportes_pagos.php';
    include '../../destruye_sesion_cierra.php';

    $coduser = $_SESSION['codigo'];
    $codsecure = $_GET['codsecure'];
    $proyecto = $_POST['proyecto'];
    $entini = $_POST['entini'];
    $entfin = $_POST['entfin'];
    $fecpagini = $_POST['fecpagini'];
    $fecpagfin = $_POST['fecpagfin'];
    $puntPagoFin = $_POST['puntPagoFin'];
    $puntPagoIni = $_POST['puntPagoIni'];
    $cajaPagoIni = $_POST['cajaPagoIni'];
    $cajaPagoFin = $_POST['cajaPagoFin'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/css.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript">
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
                height:16px;
                font-weight:normal;
            }
        </style>

    </head>
    <body>
    <form name="reporte_obslec" action="vista.reporte_pagos_x_fecha_det.php" method="post">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Reporte Pagos Por Entidad y Fecha Detallado</h3>
        <div class="flexigrid" style="width:1220px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Pagos Por Entidad y Fecha Detallado</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td width="19%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input"><option></option>
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
                            </td>
                            <td width="20%" style=" border:1px solid #EEEEEE; text-align:center">Entidad<br />
                                Desde:&nbsp;&nbsp;<input type="text" name="entini" id="entini" value="<?php echo $entini;?>" size="2" class="input" maxlength="3" />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="text" name="entfin" id="entfin" value="<?php echo $entfin;?>" size="2" class="input" maxlength="3" />
                            </td>

                            <td width="20%" style=" border:1px solid #EEEEEE; text-align:center">Punto de pago<br />
                                Desde:&nbsp;&nbsp;<input type="text" name="puntPagoIni" id="puntPagoIni" value="<?php echo $puntPagoIni;?>" size="2" class="input" maxlength="3" />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="text" name="puntPagoFin" id="puntPagoFin" value="<?php echo $puntPagoFin;?>" size="2" class="input" maxlength="3" />
                            </td>

                            <td width="20%" style=" border:1px solid #EEEEEE; text-align:center">Caja de pago<br />
                                Desde:&nbsp;&nbsp;<input type="text" name="cajaPagoIni" id="cajaPagoIni" value="<?php echo $cajaPagoIni;?>" size="2" class="input" maxlength="3" />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="text" name="cajaPagoFin" id="cajaPagoFin" value="<?php echo $cajaPagoFin;?>" size="2" class="input" maxlength="3" />
                            </td>


                            <td style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Fecha Pago<br />
                                Desde:&nbsp;&nbsp;<input type="date" name="fecpagini" id="fecpagini" value="<?php echo $fecpagini;?>" class="input" required />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="date" name="fecpagfin" id="fecpagfin" value="<?php echo $fecpagfin;?>" class="input"  required/>
                            </td>

                            <td width="27%" style=" border:1px solid #EEEEEE; text-align:center">
                                <input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO " style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#FF8000; color:#FF8000;">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
    if(isset($_REQUEST["Generar"])){
        ?>
        <form action="../../funciones/ficheroExcel.php?nomrepo=pagos_por_fecha" method="post" target="_blank"  id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <div class="flexigrid" style="width:1220px">
                <div class="mDiv">
                    <div>Exportar a:</div>
                    <div style="background-color:rgb(255,255,255)">
                        <!--				<a href="vista.reporte_excel_pago_fecha.php?&proyecto=--><?php //echo $proyecto?><!--&zonini=--><?php //echo $zonini?><!--&zonfin=--><?php //echo $zonfin?><!--&lecini=--><?php //echo $lecini?><!--&perini=--><?php //echo $perini?><!--">-->
                        <!--					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>-->
                        <!--                <a href="vista.reporte_word_pago_fecha.php?&proyecto=--><?php //echo $proyecto?><!--&zonini=--><?php //echo $zonini?><!--&zonfin=--><?php //echo $zonfin?><!--&lecini=--><?php //echo $lecini?><!--&perini=--><?php //echo $perini?><!--">-->
                        <!--					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>-->
                        <!--				<!--a href="vista.reporte_pdf_pago_fecha.php?&proyecto=--><?php //echo $proyecto?><!--&zonini=--><?php //echo $zonini?><!--&zonfin=--><?php //echo $zonfin?><!--&lecini=--><?php //echo $lecini?><!--&perini=--><?php //echo $perini?><!--">-->
                        <!--					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a-->
                        <img src="../../images/excel/Excel.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                        <!--                <img src="../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>-->


                    </div>
                </div>
            </div>
        </form>
        <div class="flexigrid" style="width:1220px">
            <?php
            $l=new Reportes();
            $registros=$l->seleccionaHora();
            while (oci_fetch($registros)) {
                $fecha = oci_result($registros, 'FECHA') ;
            }oci_free_statement($registros);

            $fecha1= new DateTime("$fecpagini");
            $fecha2= new DateTime("$fecpagfin");
            $diff = $fecha1->diff($fecha2) ;
            $dias = $diff->days + 1;
            ?>

            <div class="mDiv">
                <div>Reporte Pagos Por Entidad y Fecha Generado el <?php echo $fecha?></div>
            </div>
        </div>
        <div class="datagrid" style="width:1220px; height:300px">
            <?php
            /*$c=new Reportes();
            $registros=$c->seleccionaPagosEntidad($proyecto,$entini,$entfin,$fecpagini,$fecpagfin);
            while (oci_fetch($registros)) {
                $cantob = oci_result($registros, 'CANTIDAD');
            }oci_free_statement($registros);*/
            ?>
            <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
                <thead>
                <tr>
                    <th colspan="9" style="border:none; border-right:1px solid #DEDEDE; text-align:center; display: none">
                        Reporte Pagos Por Entidad y Fecha Detallado -.- Impreso el :<?php echo date('d/m/Y') ?>
                    </th>
                </tr>
                <tr>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center"><?php echo 'No.'?></th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ENTIDAD</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">PUNTO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">CAJA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">TIPO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">MEDIO PAGO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">NRO MEDIOS</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center" colspan="2">IMPORTE</th>
                    <?php
                    $date = date("$fecpagini");
                    for($i=0; $i<$dias; $i++){
                        $mod_date = strtotime($date."+ $i day");
                        $dia_fecha = date("d/m/Y",$mod_date);
                        echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center">'.$dia_fecha.'</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>

                <?php
                $total_entidad=0;
                $total_punto=0;
                $total_caja=0;
                $total=0;
                $total_caja_dia=0;
                $total_caja_dia_x=0;

                $totalcant_entidad=0;
                $totalcant_punto=0;
                $totalcant_caja=0;
                $totalcant=0;

                $cont = 1;
                $entidadant = ''; $puntoant = '';$cajaant = '';$tipoant='';
                echo '<tr>';
                $c=new Reportes();
                $registros=$c->seleccionaPagosEntidad($proyecto,$entini,$entfin,$fecpagini,$fecpagfin,$puntPagoIni,$puntPagoFin,$cajaPagoIni,$cajaPagoFin);
                while (oci_fetch($registros)) {
                    $entidad = oci_result($registros, 'ENTIDAD');
                    $punto = oci_result($registros, 'PUNTO');
                    $caja = oci_result($registros, 'DESCRIPCION');
                    $numpagos = oci_result($registros, 'CANTIDAD');
                    $importe = oci_result($registros, 'IMPORTE');
                    $medPago = oci_result($registros, 'MEDIO');
                    $tipo = oci_result($registros, 'TIPO');
                    $puntocomp=oci_result($registros, 'PPAGOCOMP');
                    $cajacomp=oci_result($registros, 'ID_CAJA');
                    $cod_entidad = oci_result($registros, 'COD_ENTIDAD');
                    $cod_caja =oci_result($registros, 'ID_CAJA');
                    $cod_medio = oci_result($registros, 'CODIGO');


                    if(($cajaant<>$cajacomp)){
                        if($cont>1){ echo '<tr>';
                            echo '<td></td>';
                            echo '<td style="border-right:0px;border-top: 0px;border-bottom:0px"></td>';
                            echo '<td style="border-right:0px;border-top: 0px;border-bottom:0px"></td>';
                            echo '<td style="border-right:0px;">Total Caja</td>';
                            echo '<td style="border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-right:0px;text-align:center"><b>
							<a href="JAVASCRIPT:popup('.$cajaant.',\''. $fecpagini.'\',\''.$fecpagfin.'\',\''. $proyecto.'\');">' .$totalcant_caja.'</a></b></td>';
                            echo '<td style="border-right:0px;border-bottom: 0px ">$RD</td>';
                            echo '<td style="border-left:0px ;text-align:right">'.number_format($total_caja,'2',',','.').'</td>';


                            $date_x = date("$fecpagini");
                            for($i=0; $i<$dias; $i++){
                                $mod_date_x = strtotime($date_x."+ $i day");
                                $dia_fecha_x = date("d/m/Y",$mod_date_x);

                                $e=new Reportes();
                                if ($tipo == 'PAGO' && isset($medPago)) {
                                    $registrosdiaa = $e->seleccionaPagosEntidadDia($proyecto, $cod_entidad, $dia_fecha_x, $puntocomp, $cod_caja, $cod_medio);
                                    //echo $proyecto.'  '.$cod_entidad.'  '.$dia_fecha_x.'  '.$puntocomp.'  '.$cod_caja.'  '.$cajaant.'  '.$cod_medio.'<br>';
                                    while (oci_fetch($registrosdiaa)) {
                                        $importe_diaa = oci_result($registrosdiaa, 'IMPORTE_DIA');
                                        //echo '<td style="border-left:0px solid #DEDEDE;  border-right:1px solid #DEDEDE; text-align:right">' . number_format($importe_dia, '2', ',', '.') . '</td>';
                                        $total_caja_dia_x += $importe_diaa;
                                    }
                                }

                                if ($tipo == 'OTRO RECAUDO' && isset($medPago)) {
                                    $registrosdia = $d->seleccionaOtrosEntidadDia($proyecto, $cod_entidad, $dia_fecha, $puntocomp, $cod_caja, $cod_medio);
                                    while (oci_fetch($registrosdia)) {
                                        $importe_dia = oci_result($registrosdia, 'IMPORTE_DIA');
                                        //echo '<td style="border-left:0px solid #DEDEDE;  border-right:1px solid #DEDEDE; text-align:right">' . number_format($importe_dia, '2', ',', '.') . '</td>';
                                        $total_caja_dia += $importe_dia;
                                    }
                                }

                                echo '<td style="border-left:0px ;text-align:right">'.number_format($total_caja_dia_x,'2',',','.').'</td>';

                            }
                            echo '</tr>';
                            $total_caja=0;
                            $totalcant_caja=0;
                            //$total_caja_dia_x=0;
                            //$importe_diaa=0;

                        }

                    }

                    if(($puntoant<>$puntocomp)){
                        if($cont>1){
                            echo '<tr>';
                            echo '<td></td>';
                            echo '<td style="border-right:0px;border-top: 0px;border-bottom:0px"></td>';
                            echo '<td style="border-right:0px;">Total Punto Pago</td>';
                            echo '<td style="border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-right:0px;text-align:center">'.$totalcant_punto.'</td>';
                            echo '<td style="border-right:0px;border-bottom: 0px ">$RD</td>';
                            echo '<td style="border-left:0px ;text-align:right">'.number_format($total_punto,'2',',','.').'</td>';
                            echo '</tr>';
                            $total_punto=0;
                            $totalcant_punto=0;
                        }
                    }

                    if($entidadant <> $entidad){
                        if($cont>1){ echo '<tr>';
                            echo '<td style="border-bottom-width: thick"></td>';
                            echo '<td style="border-bottom-width: thick; border-right:0px" >Total Entidad</td>';
                            echo '<td style="border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                            echo '<td style="border-bottom-width: thick;border-right:0px;text-align:center">'.$totalcant_entidad.'</td>';
                            echo '<td style="border-bottom-width: thick;border-right:0px; ">$RD</td>';
                            echo '<td style="border-bottom-width: thick;border-left:0px ;text-align:right">'.number_format($total_entidad,'2',',','.').'</td>';
                            echo '</tr>';
                            $total_entidad=0;
                            $totalcant_entidad=0;

                        }
                    }

                    if($entidadant <> $entidad){
                        echo '<td style="border-right:1px solid #DEDEDE; border-bottom:0px solid #DEDEDE; text-align:center">'.$cont.'</td>';
                        echo '<td style="border-right:1px solid #DEDEDE; border-bottom:0px solid #DEDEDE; text-align:center">'.$entidad.'</td>';
                        $cont++;
                        $entidadant=$entidad;
                    }
                    else
                    {
                        echo '<td style="border:none; border-right:1px solid #DEDEDE; text-align:center"></td>';
                        echo '<td style="border:none; border-right:1px solid #DEDEDE; text-align:center"></td>';
                    }


                    if($puntoant <> $puntocomp){

//
                        echo '<td style="border-right:1px solid #DEDEDE; border-bottom:0px solid #DEDEDE; text-align:center">'.$punto.'</td>';
                        echo '<td style=" border-right:1px solid #DEDEDE; border-bottom:0px ; text-align:center">'.$caja.'</td>';
                        echo '<td style="border-right:1px solid #DEDEDE; text-align:center;border-bottom:0px;">'.$tipo.'</td>';
                        $cajaant=$cajacomp;
                        $tipoant=$tipo;
                        $puntoant = $puntocomp;

                    }
                    else
                    {
                        echo '<td style="border:none; border-right:1px solid #DEDEDE; text-align:center"></td>';
                        if($cajacomp<>$cajaant){
                            echo '<td style="border-right:1px solid #DEDEDE; border-bottom:0px ; text-align:center">'.$caja.'</td>';
                            echo '<td style="border-right:1px solid #DEDEDE;border-bottom:0px; text-align:center">'.$tipo.'</td>';
                            $cajaant=$cajacomp;
                            $tipoant=$tipo;
                        }else{
                            echo '<td style="border:none; border-right:1px solid #DEDEDE; text-align:center"></td>';
                            if($tipoant<>$tipo){
                                echo '<td style="border-right:1px solid #DEDEDE;  border-bottom:0px;text-align:center">'.$tipo.'</td>';
                                $tipoant=$tipo;
                            }else{
                                echo '<td style="border:none; border-right:1px solid #DEDEDE; text-align:center"></td>';
                            }
                        }
                    }

                    echo '<td style="border-right:1px solid #DEDEDE; text-align:center">'.$medPago.'</td>';
                    echo '<td style="border-right:1px solid #DEDEDE; text-align:center">'.$numpagos.'</td>';
                    echo '<td style="border-right:0px solid #DEDEDE; border-bottom: 0px; text-align:left">$RD</td>';
                    echo '<td style="border-left:0px solid #DEDEDE;  border-right:1px solid #DEDEDE; text-align:right">'.number_format($importe,'2',',','.').'</td>';
                    if($cont == 1){
                        echo '<td style="border-left:0px ;text-align:right">'.number_format($total_caja_dia_x,'2',',','.').'</td>';
                    }

                    $date = date("$fecpagini");
                    for($i=0; $i<$dias; $i++){
                        $mod_date = strtotime($date."+ $i day");
                        $dia_fecha = date("d/m/Y",$mod_date);

                        $d=new Reportes();
                        if ($tipo == 'PAGO' && isset($medPago)) {
                            $registrosdia = $d->seleccionaPagosEntidadDia($proyecto, $cod_entidad, $dia_fecha, $puntocomp, $cod_caja, $cod_medio);
                            while (oci_fetch($registrosdia)) {
                                $importe_dia = oci_result($registrosdia, 'IMPORTE_DIA');
                                echo '<td style="border-left:0px solid #DEDEDE;  border-right:1px solid #DEDEDE; text-align:right">' . number_format($importe_dia, '2', ',', '.') . '</td>';
                                $total_caja_dia += $importe_dia;
                            }
                        }

                        if ($tipo == 'OTRO RECAUDO' && isset($medPago)) {
                            $registrosdia = $d->seleccionaOtrosEntidadDia($proyecto, $cod_entidad, $dia_fecha, $puntocomp, $cod_caja, $cod_medio);
                            while (oci_fetch($registrosdia)) {
                                $importe_dia = oci_result($registrosdia, 'IMPORTE_DIA');
                                echo '<td style="border-left:0px solid #DEDEDE;  border-right:1px solid #DEDEDE; text-align:right">' . number_format($importe_dia, '2', ',', '.') . '</td>';
                                $total_caja_dia += $importe_dia;
                            }
                        }
                    }

                    echo '</tr>';

                    $total_punto+=$importe;
                    $total_entidad+=$importe;
                    $total_caja+=$importe;
                    $total+=$importe;

                    $totalcant_entidad+=$numpagos;
                    $totalcant_punto+=$numpagos;
                    $totalcant_caja+=$numpagos;
                    $totalcant+=$numpagos;

                    $total_caja_dia_x=0;


                } oci_free_statement($registros);


                echo '<td></td>';
                echo '<td style="border-right:0px;border-top: 0px;border-bottom:0px"></td>';
                echo '<td style="border-right:0px;border-top: 0px;border-bottom:0px"></td>';
                echo '<td style="border-right:0px;">Total Caja</td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;text-align:center"><b><a href="JAVASCRIPT:popup('.$cajaant.',\''. $fecpagini.'\',\''.$fecpagfin.'\',\''. $proyecto.'\');">' .$totalcant_caja.'</a></b></td>';
                echo '<td style="border-right:0px;border-bottom: 0px ">$RD</td>';
                echo '<td style="border-left:0px ;text-align:right">'.number_format($total_caja,'2',',','.').'</td>';





                echo '</tr>';

                echo '<td></td>';
                echo '<td style="border-right:0px;border-top: 0px;border-bottom:0px"></td>';
                echo '<td style="border-right:0px;">Total Punto Pago</td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;text-align:center">'.$totalcant_punto.'</td>';
                echo '<td style="border-right:0px;border-bottom: 0px">$RD</td>';
                echo '<td style="border-left:0px ;text-align:right">'.number_format($total_punto,'2',',','.').'</td>';
                echo '</tr>';



                echo '<tr>';
                echo '<td style=" border-bottom-width: thick"></td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px" >Total Entidad</td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px;border-left:0px"></td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px;text-align:center ">'.$totalcant_entidad.'</td>';
                echo '<td style=" border-bottom-width: thick;border-right:0px  ">$RD</td>';
                echo '<td style=" border-bottom-width: thick;border-left:0px ;text-align:right">'.number_format($total_entidad,'2',',','.').'</td>';
                echo '</tr>';

                $c=new Reportes();
                $registros=$c->cantidadPagos($proyecto,$entini,$entfin,$fecpagini,$fecpagfin,$puntPagoIni,$puntPagoFin,$cajaPagoIni,$cajaPagoFin);
                while (oci_fetch($registros)) {
                    $totalpagos = oci_result($registros, 'CANTIDAD');
                    $totaldinero = oci_result($registros, 'IMPORTE');
                }
                echo '<tr>';
                echo '<td style="border-right:0px" >Total Formas de Pago</td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;text-align:center ">'.$totalcant.'</td>';
                echo '<td style="border-right:0px  ">$RD</td>';
                echo '<td style="border-left:0px ;text-align:right">'.number_format($total,'2',',','.').'</td>';
                echo '</tr>';


                echo '<tr>';
                echo '<td style="border-right:0px" >Total Pagos</td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;border-left:0px"></td>';
                echo '<td style="border-right:0px;text-align:center ">'.$totalpagos.'</td>';
                echo '<td style="border-right:0px  ">$RD</td>';
                echo '<td style="border-left:0px ;text-align:right">'.number_format($totaldinero,'2',',','.').'</td>';
                echo '</tr>';
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    ?>
    </body>
    </html>
    <script type="text/javascript" language="javascript">
        function changeBgcolor(cell) {
            cell.style.backgroundColor = (cell.style.backgroundColor=="#E1EEF4" ? "#FFFFFF":"#E1EEF4");
        }

        function changeBgcolor1(cell) {
            cell.style.backgroundColor = (cell.style.backgroundColor=="#FFFFFF" ? "#E1EEF4":"#FFFFFF");
        }

        function changeBgcolor2(cell) {
            cell.style.backgroundColor = (cell.style.backgroundColor=="#FFFFFF" ? "#FFFFFF":"#FFFFFF");
        }
        function changeBgcolor3(cell) {
            cell.style.backgroundColor = (cell.style.backgroundColor=="#FFFFFF" ? "#FFFFFF":"#FFFFFF");
        }
        addTableRolloverEffect('colores','tableRollOverEffect1','tableRowClickEffect1');



        function popup(caja,fecini,fecfin,proyecto) {

            window.open('vista.detallePag.php?id_caja='+caja+'&fecini='+fecini+'&fecfin='+fecfin+'&proyecto='+proyecto, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=800,height=590");
        }

    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

