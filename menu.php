<?php
  session_start();
 
  include_once ('../../../include.php');
	$loguser = $_SESSION['usuario'];
	$passuser = $_SESSION['contrasena'];
	$coduser = $_SESSION['codigo']; 
	$nomuser = $_SESSION['nombre']; 
//echo $userora;
//Establecemos la conexi�n
$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<script src="tree.php?usuario=<?php echo $coduser; ?>"></script>
</head>
<body onLoad="javascript:loadXMLDoc('./');">


<table width="180">
<tr class="pageTitle">
<th width="27" class="menuHeader"><img src="../imagenes/arrow_left.png" alt="Mostrar men�" onClick="top.document.getElementById('main').cols= '12%,*'"></th>
<th width="72" class="menuHeader" style="font:bold; font-size:12px">MENU</th>
<th width="45" class="menuHeader"><img src="../imagenes/arrow_right.png" alt="Ocultar men�" align="left" onClick="top.document.getElementById('main').cols= '55,*'"></th>
</tr>
<tr>
<th colspan="3" align="left"><div id="tree"></div></th>
</tr>
<tr>
<td height="80" colspan="3">&nbsp;</td>
</tr>
</table>


</body>
</html>