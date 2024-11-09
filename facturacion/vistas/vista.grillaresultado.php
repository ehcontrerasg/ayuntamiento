<?php
session_start();
include_once ('../../include.php');
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$codsistema = $_GET['codsistema'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Consulta General de Inmuebles</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
 	<script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
	<link href="../../css/tabber.css" rel="stylesheet" type="text/css">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form name="consulta1" action="vista.grillaresultado.php" method="post">
<table width="100%">
	<tr>
		<td height="24" align="left" style="font-size:11px">El codigo del sistema es <?php echo $codsistema;?></td>
	</tr>
</table>
</form>
<?php
/*if (isset($_REQUEST["consultar1"])){
	echo "Entro 1";
}*/
?>
