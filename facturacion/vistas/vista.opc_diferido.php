<?php
session_start();
include '../clases/class.inmuebles.php';
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$codinmueble = $_GET['codinmueble'];
$proyecto = $_GET['proyecto'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../frames/menu_acordion/style_deudacero.css">
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
	<ul id="accordion" class="accordion">
		<li> 
			<div class="link"><i class="fa fa-upload" style="color:#006600"></i>
			<a href="vista.creadiferido.php?codinmueble=<?php echo $codinmueble?>" target="jobFrame">Crear Acuerdo de Pago</a></div>
		</li>
		<li>
			<div class="link"><i class="fa fa-download" style="color:#990000"></i>
			<a href="vista.creadiferido2.php?codinmueble=<?php echo $codinmueble?>" target="jobFrame">Crear Diferido</a></div>
		</li>
		<li>
		</li>
	</ul>
</body>
</html>
