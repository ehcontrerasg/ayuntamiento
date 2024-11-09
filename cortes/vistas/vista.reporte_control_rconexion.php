<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <<?php
session_start();
include_once ('../../include.php');
include('../../destruye_sesion.php');

$coduser = $_SESSION['codigo'];
$proyecto = $_POST['proyecto'];
$sector = $_POST['sector'];
$ruta = $_POST['ruta'];
$periodo = $_POST['periodo'];
$operario = $_POST['operario'];
$proc = $_POST['proc'];
$contratista = $_POST['contratista'];

//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
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
<form name="reporte_control" action="vista.reporte_control_rconexion.php" method="post" onsubmit="return rep_control();">
<h3 class="panel-heading" style=" background-color:#88A247; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Control Diario Reconexion</h3>
<div class="flexigrid" style="width:1120px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Reporte de Control Diario Reconexiones</div>
        	<div style="background-color:rgb(255,255,255)">
        		<table width="100%">
    				<tr>
    					<td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br/>
            			<select name="proyecto" class="input" required><option></option>
						<?php
                        $sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
						WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY PR.SIGLA_PROYECTO";
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

                        <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br/>
            			<select name="periodo" class="input" required><option></option>
						<?php
                        $sql = "SELECT DISTINCT PZ.PERIODO ID_PERIODO FROM SGC_TP_PERIODO_ZONA PZ ORDER BY 1 DESC";
                        $stid = oci_parse($link, $sql);
                        oci_execute($stid, OCI_DEFAULT);
                        while (oci_fetch($stid)) {
                            $cod_periodo = oci_result($stid, 'ID_PERIODO') ;
                            if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                            else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                        }oci_free_statement($stid);
                        ?>
                        </select>
           			  </td>
                        <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Contratista<br/>
                            <select name="contratista" class="input" required><option></option>
                                <?php
                                $sql = "SELECT   C.ID_CONTRATISTA, C.DESCRIPCION 
                                FROM SGC_TP_CONTRATISTAS C , SGC_TT_PERMISOS_CONTRATISTA  PC
                                WHERE C.ID_CONTRATISTA= PC.ID_CONTRATISTA
                                AND PC.ID_USUARIO='$coduser'
                                ORDER BY 1";
                                $stid = oci_parse($link, $sql);
                                oci_execute($stid, OCI_DEFAULT);
                                while (oci_fetch($stid)) {
                                    $cod_contratista = oci_result($stid, 'ID_CONTRATISTA');
                                    $descripcion = oci_result($stid, 'DESCRIPCION') ;
                                    if($cod_contratista == $contratista)
                                        echo "<option value='$cod_contratista' selected>$descripcion</option>\n";
                                    else
                                        echo "<option value='$cod_contratista'>$descripcion</option>\n";
                                }oci_free_statement($stid);
                                ?>
                            </select>
                        </td>
    					<td width="52%" style=" border:1px solid #EEEEEE; text-align:center">
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
$agno = substr($periodo,0,4);
$mes = substr($periodo,4,2);
if($mes == '01'){$mes = Enero;} if($mes == '02'){$mes = Febrero;} if($mes == '03'){$mes = Marzo;} if($mes == '04'){$mes = Abril;}
if($mes == '05'){$mes = Mayo;} if($mes == '06'){$mes = Junio;} if($mes == '07'){$mes = Julio;} if($mes == '08'){$mes = Agosto;}
if($mes == '09'){$mes = Septiembre;} if($mes == '10'){$mes = Octubre;} if($mes == '11'){$mes = Noviembre;} if($mes == '12'){$mes = Diciembre;}
$nomrepo = 'Control Diario Mantenimiento Catastro - ';
?>
<form action="../../funciones/ficheroExcel.php?agno=<? echo $agno;?>&mes=<? echo $mes;?>&nomrepo=<? echo $nomrepo;?>" method="post" target="_blank"  id="FormularioExportacion">
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:1120px">
		<div class="mDiv">
    		<div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
            	<img src="../../images/excel/Excel.ico" onMouseOver="this.src" width="25" height="25" class="botonExcel" style="cursor:pointer" title="Excel"/>
                <!--a href="vista.reporte_word_controldiario.php">
					<img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
				<a href="vista.reporte_pdf_controldiario.php">
					<img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a-->
            </div>
    	</div>
	</div>
</form>
<div class="flexigrid" style="width:1120px">
	<div class="mDiv">
    	<div>Control Diario Mantenimiento - <?php echo $mes.' '.$agno ?></div>
    </div>
</div>
<div class="datagrid" style="width:1120px; height:300px">
	<table id="Exportar_a_Excel" class="scroll" border="1" bordercolor="#CCCCCC" style="height:300px">
    	<thead>
        	<tr>
				<th>N&deg;</th>
				<th>OPERARIO</th>
				<th>DESCRIPCI&Oacute;N</th>
            	<?php
				$total_mes=0;
				$total_oper_mes=0;
				$sql = "SELECT DISTINCT TO_CHAR(RC.FECHA_EJE,'DD') DIA
                FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES I
                WHERE TO_CHAR(RC.FECHA_EJE,'YYYYMM') = '$periodo'
                AND I.CODIGO_INM=RC.ID_INMUEBLE
                AND I.ID_PROYECTO='$proyecto'
                ORDER BY 1";// echo $sql;
				$stid = oci_parse($link, $sql);
				oci_execute($stid, OCI_DEFAULT);
				while (oci_fetch($stid)) {
					$dia = oci_result($stid, 'DIA');
					?>
					<th><?php echo $dia;?></th>
					<?
				} unset($dia);
				oci_free_statement($stid);
				?>
				<th align="center">TOTALES</th>
            </tr>
		</thead>
        <tbody>
			<?php
			$sql = "SELECT DISTINCT A.USR_EJE ID_OPERARIO, U.NOM_USR, U.APE_USR, COUNT(*) CANTIDAD
            FROM SGC_TT_REGISTRO_RECONEXION A, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
            WHERE U.ID_USUARIO = A.USR_EJE AND TO_CHAR(A.FECHA_EJE,'YYYYMM') = '$periodo'
            AND I.CODIGO_INM=A.ID_INMUEBLE
            AND I.ID_PROYECTO='$proyecto'
            AND U.CONTRATISTA=$contratista
            GROUP BY A.USR_EJE,  U.NOM_USR, U.APE_USR
            ORDER BY A.USR_EJE";// echo $sql;
			$stid = oci_parse($link, $sql);
			oci_execute($stid, OCI_DEFAULT);
			$item=1;
			while (oci_fetch($stid)) {
			    $cod_operario = oci_result($stid, 'ID_OPERARIO');
			    $nom_operario = oci_result($stid, 'NOM_USR');
			    $ape_operario = oci_result($stid, 'APE_USR');
				$tot_operario = oci_result($stid, 'CANTIDAD');
				if ($item % 2 == 0){
				?>
				<tr onMouseOver="changeBgcolor(this)" onMouseOut="changeBgcolor1(this)" class="alt">
				<?php
				}
				else{
				?>
				<tr onMouseOver="changeBgcolor2(this)" onMouseOut="changeBgcolor3(this)">
				<?php
				}
				?>
				<td align="center" rowspan="4"><b><? echo $item;?></b></td>
				<td align="left" rowspan="4"><b><? echo $nom_operario." ".$ape_operario;?></b></td>
				<td align="left"><b>RUTAS</b></td>
				<?php
				$cant_rutas = 0;
				$sql = "SELECT DISTINCT TO_CHAR(RC.FECHA_EJE,'DD') DIA
                FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES I
                WHERE TO_CHAR(FECHA_EJE,'YYYYMM') = '$periodo'
                AND I.CODIGO_INM=RC.ID_INMUEBLE
                AND I.ID_PROYECTO='$proyecto'
                GROUP BY TO_CHAR(FECHA_EJE,'DD')
                ORDER BY 1";// echo $sql;
				$stid1 = oci_parse($link, $sql);
				oci_execute($stid1, OCI_DEFAULT);
				while (oci_fetch($stid1)) {
					$dia1 = oci_result($stid1, 'DIA');
					$sql = "SELECT DISTINCT CONCAT(I.ID_SECTOR,I.ID_RUTA) RUTA
                    FROM SGC_TT_REGISTRO_RECONEXION A, SGC_TT_INMUEBLES I
                    WHERE I.CODIGO_INM = A.ID_INMUEBLE AND TO_CHAR(A.FECHA_EJE,'YYYYMM') = '$periodo'
                    AND A.USR_EJE = '$cod_operario' AND TO_CHAR(A.FECHA_EJE,'DD') = '$dia1'
                    AND I.CODIGO_INM=A.ID_INMUEBLE
                    AND I.ID_PROYECTO='$proyecto'
                    GROUP BY CONCAT(I.ID_SECTOR,I.ID_RUTA)
                    ORDER BY 1";// echo $sql;
					$stid2 = oci_parse($link, $sql);
					oci_execute($stid2, OCI_DEFAULT);

					$rutaimp = '';
					while (oci_fetch($stid2)) {
						$ruta = oci_result($stid2, 'RUTA');
						$rutaimp .= $ruta."<br>";
						$cant_rutas++;
					} oci_free_statement($stid2);
					?>
					<td align="center"><?php echo $rutaimp?></td>
					<?php
				} oci_free_statement($stid1);
				?>
				<td align="center" style="background-color:#DADADA; color:#000000"><? echo $cant_rutas;?></td>
				</tr>
				<?php
				if ($item % 2 == 0){
				?>
				<tr onMouseOver="changeBgcolor(this)" onMouseOut="changeBgcolor1(this)" class="alt">
				<?php
				}
				else{
				?>
				<tr onMouseOver="changeBgcolor2(this)" onMouseOut="changeBgcolor3(this)">
				<?php
				}
				?>
				<td align="left"><b>PREDIOS</b></td>
				<?php
				$predios = '';
				$sql = "SELECT DISTINCT TO_CHAR(RC.FECHA_EJE,'DD') DIA, COUNT(*) CANTIDAD
                FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES I
                WHERE TO_CHAR(RC.FECHA_EJE,'YYYYMM') = '$periodo'
                AND I.CODIGO_INM=RC.ID_INMUEBLE
                AND I.ID_PROYECTO='$proyecto'
                GROUP BY TO_CHAR(RC.FECHA_EJE,'DD')
                ORDER BY 1";// echo $sql;
				$stid1 = oci_parse($link, $sql);
				oci_execute($stid1, OCI_DEFAULT);
				while (oci_fetch($stid1)) {
					$dia2 = oci_result($stid1, 'DIA');
					$total_dia = oci_result($stid1, 'CANTIDAD');
					$sql = "SELECT COUNT(*) CANTIDAD2
                    FROM SGC_TT_REGISTRO_RECONEXION A, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
                    WHERE TO_CHAR(A.FECHA_EJE,'YYYYMM') = '$periodo'
                    AND U.ID_USUARIO = '$cod_operario' AND U.ID_USUARIO = A.USR_EJE 
                    AND TO_CHAR(A.FECHA_EJE,'DD') = '$dia2'  
                    AND I.CODIGO_INM=A.ID_INMUEBLE
                    AND U.CONTRATISTA='$contratista'
                    AND I.ID_PROYECTO='$proyecto'";
					$stid2 = oci_parse($link, $sql);
					oci_execute($stid2, OCI_DEFAULT);
					while (oci_fetch($stid2)) {
						$cantidad = oci_result($stid2, 'CANTIDAD2');
						$predios .= $cantidad."-";
						?>
						<td align="center"><?php echo $cantidad?></td>
						<?php
					}oci_free_statement($stid2);
				} oci_free_statement($stid1);
				?>
				<td align="center" style="background-color:#DADADA; color:#000000"><? echo $tot_operario;?></td>
				</tr>
				<?php
				if ($item % 2 == 0){
				?>
				<tr onMouseOver="changeBgcolor(this)" onMouseOut="changeBgcolor1(this)" class="alt">
				<?php
				}
				else{
				?>
				<tr onMouseOver="changeBgcolor2(this)" onMouseOut="changeBgcolor3(this)">
				<?php
				}
				?>
				<td align="left"><b>TIEMPO (HH:MM:SS)</b></td>
				<?php
				$tiempos = '';
				$total_min = 0;
				$sql = "
SELECT DISTINCT TO_CHAR(FECHA_EJE,'DD') DIA, COUNT(*) CANTIDAD
                FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES I
                WHERE TO_CHAR(RC.FECHA_EJE,'YYYYMM') = '$periodo'
                AND I.CODIGO_INM=RC.ID_INMUEBLE
                AND I.ID_PROYECTO='$proyecto'
                GROUP BY TO_CHAR(RC.FECHA_EJE,'DD')
                ORDER BY 1";// echo $sql;
				$stid1 = oci_parse($link, $sql);
				oci_execute($stid1, OCI_DEFAULT);
				while (oci_fetch($stid1)) {
					$dia3 = oci_result($stid1, 'DIA');
					$total_dia = oci_result($stid1, 'CANTIDAD');
					$sqla = "SELECT MAX(TO_CHAR(A.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')) FEC_MAX
                    FROM SGC_TT_REGISTRO_RECONEXION A, SGC_TT_USUARIOS U, SGC_tT_INMUEBLES I
                    WHERE TO_CHAR(A.FECHA_EJE,'YYYYMM') = '$periodo'
                    AND TO_CHAR(A.FECHA_EJE,'DD') = '$dia3' AND U.ID_USUARIO = '$cod_operario' AND U.ID_USUARIO = A.USR_EJE  
                    AND I.CODIGO_INM=A.ID_INMUEBLE
                    AND U.CONTRATISTA='$contratista'
                    AND I.ID_PROYECTO='$proyecto'";
					$stida = oci_parse($link, $sqla);
					oci_execute($stida, OCI_DEFAULT);
					while (oci_fetch($stida)) {
						$fec_max = oci_result($stida, 'FEC_MAX');
					}oci_free_statement($stida);

					$sqla = "SELECT MIN(TO_CHAR(A.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')) FEC_MIN
                    FROM SGC_TT_REGISTRO_RECONEXION A, SGC_TT_USUARIOS U,SGC_TT_INMUEBLES I
                    WHERE TO_CHAR(A.FECHA_EJE,'YYYYMM') = '$periodo'
                    AND TO_CHAR(A.FECHA_EJE,'DD') = '$dia3' 
                    AND U.ID_USUARIO = '$cod_operario' 
                    AND U.ID_USUARIO = A.USR_EJE
                    AND I.CODIGO_INM=A.ID_INMUEBLE
                    AND U.CONTRATISTA='$contratista'
                    AND I.ID_PROYECTO='$proyecto'";
					$stida = oci_parse($link, $sqla);
					oci_execute($stida, OCI_DEFAULT);
					while (oci_fetch($stida)) {
						$fec_min = oci_result($stida, 'FEC_MIN');
					}oci_free_statement($stida);
					$fecha_max = substr($fec_max,0,10);
					$horai=substr($fec_max,11,2);
					$mini=substr($fec_max,14,2);
					$segi=substr($fec_max,17,2);

					$fecha_min = substr($fec_min,0,10);
					$horaf=substr($fec_min,11,2);
					$minf=substr($fec_min,14,2);
					$segf=substr($fec_min,17,2);

					$difdias=$fecha_max-$fecha_min;

					$difdias = (24*$difdias);
					$diferencia_dias = $fecha_max-$fecha_min;

					$ini=((($horai*60)*60)+($mini*60)+$segi);
					$fin=((($horaf*60)*60)+($minf*60)+$segf);

					$dif=$ini-$fin;

					$difh=floor($dif/3600);
					$difm=floor(($dif-($difh*3600))/60);
					$difs=$dif-($difm*60)-($difh*3600);

					if($difh < 10){ $difh = '0'.$difh; }
					if($difm < 10){ $difm = '0'.$difm; }
					if($difs < 10){ $difs = '0'.$difs; }

					$hora_total = $difh.":".$difm.":".$difs;

					$min_total = round($dif/60);
					$total_min += $min_total;
					$tiempos .= $min_total."-";

					?>
					<td align="center"><? echo $hora_total;?></td>
					<?php
				} oci_free_statement($stid1);
				$hora_parcial = round($total_min/60);
				//$min_parcial % $hora_parcial/60;
				//$seg_parcial = $min_parcial/60;
				?>
				<td align="center" style="background-color:#DADADA; color:#000000"><? echo $hora_parcial;?></td>
				</tr>
				<?php
				if ($item % 2 == 0){
				?>
				<tr onMouseOver="changeBgcolor(this)" onMouseOut="changeBgcolor1(this)" class="alt">
				<?php
				}
				else{
				?>
				<tr onMouseOver="changeBgcolor2(this)" onMouseOut="changeBgcolor3(this)">
				<?php
				}
				?>
				<td align="left"><b>PROMEDIO (Pred/Hora)</b></td>
				<?php
				$cant_promedios = 0;
				$total_prom = 0;
				$porcion_predio = explode("-", $predios);
				$porcion_tiempo = explode("-", $tiempos);
				$total_array = count($porcion_predio)-1;
				for($i=0; $i<$total_array; $i++){
					//$horas_prom = round($porcion_tiempo[$i]/60);
					$promedio_total = @round(($porcion_predio[$i]/$porcion_tiempo[$i])*60);
					?>
					<td align="center"><?php echo $promedio_total;?></td>
					<?php
					if($promedio_total > 0){
						$cant_promedios++;
						$total_prom += $promedio_total;
					}
				}
				?>
				<td align="center" style="background-color:#DADADA; color:#000000"><?php echo round($total_prom/$cant_promedios);?></td>
				</tr>
				<?php
				$item++;
			}oci_free_statement($stid);
			?>
			<tr>
			<td align="center" colspan="3" style="background-color:#DADADA; color:#000000"><strong>TOTAL PREDIOS</strong></td>
			<?php
			$sql = "SELECT DISTINCT TO_CHAR(RC.FECHA_EJE,'DD')DIA, COUNT(*) CANTIDAD3
            FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_tT_INMUEBLES I, SGC_TT_USUARIOS U
            WHERE TO_CHAR(RC.FECHA_EJE,'YYYYMM') = '$periodo'
            AND I.CODIGO_INM=RC.ID_INMUEBLE
            AND RC.USR_EJE=U.ID_USUARIO
            AND I.ID_PROYECTO='$proyecto'
            AND U.CONTRATISTA='$contratista'  
            GROUP BY TO_CHAR(RC.FECHA_EJE,'DD') ORDER BY 1";// echo $sql;
			$stid = oci_parse($link, $sql);
			oci_execute($stid, OCI_DEFAULT);
			while (oci_fetch($stid)) {
				$dia  = oci_result($stid, 'DIA');
				$total_dia  = oci_result($stid, 'CANTIDAD3');
				$total_mes=$total_mes+$total_dia;
				?>
				<td align="center" style="background-color:#DADADA; color:#000000"><?php echo $total_dia;?></td>
				<?php
			} oci_free_statement($stid);
			?>
			<td align="center" style="background-color:#800000; color:#FFFFFF;" class="titulo"><?php echo $total_mes; ?></td>
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
function rep_control(){
	if (document.reporte_control.proyecto.value == "") {
		document.reporte_control.proc.value=0;
		return false;
	}
	if (document.reporte_control.periodo.value == "") {
		document.reporte_control.proc.value=0;
		return false;
	}
	else {
		document.reporte_control.proc.value = 1;
		return true;
	}
}
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

