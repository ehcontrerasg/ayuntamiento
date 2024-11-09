<?php
session_start();
/********************************************************************/
	/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
	/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
	/*  CREADO POR JESUS GUTIERREZ ORTIZ								*/
	/*  FECHA CREACION 23/09/2014										*/
	/********************************************************************/
include_once ('../../include.php');
$loguser = $_SESSION['usuario'];
$passuser = $_SESSION['contrasena'];
$coduser = $_SESSION['codigo'];
$nomuser = $_SESSION['nombre'];
// Establecemos la conexión
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>Sistema de Gesti&oacute;n Comercial</title>
    <link type="image/ico" href="../images/logo_acea.png" rel="icon">
	<link href="../../css/bootstrap.min.css" rel="stylesheet" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../../css/site2.css"/>
	<link rel="stylesheet" href="../css/encabezado.css" />

	<script type="text/javascript" src="../../js/jquery.min.js"></script>
	<script type="text/JavaScript" src="../../js/bootstrap.min.js"></script>
</head>
<body>
	<!-- <div id="header" style="background:linear-gradient(#333333,#88A247,#88A247);">

			<?php
			$_SESSION['modulo'] = 19;
			?>
		}
		//
	</script> -->
			<div class="menu" id="menu">
          <div class="logo-acea" >
              <img src="../../images/aceadom201904.png" class="img-responsive" width="130" height="100">
          </div>

            <div class="" >
            <h1><i class="fas fa-oil-can" style="color: white;"></i>&nbsp; Módulo de Incidencias</h1>
            </div>
            <div id="user-menu">
              <!-- <img src="../images/logo_caasd.png" class="img-responsive" width="110" height="130"> -->
              <?php if (isset($loguser) ){?>
              <h5><i class="fa fa-user"></i> <?php echo $nomuser; ?></h5>
       <!--       <a class="btn btn-info btn-xs" href="./../../general/vistas/vista.consulta.php?mod=3" target="jobFrame">
			  	  <i class="fa fa-binoculars"></i>  Consulta General</a>-->
              <?php } else {
    ?>
              <a href="../../index.php" target="_top"><i class="glyphicon glyphicon-off"></i> Salir</a>
            <!--  <a class="btn btn-info btn-xs" href="./../../general/vistas/vista.consulta.php?mod=3" target="jobFrame">
			  	  <i class="fa fa-binoculars"></i>  Consulta General</a>-->
                <?php
}?>
            </div>
    </div>
</body>
</html>

