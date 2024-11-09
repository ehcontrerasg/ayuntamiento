<?php
session_start();
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
//$codsecure = md5('ehdj65$kd*@';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Reportes Lecturas</title>
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
<body style="margin-top:-25px">
<?php
if (isset($_REQUEST["obslec"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_obslec.php','Observaciones de Lecturas','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["planificalec"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_plan_lec.php','Planificaci&oacute;n Toma de Lecturas','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["modificalec"])){
?>
<script type="text/javascript">
    window.open('vista.reporte_modifica_lec.php','Modificaci&oacute;n Toma de Lecturas','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
?>
<div id="content">
	<form name="repolec" action="vista.repo_lec.php" method="post">
		<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Reportes de Lecturas</h3>
		<div style="text-align:center">
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						<button type="submit" name="obslec" id="obslec" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-edit"></i>&nbsp;&nbsp;Observaciones de<br />Lecturas
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="planificalec" id="planificalec" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-calendar"></i>&nbsp;&nbsp;Planificacion Toma<br />De Lectura 
						</button>
					</td>
					<td align="center" width="20%">
                        <button type="submit" name="modificalec" id="modificalec" class="btn btn btn-INFO"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;Modificacion Toma<br />De Lectura
                        </button>
					</td>
					<td align="center" width="20%">
						
					</td>
					<td align="center" width="20%">
						
					</td>
				</tr>
			</table>
			<p></p>
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						
					</td>
					<td align="center" width="20%">
						
					</td>
					<td align="center" width="20%">
						
					</td>
					<td align="center" width="20%">
						
					</td>
					<td align="center" width="20%">
						
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>
</body>
</html>