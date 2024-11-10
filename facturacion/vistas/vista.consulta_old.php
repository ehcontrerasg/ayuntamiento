<?php
session_start();
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$proyecto = $_POST['proyecto'];
$codinmueble = $_POST['codinmueble'];
$procini = $_POST['procini'];
$procfin = $_POST['procfin'];
$estado = $_POST['estado'];
$estado_inm = $_POST['estado_inm'];
$zona = $_POST['zona'];
$codcliente = $_POST['codcliente'];
$nomcliente = $_POST['nomcliente'];
$numdoc = $_POST['numdoc'];
$grupo = $_POST['grupo'];
$tipocli = $_POST['tipocli'];
$nummed = $_POST['nummed'];
$fecins = $_POST['fecins'];
$mora = $_POST['mora'];
$totalizador = $_POST['totalizador'];
$numfac = $_POST['numfac'];
$tipofac = $_POST['tipofac']; 
//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Consulta General de Inmuebles</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
 	<script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<link href="../../css/Static_Tabstrip.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="../../acordion/style_fac.css" rel="stylesheet" />
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script src="../../acordion/js/jquery.min.js"></script>
	<script src="../../acordion/js/main.js"></script>
</head>
<body>
<?php
if (isset($_REQUEST["buscar"])){
?>
<script type="text/javascript">
	window.open('vista.detalle_consulta.php?proyecto=<?php echo $proyecto;?>&codinmueble=<?php echo $codinmueble;?>&procini=<?php echo $procini;?>
	&procfin=<?php echo $procfin;?>&estado=<?php echo $estado;?>&estado_inm=<?php echo $estado_inm;?>&zona=<?php echo $zona?>&codcliente=<?php echo $codcliente?>&nomcliente=<?php echo $nomcliente?>
	&numdoc=<?php echo $numdoc;?>&grupo=<?php echo $grupo;?>&tipocli=<?php echo $tipocli;?>&nummed=<?php echo $nummed;?>&fecins=<?php echo $fecins;?>&mora=<?php echo $mora;?>&numfac=<?php echo $numfac;?>&tipofac=<?php echo $tipofac;?>',
	'<?php echo $codinmueble;?>','width=1350, height=660, replace=true');
</script>
<?php
}
?>
<div id="content">
	<form name="consulta" action="vista.consulta.php" method="post">
		<h3 class="panel-heading" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); font-size:15px; color:#336699" align="center"><b>Consulta General de Inmuebles</b></h3>
		<div style="text-align:center">
			<table width="100%">
				<tr>
					<td align="left" style="font-size:13px"><b>OPCIONES DE BUSQUEDA</b></td>
					<td align="right">
					<button type="submit" name="buscar" id="buscar" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO">
						<b>Buscar&nbsp;&nbsp;</b><i class="fa fa-search"></i>
					</button>
					</td>
				</tr>
			</table>
		</div>
		<hr style="border-color:#336699"/>
		<div>
			<ul id="accordion" class="accordion">
				<li>
					<div class="link"><i class="fa fa-home"></i>Busqueda Inmueble<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<table width="100%">
							<tr>
								<td width="12%" align="left" style="font-size:11px"><p><b>Acueducto:</b></p>
									<p>
									<select name='proyecto' class='btn btn-default btn-sm dropdown-toggle'>
											<?php 
											$sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
											FROM SGC_TP_PROYECTOS
											ORDER BY SIGLA_PROYECTO";
											$stid = oci_parse($link, $sql);
											oci_execute($stid, OCI_DEFAULT);
											while (oci_fetch($stid)) {
												$id_proyecto = oci_result($stid, 'ID_PROYECTO') ;
												$des_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;	
												if($id_proyecto == $proyecto) echo "<option value='$id_proyecto' selected>$des_proyecto</option>\n";
												else echo "<option value='$id_proyecto'>$des_proyecto</option>\n";
											}oci_free_statement($stid);
											?>	
									  </select>
									</p>
						  	  </td>
								<td width="12%" align="left" style="font-size:11px"><p><b>C&oacute;digo Sistema:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="codinmueble" id="codinmueble" value="<? echo $codinmueble; ?>" 
										size="11" maxlength="10" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');"> 
									</p>
						  	  </td>
								<td width="12%" align="left" style="font-size:11px"><p><b>Id Proceso Inicial:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="procini" id="procini" value="<? echo $procini; ?>" 
										size="11" maxlength="11" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');"> 
									</p>
						  	  </td>
								<td width="12%" align="left" style="font-size:11px"><p><b>Id Proceso Final:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="procfin" id="procfin" value="<? echo $procfin; ?>" 
										size="11" maxlength="11" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');"> 
									</p>
						  	  </td>
								<td width="35%" align="left" style="font-size:11px"><p><b>Estado Inmueble:</b></p>
									<p>
										<input type="radio" name="estado" value="T" <? if ($estado=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todos&nbsp;&nbsp;&nbsp;
										<input type="radio" name="estado" value="A" <? if ($estado=='A'){ echo "checked";}?>>&nbsp;&nbsp;Activos&nbsp;&nbsp;&nbsp;
										<input type="radio" name="estado" value="I" <? if ($estado=='I'){ echo "checked";}?>>&nbsp;&nbsp;Inactivos&nbsp;&nbsp;&nbsp;
										<select name='estado_inm' class='btn btn-default btn-sm dropdown-toggle'>
											<option value="" disabled selected>Seleccione Estado...</option>
											<?php 
											$sql = "SELECT ID_ESTADO, DESC_ESTADO
											FROM SGC_TP_ESTADOS_INMUEBLES
											ORDER BY DESC_ESTADO";
											$stid = oci_parse($link, $sql);
											oci_execute($stid, OCI_DEFAULT);
											while (oci_fetch($stid)) {
												$id_estado = oci_result($stid, 'ID_ESTADO') ;
												$des_estado = oci_result($stid, 'DESC_ESTADO') ;	
												if($id_estado == $estado_inm) echo "<option value='$id_estado' selected>$des_estado</option>\n";
												else echo "<option value='$id_estado'>$des_estado</option>\n";
											}oci_free_statement($stid);
											?>	
										</select>
									</p>
						  	  </td>
							
								<td width="17%" align="left" style="font-size:11px"><p><b>Zona:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="zona" id="zona" value="<? echo $zona; ?>" 
										size="3" maxlength="3"> 
									</p>
						  	  	</td>
							</tr>
						</table>
					</ul>
				</li>
				<li>
					<div class="link"><i class="fa fa-user"></i>Busqueda Cliente<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<table width="100%">
							<tr>
								<td width="11%" align="left" style="font-size:11px"><p><b>C&oacute;digo Cliente:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="codcliente" id="codcliente" value="<? echo $codcliente; ?>" 
										size="11" maxlength="6" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');"> 
									</p>
						  	  </td>
								<td width="27%" align="left" style="font-size:11px"><p><b>Nombre Cliente:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="nomcliente" id="nomcliente" value="<? echo $nomcliente; ?>" 
										size="40" maxlength="40" onKeyUp="this.value=this.value.replace(/[^A-Z ]/ig, '');"> 
									</p>
						  	  </td>
								<td width="13%" align="left" style="font-size:11px"><p><b>C&eacute;dula:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="numdoc" id="numdoc" value="<? echo $numdoc; ?>" 
										size="13" maxlength="13" onKeyUp="this.value=this.value.replace(/[^0-9-]/ig, '');"> 
									</p>
						  	  </td>
							  	<td width="31%" align="left" style="font-size:11px"><p><b>Grupo de Empresas:</b></p>
									<p>
										<select name='grupo' class='btn btn-default btn-sm dropdown-toggle'>
											<option value="" disabled selected>Seleccione Grupo...</option>
											<?php 
											$sql = "SELECT COD_GRUPO, DESC_GRUPO
											FROM SGC_TP_GRUPOS
											ORDER BY COD_GRUPO";
											$stid = oci_parse($link, $sql);
											oci_execute($stid, OCI_DEFAULT);
											while (oci_fetch($stid)) {
												$id_grupo = oci_result($stid, 'COD_GRUPO') ;
												$des_grupo = oci_result($stid, 'DESC_GRUPO') ;	
												if($id_grupo == $grupo) echo "<option value='$id_grupo' selected>$des_grupo</option>\n";
												else echo "<option value='$id_grupo'>$des_grupo</option>\n";
											}oci_free_statement($stid);
											?>	
										</select>
									</p>
						  	  </td>
								<td width="18%" align="left" style="font-size:11px"><p><b>Tipo Cliente:</b></p>
									<p>
										<input type="radio" name="tipocli" value="T" <? if ($tipocli=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todos
										<input type="radio" name="tipocli" value="G" <? if ($tipocli=='G'){ echo "checked";}?>>&nbsp;&nbsp;Grandes Clientes
									</p>
					  	  	  </td>
							</tr>
						</table>
					</ul>
				</li>
				<li>	
					<div class="link"><i class="fa fa-dashboard"></i>Busqueda Medidor<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<table width="100%">
							<tr>
								<td width="13%" height="24" align="left" style="font-size:11px"><p><b>N&deg; Medidor:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="nummed" id="nummed" value="<? echo $nummed; ?>" 
										size="11" maxlength="11"> 
									</p>
							  	</td>
								<td width="13%" height="24" align="left" style="font-size:11px"><p><b>Fecha de Instalaci&oacute;n:</b></p>
									<p>
										<input class="form-control" type="date" format="dd/mm/yyyy" name="fecins" id="fecins" value="<? echo $fecins; ?>" 
										size="10" maxlength="10"> 
									</p>
							  	</td>
								<td width="74%">
								</td>
							</tr>
						</table>
					</ul>
				</li>
				<li>	
					<div class="link"><i class="fa fa-qrcode"></i>Busqueda C&oacute;digos<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<table width="100%">
							<tr>
								<td width="25%" align="left" style="font-size:11px"><p><b>Mora:</b></p>
									<p>
										<input type="radio" name="mora" value="T" <? if ($mora=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todos&nbsp;&nbsp;&nbsp;
										<input type="radio" name="mora" value="M" <? if ($mora=='M'){ echo "checked";}?>>&nbsp;&nbsp;Con Mora&nbsp;&nbsp;&nbsp;
										<input type="radio" name="mora" value="S" <? if ($mora=='S'){ echo "checked";}?>>&nbsp;&nbsp;Sin Mora&nbsp;&nbsp;&nbsp;
									</p>
							  	</td>
								<td width="75%" align="left" style="font-size:11px"><p><b>Totalizadores:</b></p>
									<p>
										<input type="radio" name="totalizador" value="I" <? if ($totalizador=='I'){ echo "checked";}?> checked>
										&nbsp;&nbsp;Todos los Inmuebles&nbsp;&nbsp;&nbsp;
										<input type="radio" name="totalizador" value="T" <? if ($totalizador=='T'){ echo "checked";}?>>
										&nbsp;&nbsp;Totalizadores&nbsp;&nbsp;&nbsp;
										<input type="radio" name="totalizador" value="D" <? if ($totalizador=='D'){ echo "checked";}?>>
										&nbsp;&nbsp;Dependientes&nbsp;&nbsp;&nbsp;
										<input type="radio" name="totalizador" value="A" <? if ($totalizador=='A'){ echo "checked";}?>>
										&nbsp;&nbsp;Totalizadores y Dependientes&nbsp;&nbsp;&nbsp;
									</p>
							  	</td>
							</tr>
						</table>	
					</ul>
				</li>	
				<li>	
					<div class="link"><i class="fa fa-file"></i>Busqueda Factura<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<table width="100%">
							<tr>
								<td width="12%" height="24" align="left" style="font-size:11px"><p><b>N&deg; Factura:</b></p>
									<p>
										<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="numfac" id="numfac" value="<? echo $numfac; ?>" 
										size="10" maxlength="10" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');"> 
									</p>
							  	</td>
								<td width="16%" align="left" style="font-size:11px"><p><b>Tipo Facturas:</b></p>
									<p>
										<input type="radio" name="tipofac" value="T" <? if ($tipofac=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todas&nbsp;&nbsp;&nbsp;
										<input type="radio" name="tipofac" value="V" <? if ($tipofac=='V'){ echo "checked";}?>>&nbsp;&nbsp;Vencidas&nbsp;&nbsp;&nbsp;
									</p>
						  	  	</td>
								<td width="72%" align="left" style="font-size:11px"><p><b>Datos Tarifarios:</b></p>
									<p>
										Concepto&nbsp;&nbsp;<select name='concepto' class='btn btn-default btn-sm dropdown-toggle'>
											<option value="">Seleccione Concepto...</option>
											<?php 
											$sql = "SELECT COD_SERVICIO, DESC_SERVICIO
											FROM SGC_TP_SERVICIOS
											ORDER BY COD_SERVICIO";
											$stid = oci_parse($link, $sql);
											oci_execute($stid, OCI_DEFAULT);
											while (oci_fetch($stid)) {
												$id_concepto = oci_result($stid, 'COD_SERVICIO') ;
												$des_concepto = oci_result($stid, 'DESC_SERVICIO') ;	
												if($id_concepto == $concepto) echo "<option value='$id_concepto' selected>$des_concepto</option>\n";
												else echo "<option value='$id_concepto'>$des_concepto</option>\n";
											}oci_free_statement($stid);
											?>	
										</select>
										&nbsp;&nbsp;Uso&nbsp;&nbsp;<select name='uso' class='btn btn-default btn-sm dropdown-toggle'>
											<option value="">Seleccione Uso...</option>
											<?php 
											$sql = "SELECT ID_USO, DESC_USO
											FROM SGC_TP_USOS
											ORDER BY ID_USO";
											$stid = oci_parse($link, $sql);
											oci_execute($stid, OCI_DEFAULT);
											while (oci_fetch($stid)) {
												$id_uso = oci_result($stid, 'ID_USO') ;
												$des_uso = oci_result($stid, 'DESC_USO') ;	
												if($id_uso == $uso) echo "<option value='$id_uso' selected>$des_uso</option>\n";
												else echo "<option value='$id_uso'>$des_uso</option>\n";
											}oci_free_statement($stid);
											?>	
										</select>
										&nbsp;&nbsp;Tarifa&nbsp;&nbsp;<select name='tarifa' class='btn btn-default btn-sm dropdown-toggle'>
											<option value="">Seleccione Tarifa...</option>
											<?php 
											$sql = "SELECT CONSEC_TARIFA, DESC_TARIFA
											FROM SGC_TP_TARIFAS
											ORDER BY DESC_TARIFA";
											$stid = oci_parse($link, $sql);
											oci_execute($stid, OCI_DEFAULT);
											while (oci_fetch($stid)) {
												$id_tarifa = oci_result($stid, 'CONSEC_TARIFA') ;
												$des_tarifa = oci_result($stid, 'DESC_TARIFA') ;	
												if($id_tarifa == $tarifa) echo "<option value='$id_tarifa' selected>$des_tarifa</option>\n";
												else echo "<option value='$id_tarifa'>$des_tarifa</option>\n";
											}oci_free_statement($stid);
											?>	
										</select>
									</p>
						  	  	</td>
							</tr>
						</table>
					</ul>
				</li>
			</ul>
		</div>	
	</form>
</div>
</body>
</html>