<?php
session_start();
include_once ('../../include.php');
include '../clases/class.rendimiento.php';
$coduser = $_SESSION['codigo'];
$cod_sistema = $_GET['cod_sistema'];
$fecini=$_GET['fecini'];
$fecfin=$_GET['fecfin'];
$periodo=$_GET['periodo'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="content" class="flexigrid" >
	<form  id="FMRelMet" onSubmit="return validacampos();" name="FMRelMet" action="vista.rel_metros.php" method="post">
		<div class="panel panel-primary" style="border-color:#336699">
		<h3 class="panel-heading" style="background-color:#336699;border-color:#336699"><center>Fotograf&iacute;as Lectura Inmueble <?php echo $cod_sistema?></center></h3>
			<table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#000000">
				<?php
				/*consulta de fotos del predio en particular*/
				$i=new Rendimiento();
				$result=$i->urlfotolec($cod_sistema,$fecini,$fecfin,$periodo);
				while (oci_fetch($result)) {
				  $url_foto = oci_result($result, 'URL_FOTO');
				  $fecha_foto = oci_result($result, 'FECHA');
				  $usuario_lec = oci_result($result, 'LOGIN');
				  $obs_lec = oci_result($result, 'OBSERVACION');
				  //echo($url_foto).'<BR>';
				  $url_foto = substr($url_foto, 3);
				  //echo($url_foto);
				?>
                    <tr class="titulo" bgcolor="#DCDCDA">
                        <td>

                            <h4 class="panel-heading" style="background-color:#336699;border-color:#336699"><center> <?php echo $cod_sistema.'     /-/      '.$fecha_foto.' /-/ '.$usuario_lec.' /-/ '.$obs_lec ?></center></h4>
                        </td>



                    </tr>
				<tr class="titulo" bgcolor="#DCDCDA">

					<td align="center" ><img src="../../../webservice/<? echo $url_foto; ?>" width="640px" height="480px"></td>
				</tr>
				<?
				} oci_free_statement($result);
				?>
			</table>
		</div>
	</form>
</div>
</body>
</html>