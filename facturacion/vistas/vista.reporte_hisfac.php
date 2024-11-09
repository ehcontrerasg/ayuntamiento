<?php
session_start();
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_hisfac.php';
require '../clases/class.consultaGeneral.php';
include '../../destruye_sesion_cierra.php';

//$coduser    = $_SESSION['codigo'];
/*$codsecure  = $_GET['codsecure'];
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
$metodo     = $_POST['metodo'];*/
$obs        = strtoupper($_POST['observacion']);
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
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../js/reporteHisfac.js?<?echo time()?>"></script>
<script type="text/javascript">

	$(function() {
    	$("#zonini").autocomplete({
        	source: "../datos/datos.zona2.php",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
            	$(".ui-autocomplete").css("z-index", 1000);
            }
		});
	});

	$(function() {
       	$("#zonfin").autocomplete({
           	source: "../datos/datos.zona2.php",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
               	$(".ui-autocomplete").css("z-index", 1000);
            }
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

</head>
<body>
<?php
if ($canper == '') {
    $canper = 3;
} else {
    $canper = $canper;
}

?>
<form id="reporte_hisfac" onsubmit="return false" >
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Hist&oacute;rico de Facturas</h3>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Hist&oacute;rico de Facturas</div>
        <div style="background-color:rgb(255,255,255)">
        	<table width="100%">
    			<tr>
    				<td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
						<select name="proyecto" class="input" required><option></option>
						<?php
$l         = new Reportes();
$registros = $l->seleccionaAcueducto();
while (oci_fetch($registros)) {
    $cod_proyecto   = oci_result($registros, 'ID_PROYECTO');
    $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO');
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

					<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Inmueble<br />
                        <input type="number" name="inm" id="inm" value="<?php echo $inm; ?>" class="input"  style="width:40px" min="1" max="10dd"/>
                    </td>

					<td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Grupo<br />
                        <select name="grupo" class="input" ><option></option>
                            <?php
$l         = new Reportes();
$registros = $l->obtener_grupos();
while (oci_fetch($registros)) {
    $cod_grupo   = oci_result($registros, 'CODIGO');
    $sigla_grupo = oci_result($registros, 'DESCRIPCION');
    if ($cod_grupo == $grupo) {
        echo "<option value='$cod_grupo' selected>$sigla_grupo</option>\n";
    } else {
        echo "<option value='$cod_grupo'>$sigla_grupo</option>\n";
    }

}
oci_free_statement($registros);
?>
                        </select>
                    </td>
					<td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Zonas<br />
						Desde:&nbsp;&nbsp;<input type="text" name="zonini" id="zonini" value="<?php echo $zonini; ?>" size="2" class="input" maxlength="3" required />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="text" name="zonfin" id="zonfin" value="<?php echo $zonfin; ?>" size="2" class="input" maxlength="3" required/>
			  	  	</td>

				  	<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">
                       	<input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#336699; color:#336699;">
                   	</td>
				</tr>
				<tr>
					<td width="18%" style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Proceso<br />
                        Desde:&nbsp;&nbsp;<input type="text" name="proini" id="proini" value="<?php echo $proini; ?>" size="10" class="input" maxlength="12"  />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="text" name="profin" id="profin" value="<?php echo $profin; ?>" size="10" class="input" maxlength="12" />
                    </td>

					<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Catastro<br />
                        Desde:&nbsp;&nbsp;<input type="text" name="catini" id="catini" value="<?php echo $catini; ?>" size="20" class="input" maxlength="22"  />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="text" name="catfin" id="catfin" value="<?php echo $catfin; ?>" size="20" class="input" maxlength="22" />
                    </td>

					<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Periodos<br />
						Cant. Periodos:&nbsp;&nbsp;
						<input type="number" name="canper" id="canper" value="<?php echo $canper; ?>" class="input" required style="width:40px" min="1" max="6"/>
						&nbsp;&nbsp;&nbsp;&nbsp;Periodo:&nbsp;&nbsp;
						<input type="number" name="perini" id="perini" value="<?php echo $perini; ?>" class="input" required style="width:60px" min="190001" max="205012"/>
       		  	  	</td>
       		  	  	<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Observaciones<br />

						&nbsp;&nbsp;&nbsp;&nbsp;Observación:&nbsp;&nbsp;
						<input type="text" name="observacion" id="observacion" class="input" style="width:60px"/>
       		  	  	</td>

				</tr>
        	</table>
			<table width="100%">
							<tr>
								<td width="17%" align="left" style=" border:1px solid #EEEEEE; text-align:center" ><b>Urbanizaci&oacute;n</b><br />
									<input type="text" id="urbaniza" name="urbaniza" value="<?echo $urbaniza ?>"  class="input" >
								</td>
							  	<td width="16%" align="left" style=" border:1px solid #EEEEEE; text-align:center"><b>Tipo V&iacute;a</b><br />
									<select name='tipovia' class='input'>
										<option value="" selected>Seleccione Tipo V&iacute;a...</option>
										<?php
$c    = new Consulta();
$stid = $c->seleccionaTipoVia();
while (oci_fetch($stid)) {
    $id_tvia  = oci_result($stid, 'ID_TIPO_VIA');
    $des_dvia = oci_result($stid, 'DESC_TIPO_VIA');
    if ($id_tvia == $tipovia) {
        echo "<option value='$id_tvia' selected>$des_dvia</option>\n";
    } else {
        echo "<option value='$id_tvia'>$des_dvia</option>\n";
    }

}
oci_free_statement($stid);
?>
									</select>
								</td>

                                <td width="35%" align="left" style=" border:1px solid #EEEEEE; text-align:center"><b>Metodo</b><br />
                                    <input type="radio" name="metodo" value="T" <?if ($metodo == 'T') {echo "checked";}?> checked>&nbsp;&nbsp;Todos&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="metodo" value="M" <?if ($metodo == 'M') {echo "checked";}?>>&nbsp;&nbsp;Medidos&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="metodo" value="N" <?if ($metodo == 'N') {echo "checked";}?>>&nbsp;&nbsp;No Medidos&nbsp;&nbsp;&nbsp;
                                </td>


                                <td width="35%" align="left" style=" border:1px solid #EEEEEE; text-align:center"><b>Estado Inmueble</b><br />
									<input type="radio" name="estado" value="T" <?if ($estado == 'T') {echo "checked";}?> checked>&nbsp;&nbsp;Todos&nbsp;&nbsp;&nbsp;
									<input type="radio" name="estado" value="A" <?if ($estado == 'A') {echo "checked";}?>>&nbsp;&nbsp;Activos&nbsp;&nbsp;&nbsp;
									<input type="radio" name="estado" value="I" <?if ($estado == 'I') {echo "checked";}?>>&nbsp;&nbsp;Inactivos&nbsp;&nbsp;&nbsp;
									<select name='estado_inm' class='input'>
										<option value="" selected>Seleccione Estado...</option>
										<?php
$c    = new Consulta();
$stid = $c->seleccionaEstado();
while (oci_fetch($stid)) {
    $id_estado  = oci_result($stid, 'ID_ESTADO');
    $des_estado = oci_result($stid, 'DESC_ESTADO');
    if ($id_estado == $estado_inm) {
        echo "<option value='$id_estado' selected>$des_estado</option>\n";
    } else {
        echo "<option value='$id_estado'>$des_estado</option>\n";
    }

}
oci_free_statement($stid);
?>
									</select>
								</td>
							</tr>
							<tr style="height:10px"></tr>
						</table>
        </div>
    </div>
</div>
</form>

<div id="divReporte" style="margin-top: 5px"></div>

<?php
/*if (isset($_REQUEST["Generar"])) {
    */?><!--
<form action="../../funciones/ficheroExcel.php?nomrepo=<?/*echo $nomrepo; */?>" method="post" target="_blank"  id="FormularioExportacion">
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:1220px">
		<div class="mDiv">
    		<div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
				<a href="vista.reporte_excel_hisfac.php?&proyecto=<?php /*echo $proyecto */?>&zonini=<?php /*echo $zonini */?>&zonfin=<?php /*echo $zonfin */?>&canper=<?php /*echo $canper */?>&perini=<?php /*echo $perini */?>&proini=<?php /*echo $proini */?>&profin=<?php /*echo $profin */?>&catini=<?php /*echo $catini */?>&catfin=<?php /*echo $catfin */?>&urbaniza=<?php /*echo $urbaniza */?>&tipovia=<?php /*echo $tipovia */?>&estado=<?php /*echo $estado */?>&estado_inm=<?php /*echo $estado_inm */?>&metodo=<?php /*echo $metodo */?>&grupo=<?php /*echo $grupo */?>&inm=<?php /*echo $inm */?>&observacion=<?php /*echo $obs */?>">
					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                <!--a href="vista.reporte_word_hisfac.php?&proyecto=<?php /*echo $proyecto */?>&zonini=<?php /*echo $zonini */?>&zonfin=<?php /*echo $zonfin */?>&canper=<?php /*echo $canper */?>&perini=<?php /*echo $perini */?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_hisfac.php?&proyecto=<?php /*echo $proyecto */?>&zonini=<?php /*echo $zonini */?>&zonfin=<?php /*echo $zonfin */?>&canper=<?php /*echo $canper */?>&perini=<?php /*echo $perini */?>">
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

</body>
</html>