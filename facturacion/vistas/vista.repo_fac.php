<?php
session_start();
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Reportes Facturaci&oacute;n</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script> 
 	<script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<link href="../../css/Static_Tabstrip.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="../../acordion/style_fac.css" rel="stylesheet" />
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script src="../../acordion/js/jquery.min.js"></script>
	<script src="../../acordion/js/main.js"></script>
</head>
<body style="margin-top:-25px">
<?php
if (isset($_REQUEST["resumengen"])){
?>
<script type="text/javascript">
	window.open('vista.resumenFacturacion.php','Resumen General','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["historicofac"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_hisfac.php','Historico de Facturas','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["emisionfac"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_emitefac.php','Emisi&oacute;n de facturas','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["difcortereco"])){
?>
<script type="text/javascript">
	window.open('vista.diferidos_corte_reco.php','Diferidos de Corte y Reconexi&oacute;n','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["facxruta"])){
?>
<script type="text/javascript">
	window.open('vista.facturas_x_ruta.php','Total de Facturas Por Ruta','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["recxruta"])){
?>
<script type="text/javascript">
	window.open('vista.reclamos_x_ruta.php','Total de Reclamos Por Ruta','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["planificafac"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_plan_fac.php','Planificaci&oacute;n Reparto de Facturas','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["catnoactivos"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_no_activos.php','Catastrados No Activos','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["medinstalados"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_med_instalados.php','Medidores Instalados','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["medcancelados"])){
?>
<script type="text/javascript">
	window.open('vista.reporte_med_cancelados.php','Medidores Cancelados','width=1255, height=600, top=30px, left=40px');
</script>
<?php
}
if (isset($_REQUEST["facdigital"])){
    ?>
    <script type="text/javascript">
        window.open('vista.reporte_fac_digital.php','Facturas Digitales','width=1255, height=600, top=30px, left=40px');
    </script>
    <?php
}
if (isset($_REQUEST["incondatos"])){
    ?>
    <script type="text/javascript">
        window.open('vista.reporte_inco_datos.php','Inconsistencias Datos','width=1255, height=600, top=30px, left=40px');
    </script>
    <?php
}
if (isset($_REQUEST["repodgii"])){
    ?>
    <script type="text/javascript">
        window.open('vista.reporte_dgii.php','Reporte DGII','width=1255, height=600, top=30px, left=40px');
    </script>
    <?php
}
?>
<div id="content">
	<form name="repofac" action="vista.repo_fac.php" method="post">
		<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Reportes de Facturaci&oacute;n</h3>
		<div style="text-align:center">
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						<button type="submit" name="resumengen" id="resumengen" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-list"></i>&nbsp;&nbsp;Resumen General<br />de Facturaci&oacute;n 
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="historicofac" id="historicofac" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-history"></i>&nbsp;&nbsp;Hist&oacute;rico de<br />Facturas
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="emisionfac" id="emisionfac" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-files-o"></i>&nbsp;&nbsp;Emisi&oacute;n de<br />Facturas
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="difcortereco" id="difcortereco" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-filter"></i>&nbsp;&nbsp;Diferidos Corte <br /> y Reconexi&oacute;n 
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="facxruta" id="facxruta" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-road"></i>&nbsp;&nbsp;Facturas Por<br />Ruta
						</button>
					</td>
				</tr>
			</table>
			<p></p>
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						<button type="submit" name="recxruta" id="relfac" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-ticket"></i>&nbsp;&nbsp;Reclamos <br />   Por Ruta
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="planificafac" id="planificafac" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-calendar"></i>&nbsp;&nbsp;Planificaci&oacute;n <br /> Reparto de Facturas
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="catnoactivos" id="catnoactivos" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-user-times"></i>&nbsp;&nbsp;Catastrados No Activos <br />Por Uso
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="medinstalados" id="medinstalados" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Medidores Instalados <br />Por gerencia
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="medcancelados" id="medcancelados" class="btn btn btn-INFO"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
						<i class="fa fa-minus-square-o"></i>&nbsp;&nbsp;Medidores Cancelados <br />Por Gerencia
						</button>
					</td>
				</tr>
            </table>
            <p></p>
            <table width="100%">
                <tr>
                    <td>
                        <button type="submit" name="facdigital" id="facdigital" class="btn btn btn-INFO"
                                    style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
                            <i class="fa fa-files-o"></i>&nbsp;&nbsp;Facturas <br />Digitales
                        </button>
                    </td>
                    <td>
                        <button type="submit" name="incondatos" id="incondatos" class="btn btn btn-INFO"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
                            <i class="fa fa-files-o"></i>&nbsp;&nbsp;Inconsistencias <br />Datos
                        </button>
                    </td>
                    <td>
                        <button type="submit" name="repodgii" id="repodgii" class="btn btn btn-INFO"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699; width:200px; height:100px">
                            <i class="fa fa-files-o"></i>&nbsp;&nbsp;Reportes DGII
                        </button>
                    </td>
                </tr>
            </table>
		</div>
	</form>
</div>
</body>
</html>