
<?php
session_start();
include '../clases/class.reportes_lectura.php';
include '../../destruye_sesion_cierra.php';

$coduser = $_SESSION['codigo'];


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
    <script type="text/javascript" src="../js/reporteEmiteFac.js?<?echo time()?>"></script>
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
<form id="reporte_emitefac" onsubmit="return false" >
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Generaci&oacute;n Archivo Plano de Facturas</h3>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Filtros Generaci&oacute;n Archivo Plano de Facturas</div>
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
					<td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Zona<br />
						<input type="text" name="zonini" id="zonini" value="<?php echo $zonini;?>" size="2" class="input" maxlength="3" required />
			  	  	</td>
					<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br />
						<input type="number" name="perini" id="perini" value="<?php echo $perini;?>" class="input" required style="width:60px" min="190001" max="205012"/>
       		  	  	</td>
                    <td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Uso<br />
                        <input type="text" name="usoini" id="usoini" value="<?php echo $usoini;?>" size="2" class="input" maxlength="2" />
                    </td>
					<td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Tipo<br />
						<select name="tipo" class="input" required placeholder="Seleccione Tipo..."><option></option>
						<option value="T">Todos</option>
						<option value="A">Azules</option>
						<option value="R">Rojas</option>
						</select>
					</td>	
				  	<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">
                       	<input type="submit" value="Generar Archivo" name="Generar" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#336699; color:#336699;">
                   	</td>
				</tr>
        	</table>
        </div>
    </div>
</div>
</form>
<div id="divReporte" style="margin-top: 5px">
    <a id="descargaReporte" download style="display: none">Volver a descargar reporte</a>
</div>
</body>
</html>