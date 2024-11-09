<?php
session_start();
include '../clases/class.reportes_lectura.php';
include '../../destruye_sesion_cierra.php';

$coduser = $_SESSION['codigo'];
$codsecure = $_GET['codsecure'];
$proyecto = $_POST['proyecto'];
$zonini = $_POST['zonini'];
$zonfin = $_POST['zonfin'];
$lecini = $_POST['lecini'];
//$lecfin = $_POST['lecfin'];
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
	
	$(function() {
       	$("#lecini").autocomplete({
           	source: "../datos/datos.lectores.php",
            minLength: 0,
            html: true,
            open: function(event, ui)
            {
               	$(".ui-autocomplete").css("z-index", 1000);
            }
        });
	});
	
/*	$(function() {
       	$("#lecfin").autocomplete({
           	source: "../datos/datos.lectores.php",
            minLength: 0,
            html: true,
            open: function(event, ui)
            {
               	$(".ui-autocomplete").css("z-index", 1000);
            }
        });
	});
	*/
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
<form name="reporte_hiscon" action="vista.reporte_hiscon.php" method="post">
<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Reporte Hist&oacute;rico de Consumos</h3>
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Reporte Hist&oacute;rico de Consumos</div>
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
						<td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Zonas<br />
							Desde:&nbsp;&nbsp;<input type="text" name="zonini" id="zonini" value="<?php echo $zonini;?>" size="2" class="input" maxlength="3" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							Hasta:&nbsp;&nbsp;<input type="text" name="zonfin" id="zonfin" value="<?php echo $zonfin;?>" size="2" class="input" maxlength="3" />
				  	  </td>
                        <td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Lectores<br />
							<input type="text" name="lecini" id="lecini" value="<?php echo $lecini;?>" class="input" size="10" maxlength="12" />
       			  	  </td>
                      	<td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br />
							<input type="text" name="perini" id="perini" value="<?php echo $perini;?>" class="input" size="5" maxlength="6" required/>
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
				<a href="vista.reporte_excel_hiscon.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&zonfin=<?php echo $zonfin?>&lecini=<?php echo $lecini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                <a href="vista.reporte_word_hiscon.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&zonfin=<?php echo $zonfin?>&lecini=<?php echo $lecini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<!--a href="vista.reporte_pdf_hiscon.php?&proyecto=<?php echo $proyecto?>&zonini=<?php echo $zonini?>&zonfin=<?php echo $zonfin?>&lecini=<?php echo $lecini?>&perini=<?php echo $perini?>">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a-->
            </div> 
    	</div>
	</div>
</form>  
<div class="flexigrid" style="width:1220px">
	<div class="mDiv">
    	<div>Reporte Hist&oacute;rico de Consumos</div>
    </div>
</div>
<div class="datagrid" style="width:1220px; height:300px">
	<?php
	$c=new Reportes();
	$registros=$c->seleccionaCantObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
	while (oci_fetch($registros)) {
		$cantob = oci_result($registros, 'CANTIDAD');
	}oci_free_statement($registros);
	?>
	<table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
    	<thead>
        	<tr>
				<th style="border-right:1px solid #DEDEDE; text-align:center" rowspan="2">N&deg;</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="2">ZONA</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="2">LECTOR</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" colspan="<?php echo $cantob;?>">OBSERVACIONES</th>
				<th style="border:none; border-right:1px solid #DEDEDE; text-align:center" rowspan="2">TOTALES</th>
            </tr>
        	<tr>
				<?php
				$c=new Reportes();
				$registros=$c->seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
				while (oci_fetch($registros)) {
					$observacion = oci_result($registros, 'OBSERVACION_ACTUAL');
					echo "<th style='border:none; border-right:1px solid #DEDEDE'>$observacion</th>";
				} unset($observacion);
				oci_free_statement($registros);
				?>
      	  	</tr>
		</thead>
        <tbody>
			<?php
			$c=new Reportes();
			$registros=$c->ObtieneZonasObslec($perini,$zonini,$zonfin,$lecini,$proyecto);
			$item = 1;
			$cantlectores = 0;
			while (oci_fetch($registros)) {
				$zona = oci_result($registros, 'ID_ZONA');
				$h=new Reportes();
				$registrosC=$h->ObtieneCantLector($perini,$proyecto,$zona,$lecini);
				while (oci_fetch($registrosC)) {
					$cantlec = oci_result($registrosC, 'CANTIDAD');
				}oci_free_statement($registrosC);
				echo "<tr>";
				echo "<td align='center' style='border-color:#DEDEDE' rowspan='$cantlec'><b>$item</b></td>";
				echo "<td align='left' style='border-color:#DEDEDE' rowspan='$cantlec'><b>$zona</b></td>";
				$d=new Reportes();
				$registrosR=$d->ObtieneLectorObslec($perini,$proyecto,$zona,$lecini);
				while (oci_fetch($registrosR)) {
					$lector = oci_result($registrosR, 'COD_LECTOR_ORI');
					$nomlector = oci_result($registrosR, 'NOM_USR');
					$apelector = oci_result($registrosR, 'APE_USR');
					$cantlectores++;
					echo "<td align='center' style='border-color:#DEDEDE'><b>$lector - $nomlector $apelector</b></td>";
					$p=new Reportes();
					$registrosM=$p->seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
					$cantotope = 0;
					while (oci_fetch($registrosM)) {
						$canope = 0;
						$observacionop = oci_result($registrosM, 'OBSERVACION_ACTUAL');
						echo "<td align='center' style='border-color:#DEDEDE'>";
						$f=new Reportes();
						$registrosO=$f->ObtieneObservaciones($perini,$lector,$proyecto,$zona,$observacionop);
						while (oci_fetch($registrosO)) {	
							$canope = oci_result($registrosO, 'CANTIDAD');
							$obsope = oci_result($registrosO, 'OBSERVACION_ACTUAL');
							echo "$canope</td>";
							$cantotope += $canope;
						}oci_free_statement($registrosO);
					}oci_free_statement($registrosM);
					echo "<td align='center' style='border-color:#DEDEDE; background-color:#F3F3F3; color:#000000'><b>$cantotope</b></td>";
					echo "</tr>";
				}oci_free_statement($registrosR); unset($obsope,$canope,$cantotope);
				$item++;
			}oci_free_statement($registros);
			echo "<tr>";
			echo "<td align='center' style='border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000' colspan='2'><b>TOTALES</b></td>";
			echo "<td align='center' style='border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000' ><b>$cantlectores</b></td>";
			$p=new Reportes();
			$registrosM=$p->seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto);
			$totalobservaciones = 0;
			while (oci_fetch($registrosM)) {
				$observacionop = oci_result($registrosM, 'OBSERVACION_ACTUAL');
				$f=new Reportes();
				$registrosO=$f->ObtieneTotalesObservacion($perini,$proyecto,$observacionop,$zonini,$zonfin,$lecini);
				while (oci_fetch($registrosO)) {
					$cantobserva = oci_result($registrosO, 'CANTIDAD');
					echo "<td align='center' style='border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#F3F3F3; color:#000000'>
						<b>$cantobserva</b>
					</td>";
					$totalobservaciones += $cantobserva;
				}oci_free_statement($registrosO);
			}oci_free_statement($registrosM);
			echo "<td align='center' style='border-color:#DEDEDE; border-bottom:1px solid #DEDEDE; background-color:#800000; color:#FFFFFF;'><b>$totalobservaciones</b></td>";
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