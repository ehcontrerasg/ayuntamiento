<?php
session_start();
include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_plan_lec.php';
include '../../destruye_sesion_cierra.php';

$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];
$perini = $_POST['perini'];
$secini = $_POST['secini'];
$secfin = $_POST['secfin'];
$fecini = $_POST['fecini'];
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
	$(document).ready(function() {
		$(".botonExcel").click(function(event) {
			$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
			$("#FormularioExportacion").submit();
	});
	});
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
<form name="planifica_lecturas" action="vista.reporte_plan_lec.php" method="post">
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Planificaci&oacute;n Toma de Lecturas</h3>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Planificaci&oacute;n Toma de Lectura</div>
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
					  	<td width="20%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br />
							<input type="text" name="perini" id="perini" value="<?php echo $perini;?>" class="input" size="5" maxlength="6" required/>
   			  	  	  </td>
					  	<td width="20%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Asignaci&oacute;n<br />
							<input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>" class="input"/>
		  	  		  </td>
						<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Sector<br />
							Desde:&nbsp;&nbsp;<input type="text" name="secini" id="secini" value="<?php echo $secini;?>" size="2" class="input" maxlength="2" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							Hasta:&nbsp;&nbsp;<input type="text" name="secfin" id="secfin" value="<?php echo $secfin;?>" size="2" class="input" maxlength="2" />
			  	  	  </td>
					  	<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">
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
				<a href="vista.reporte_excel_planlec.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&secfin=<?php echo $secfin?>&perini=<?php echo $perini?>&fecini=<?php echo $fecini?>">
					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                <a href="vista.reporte_word_planlec.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&secfin=<?php echo $secfin?>&perini=<?php echo $perini?>&fecini=<?php echo $fecini?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_planlec.php?&proyecto=<?php echo $proyecto?>&secini=<?php echo $secini?>&secfin=<?php echo $secfin?>&perini=<?php echo $perini?>&fecini=<?php echo $fecini?>">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
            </div> 
    	</div>
	</div>
</form>  
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Reporte Planificaci&oacute;n Toma de Lecturas</div>
    </div>
</div>
<div class="datagrid" style="width:1220px; height:300px">
	<table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
    	<thead>
        	<tr>
				<th style="border-right:1px solid #DEDEDE; text-align:center" >N&deg;</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" >DISTRIBUIDOR</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" >ZONA</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" >RUTAS PLANIFICADAS</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" >CANTIDAD</th>
            </tr>
		</thead>
        <tbody>
			<?php
			$c=new Reportes_Plan_Lec();
			$registros=$c-> ObtienePlanificacionRuta($perini,$proyecto,$secini,$secfin,$fecini);
			$item = 0;
			
			while (oci_fetch($registros)) {
				$canrutas = '';
				$cod_lector = oci_result($registros, 'COD_LECTOR');
				$lector = oci_result($registros, 'USUARIO');
				$zona = oci_result($registros, 'ID_ZONA');
				$cantidad = oci_result($registros, 'CANTIDAD');
				$h=new Reportes_Plan_Lec();
				$registrosC=$h->ObtieneDetalleRutas($cod_lector, $zona, $perini);
				while (oci_fetch($registrosC)) {
					$ruta = oci_result($registrosC, 'RUTA');
					$canrutas .= $ruta.', ';
				}oci_free_statement($registrosC);
				$canrutas = substr($canrutas,0,strlen($canrutas)-2);
				$item++;
				echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE' <b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'><b>$lector</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'><b>$zona</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'><b>$canrutas</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE'><b>$cantidad</b></td>";
				$totallec += $cantidad;
			}oci_free_statement($registros);
			echo "<tr>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;' colspan='2'><b>Total Distribuidores: $item</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;' colspan='2'><b>Total lecturas:</b></td>";
			echo "<td align='left' style='border-color:#DEDEDE; background-color:#990000; color:#FFFFFF;'><b>$totallec</b></td>";
			echo "</tr>";
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