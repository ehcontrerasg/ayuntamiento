<?php
session_start();
$loguser = $_SESSION['usuario'];
$passuser = $_SESSION['contrasena'];
$coduser = $_SESSION['codigo']; 
$nomuser = $_SESSION['nombre'];
	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>		
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Menu Servicio al Cliente</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<ul id="accordion" class="accordion">
	<?php										
	$sql = "SELECT M.ID_MENU, M.DESC_MENU, M.ICONO, M.ORDEN
	FROM SGC_TP_MENUS M, SGC_TP_PERFILES P 
	WHERE M.ID_MENU = P.ID_MENU AND M.ID_PADRE = 0 
	AND P.ID_USUARIO = '$coduser' AND M.ACTIVO = 'S' 
	AND (M.ID_MODULO = 15) ORDER BY ORDEN ASC";
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	while (oci_fetch($stid)) {
		$cod_menu = oci_result($stid, 'ID_MENU');
		$des_menu = oci_result($stid, 'DESC_MENU');
		$icono = oci_result($stid, 'ICONO');
		?>
		<li>
			<div class="link"><i class="fa <?php echo $icono?>"></i><?php echo $des_menu;?><i class="fa fa-chevron-down"></i></div>
		<?php
		$sql2 = "SELECT DISTINCT M.DESC_MENU, M.URL, M.ORDEN 
		FROM SGC_TP_MENUS M, SGC_TP_PERFILES P  
		WHERE M.URL IS NOT NULL AND M.ID_PADRE = $cod_menu AND  P.ID_MENU=M.ID_MENU AND P.ID_USUARIO='$coduser'
		ORDER BY ORDEN ASC";
		$stida = oci_parse($link, $sql2);
		oci_execute($stida, OCI_DEFAULT);	
		?>
		<ul class="submenu">
		<?php
		while (oci_fetch($stida)){
			$detalle = oci_result($stida, 'DESC_MENU');
			$url = oci_result($stida, 'URL');
			?>
				<li><a href="<?php echo $url;?>" target="jobFrame"><?php echo $detalle;?></a></li>
			<?php
		}oci_free_statement($stida);
		?>	
		</ul>
		</li>
		<?php   
	}oci_free_statement($stid);
	oci_close($link);
	?>	
		<li>
			<a href="../../../index2.php/" target="_top"><div class="link"><i class="fa fa-th"></i>MEN&Uacute; PRINCIPAL</div></a>
		</li>
		<li>
			<a href="../../../index.php" target="_top"><div class="link"><i class="fa fa-reply-all"></i>SALIR</div></a>
		</li>
	</ul>
	<script src="js/jquery.min.js"></script>
	<script src="js/main.js"></script>
</body>
</html>
