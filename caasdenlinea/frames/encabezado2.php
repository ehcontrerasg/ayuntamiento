<?php
session_start();
/********************************************************************/
	/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
	/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
	/*  CREADO POR JESUS GUTIERREZ ORTIZ								*/
	/*  FECHA CREACION 23/09/2014										*/
	/********************************************************************/

$loguser = $_SESSION['usuario'];
$passuser = $_SESSION['contrasena'];
$coduser = $_SESSION['codigo'];
$nomuser = $_SESSION['nombre'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>CAASD EN LINEA</title>
    <link type="image/ico" href="../images/logo_acea.png" rel="icon">
    <link rel="stylesheet" type="text/css" href="../css/site.css"/>
    <script type="text/javascript" src="../funciones/funciones.js"></script>
</head>
<body>
	<div id="header" style="background-color:rgb(14,54,212)">
    	<div id="masthead">
        	<img src="../images/usuarios.png" alt="Critica" width="140" height="100" />
            <h1>CAASD EN LINEA</h1>
            <p>Gesti&oacute;n de usuarios</p>
            <p align="right">Bienvenido:
                <font color="#CCC">
					<?php
                    echo $nomuser;
                    ?>
                </font>
            </p>
		</div>
	</div>

	<script type="text/javascript" language="javascript">
		<!--
		function cambio_password()
		{
			popup("funciones/cambio_passwd.php", 420, 280);
		}
		function cambio_modulo()
		{
			window.top.location.replace("principal_actualizaciones.php");
			<?php
			$_SESSION['modulo'] = 1;
			?>
		}
		//-->
	</script>
</body>
</html>

