<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.reportes_catastro.php';
    include '../../destruye_sesion.php';

    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $tipo = $_POST['tipo'];
    $secini = $_POST['secini'];
    $secfin = $_POST['secfin'];
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];
    $usoini = $_POST['usoini'];
    $usofin = $_POST['usofin'];

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
    <body>
    <form name="reporte_altas_bajas" action="vista.reporte_altas_bajas.php" method="post">
        <h3 class="panel-heading" style=" background-color:#A349A3; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Altas y Bajas de Inmuebles</h3>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Altas y Bajas de Inmuebles</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%" >
                        <tr>
                            <td width="12%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input" required>
                                    <option></option>
                                    <?php
                                    $l=new Reportes();
                                    $registros=$l->obtenerProyecto($coduser);
                                    while (oci_fetch($registros)) {
                                        $cod_proyecto = oci_result($registros, 'CODIGO') ;
                                        $sigla_proyecto = oci_result($registros, 'DESCRIPCION') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="12%" style=" border:1px solid #EEEEEE; text-align:center">Tipo<br />
                                <select name="tipo" class="input" required>
                                    <option value=""></option>
                                    <?php
                                    $l=new Reportes();
                                    $registros=$l->seleccionaTipo();
                                    while (oci_fetch($registros)) {
                                        $cod_tipo = oci_result($registros, 'ID_TIPO') ;
                                        $des_tipo = oci_result($registros, 'DESC_TIPO') ;
                                        if($cod_tipo == $tipo) echo "<option value='$cod_tipo' selected>$des_tipo</option>\n";
                                        else echo "<option value='$cod_tipo'>$des_tipo</option>\n";
                                    }oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="36%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Solicitud<br />
                                Inicial:&nbsp;&nbsp;
                                <input type="date" name="fecini" value="<?php echo $fecini;?>" class="input"  />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="date" name="fecfin" value="<?php echo $fecfin;?>" class="input" />
                            </td>
                            <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Sectores<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="secini" value="<?php echo $secini;?>" size="3" class="input" maxlength="6" />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="secfin" value="<?php echo $secfin;?>" size="3" maxlength="6" class="input" />
                            </td>
                            <td width="16%" rowspan="2" style=" border:1px solid #EEEEEE; text-align:center">
                                <input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO"
                                       style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#A349A3; color:#A349A3;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" border:1px solid #EEEEEE; text-align:center">Usos<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="usoini" value="<?php echo $usoini;?>" size="3" class="input" maxlength="6" />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="usofin" value="<?php echo $usofin;?>" size="3" maxlength="6" class="input" />
                            </td>
                            <td colspan="2" style=" border:1px solid #EEEEEE">
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
        <form action="../../funciones/ficheroExcel.php?nomrepo=<? echo $nomrepo;?>" method="post" target="_blank"  id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <div class="flexigrid" style="width:1120px">
                <div class="mDiv">
                    <div>Exportar a:</div>
                    <div style="background-color:rgb(255,255,255)">
                        <a href="vista.reporte_excel_altas_bajas.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&secfin=<?php echo $secfin?>&fecini=<?php echo $fecini?>&fecfin=<?php echo $fecfin?>&usoini=<?php echo $usoini?>&usofin=<?php echo $usofin?>&tipo=<?php echo $tipo?>">
                            <img src="../../images/excel/Excel.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
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
                <div>Reporte Alta y Baja de Inmuebles </div>
            </div>
        </div>
        <div class="datagrid" style="width:1120px; height:280px">
            <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:280px">
                <thead>
                <tr>
                    <th style="border-right:1px solid #DEDEDE; text-align:center">SECTOR</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ZONA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">C&Oacute;DIGO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">DIRECCION</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">CLIENTE</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">PROCESO</th>
                    <?php
                    if($tipo == 'A'){
                        echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center">PERIODO ALTA</th>';
                        echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center">FECHA ALTA</th>';
                    }
                    if($tipo == 'B'){
                        echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center">PERIODO BAJA</th>';
                        echo '<th style="border:none; border-right:1px solid #DEDEDE; text-align:center">FECHA BAJA</th>';
                    }
                    ?>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ESTADO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">USO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">TARIFA</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $s=new Reportes();
                $registrosS=$s->obtenerAltas ($proyecto, $tipo, $fecini, $fecfin, $secini, $secfin, $usoini, $usofin);
                while(oci_fetch($registrosS)){
                    $sector = oci_result($registrosS, 'ID_SECTOR');
                    $zona = oci_result($registrosS, 'ID_ZONA');
                    $codigo = oci_result($registrosS, 'CODIGO_INM');
                    $urbaniza = oci_result($registrosS, 'DESC_URBANIZACION');
                    $direccion = oci_result($registrosS, 'DIRECCION');
                    $cliente = oci_result($registrosS, 'ALIAS');
                    $proceso = oci_result($registrosS, 'ID_PROCESO');
                    $peralta = oci_result($registrosS, 'PER_ALTA');
                    $fecalta = oci_result($registrosS, 'FEC_ALTA');
                    $perbaja = oci_result($registrosS, 'PER_BAJA');
                    $fecbaja = oci_result($registrosS, 'FEC_BAJA');
                    $estado = oci_result($registrosS, 'ID_ESTADO');
                    $uso = oci_result($registrosS, 'ID_USO');
                    $tarifa = oci_result($registrosS, 'DESC_TARIFA');

                    echo "<tr>";
                    if($secini != $sector){
                        echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> $sector </b></td>";
                        $secini = $sector;
                    }
                    else{
                        echo "<td align='center' style='border:none'></td>";
                    }
                    if($zonini != $zona){
                        echo "<td align='center' style='border-top:1px solid #DEDEDE;  border-bottom:none; border-right:none'><b> $zona </b></td>";
                        $zonini = $zona;
                    }
                    else{
                        echo "<td align='center' style='border:none; border-left:1px solid #DEDEDE'></td>";
                    }
                    echo "<td align='center'> $codigo </td>";
                    echo "<td align='center'> $urbaniza $direccion </td>";
                    echo "<td align='center'> $cliente </td>";
                    echo "<td align='center'> $proceso </td>";
                    if($tipo == 'A'){
                        echo "<td align='center'> $peralta </td>";
                        echo "<td align='center'> $fecalta </td>";
                    }
                    else if($tipo == 'B'){
                        echo "<td align='center'> $perbaja </td>";
                        echo "<td align='center'> $fecbaja </td>";
                    }
                    echo "<td align='center'> $estado </td>";
                    echo "<td align='center'> $uso </td>";
                    echo "<td align='center'> $tarifa </td>";
                    echo "</tr>";
                }oci_free_statement($registrosS);
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
    </script>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

