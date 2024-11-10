<?php
  session_start();
 
  include_once ('../../../include.php');
	$loguser = $_SESSION['usuario'];
	$passuser = $_SESSION['contrasena'];
	$coduser = $_SESSION['codigo']; 
	$nomuser = $_SESSION['nombre']; 
//echo $userora;
//Establecemos la conexión
//$Cnn = new OracleConn(UserGeneral, PassGeneral);
	//$link = $Cnn->link;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="conten-type" content="text/html; charset=UTF-8" />
<link href="style.css" rel="stylesheet" type="text/css">
<script src="tree.php?usuario=<?php echo $coduser; ?>"></script>
</head>
<body onLoad="javascript:loadXMLDoc('./');">


<table width="180">
<tr class="pageTitle">
<!--<th width="27" class="menuHeader"><img src="../imagenes/arrow_left.png" alt="Mostrar menú" onClick="top.document.getElementById('main').cols= '12%,*'"></th>-->
<th class="menuHeader" >MENU</th>
<!--<th width="45" class="menuHeader"><img src="../imagenes/arrow_right.png" alt="Ocultar menú" align="left" onClick="top.document.getElementById('main').cols= '55,*'"></th>-->
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