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
    $secini = $_POST['secini'];
    $secfin = $_POST['secfin'];
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];

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
                height:16px;
                font-weight:normal;
            }
        </style>
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
    </head>
    <body>
    <form name="reporte_inmuebles" action="vista.reporte_inmuebles.php" method="post">
        <h3 class="panel-heading" style=" background-color:#A349A3; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Incorporaci&oacute;n de Inmuebles</h3>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Incorporaci&oacute;n de Inmuebles</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td width="20%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input" required><option></option>
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

                            <td width="39%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Incorporaci&oacute;n<br />
                                Inicial:&nbsp;&nbsp;<input type="date" name="fecini" value="<?php echo $fecini;?>" class="input" required />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="date" name="fecfin" value="<?php echo $fecfin;?>" class="input" required/>
                            </td>
                            <td width="26%" style=" border:1px solid #EEEEEE; text-align:center">Sectores<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="secini" value="<?php echo $secini;?>" size="3" class="input" maxlength="6" required/>&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="secfin" value="<?php echo $secfin;?>" size="3" class="input" maxlength="6" required/>
                            </td>
                            <td width="15%" style=" border:1px solid #EEEEEE; text-align:center">
                                <input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#A349A3; color:#A349A3;">
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
                        <a href="vista.reporte_excel_inmuebles.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&secfin=<?php echo $secfin?>&fecini=<?php echo $fecini?>&fecfin=<?php echo $fecfin?>">
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
                <div>Reporte Incorporaci&oacute;n de Inmuebles del <?php echo $fecini;?> al <?php echo $fecfin;?> - Sectores del <?php echo $secini;?> al <?php echo $secfin;?></div>
            </div>
        </div>
        <div class="datagrid" style="width:1120px; height:300px">
            <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
                <thead>
                <tr>
                    <th style="border-right:1px solid #DEDEDE; text-align:center">SECTOR</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">C&Oacute;DIGO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">DIRECCI&Oacute;N</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ID PROCESO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">SECTOR</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">RUTA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">NOMBRE</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">ESTADO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">FECHA SOLICITUD</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">USO</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">GERENCIA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">TARIFA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">CODIGO TARIFA</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $cont = 1;
                $s=new Reportes();
                $registros=$s->obtenerInmueblesInc ($proyecto, $secini, $secfin, $fecini, $fecfin);
                while (oci_fetch($registros)) {
                    $codigo = oci_result($registros, 'CODIGO_INM');
                    $direccion = oci_result($registros, 'DIRECCION');
                    $proceso = oci_result($registros, 'ID_PROCESO');
                    $sector = oci_result($registros, 'SECTOR');
                    $ruta = oci_result($registros, 'RUTA');
                    $nombre = oci_result($registros, 'ALIAS');
                    $estado = oci_result($registros, 'ID_ESTADO');
                    $fecha = oci_result($registros, 'FECHA_INICIO');
                    $sector = oci_result($registros, 'ID_SECTOR');
                    $uso = oci_result($registros, 'ID_USO');
                    $gerencia = oci_result($registros, 'ID_GERENCIA');
                    $tarifa = oci_result($registros, 'TARIFA');
                    $codtar = oci_result($registros, 'CODIGO_TARIFA');

                    echo "<tr>";
                    if($sector != $secini){
                        echo "<td align='left' style='border:none; border-top:1px solid #DEDEDE'><b>$sector</b></td>";
                        $secini = $sector;
                    }
                    else if ($sector == $secini && $cont == 1) echo "<td align='left' style='border:none; border-top:1px solid #DEDEDE'><b>$sector</b></td>";
                    else echo "<td align='left' style='border:none; border-right:1px solid #DEDEDE'></td>";
                    echo "<td align='center'>$codigo</td>";
                    echo "<td align='center'>$direccion</td>";
                    echo "<td align='center'>$proceso</td>";
                    echo "<td align='center'>$sector</td>";
                    echo "<td align='center'>$ruta</td>";
                    echo "<td align='center'>$nombre</td>";
                    echo "<td align='center'>$estado</td>";
                    echo "<td align='center'>$fecha</td>";
                    echo "<td align='center'>$uso</td>";
                    echo "<td align='center'>$gerencia</td>";
                    echo "<td align='center'>$tarifa</td>";
                    echo "<td align='center'>$codtar</td>";

                    echo "</tr>";
                    $cont++;
                }oci_free_statement($registros);
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
    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
