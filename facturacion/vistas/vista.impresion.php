<?php
session_start();
include_once ('../../include.php');
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$proyecto=$_POST['proyecto'];
$zona=$_POST['zona'];
$periodo=$_POST['periodo'];

//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>	
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="../../js/combos_asigna_lotes.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<style type="text/css">

        .th{
            background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
            height:24px;
            border:0px solid #ccc;
            border-left:1px solid #ccc;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
			text-align:center;
        }
    </style>
</head>
<body style="margin-top:-25px">
<div id="content" style="padding:0px">
	<form name="imprimelibros" action="vista.impresion.php" method="post" >
	<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Impresi&oacute;n Hojas de Lecturas</h3>
    <div style="text-align:center; width:1120px; margin-left:0px">
		<table width="100%" border="1" bordercolor="#CCCCCC" align="center">
    		<tr>
    			<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Acueducto:&nbsp;</td>
			    <td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='proyecto' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();' >
					<option value="" disabled selected>Seleccione proyecto...</option>
					<?php 
					$sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO 
					FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
					WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
					$stid = oci_parse($link, $sql);
					oci_execute($stid, OCI_DEFAULT);
					while (oci_fetch($stid)) {
						$cod_proyecto = oci_result($stid, 'ID_PROYECTO') ;	
						$sigla_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;
						if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
						else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
					}oci_free_statement($stid);
					?>
					</select>
	        	</td>
				<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Periodo:&nbsp;</td>
			    <td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='periodo' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();'>
					<option value="" disabled selected>Seleccione periodo...</option>
					<?php 
					$sql = "SELECT PERIODO 
					FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z
					WHERE L.ID_ZONA = Z.ID_ZONA AND FECHA_LECTURA IS NULL AND L.COD_LECTOR IS NOT NULL AND Z.ID_PROYECTO = '$proyecto'
					GROUP BY PERIODO ORDER BY PERIODO DESC";
					$stid = oci_parse($link, $sql);
					oci_execute($stid, OCI_DEFAULT);
					while (oci_fetch($stid)) {
						$cod_periodo = oci_result($stid, 'PERIODO') ;	
						if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
						else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
					}oci_free_statement($stid);
					?>	
					</select>
	        	</td>
    			<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Zona:&nbsp;</td>
				<td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='zona' id='zona' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();'>
					<option value="" disabled selected>Seleccione zona...</option>
					<?php 
					$sql = "SELECT L.ID_ZONA
					FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z
					WHERE L.ID_ZONA = Z.ID_ZONA AND PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' AND L.COD_LECTOR IS NOT NULL
					GROUP BY L.ID_ZONA ORDER BY 1 ASC";
					$stid = oci_parse($link, $sql);
					oci_execute($stid, OCI_DEFAULT);
					while (oci_fetch($stid)) {
						$cod_zona = oci_result($stid, 'ID_ZONA') ;	
						if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
						else echo "<option value='$cod_zona'>$cod_zona</option>\n";
					}oci_free_statement($stid);
					?>	
					</select>
				</td>
			</tr>
		</table>
	</div>
	<?php
	if ($periodo != "" && $zona != "" && $proyecto != ""){	
	?>
	<p></p>
	<div style="text-align:center; width:1120px">
		<table width="100%" border="1" bordercolor="#CCCCCC" align="center" vspace="12">
    		<tr>
				<td colspan="3">
					<div class="flexigrid" style="width:100%">
						<div class="mDiv">
							<div>Impresi&oacute;n Hojas de Lectura - Zona <?php echo $zona?> - Periodo  <?php echo $periodo?></div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="th">Vista Previa
				</td>
			</tr>
			<tr>
				<td><center>
				<iframe src="../clases/classLibroPdf.php?proyecto=<? echo $proyecto; ?>&periodo=<? echo $periodo; ?>&zona=<? echo $zona; ?>" id="gen_pdf" name="gen_pdf" width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="400" style="background-color: #E0E0E0;">
				</iframe>
			</center></td>
			</tr>
		</table>
	</div>
	<?php
	}
	?>	
</form>
</div>
</body>
</html>
<script>
    function recarga() {
		document.imprimelibros.submit();
    }
</script>