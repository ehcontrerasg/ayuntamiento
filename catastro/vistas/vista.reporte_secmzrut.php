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
    $rutini = $_POST['rutini'];
    $rutfin = $_POST['rutfin'];

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
            }
        </style>
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
    </head>
    <body>
    <form name="reporte_secmzrut" action="vista.reporte_secmzrut.php" method="post">
        <h3 class="panel-heading" style=" background-color:#A349A3; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Sector, Ruta y Manzana</h3>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Sector, Ruta y Manzana</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td width="23%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input" required><option></option>
                                    <?php
                                    $l=new Reportes();
                                    $registros=$l->seleccionaAcueducto();
                                    while (oci_fetch($registros)) {
                                        $cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;
                                        $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>

                            <td width="29%" style=" border:1px solid #EEEEEE; text-align:center">Sectores<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="secini" value="<?php echo $secini;?>" size="3" maxlength="6" class="input" required />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="secfin" value="<?php echo $secfin;?>" size="3" maxlength="6" class="input" required/>
                            </td>
                            <td width="29%" style=" border:1px solid #EEEEEE; text-align:center">Rutas<br />
                                Inicial:&nbsp;&nbsp;<input type="text" name="rutini" value="<?php echo $rutini;?>" size="3" class="input" maxlength="6" required />&nbsp;&nbsp;&nbsp;&nbsp;
                                Final:&nbsp;&nbsp;<input type="text" name="rutfin" value="<?php echo $rutfin;?>" size="3" class="input" maxlength="6" required/>
                            </td>
                            <td width="19%" style=" border:1px solid #EEEEEE; text-align:center">
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
                        <a href="vista.reporte_excel_secrutmz.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&secfin=<?php echo $secfin?>&rutini=<?php echo $rutini?>&rutfin=<?php echo $rutfin?>">
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
                <div>Reporte Sector, Ruta y Manzana </div>
            </div>
        </div>
        <div class="datagrid" style="width:1120px; height:300px">
            <table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
                <thead>
                <tr>
                    <th style="border-right:1px solid #DEDEDE; text-align:center">SECTOR</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">RUTA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">MANZANA</th>
                    <th style="border:none; border-right:1px solid #DEDEDE; text-align:center">N&deg; INMUEBLES</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $contsec = 1;
                $countsec = 1;
                $s=new Reportes();
                $registrosS=$s->obtenerSectores ($proyecto, $secini, $secfin);
                while (oci_fetch($registrosS)) {
                    $sector = oci_result($registrosS, 'ID_SECTOR');
                    echo "<tr>";
                    echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none; border-right:none'><b> SECTOR $sector </b></td>";
                    $r=new Reportes();
                    $registrosR=$r->obtenerRutas ($proyecto, $sector, $rutini, $rutfin);
                    $cantmzsec = 0;
                    $cantinmsec= 0;
                    $cantrutas=0;
                    while (oci_fetch($registrosR)) {
                        $ruta = oci_result($registrosR, 'ID_RUTA');
                        if($countsec != $contsec) echo "<td align='left' style='border:none'></td>";
                        echo "<td align='center' style='border-top:1px solid #DEDEDE; border-bottom:none;'><b> RUTA $ruta </b></td>";
                        $m=new Reportes();
                        $registrosM=$m->obtenerRegistros ($proyecto, $sector, $ruta);
                        $countsec = 1;
                        $cantmzrut = 0;
                        $cantinmrut = 0;
                        while (oci_fetch($registrosM)) {
                            $manzana = oci_result($registrosM, 'MANZANA');
                            $inmuebles = oci_result($registrosM, 'CANTIDAD');
                            if($countsec != $contsec) echo "<td align='left' style='border:none; border-right:1px solid #DEDEDE'></td> <td align='left' style='border:none'></td>";
                            echo "<td align='center'> $manzana </td>";
                            echo "<td align='center'> $inmuebles </td>";
                            echo "</tr>";
                            $countsec++;
                            $cantmzrut++;
                            $cantinmrut += $inmuebles;
                            $cantmzsec++;
                            $cantinmsec += $inmuebles;
                        }oci_free_statement($registrosM);
                        echo "<tr>";
                        echo "<td align='center' style='border:none;'><b></b></td>";
                        echo "<td align='center' style='background-color:#DEDEDE; color:#000000'><b>TOTAL RUTA $ruta</b></td>";
                        echo "<td align='center' style='background-color:#DEDEDE; color:#000000'><b>$cantmzrut</b></td>";
                        echo "<td align='center' style='background-color:#DEDEDE; color:#000000'<b>$cantinmrut</b></td>";
                        echo "</tr>";
                        $cantrutas++;
                    }oci_free_statement($registrosR);
                    echo "</tr>";
                    //$contsec++;
                    $contsec = 1;
                    $countsec = 1;
                    echo "<tr>";
                    echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>TOTAL SECTOR $sector</b></td>";
                    echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>$cantrutas</b></td>";
                    echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>$cantmzsec</b></td>";
                    echo "<td align='center' style='background-color:#A349A3; color:#ffffff'><b>$cantinmsec</b></td>";
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

