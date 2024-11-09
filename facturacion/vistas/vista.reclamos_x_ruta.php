<?php
session_start();
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_reclamo_ruta.php';
include '../../destruye_sesion_cierra.php';

$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];

$zonini = $_POST['zonini'];
$perini = $_POST['perini'];
$secini = $_POST['secini'];
$perini = $_POST['perini'];
$motivo = $_POST['motivo'];
$diagnostico = $_POST['diagnostico'];

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
<form name="fac_x_ruta" action="vista.reclamos_x_ruta.php" method="post" >
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Total De Reclamos Por Ruta</h3>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Reclamos Por Ruta</div>
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
					<td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Sector<br />
						<input type="text" name="secini" id="secini" value="<?php echo $secini;?>" class="input" size="4" maxlength="2" />
			  	  	</td>
					<td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br />
						<input type="number" name="perini" id="perini" value="<?php echo $perini;?>" class="input" required style="width:60px" min="190001" max="210012"/>
       		  	  	</td>
                    <td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Motivo<br />
                        <select name="motivo" class="input" required><option></option>
                            <?php
                            $l=new Reportes_Rec_Ruta();
                            $registros=$l->obtieneMotivosReclamosFac();
                            while (oci_fetch($registros)) {
                                $cod_reclamo = oci_result($registros, 'ID_MOTIVO_REC') ;
                                $des_reclamo = oci_result($registros, 'DESC_MOTIVO_REC') ;
                                if($cod_reclamo == $motivo) echo "<option value='$cod_reclamo' selected>$cod_reclamo - $des_reclamo</option>\n";
                                else echo "<option value='$cod_reclamo'>$cod_reclamo - $des_reclamo</option>\n";
                            }oci_free_statement($registros);
                            ?>
                        </select>
                    </td>
                    <td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Diagnostico<br />
                        <select name="diagnostico" id="diagnostico" class="input"><option></option>
                            <?php
                            $l=new Reportes_Rec_Ruta();
                            $registros=$l->obtieneDiagnosticoReclamo();
                            while (oci_fetch($registros)) {
                                $cod_diagnostico = oci_result($registros, 'ID_DIAGNOSTICO') ;
                                $des_diagnostico = oci_result($registros, 'DESC_DIAGNOSTICO') ;
                                if($cod_diagnostico == $diagnostico) echo "<option value='$cod_diagnostico' selected>$des_diagnostico</option>\n";
                                else echo "<option value='$cod_diagnostico'>$des_diagnostico</option>\n";
                            }oci_free_statement($registros);
                            ?>
                        </select>
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
<!--form action="../../funciones/ficheroExcel.php?nomrepo=<? echo $nomrepo;?>" method="post" target="_blank"  id="FormularioExportacion">
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:1220px">
		<div class="mDiv">
    		<div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
				<a href="vista.reporte_excel_rec_x_ruta.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                <a href="vista.reporte_word_rec_x_ruta.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_rec_x_ruta.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
            </div> 
    	</div>
	</div>
</form>  
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Total de Facturas Por Ruta</div>
				<a href="vista.reporte_excel_rec_x_ruta.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $secini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                <a href="vista.reporte_word_rec_x_ruta.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $secini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_rec_x_ruta.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $secini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
            </div>
    	</div>
	</div>
</form-->
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Total de Reclamos Por Ruta Motivo <?php echo $motivo;?></div>
    </div>
</div>
<div class="datagrid" style="width:1220px; height:350px; border:none">
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Sector</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ruta</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Inmueble</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Fecha Reclamo</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ultima Fecha<br>Asignación</th>
            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ultimo Operario<br>Asignación</th>
            <?php
            if($motivo == 10 || $motivo == 41) {
                ?>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ultimo
                    Fecha<br>Entrega
                </th>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">
                    Operario<br>Entrega
                </th>
                <?php
            }
            if($motivo == 17) {
                ?>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Ultima
                    Fecha<br>Lectura
                </th>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">
                    Operario<br>Lectura
                </th>
                <?php
            }
            ?>

            <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Descripción del Reclamo</th>
		</tr>
		<?php
		$item = 0;
		$totalfac = 0;
		$totalrec = 0;
		$c=new Reportes_Rec_Ruta();
		$registros=$c->obtieneRutasCantidadRec($proyecto,$secini,$perini,$motivo,$diagnostico);
		while (oci_fetch($registros)) {
			$item ++;
			$sector = oci_result($registros, 'ID_SECTOR');
			$ruta = oci_result($registros, 'ID_RUTA');
			$inmueble = oci_result($registros, 'COD_INMUEBLE');
			$fecrec = oci_result($registros, 'FECHA_RECLAMO');
			$desc = oci_result($registros, 'DESCRIPCION');
			$fecasig = oci_result($registros, 'ULTIMA_FECHA_ASIGNA');
            $operasig = oci_result($registros, 'ASIGNADO_A');
            $fecent = oci_result($registros, 'ULTIMA_FECHA_ENTREGA');
			$operario = oci_result($registros, 'ENTREGADO_POR');
			echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$sector</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$ruta</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$inmueble</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$fecrec</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$fecasig</td>";
                echo "<td align='left' style='border-color:#DEDEDE'>$operasig</td>";
                echo "<td align='left' style='border-color:#DEDEDE'>$fecent</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$operario</td>";
				echo "<td align='left' style='border-color:#DEDEDE'>$desc</td>";
			echo "</tr>";
			$totalfac += $cantidad_fac;
			$totalrec += $cantidad_rec;
		}oci_free_statement($registros);
		echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";

			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;' colspan='9'><b>$item</b></td>";
		echo "</tr>";
		?>
</table>
</div>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Total de Reclamos Motivo <?php echo $motivo;?> Por Operario</div>
        </div>
    </div>
    <div class="datagrid" style="width:1220px; height:350px; border:none">
        <table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
            <tr>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">N&deg;</th>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Nombre operario</th>
                <th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Cantidad</th>
            </tr>
            <?php
            $item = 0;
            $totalrec = 0;
            $c=new Reportes_Rec_Ruta();
            $registros=$c->obtieneCantidadRecOperario($proyecto,$secini,$perini,$motivo,$diagnostico);
            while (oci_fetch($registros)) {
                $item ++;
                $cantidadrec = oci_result($registros, 'CANTIDAD');
                $nombreope = oci_result($registros, 'OPERARIO');
                if($nombreope <> 'NO EJECUTADO'){
                    echo "<tr>";
                    echo "<td align='center' style='border-color:#DEDEDE'><b>$item</b></td>";
                    echo "<td align='left' style='border-color:#DEDEDE'>$nombreope</td>";
                    echo "<td align='left' style='border-color:#DEDEDE'>$cantidadrec</td>";
                    echo "</tr>";
                }
                else{
                    echo "<tr>";
                    echo "<td align='center' style='border-color:#DEDEDE; background-color: #dca7a7; color: #6f2c2c'><b>$item</b></td>";
                    echo "<td align='left' style='border-color:#DEDEDE; background-color: #dca7a7; color: #6f2c2c'><b>$nombreope</b></td>";
                    echo "<td align='left' style='border-color:#DEDEDE; background-color: #dca7a7; color: #6f2c2c'><b>$cantidadrec</b></td>";
                    echo "</tr>";
                }

                //$totalfac += $cantidad_fac;
                $totalrec += $cantidadrec;
            }oci_free_statement($registros);
            echo "<tr>";
            echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>Totales</b></td>";
            echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$item</b></td>";
            echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totalrec</b></td>";
            echo "</tr>";
            ?>
        </table>
    </div>

<?php
}
?>
</body>
</html>