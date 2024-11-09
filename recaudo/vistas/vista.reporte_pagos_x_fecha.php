<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.reportes_pagos.php';
    include '../../destruye_sesion_cierra.php';
    include '../../clases/class.proyecto.php';

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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
        <link href="../../librerias/bootstrap-4.6/css/bootstrap.min.css" rel="stylesheet">
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

            .panel-heading{
                text-align: center;
            }

            #frmReporte {
                background: #eee;
                border: 1px solid #dddbdb;
                border-radius: 5px;
            }   
        </style>

    </head>
    <body>
    <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:100%; margin-top:-5px">Reporte Pagos Por Entidad y Fecha</h3>
    <div class="container-fluid">
        <div class="row">
            <form name="reporte_obslec" action="vista.reporte_pagos_x_fecha.php" method="post" class="col-sm-12" id="frmReporte">
                <div class="row">
                    <div class="col-sm-12 col-md-2 form-group">
                        <label for="slcAcueducto">
                            <b>Acueducto</b>
                            <select name="proyecto" id="slcAcueducto" class="form-control">
                                <option value="">Todos los acueductos</option>
                                <?php
                                    $l=new Proyecto();
                                    $registros=$l->obtenerProyecto($coduser);
                                    while (oci_fetch($registros)) {
                                        $cod_proyecto = oci_result($registros, 'CODIGO') ;
                                        $sigla_proyecto = oci_result($registros, 'DESCRIPCION') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($registros);
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-sm-12 col-md-2 form-group">
                        <div class="row">
                            <label class="col-sm-12"><b>Entidad</b></label>
                            <label for="entini" class="col-sm-3">Desde</label>
                            <input type="text" name="entini" id="entini" class="form-control col-sm-3" value="<?php echo $entini;?>" maxlength="3">
                            <label for="entfin" class="col-sm-3">Hasta</label>
                            <input type="text" name="entfin" id="entfin" class="form-control col-sm-3" value="<?php echo $entfin;?>" maxlength="3">                
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 form-group">
                        <div class="row">
                            <label class="col-sm-12"><b>Punto de pago</b></label>                        
                            <label for="puntPagoIni" class="col-sm-3">Desde</label>
                            <input type="text" name="puntPagoIni" id="puntPagoIni" class="form-control col-sm-3" value="<?php echo $puntPagoIni;?>" maxlength="3">
                            <label for="puntPagoFin" class="col-sm-3">Hasta</label>
                            <input type="text" name="puntPagoFin" id="puntPagoFin" class="form-control col-sm-3" value="<?php echo $puntPagoFin;?>" maxlength="3">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 form-group">
                    <div class="row">
                        <label for="" class="col-sm-12"><b>Cajas de pago</b></label>                        
                        <label for="cajaPagoIni" class="col-sm-3">Desde</label>
                        <input type="text" name="cajaPagoIni" id="cajaPagoIni" class="col-sm-3 form-control" value="<?php echo $cajaPagoIni;?>" maxlength="3">
                        <label for="cajaPagoFin" class="col-sm-3">Hasta</label>
                        <input type="text" name="cajaPagoFin" id="cajaPagoFin" class="col-sm-3 form-control" value="<?php echo $cajaPagoFin;?>" maxlength="3">                        
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-2 form-group">
                        <div class="row">
                            <label for="" class="col-sm-12"><b>Fecha de pago</b></label>
                            <label for="" class="col-sm-6">Desde</label>
                            <input type="date" name="fecpagini" id="fecpagini" class="col-sm-6 form-control" value="<?php echo $fecpagini;?>">
                            <label for="" class="col-sm-6">Hasta</label>
                            <input type="date" name="fecpagfin" id="fecpagfin" class="col-sm-6 form-control" value="<?php echo $fecpagfin;?>">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 form-group">
                        <div class="row justify-content-center">
                            <label for="" class="col-sm-12">&nbsp;</label>
                            <input type="submit" value="Generar reporte" class="btn btn-success col-sm-8" id="btnSubmit" name="Generar">
                        </div>
                    </div>
                </div>
            </form>        
    <?php if(isset($_REQUEST["Generar"])){?>
        <form action="../../funciones/ficheroExcel.php?nomrepo=pagos_por_fecha" method="post" target="_blank"  id="FormularioExportacion" class="col-sm-6">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <div class="flexigrid">
                <div class="mDiv">
                    <div>Exportar a:</div>
                    <div style="background-color:rgb(255,255,255)">
                        <img src="../../images/excel/Excel.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                    </div>
                </div>
            </div>
        </form>
        <div class="flexigrid col-sm-12">
            <?php
            $l=new Reportes();
            $registros=$l->seleccionaHora();
            while (oci_fetch($registros)) {
                $fecha = oci_result($registros, 'FECHA') ;
            }oci_free_statement($registros);

            ?>

            <div class="mDiv">
                <div>Reporte Pagos Por Entidad y Fecha Generado el <?php echo $fecha?></div>
            </div>
        </div>
        <div class="datagrid col-sm-12">
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
                        Reporte Pagos Por Entidad y Fecha -.- Impreso el :<?php echo date('d/m/Y') ?>
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
                </tr>
                </thead>
                <tbody>

                <?php
                $total_entidad=0;
                $total_punto=0;
                $total_caja=0;
                $total=0;

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
                            echo '</tr>';
                            $total_caja=0;
                            $totalcant_caja=0;

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
                    echo '</tr>';
                    $total_punto+=$importe;
                    $total_entidad+=$importe;
                    $total_caja+=$importe;
                    $total+=$importe;

                    $totalcant_entidad+=$numpagos;
                    $totalcant_punto+=$numpagos;
                    $totalcant_caja+=$numpagos;
                    $totalcant+=$numpagos;



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
                echo '<td style="border-left:0px ;text-align:right">'.number_format($total,'2',',','.').'</td>';
                echo '</tr>';
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    ?>
    </div>
    </div>
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

