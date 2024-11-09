<?php
session_start();
include '../clases/class.modifica.php';
include_once ('../../include.php');
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$proyecto=$_POST['proyecto'];
$zona=$_POST['zona'];
$periodo=$_POST['periodo'];
$operario=$_POST['operario'];
$inmueble=$_POST['inmueble'];
$ruta=$_POST['ruta'];

$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
if (!$sortname) $sortname = "L.COD_INMUEBLE";
if (!$sortorder) $sortorder = "DESC";
$sort = "ORDER BY $sortname $sortorder";
$fname="L.COD_INMUEBLE";
$tname="SGC_TT_REGISTRO_LECTURAS L, SGC_TT_RANGOS_CONSUMOS R, SGC_TT_INMUEBLES I ,SGC_tT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_MEDIDOR_INMUEBLE MI,
        SGC_TT_SERVICIOS_INMUEBLES SI  ";
$where= "L.COD_INMUEBLE = R.COD_INM(+) AND
F.INMUEBLE=I.CODIGO_INM AND
 MI.COD_INMUEBLE=I.CODIGO_INM AND
 MI.FECHA_BAJA(+) IS NULL AND
F.PERIODO='$periodo'
AND F.FEC_EXPEDICION IS NULL
AND DF.FACTURA=F.CONSEC_FACTURA
AND DF.CONCEPTO IN (1,3)
AND SI.COD_INMUEBLE=I.CODIGO_INM
AND SI.COD_SERVICIO IN (1,3)
AND L.PERIODO = R.PERIODO(+) AND L.COD_INMUEBLE = I.CODIGO_INM
AND L.FECHA_LECTURA IS NOT NULL AND L.ID_ZONA = '$zona'
AND L.PERIODO = '$periodo' AND I.ID_PROYECTO = '$proyecto'";
if($ruta != '') $where .= " AND SUBSTR(L.RUTA,3,2) = '$ruta'";
$where .= " AND DF.RANGO=0";
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
    
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
        .th{
            background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
            height:24px;
            border:0px solid #ccc;
            border-left:0px solid #ccc;
			border-right:1px solid #ccc;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
			text-align:center;
        }
		.input{
			border:1px solid #ccc;
			font-family: Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:normal;
		}

		i {
			width: 100%;
			color: #369;
			font-size: 18px;
		}
    </style>
	<link rel="stylesheet" href="../../librerias/bootstrap-4.6/bootstrap-icons-1.5.0/bootstrap-icons.css">
</head>
<body style="margin-top:-25px">
<div id="content" style="padding:0px">
	<form name="imprimelibros" action="vista.modifica.php" method="post" >
	<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Modificaci&oacute;n Datos Lecturas</h3>
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
					$sql = "SELECT L.PERIODO 
                    FROM SGC_TT_REGISTRO_LECTURAS L,  SGC_TP_PERIODO_ZONA PZ, SGC_TP_ZONAS Z
                    WHERE 
                    L.ID_ZONA=PZ.ID_ZONA
                    AND Z.ID_ZONA=PZ.ID_ZONA
                    AND PZ.FEC_APERTURA IS NOT NULL
                    AND PZ.FEC_CIERRE IS NULL
                    AND L.COD_LECTOR IS NOT NULL AND Z.ID_PROYECTO = '$proyecto'
                    and L.PERIODO=PZ.PERIODO
                    GROUP BY L.PERIODO ORDER BY L.PERIODO DESC";
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
                    FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z,SGC_TP_PERIODO_ZONA PZ
                    WHERE 
                    PZ.ID_ZONA=Z.ID_ZONA
                    AND PZ.PERIODO='$periodo'
                    AND PZ.FEC_CIERRE IS NULL
                    AND PZ.FEC_APERTURA IS NOT NULL
                    AND L.ID_ZONA = Z.ID_ZONA AND L.PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' 
                    AND L.COD_LECTOR IS NOT NULL AND L.FECHA_LECTURA IS NOT NULL
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
			<tr>
				<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Ruta:&nbsp;</td>
				<td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='ruta' id='ruta' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
					<option value="" disabled selected>Seleccione ruta...</option>
					<?php 
					$sql = "SELECT SUBSTR(L.RUTA,3,2)RUTA
                    FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_RUTAS R
                    WHERE 
                    SUBSTR(L.RUTA,3,2)=R.ID_RUTA
                    AND L.PERIODO='$periodo'
                    AND R.ID_PROYECTO = '$proyecto' 
                    AND L.ID_ZONA = '$zona'
                    AND L.COD_LECTOR IS NOT NULL AND L.FECHA_LECTURA IS NOT NULL
                    GROUP BY L.RUTA ORDER BY 1 ASC";
					$stid = oci_parse($link, $sql);
					oci_execute($stid, OCI_DEFAULT);
					while (oci_fetch($stid)) {
						$cod_ruta = oci_result($stid, 'RUTA') ;	
						if($cod_ruta == $ruta) echo "<option value='$cod_ruta' selected>$cod_ruta</option>\n";
						else echo "<option value='$cod_ruta'>$cod_ruta</option>\n";
					}oci_free_statement($stid);
					?>	
					</select>
				</td>
				<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Operario:&nbsp;</td>
				<td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='operario' id='operario' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
					<option value="" selected>Seleccione operario...</option>
					<?php 
					$sql = "SELECT L.COD_LECTOR
                    FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z, SGC_TT_FACTURA F
                    WHERE  F.PERIODO='$periodo'
                    AND F.INMUEBLE=L.COD_INMUEBLE
                    AND F.FEC_EXPEDICION IS NULL
                    AND
                    Z.ID_ZONA='$zona' AND L.PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' 
                    AND L.ID_ZONA= '$zona' AND L.FECHA_LECTURA IS NOT NULL
                    GROUP BY L.COD_LECTOR ORDER BY 1 ASC";
					$stid = oci_parse($link, $sql);
					oci_execute($stid, OCI_DEFAULT);
					while (oci_fetch($stid)) {
						$cod_operario = oci_result($stid, 'COD_LECTOR') ;	
						if($cod_operario == $operario) echo "<option value='$cod_operario' selected>$cod_operario</option>\n";
						else echo "<option value='$cod_operario'>$cod_operario</option>\n";
					}oci_free_statement($stid);
					?>	
					</select>
				</td>
				<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Inmueble:&nbsp;</td>
				<td align="left" width="15%" bgcolor="#EBEBEB">
					<input type='number' name='inmueble' id='inmueble' value='<?php echo $inmueble?>' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();' />
				</td>
			</tr>
		</table>
	</div>
	</form>
</div><br />
	<?php
	if ($proyecto != "" && $periodo != "" && $zona != ""){	
		?>
		<form id="ficha" method="post">
			<div>
			<table id="flex1" style="display:block; ">
				<thead>
					<tr>
						<th colspan="2" align="center" class="th" style="width:85px">Datos Inmueble</th>
						<th colspan="3" align="center" class="th" style="width:147px">Datos Cr&iacute;tica </th>
						<th colspan="5" align="center" class="th" style="width:336px">Datos Toma Lectura En terreno </th>
						<th colspan="6" align="center" class="th" style="width:240px">Datos Actuales </th>
						<th colspan="4" align="center" class="th" style="width:240px">Datos Medidor </th>
					</tr>
				</thead>
				<tbody>
					<tr>
					  <th align="center" class="th" style="width:30px">N&deg;</th>
				      <th align="center" class="th" style="width:60px">Inmueble</th>
				      <th align="center" class="th" style="width:50px">Posible Lectura</th>
					  <th align="center" class="th" style="width:50px">Lectura M&iacute;nima</th>
				      <th align="center" class="th" style="width:50px">Lectura M&aacute;xima</th>
				      <th align="center" class="th" style="width:90px">Lector</th>
				      <th align="center" class="th" style="width:120px">Fecha Lectura</th>
				      <th align="center" class="th" style="width:50px">Lectura</th>
				      <th align="center" class="th" style="width:80px">Observaci&oacute;n</th>
					  <th align="center" class="th" style="width:80px">Lectura Anterior</th>
					  <th align="center" class="th" style="width:80px">Lectura Actual</th>
				      <th align="center" class="th" style="width:80px">Observaci&oacute;n</th>
				      <th align="center" class="th" style="width:80px">Consumo</th>
				      <th align="center" class="th" style="width:120px">Fech Ins.</th>
				      <th align="center" class="th" style="width:120px">Cons Min</th>
				      <th align="center" class="th" style="width:120px">Hist. de lectura</th>
				  </tr>
					<tr>
						<?php
						if($operario != '') $where .= " AND L.COD_LECTOR = '$operario'";
						if($inmueble != '') $where .= " AND L.COD_INMUEBLE = '$inmueble'";
						$cont = 0;
						$l=new modificacion();
						$registros=$l->obtenerLecturas($where,$sort,$tname,$periodo);
						while (oci_fetch($registros)) {
							$cont++;
							$cod_inm = oci_result($registros, 'COD_INMUEBLE');
							$lec_act = oci_result($registros, 'LECTURA_ACTUAL');
							$fec_lec = oci_result($registros, 'FEC_LEC');
							$obs_lec = oci_result($registros, 'OBSERVACION');
							$cod_ope = oci_result($registros, 'COD_LECTOR');
							$con_act = oci_result($registros, 'CONSUMO_INM');
							$lec_pos = oci_result($registros, 'POSIBLE_LECT');
							$lec_max = oci_result($registros, 'CONSUMO_MINIMO');
							$lec_min = oci_result($registros, 'CONSUMO_MAXIMO');
							$lec_ori = oci_result($registros, 'LECTURA_ORIGINAL');
							$obs_act = oci_result($registros, 'OBSERVACION_ACTUAL');
                            $fecIns = oci_result($registros, 'FECHA_INSTALACION');
                            $consMin = oci_result($registros, 'CONSUMO');
							$lec_ant = oci_result($registros, 'LECANTERIOR');
                            //$url_fot = oci_result($registros, 'URL');

							if($lec_act == -1) $lec_act = '';
                            if($lec_ori == -1) $lec_ori = '';
                            if($lec_ant == -1) $lec_ant = 'Sin Lec.';
							?>
							<td class="tda" style="width:30px; text-align:center"><b><?php echo $cont?></b></td>
							<td class="tda" style="width:60px; text-align:center"><?php echo $cod_inm?></td>
							<td class="tda" style="width:50px; text-align:center"><?php echo $lec_pos?></td>
							<td class="tda" style="width:50px; text-align:center"><?php echo $lec_max?></td>
							<td class="tda" style="width:50px; text-align:center"><?php echo $lec_min?></td>
							<td class="tda" style="width:90px; text-align:center"><?php echo $cod_ope?></td>
							<td class="tda" style="width:120px; text-align:center"><?php echo $fec_lec?></td>
							<td class="tda" style="width:50px; text-align:center"><?php echo $lec_ori?></td>
							<td class="tda" style="width:80px; text-align:center"><?php echo $obs_lec?></td>
							<td class="tda" style="width:80px; text-align:center"><?php echo $lec_ant?></td>
							<td class="tda" style="width:80px; text-align:center">
							<div style="padding:0px" align="center" id="content_lec_<?php echo $cod_inm?>">
							<input class="input" type="text" id="lectura_actual <?php echo $cod_inm.' '.$periodo?>" name="lectura_actual <?php echo $cod_inm.' '.$periodo?>" value="<?php echo $lec_act?>" size="3"/>
							</div></td>
							<td class="tda" style="width:80px; text-align:center">
							<div style="padding:0px" align="center" id="content_observa_<?php echo $cod_inm?>">
							<input class="input" type="text" id="observacion_actual <?php echo $cod_inm.' '.$periodo?>" name="observacion_actual <?php echo $cod_inm.' '.$periodo?>" value="<?php echo $obs_act?>" size="3"/>
							</div></td>
							<td class="tda" style="width:80px; text-align:center">
								<div style="padding:0px" align="center" id="content_consumo_<?php echo $cod_inm?>">
									<input class="input" type="text" id="consumo <?php echo $cod_inm.' '.$periodo?>" name="consumo <?php echo $cod_inm.' '.$periodo?>" value="<?php echo $con_act?>" size="3"/>
								</div>
							</td>                                
							<td class="tda" style="width:120px; text-align:center"><?php echo $fecIns?></td>
							<td class="tda" style="width:120px; text-align:center"><?php echo $consMin?></td>
							<td class="tda" style="width:120px; text-align:center">
								<div>
									<a href="JAVASCRIPT:hislec(<?php echo $cod_inm?>);"><i class="bi bi-card-list"></i></i></a>
								</div>
							</td>
						</tr>
					<?php
				}
				if($proyecto == 'SD')$des_proyecto = 'CAASD';
				if($proyecto == 'BC')$des_proyecto = 'COORABO';
				if($operario != '') $info_operario = ' - Operario '.$operario;
				?>
				</tbody>
			</table>
		</div>
	</form>
	<?php
	}
	?>
</body>
</html>
<script type="text/javascript">

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	({
    	title: 'Listado de Lecturas <?php echo $des_proyecto?> - Periodo <?php echo $periodo?> - Zona <?php echo $zona?> <?php echo $info_operario?>',
        width: 1120,
        height: 250
    });
		
	$(document).ready(function(){
        $('input').blur(function(){
            var field = $(this);

            var parent = field.parent().attr('id');
            $('#im1'+parent).remove();
            $('#imagen_'+parent).remove();
            if($('#'+parent).find(".ok").length){
                $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
            }else{
                $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
            }

            var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name');
            $.ajax({
                type: "POST",
                url: "../datos/datos.modifica.php",
                data: dataString,

                success: function(data) {
                    field.val(data);
                    $('#imagen_'+parent).remove();

                    $('#'+parent).append('<img id="im1'+parent+'" src="ok.png"/>').fadeIn('slow');
                }
            });
        });
    });
    
	function recarga() {
		document.imprimelibros.submit();
    }

	var popped = null;
	function popup(uri, awid, ahei, scrollbar) {
		var params;
		if (uri != "") {
			if (popped && !popped.closed) {
				popped.location.href = uri;
				popped.focus();
			}
			else {
				params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
				popped = window.open(uri, "popup", params);
			}
		}
	}

	function hislec(inm) { // Traer la fila seleccionada
		popup("vista.hist_lec.php?inmueble="+inm,500,310,'yes','pop3');
	}
</script>