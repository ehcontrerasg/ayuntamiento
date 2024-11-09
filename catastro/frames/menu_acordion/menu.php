<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once '../../../clases/class.usuario.php';
$loguser = $_SESSION['usuario'];
$passuser = $_SESSION['contrasena'];
$coduser = $_SESSION['codigo']; 
$nomuser = $_SESSION['nombre'];

?>		
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Menu Facturaci&oacute;n</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<ul id="accordion" class="accordion">
	<?php
    $u=new Usuario();
    $stid=$u->getMenuByModulosUser($coduser,1);
	while (oci_fetch($stid)) {
		$cod_menu = oci_result($stid, 'ID_MENU');
		$des_menu = oci_result($stid, 'DESC_MENU');
		$icono = oci_result($stid, 'ICONO');
		?>
		<li>
			<div class="link"><i class="fa <?php echo $icono?>"></i><?php echo $des_menu;?><i class="fa fa-chevron-down"></i></div>
		<?php
        $stida=$u->getMenuHijByMenuUser($cod_menu,$coduser);
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
