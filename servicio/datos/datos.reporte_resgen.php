<?php
$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];
$zonini = $_POST['zonini'];
$zonfin = $_POST['zonfin'];
$perini = $_POST['perini'];
$perfin = $_POST['perfin'];
//if(isset($_REQUEST["Generar"])){
    ?>
    <form action="../../funciones/ficheroExcel.php?nomrepo=<? echo $nomrepo;?>" method="post" target="_blank"  id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        <div class="flexigrid" style="width:1220px">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <a href="vista.reporte_excel_resgen.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&zonfin=<?php echo $zonfin?>&perfin=<?php echo $perfin?>&perini=<?php echo $perini?>">
                        <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                    <a href="vista.reporte_word_resgen.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&zonfin=<?php echo $zonfin?>&perfin=<?php echo $perfin?>&perini=<?php echo $perini?>">
                        <img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
                    <a href="vista.reporte_pdf_resgen.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&zonfin=<?php echo $zonfin?>&perfin=<?php echo $perfin?>&perini=<?php echo $perini?>">
                        <img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
                </div>
            </div>
        </div>
    </form>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Resumen General de Facturaci&oacute;n</div>
        </div>
    </div>
    <div class="datagrid" style="width:1220px; height:145px; border:none">
        <?php
        $c=new ReportesFacturacion();
        $registros=$c->CantidadLeidos($perini,$zonini,$zonfin,$perfin,$proyecto);
        while (oci_fetch($registros)) {
            $cantidadL = oci_result($registros, 'CANTIDAD');
            $consumoL = oci_result($registros, 'CONSUMO');
            $facturadoL = oci_result($registros, 'FACTURADO');
            $recaudadoL = oci_result($registros, 'RECAUDADO');
            $pendienteL = oci_result($registros, 'PENDIENTE');
        }oci_free_statement($registros);
        $c=new ReportesFacturacion();
        $registros=$c->CantidadPromedios($perini,$zonini,$zonfin,$perfin,$proyecto);
        while (oci_fetch($registros)) {
            $cantidadP = oci_result($registros, 'CANTIDAD');
            $consumoP = oci_result($registros, 'CONSUMO');
            $facturadoP = oci_result($registros, 'FACTURADO');
            $recaudadoP = oci_result($registros, 'RECAUDADO');
            $pendienteP = oci_result($registros, 'PENDIENTE');
        }oci_free_statement($registros);
        ?>
        <table border="1" bordercolor="#CCCCCC" style="height:145px">
            <thead>
            <tr>
                <th colspan="6" style="border:1px solid #DEDEDE">RESUMEN GENERAL</th>
            </tr>
            <tr>
                <th style="border:1px solid #DEDEDE">M&eacute;todo</th>
                <th style="border:1px solid #DEDEDE">Inmuebles</th>
                <th style="border:1px solid #DEDEDE">Consumo</th>
                <th style="border:1px solid #DEDEDE">Facturado</th>
                <th style="border:1px solid #DEDEDE">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Promedio</td>
                <td align="right"><?php echo $cantidadP?></td>
                <td align="right"><?php echo number_format($consumoP,2,'.',',').' m&sup3'?></td>
                <td align="right" style="background:#336699; color:#FFFFFF"><b><?php echo 'RD$ '.number_format($facturadoP,2,'.',',')?></b></td>
                <td align="right" style="background:#336699; color:#FFFFFF"><b><?php echo 'RD$ '.number_format(round($facturadoP),2,'.',',')?></b></td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid #DEDEDE">Diferencia</td>
                <td align="right" style="border-bottom:1px solid #DEDEDE"><?php echo $cantidadL?></td>
                <td align="right" style="border-bottom:1px solid #DEDEDE"><?php echo number_format($consumoL,2,'.',',').' m&sup3'?></td>
                <td align="right" style="border-bottom:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b><?php echo 'RD$ '.number_format($facturadoL,2,'.',',')?></b>
                </td>
                <td align="right" style="border-bottom:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b><?php echo 'RD$ '.number_format(round($facturadoL),2,'.',',')?></b>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Total</td>
                <td align="right" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000"><?php echo $cantidadP + $cantidadL?></td>
                <td align="right" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000"><?php echo number_format($consumoP+$consumoL,2,'.',',').' m&sup3'?></td>
                <td align="right" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000"><?php echo 'RD$ '.number_format($facturadoP+$facturadoL,2,'.',',')?>
                </td>
                <td align="right" style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000"><?php echo 'RD$ '.number_format(round($facturadoP+$facturadoL),2,'.',',')?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="datagrid" style="width:1220px; height:200px; border:none">
        <table class="scroll" border="1" bordercolor="#CCCCCC" style="height:200px">
            <tr>
                <td colspan="8" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF"><b>RESUMEN CONCEPTO - USO - TARIFA</b></td>
            </tr>
            <?php
            $cantidadinmtot = 0; $cantidadcontot = 0; $cantidadunitot = 0; $cantidadfactot = 0; $cantidadrectot = 0; $cantidadpentot = 0;
            $c=new ReportesFacturacion();
            $registrosC=$c->CantidadConceptos($perini,$zonini,$zonfin,$perfin,$proyecto);
            while (oci_fetch($registrosC)) {
                $codconcepto = oci_result($registrosC, 'CONCEPTO');
                $concepto = oci_result($registrosC, 'DESC_SERVICIO');
                ?>
                <tr>
                    <td colspan="8" style="border:1px solid #DEDEDE; background-color:#0080C0; color:#FFFFFF"><b>CONCEPTO:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $concepto?></b></td>
                </tr>
                <?php
                $cantidadinmcon = 0; $cantidadconcon = 0; $cantidadunicon = 0; $cantidadfaccon = 0; $cantidadreccon = 0; $cantidadpencon = 0;
                $c=new ReportesFacturacion();
                $registrosF=$c->CantidadUsos($perini,$zonini,$zonfin,$perfin,$proyecto,$concepto);
                while (oci_fetch($registrosF)) {
                    $codser = oci_result($registrosF, 'CONCEPTO');
                    $desser = oci_result($registrosF, 'DESC_SERVICIO');
                    $desuso = oci_result($registrosF, 'DESC_USO');
                    ?>
                    <tr>
                        <td colspan="8" style="border:1px solid #DEDEDE; background-color:#83B9FC; color:#FFFFFF"><b>USO:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $desuso?></b></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Tarifa</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Inmuebles</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Consumo</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Unidades</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Facturado</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Total</td>
                    </tr>
                    <?php
                    $cantidadinmuso = 0; $cantidadconuso = 0; $cantidaduniuso = 0; $cantidadfacuso = 0; $cantidadrecuso = 0; $cantidadpenuso = 0;
                    $c=new ReportesFacturacion();
                    $registrosU=$c->CantidadConceptoPorUso($perini,$zonini,$zonfin,$perfin,$proyecto,$codconcepto,$desuso);
                    while (oci_fetch($registrosU)) {
                        $codtar = oci_result($registrosU, 'CODIGO_TARIFA');
                        $destar = oci_result($registrosU, 'DESC_TARIFA');
                        $consumouso = oci_result($registrosU, 'CONSUMO');
                        $cantidaduso = oci_result($registrosU, 'CANTIDAD');
                        $facturadouso = oci_result($registrosU, 'FACTURADO');
                        $recaudadouso = oci_result($registrosU, 'RECAUDADO');
                        $pendienteuso = oci_result($registrosU, 'PENDIENTE');
                        $unidadesuso = oci_result($registrosU, 'UNIDADES');
                        ?>
                        <tr>
                            <td><?php echo $codtar.' - '.$destar?></td>
                            <td><?php echo $cantidaduso?></td>
                            <td align="right"><?php echo number_format($consumouso,2,'.',',').' m&sup3'?></td>
                            <td align="right"><?php echo $unidadesuso?></td>
                            <td align="right"><?php echo 'RD$ '.number_format($facturadouso,2,'.',',')?></td>
                            <td align="right"><?php echo 'RD$ '.number_format(round($facturadouso),2,'.',',')?></td>
                        </tr>
                        <?php
                        $cantidadinmuso += $cantidaduso;
                        $cantidadconuso += $consumouso;
                        $cantidaduniuso += $unidadesuso;
                        $cantidadfacuso += $facturadouso;
                        $cantidadrecuso += $recaudadouso;
                        $cantidadpenuso += $pendienteuso;
                    }oci_free_statement($registrosU);
                    ?>
                    <tr>
                        <td style="border:1px solid #DEDEDE; background-color:#999999; color:#FFFFFF"><b>Total Uso:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $desuso?></b></td>
                        <td style="background-color:#999999; color:#FFFFFF"><?php echo $cantidadinmuso?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo number_format($cantidadconuso,2,'.',',').' m&sup3'?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo $cantidaduniuso?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo 'RD$ '.number_format($cantidadfacuso,2,'.',',')?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo 'RD$ '.number_format(round($cantidadfacuso),2,'.',',')?></td>
                    </tr>
                    <?php
                    $cantidadinmcon += $cantidadinmuso;
                    $cantidadconcon += $cantidadconuso;
                    $cantidadunicon += $cantidaduniuso;
                    $cantidadfaccon += $cantidadfacuso;
                    $cantidadreccon += $cantidadrecuso;
                    $cantidadpencon += $cantidadpenuso;
                }oci_free_statement($registrosF);
                ?>
                <tr>
                    <td style="border:1px solid #DEDEDE; background-color:#666666; color:#FFFFFF"><b>Total Concepto:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $concepto?></b></td>
                    <td style="background-color:#666666; color:#FFFFFF"><?php echo $cantidadinmcon?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo number_format($cantidadconcon,2,'.',',').' m&sup3'?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo $cantidadunicon?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo 'RD$ '.number_format($cantidadfaccon,2,'.',',')?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo 'RD$ '.number_format(round($cantidadfaccon),2,'.',',')?></td>
                </tr>
                <?php
                //SUMAR LOS DEMAS CONCEPTOS
                $cantidadinmtot += $cantidadinmcon;
                $cantidadcontot += $cantidadconcon;
                $cantidadunitot += $cantidadunicon;
                $cantidadfactot += $cantidadfaccon;
                $cantidadrectot += $cantidadreccon;
                $cantidadpentot += $cantidadpencon;
            }oci_free_statement($registrosC);

            $c=new ReportesFacturacion();
            $registrosO=$c->CantidadOtrosConceptos($perini,$zonini,$zonfin,$perfin,$proyecto);
            while (oci_fetch($registrosO)) {
                $otroconcepto = oci_result($registrosO, 'CONCEPTO');
                $otrodesconcepto = oci_result($registrosO, 'DESC_SERVICIO');
                ?>
                <tr>
                    <td colspan="8" style="border:1px solid #DEDEDE; background-color:#0080C0; color:#FFFFFF"><b>CONCEPTO:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $otrodesconcepto?></b></td>
                </tr>
                <?php
                $totalinmotrocon = 0; $totalconotrocon = 0; $totaluniotrocon = 0; $totalfacotrocon = 0;
                $c=new ReportesFacturacion();
                $registrosU=$c->CantidadOtrosUsos($perini,$zonini,$zonfin,$perfin,$otroconcepto,$proyecto);
                while (oci_fetch($registrosU)) {
                    $otrouso = oci_result($registrosU, 'DESC_USO');
                    ?>
                    <tr>
                        <td colspan="8" style="border:1px solid #DEDEDE; background-color:#83B9FC; color:#FFFFFF"><b>USO:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $otrouso?></b></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Tarifa</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Inmuebles</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Consumo</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Unidades</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Facturado</td>
                        <td style="border:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000">Total</td>
                    </tr>
                    <?php

                    $cantidadinmotrocon = 0; $cantidadconotrocon = 0; $cantidaduniotrocon = 0; $cantidadfacotrocon = 0;

                    $c=new ReportesFacturacion();
                    $registrosD=$c->DetalleOtrosConceptos($perini,$zonini,$zonfin,$perfin,$proyecto,$otrouso,$otroconcepto);
                    while (oci_fetch($registrosD)) {

                        $otroconceptoinm = oci_result($registrosD, 'CANTIDAD');
                        $otroconceptocon = oci_result($registrosD, 'CONSUMO');
                        $otroconceptouni = oci_result($registrosD, 'UNIDADES');
                        $otroconceptofac = oci_result($registrosD, 'FACTURADO');
                        ?>
                        <tr>
                            <td><b>No Aplica</b></td>
                            <td><?php echo $otroconceptoinm?></td>


                            <td align="right"><?php echo number_format($otroconceptocon,2,'.',',').' m&sup3'?></td>

                            <td align="right"><?php echo $otroconceptouni?></td>


                            <td align="right"><?php echo 'RD$ '.number_format($otroconceptofac,2,'.',',')?></td>


                            <td align="right"><?php echo 'RD$ '.number_format(round($otroconceptofac),2,'.',',')?></td>

                        </tr>
                        <?php
                        $cantidadinmotrocon += $otroconceptoinm;
                        $cantidadconotrocon += $otroconceptocon;
                        $cantidaduniotrocon += $otroconceptouni;
                        $cantidadfacotrocon += $otroconceptofac;
                    }oci_free_statement($registrosD);
                    ?>
                    <tr>
                        <td style="border:1px solid #DEDEDE; background-color:#999999; color:#FFFFFF"><b>Total Uso:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $otrouso?></b></td>
                        <td style="background-color:#999999; color:#FFFFFF"><?php echo $cantidadinmotrocon?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo number_format($cantidadconotrocon,2,'.',',').' m&sup3'?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo $cantidaduniotrocon?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo 'RD$ '.number_format($cantidadfacotrocon,2,'.',',')?></td>
                        <td align="right" style="background-color:#999999; color:#FFFFFF"><?php echo 'RD$ '.number_format(round($cantidadfacotrocon),2,'.',',')?></td>
                    </tr>
                    <?php
                    $totalinmotrocon += $cantidadinmotrocon;
                    $totalconotrocon += $cantidadconotrocon;
                    $totaluniotrocon += $cantidaduniotrocon;
                    $totalfacotrocon += $cantidadfacotrocon;
                }oci_free_statement($registrosU);S
                ?>
                <tr>
                    <td style="border:1px solid #DEDEDE; background-color:#666666; color:#FFFFFF"><b>Total Concepto:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $otrodesconcepto?></b></td>
                    <td style="background-color:#666666; color:#FFFFFF"><?php echo $totalinmotrocon?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo number_format($totalconotrocon,2,'.',',').' m&sup3'?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo $totaluniotrocon?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo 'RD$ '.number_format($totalfacotrocon,2,'.',',')?></td>
                    <td align="right" style="background-color:#666666; color:#FFFFFF"><?php echo 'RD$ '.number_format(round($totalfacotrocon),2,'.',',')?></td>
                </tr>
                <?php
                $cantidadinmtot += $totalinmotrocon;
                $cantidadcontot += $totalconotrocon;
                $cantidadunitot += $totaluniotrocon;
                $cantidadfactot += $totalfacotrocon;

            }oci_free_statement($registrosO);
            ?>
            <tr>
                <td style="border:1px solid #DEDEDE; background-color:#000000; color:#FFFFFF"><b>Total General</b></td>
                <td style="background-color:#000000; color:#FFFFFF"><?php echo $cantidadinmtot?></td>
                <td align="right" style="background-color:#000000; color:#FFFFFF"><?php echo number_format($cantidadcontot,2,'.',',').' m&sup3'?></td>
                <td align="right" style="background-color:#000000; color:#FFFFFF"><?php echo $cantidadunitot?></td>
                <td align="right" style="background-color:#000000; color:#FFFFFF"><?php echo 'RD$ '.number_format($cantidadfactot,2,'.',',')?></td>
                <td align="right" style="background-color:#000000; color:#FFFFFF"><?php echo 'RD$ '.number_format(round($cantidadfactot),2,'.',',')?></td>
            </tr>
        </table>
    </div>
    <?php
//}
?>