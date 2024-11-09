<?php
session_start();
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_fac_ruta.php';
include '../../destruye_sesion_cierra.php';

$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];
$secini = $_POST['secini'];
$perini = $_POST['perini'];
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
<form name="fac_x_ruta" action="vista.medidores_x_ruta.php" method="post" >
    <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Total De Medidores Por Ruta</h3>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Filtros de B&uacute;squeda Medidores Por Ruta</div>
            <div style="background-color:rgb(255,255,255)">
                <table width="100%">
                    <tr>
                        <td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
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
                        <td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br />
                            <input type="number" name="perini" id="perini" value="<?php echo $perini;?>" class="input" style="width:60px" required min="190001" max="205012"/>
                        </td>
                        <td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Sector<br />
                            <input type="text" name="secini" id="secini" value="<?php echo $secini;?>" class="input" size="4" maxlength="3" required />
                        </td>

                        <td width="22%" style=" border:1px solid #EEEEEE; text-align:center">
                            <input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#336699; color:#336699;">
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
        <div class="flexigrid" style="width:1220px">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <a href="vista.reporte_excel_medidores_x_ruta.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&perini=<?php echo $perini?>">
                        <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                    <a href="vista.reporte_word_medidores_x_ruta.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&perini=<?php echo $perini?>">
                        <img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
                    <a href="vista.reporte_pdf_medidores_x_ruta.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&perini=<?php echo $perini?>">
                        <img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
                </div>
            </div>
        </div>
    </form>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Total de Medidores Por Ruta</div>
        </div>
    </div>
    <div class="datagrid" style="width:1220px; height:350px; border:none">
        <table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
            <tr>
                <th colspan="3" style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Sector <?php echo $secini;?> Periodo <?php echo $perini?></th>
            </tr>
            <tr>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ruta</th>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad Medidores</th>
            </tr>
            <?php
            $item = 0;
            $totalfac = 0;
            $c=new Reportes_Fac_Ruta();
            $registros=$c->obtieneRutasCantidadMed($proyecto,$secini,$perini);
            while (oci_fetch($registros)) {
                $item ++;
                $ruta = oci_result($registros, 'ID_RUTA');
                $cantidad_fac = oci_result($registros, 'CANTIDAD');
                echo "<tr>";
                echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
                echo "<td align='left' style='border-color:#DEDEDE'>$ruta</td>";
                echo "<td align='left' style='border-color:#DEDEDE'>$cantidad_fac</td>";
                echo "</tr>";
                $totalfac += $cantidad_fac;
            }oci_free_statement($registros);
            echo "<tr>";
            echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
            echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
            echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalfac</b></td>";
            echo "</tr>";
            ?>
        </table>
    </div>
    <?php
}
?>
</body>
</html>