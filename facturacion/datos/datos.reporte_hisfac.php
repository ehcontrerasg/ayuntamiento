<?php
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_hisfac.php';
require '../clases/class.consultaGeneral.php';
$proyecto   = $_POST['proyecto'];
$grupo      = $_POST['grupo'];
$zonini     = $_POST['zonini'];
$zonfin     = $_POST['zonfin'];
$perini     = $_POST['perini'];
$canper     = $_POST['canper'];
$inm        = $_POST['inm'];
$proini     = $_POST['proini'];
$profin     = $_POST['profin'];
$catini     = $_POST['catini'];
$catfin     = $_POST['catfin'];
$urbaniza   = $_POST['urbaniza'];
$tipovia    = $_POST['tipovia'];
$estado     = $_POST['estado'];
$estado_inm = $_POST['estado_inm'];
$metodo     = $_POST['metodo'];
$obs        = strtoupper($_POST['observacion']);

//if (isset($_REQUEST["Generar"])) {
    ?>
    <form action="../../funciones/ficheroExcel.php?nomrepo=<?echo $nomrepo; ?>" method="post" target="_blank"  id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        <div class="flexigrid" style="width:1220px">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <a href="vista.reporte_excel_hisfac.php?&proyecto=<?php echo $proyecto ?>&zonini=<?php echo $zonini ?>&zonfin=<?php echo $zonfin ?>&canper=<?php echo $canper ?>&perini=<?php echo $perini ?>&proini=<?php echo $proini ?>&profin=<?php echo $profin ?>&catini=<?php echo $catini ?>&catfin=<?php echo $catfin ?>&urbaniza=<?php echo $urbaniza ?>&tipovia=<?php echo $tipovia ?>&estado=<?php echo $estado ?>&estado_inm=<?php echo $estado_inm ?>&metodo=<?php echo $metodo ?>&grupo=<?php echo $grupo ?>&inm=<?php echo $inm ?>&observacion=<?php echo $obs ?>">
                        <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                    <!--a href="vista.reporte_word_hisfac.php?&proyecto=<?php echo $proyecto ?>&zonini=<?php echo $zonini ?>&zonfin=<?php echo $zonfin ?>&canper=<?php echo $canper ?>&perini=<?php echo $perini ?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_hisfac.php?&proyecto=<?php echo $proyecto ?>&zonini=<?php echo $zonini ?>&zonfin=<?php echo $zonfin ?>&canper=<?php echo $canper ?>&perini=<?php echo $perini ?>">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a-->
                </div>
            </div>
        </div>
    </form>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Reporte Hist&oacute;rico de Facturas</div>
        </div>
    </div>
    <div class="datagrid" style="width:1220px; height:350px; border:none">
        <table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
            <tr>
                <td colspan="14" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b>HIST&Oacute;RICO DE FACTURAS</b></td>
            </tr>
            <?php
            $c          = new ReportesHistoricoFac();
            $registrosC = $c->CantidadZonas($perini, $zonini, $zonfin, $canper, $proyecto, $inm, $grupo, $proini, $profin, $catini, $catfin, $urbaniza, $tipovia, $estado, $estado_inm, $metodo);
            while (oci_fetch($registrosC)) {
                $zona = oci_result($registrosC, 'ID_ZONA');
                ?>
                <tr>
                    <td colspan="14" style="border:1px solid #DEDEDE; background-color:#0080C0; color:#FFFFFF"><b>ZONA&nbsp;&nbsp;<?php echo $zona ?></b></td>
                </tr>
                <?php
                $c          = new ReportesHistoricoFac();
                $registrosF = $c->InmueblesZona($perini, $zona, $inm, $grupo, $proini, $profin, $catini, $catfin, $urbaniza, $tipovia, $estado, $estado_inm, $metodo, $obs);
                while (oci_fetch($registrosF)) {
                    $inmueble   = oci_result($registrosF, 'CODIGO_INM');
                    $c          = new ReportesHistoricoFac();
                    $registrosG = $c->DatosInmueble($inmueble);
                    while (oci_fetch($registrosG)) {
                        $cliente    = oci_result($registrosG, 'ALIAS');
                        $urbaniza   = oci_result($registrosG, 'DESC_URBANIZACION');
                        $direccion  = oci_result($registrosG, 'DIRECCION');
                        $estado     = oci_result($registrosG, 'ID_ESTADO');
                        $uso        = oci_result($registrosG, 'ID_USO');
                        $proceso    = oci_result($registrosG, 'ID_PROCESO');
                        $catastro   = oci_result($registrosG, 'CATASTRO');
                        $unidades   = oci_result($registrosG, 'UNIDADES_TOT');
                        $consumomin = oci_result($registrosG, 'CONSUMO_MINIMO');
                        $tarifa     = oci_result($registrosG, 'CODIGO_TARIFA');
                        $medidor    = oci_result($registrosG, 'COD_MEDIDOR');
                        $serial     = oci_result($registrosG, 'SERIAL');
                        $emplaza    = oci_result($registrosG, 'COD_EMPLAZAMIENTO');
                    }
                    oci_free_statement($registrosG);
                    ?>
                    <tr>
                        <td colspan="2" rowspan="2" style="border:1px solid #DEDEDE; background-color:#83B9FC; color:#FFFFFF; font-size:11px">
                            <b>Inmueble&nbsp;&nbsp;<?php echo $inmueble ?></b>
                        </td>
                        <td colspan="5" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; font-size:11px">
                            <b>Cliente:&nbsp;</b><?php echo substr($cliente, 0, 50) ?>
                        </td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; border-left:none; font-size:11px" colspan="4">
                            <b>Proceso:&nbsp;</b><?php echo $proceso ?>
                        </td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000; border-left:none; font-size:11px" colspan="3">
                            <b>Catastro:&nbsp;</b><?php echo $catastro ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="border:1px solid #DEDEDE;background-color:#FFFFFF;color:#000000; font-size:11px"><b>Direcci&oacute;n:&nbsp;</b>
                            <?php echo substr($direccion . ' ' . $urbaniza, 0, 50) ?></td>
                        <td style="border:1px solid #DEDEDE;background-color:#FFFFFF;color:#000000;vertical-align:top;text-align:center;font-size:9px">
                            <b>Estado</b><br /><?php echo $estado ?></td>
                        <td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
                            <b>Unidad</b><br /><?php echo $unidades ?></td>
                        <td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
                            <b>Uso</b><br /><?php echo $uso ?></td>
                        <td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
                            <b>Tarifa</b><br /><?php echo $tarifa ?></td>
                        <td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
                            <b>Cons.Min</b><br /><?php echo $consumomin ?></td>
                        <td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
                            <b>Medidor</b><br /><?php echo $medidor ?></td>
                        <td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#000000; vertical-align:top; text-align:center;font-size:9px">
                            <b>Serial</b><br /><?php echo $serial ?></td>
                    </tr>
                    <tr>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Periodo</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Fecha</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Lectura</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Lote</b></td>
                        <td colspan="3" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Consumo</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>M&eacute;todo</b></td>
                        <td rowspan="2" colspan="3" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Lector</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Observaci&oacute;n</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Base</b></td>
                        <td rowspan="2" style="background-color:#F3F3F3; color:#000000; font-size:9px; text-align:center"><b>Total</b></td>
                    </tr>
                    <tr>
                        <td style="background-color:#F3F3F3; color:#000000; font-size:9px">Dir</td>
                        <td style="background-color:#F3F3F3; color:#000000; font-size:9px">Ajus</td>
                        <td style="background-color:#F3F3F3; color:#000000; font-size:9px">Fac</td>
                    </tr>
                    <?php
                    $c          = new ReportesHistoricoFac();
                    $registrosG = $c->PeriodosInmueble($perini, $inmueble, $canper, $obs);
                    while (oci_fetch($registrosG)) {
                        $periodo     = oci_result($registrosG, 'PERIODO');
                        $fechaexp    = oci_result($registrosG, 'FECHA');
                        $lectura     = oci_result($registrosG, 'LECTURA_ACTUAL');
                        $lote        = oci_result($registrosG, 'ID_RUTA');
                        $cons        = oci_result($registrosG, 'CONSUMO');
                        $metodo      = oci_result($registrosG, 'METODO');
                        $lector      = oci_result($registrosG, 'COD_LECTOR');
                        $observacion = oci_result($registrosG, 'OBSERVACION');
                        $total       = oci_result($registrosG, 'TOTAL');
                        //$fecpago = oci_result($registrosG, 'FECPAGO');
                        //$pagado = oci_result($registrosG, 'TOTAL_PAGADO');

                        if ($metodo == 'P') {
                            $cons     = $consumomin;
                            $ajuste   = 0;
                            $facturar = $ajuste + $cons;
                        }
                        if ($metodo == 'D') {
                            if ($cons < $consumomin) {
                                $ajuste   = $consumomin - $cons;
                                $facturar = $ajuste + $cons;
                            } else {
                                $ajuste   = 0;
                                $facturar = $cons;
                            }
                        }
                        ?>
                        <tr>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $periodo ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $fechaexp ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $lectura ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $lote ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $cons ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $ajuste ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $facturar ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $metodo ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px" colspan="3"><?php echo $lector ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo $observacion ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo number_format($total, 2, '.', ',') ?></td>
                            <td style="background-color:#FFFFFF; color:#666666; font-size:9px"><?php echo number_format(round($total), 2, '.', ',') ?></td>
                        </tr>
                        <?php
                    }
                    oci_free_statement($registrosG);
                    ?>


                    <?php
                }
                oci_free_statement($registrosF);
            }
            oci_free_statement($registrosC);
            ?>
        </table>
    </div>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Reporte Hist&oacute;rico de Facturas</div>
        </div>
    </div>
    <div class="datagrid" style="width:1220px; height:150px; border:none">
        <table class="scroll" border="1" bordercolor="#CCCCCC" style="height:150px">
            <tr>
                <td colspan="14" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b>TOTAL HIST&Oacute;RICO DE FACTURAS</b></td>
            </tr>
            <tr>
                        <td style="background-color:#F3F3F3; color:#000000; font-size:18px; text-align:center"><b>Periodo</b></td>
                        <td style="background-color:#F3F3F3; color:#000000; font-size:18px; text-align:center"><b>Total Facturado</b></td>
                    </tr>
            <?php
            $c          = new ReportesHistoricoFac();
            $registrosH = $c->Totalhisfac($perini, $inmueble, $canper, $zona);
            while (oci_fetch($registrosH)) {
                $periodo     = oci_result($registrosH, 'PERIODO');
                $total       = oci_result($registrosH, 'TOTAL');
                ?>
                <tr>
                    <td style="background-color:#FFFFFF; color:#666666; font-size:16px"><?php echo $periodo ?></td>
                    <td style="background-color:#FFFFFF; color:#666666; font-size:16px"><?php echo number_format(round($total), 2, '.', ',') ?></td>
                </tr>
                <?php
            }
            oci_free_statement($registrosH);
            ?>
        </table>
    </div>
    <?php
//}
?>