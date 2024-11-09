<?php
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_difcortes.php';
//include '../../destruye_sesion_cierra.php';

//$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];
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
<form name="dif_cortereco" action="vista.diferidos_corte_reco.php" method="post" >
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Diferidos de Corte y Reconexi&oacute;n</h3>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Diferidos Corte y Reconexi&oacute;n</div>
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
					<td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Generaci&oacute;n<br />
						Desde:&nbsp;&nbsp;<input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>" class="input" required />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>" class="input"  required/>
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
				<a href="vista.reporte_excel_difcorte.php?&proyecto=<?php echo $proyecto?>&fecini=<?php echo $fecini?>&fecfin=<?php echo $fecfin?>">
					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                <a href="vista.reporte_word_difcorte.php?&proyecto=<?php echo $proyecto?>&fecini=<?php echo $fecini?>&fecfin=<?php echo $fecfin?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_difcorte.php?&proyecto=<?php echo $proyecto?>&fecini=<?php echo $fecini?>&fecfin=<?php echo $fecfin?>">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
            </div>
    	</div>
	</div>
</form>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Diferidos Corte y Reconexi&oacute;n</div>
    </div>
</div>
<div class="datagrid" style="width:1220px; height:350px; border:none">
	<table class="scroll" border="1" bordercolor="#CCCCCC" style="height:350px">
		<tr>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">C&oacute;digo</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Fecha Pago</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Concepto</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Forma de Pago</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Usuario</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Importe</th>
			<th style="border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF; font-size:11px">Inmueble</th>
		</tr>
		<?php
		$c=new ReportesDifCortes();
		$registros=$c->DatosDiferidosCorte($proyecto, $fecini, $fecfin);
		while (oci_fetch($registros)) {
			$codigodif = oci_result($registros, 'CODIGO');
			$fechadif = oci_result($registros, 'FECPAGO');
			$concepdif = oci_result($registros, 'CONCEPTO');
			$descdif = oci_result($registros, 'DESC_SERVICIO');
			$formdif = oci_result($registros, 'ID_FORM_PAGO');
			$descformdif = oci_result($registros, 'DESCRIPCION');
			$usuariodif = oci_result($registros, 'LOGIN');
			$importedif = oci_result($registros, 'VALOR_DIFERIDO');
			$inmuebledif = oci_result($registros, 'INMUEBLE');
		?>
		<tr>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $codigodif?>
			</td>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $fechadif?>
			</td>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $concepdif.' - '.$descdif?>
			</td>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $formdif.' - '.$descformdif?>
			</td>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $usuariodif?>
			</td>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $importedif?>
			</td>
			<td style="border:1px solid #DEDEDE; background-color:#FFFFFF; color:#333333; font-size:11px">
				<?php echo $inmuebledif?>
			</td>

				</tr>
			<?php
		}oci_free_statement($registros);
		?>
</table>
</div>
<?php
}
?>
</body>
</html>