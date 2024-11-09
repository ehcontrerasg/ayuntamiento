<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.reportes_catastro.php';
    include '../clases/class.servicio.php';
    include '../../destruye_sesion.php';

    $coduser  = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $proIni   = $_POST['proIni'];
    $proFin   = $_POST['proFin'];
    $manIni   = $_POST['manIni'];
    $manFin   = $_POST['manFin'];
    $estIni   = $_POST['estIni'];
    $estFin   = $_POST['estFin'];
    $usoIni   = $_POST['usoIni'];
    $act      = $_POST['actividad'];
    $servicio      = $_POST['servicio'];
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
        </style>
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
    </head>
    <body style="width:100%;">
    <form name="reporte_inmuebles_estado" action="vista.reporte_inmueblesxestado.php" method="post">
        <h3 class="panel-heading" style=" background-color:#A349A3; color:#FFFFFF; font-size:18px; width:100%; margin-top:-5px" align="center">Reporte Inmuebles Por Estado</h3>
        <div class="flexigrid" style="width: 100%;" >
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Inmuebles Por Estado</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%" >
                        <tr>
                            <td width="7%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input" required>
                                    <option></option>
                                    <?php
                                    $l         = new Reportes();
                                    $registros = $l->obtenerProyecto($coduser);
                                    while (oci_fetch($registros)) {
                                        $cod_proyecto   = oci_result($registros, 'CODIGO');
                                        $sigla_proyecto = oci_result($registros, 'DESCRIPCION');
                                        if ($cod_proyecto == $proyecto) {
                                            echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        } else {
                                            echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                        }

                                    }
                                    oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="23%" style=" border:1px solid #EEEEEE; text-align:center">Procesos<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="proIni" value="<?php echo $proIni; ?>" size="8" class="input" maxlength="11" />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="proFin" value="<?php echo $proFin; ?>" size="8" maxlength="11" class="input" />
                            </td>
                            <td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Manzana<br />
                                Inicial:&nbsp;&nbsp;
                                <input type="text" name="manIni" value="<?php echo $manIni; ?>" class="input" size="1" maxlength="3"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="manFin" value="<?php echo $manFin; ?>" class="input" size="1" maxlength="3"/>
                            </td>

                            <td width="15%"  style=" border:1px solid #EEEEEE; text-align:center">Estado<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="estIni" value="<?php echo $estIni; ?>" size="1" class="input" maxlength="2" />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="estFin" value="<?php echo $estFin; ?>" size="1" maxlength="2" class="input" />
                            </td>

                            <td width="10%"  style=" border:1px solid #EEEEEE; text-align:center">Uso<br />
                                <select name="usoIni" id="uso" class="input" onchange="setAct();">
                                    <option></option>
                                    <?php
                                    $l         = new Reportes();
                                    $registros = $l->obtenerUsos();
                                    while (oci_fetch($registros)) {
                                        $id_uso   = oci_result($registros, 'ID_USO');
                                        $desc_uso = oci_result($registros, 'DESC_USO');
                                        if ($id_uso == $usoIni) {
                                            echo "<option value='$id_uso' selected>$desc_uso</option>\n";
                                        } else {
                                            echo "<option value='$id_uso'>$desc_uso</option>\n";
                                        }

                                    }
                                    oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="16%" id="actividad" style=" border:1px solid #EEEEEE; text-align:center">Actividad<br />
                                <select name="actividad" class="input">
                                    <option></option>
                                    <?php
                                    $l         = new Reportes();
                                    $registros = $l->obtenerActividad($usoIni);
                                    while (oci_fetch($registros)) {
                                        $id_act   = oci_result($registros, 'SEC_ACTIVIDAD');
                                        $desc_act = oci_result($registros, 'DESC_ACTIVIDAD');
                                        if ($id_act == $act) {
                                            echo "<option value='$id_act' selected>$desc_act</option>\n";
                                        } else {
                                            echo "<option value='$id_act'>$desc_act</option>\n";
                                        }

                                    }
                                    oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="23%" id="servicio" style=" border:1px solid #EEEEEE; text-align:center">Servicio<br />
                                <select name="servicio" class="input">
                                    <option value="" selected>Todos los servicios</option>
                                    <?php
                                  $l         = new Servicio();
                                    $registros = $l->obtenerservicio();
                                    while (oci_fetch($registros)) {
                                        $id_servicio   = oci_result($registros, 'COD_SERVICIO');
                                        $desc_servicio = oci_result($registros, 'DESC_SERVICIO');
                                        if ($id_act == $act) {
                                            echo "<option value='$id_servicio'>$desc_servicio</option>\n";
                                        } else {
                                            echo "<option value='$id_servicio'>$desc_servicio</option>\n";
                                        }

                                    }
                                    oci_free_statement($registros);
                                   ?>
                                </select>
                            </td>


                            <td width="18%" style=" border:1px solid #EEEEEE; text-align:center">
                                <input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO"
                                       style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#A349A3; color:#A349A3;">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
    if (isset($_REQUEST["Generar"])) {
        ?>
        <form action="../../funciones/ficheroExcel.php?nomrepo=<?echo $nomrepo; ?>" method="post" target="_blank"  id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <div class="flexigrid" style="width:1120px">
                <div class="mDiv">
                    <div>Exportar a:</div>
                    <div style="background-color:rgb(255,255,255)">
                        <a href="vista.reporte_excel_inmuebles_estado.php?&proyecto=<?php echo $proyecto ?>&proIni=<?php echo $proIni ?>&proFin=<?php echo $proFin ?>&manIni=<?php echo $manIni ?>&zonfin=<?php echo $zonfin ?>&manFin=<?php echo $manFin ?>&estIni=<?php echo $estIni ?>&estFin=<?php echo $estFin ?>&usoIni=<?php echo $usoIni ?>&actividad=<?php echo $act ?>&servicio=<?= $servicio?>">
                            <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                        <!--a href="vista.reporte_word_secrutmz.php">
                            <img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
                        <a href="vista.reporte_pdf_secrutmz.php">
                            <img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a-->
                    </div>
                </div>
            </div>
        </form>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Reporte Inmuebles Por Estado </div>
            </div>
        </div>
        <div class="datagrid" style="width:100%; height:280px">
            <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:280px">
                <thead>
                <tr>
                    <th style="border-right:1px solid #DEDEDE; text-align:center">SECTOR</th>
                    <th style="border-right:1px solid #DEDEDE; text-align:center">RUTA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ZONA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">C&Oacute;DIGO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">URBANIZACI&Oacute;N</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">DIRECCI&Oacute;N</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">CLIENTE</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">PROCESO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">CATASTRO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">MEDIDOR</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">SERIAL</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">EMPL</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">DIAM</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">USO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ACT</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">UNID</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">SUMINISTRO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">CONTRATO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ESTADO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">TARIFA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">FAC PENDIENTES</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">DEUDA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#000">CUPO BASICO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center; background-color:#A349A3; color:#000">CONSUMO MINIMO</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count      = 1;
                $s          = new Reportes();
                $registrosS = $s->obtenerInmEstado($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $usoIni, $act,$servicio);
                while (oci_fetch($registrosS)) {
                    $sector     = oci_result($registrosS, 'ID_SECTOR');
                    $ruta       = oci_result($registrosS, 'RUTA');
                    $zona       = oci_result($registrosS, 'ID_ZONA');
                    $codigo     = oci_result($registrosS, 'CODIGO_INM');
                    $urbaniza   = oci_result($registrosS, 'DESC_URBANIZACION');
                    $direccion  = oci_result($registrosS, 'DIRECCION');
                    $cliente    = oci_result($registrosS, 'ALIAS');
                    $proceso    = oci_result($registrosS, 'ID_PROCESO');
                    $catastro   = oci_result($registrosS, 'CATASTRO');
                    $medidor    = oci_result($registrosS, 'MEDIDOR');
                    $serial     = oci_result($registrosS, 'SERIAL');
                    $emplaza    = oci_result($registrosS, 'COD_EMPLAZAMIENTO');
                    $diametro   = oci_result($registrosS, 'DESC_CALIBRE');
                    $uso        = oci_result($registrosS, 'ID_USO');
                    $actividad  = oci_result($registrosS, 'DESC_ACTIVIDAD');
                    $unidades   = oci_result($registrosS, 'TOTAL_UNIDADES');
                    $suministro = oci_result($registrosS, 'DESC_SUMINISTRO');
                    $contrato   = oci_result($registrosS, 'ID_CONTRATO');
                    $estado     = oci_result($registrosS, 'ID_ESTADO');
                    $tarifa     = oci_result($registrosS, 'DESC_TARIFA');
                    $fac_pend   = oci_result($registrosS, 'FAC_PEND');
                    $deuda      = oci_result($registrosS, 'DEUDA');
                    $cup_bas    = oci_result($registrosS, 'CUPO_BASICO');
                    $cons_min   = oci_result($registrosS, 'CONSUMO_MINIMO');

                    echo "<tr>";
//                if($secini != $sector ){
                    //                    echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
                    //                    $secini = $sector;
                    //                }
                    //                else if($secini == $sector && $count == 1){
                    //                    echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
                    //                    $secini = $sector;
                    //                }
                    //                else{
                    //                    echo "<td align='center' style='border:none'></td>";
                    //                }
                    //                if($zonini != $zona){
                    //                    echo "<td align='center' style='border-top:1px solid #DEDEDE;  border-bottom:none; border-right:none'><b> $zona </b></td>";
                    //                    $zonini = $zona;
                    //                }
                    //                else if($zonini == $zona && $count ==1){
                    //                    echo "<td align='center' style='border-top:1px solid #DEDEDE;  border-bottom:none; border-right:none'><b> $zona </b></td>";
                    //                    $zonini = $zona;
                    //                }
                    //                else{
                    //                    echo "<td align='center' style='border:none; border-left:1px solid #DEDEDE'></td>";
                    //                }
                    echo "<td align='center'> $sector </td>";
                    echo "<td align='center'> $ruta </td>";
                    echo "<td align='center'> $zona </td>";
                    echo "<td align='center'> $codigo </td>";
                    echo "<td align='center'> $urbaniza </td>";
                    echo "<td align='center'> $direccion </td>";
                    echo "<td align='center'> $cliente </td>";
                    echo "<td align='center'> $proceso </td>";
                    echo "<td align='center'> $catastro </td>";
                    echo "<td align='center'> $medidor </td>";
                    echo "<td align='center'> $serial </td>";
                    echo "<td align='center'> $emplaza </td>";
                    echo "<td align='center'> $diametro </td>";
                    echo "<td align='center'> $uso </td>";
                    echo "<td align='center'> $actividad </td>";
                    echo "<td align='center'> $unidades </td>";
                    echo "<td align='center'> $suministro </td>";
                    echo "<td align='center'> $contrato </td>";
                    echo "<td align='center'> $estado </td>";
                    echo "<td align='center'> $tarifa </td>";
                    echo "<td align='center'> $fac_pend </td>";
                    echo "<td align='center'> $deuda </td>";
                    echo "<td align='center'>$cup_bas </td>";
                    echo "<td align='center'>$cons_min </td>";
                    echo "</tr>";
                    $count++;
                }
                oci_free_statement($registrosS);
                ?>
                </tr>
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

       /* $('#uso').on('change', function(event) {
            console.log('funciona');
            $.post('../datos/datos.actividades.php', {uso: uso}, function(data) {
                console.log(data);
            });
        });*/

        function setAct(){
            var uso = document.getElementById('uso').value;

            $.post('../datos/datos.actividades.php', {uso: uso}, function(data) {
                $('#actividad').html(data);
            });
        }
    </script>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

