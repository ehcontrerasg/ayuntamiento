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
	<!-- <link rel="stylesheet" type="text/css" href="../../css/site2.css"/> -->
	<link rel="stylesheet" href="../css/encabezado.css?<? echo time();?>" />

	<script type="text/javascript" src="../../js/jquery.min.js"></script>
	<script type="text/JavaScript" src="../../js/bootstrap.min.js"></script>
</head>
<body>
	<!-- <div id="header" style="background:linear-gradient(#333333,#8A2908,#FF8000);">
		<table width="99%" align="center">
		  <tr>
			  <td height="48" align="center"></td>
				<td width="9%" rowspan="2" align="center" style="color:#FFFFFF"><i class="fa fa-archive fa-5x" style="vertical-align:top"></i> </td>
			  	<td height="48" colspan="4" align="left" class="td" style="vertical-align:bottom"><b>M&Oacute;DULO ARCHIVOS</b></td>
		  </tr>
	  	  <tr>
			  <td width="24%" height="51" align="right"></td>
				<td width="4%" height="51" align="right" class="masthead">Usuario:</td>
		  	  <td width="28%" align="left" class="masthead"><font color="#CCC"><b>&nbsp;&nbsp;<?php echo $nomuser;?></b></font></td>
			  	<td width="24%" height="51" align="right" style="vertical-align:bottom">
				  <a class="btn btn-default btn-sm" href="./../../general/vistas/vista.consulta.php?mod=15" target="jobFrame">
			  	  <i class="fa fa-binoculars"></i>  Consulta General</a>  &nbsp;</td>
			  <td width="11%" height="51" align="right" style="vertical-align:bottom">
			    <a class="btn btn-default btn-sm" href="#">
			  	  <i class="fa fa-key"></i>  Cambiar Contraseña</a></td>
		  </tr>
		</table>
	</div>
	<script type="text/javascript" language="javascript">
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
		//
	</script> -->
	<div class="menu" id="menu">
          <div class="logo-acea" >
              <img src="../../images/aceadom201904.png" class="img-responsive" width="130" height="130">
            </div>

            <div class="" >
            <h1><i class="fa fa-ban fa-archive" style="color: white;"></i>&nbsp; Módulo Archivo</h1>
            </div>
            <div id="user-menu">
              <!-- <img src="../images/logo_caasd.png" class="img-responsive" width="110" height="130"> -->

              <?php if (isset($loguser) ){?>
              <h5><i class="fa fa-user"></i> <?php echo $nomuser; ?></h5>

                  <a class="btn btn-info btn-xs" href="./../../general/vistas/vista.consulta.php?mod=15&con=1" target="jobFrame" data-toggle="modal" data-target="#exampleModal">
                      <i class="fa fa-binoculars"></i>  Consulta General</a>
                 <a class="btn btn-info btn-xs" href="./../../general/vistas/vista.listadoExtensiones.php" target="jobFrame">
                     <i class="fa fa-book"></i>  Directorio Telefónico </a>

              <?php } else {
    ?>
              <a href="../../index.php" target="_top"><i class="glyphicon glyphicon-off"></i> Salir</a>
              <a class="btn btn-info btn-xs" href="./../../general/vistas/vista.consulta.php?mod=15" target="jobFrame">
			  	  <i class="fa fa-binoculars"></i>  Consulta General</a>
                <?php
}?>
            </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

