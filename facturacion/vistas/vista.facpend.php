<?php
session_start();
include '../clases/class.facturas.php';
include '../../destruye_sesion.php';

$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
if($_GET['cod_inmueble']){
    $inmueble=$_GET['cod_inmueble'];
}
if($_POST['cod_inmueble']){
    $inmueble=$_POST['cod_inmueble'];
}
$fname ="F.PERIODO";
$tname="SGC_TT_FACTURA";
$where = " AND INMUEBLE='$inmueble'";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	 <script type="text/javascript" src="../js/datFacturasRel.js?2"></script>
    <script language="javascript">

        function relmetros(id1) { // Traer la fila seleccionada
            popup("vista.rel_metros.php?factura="+id1,600,400,'yes');
        }
    </script>
    <style type="text/css">
        .input{
            border:0px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
        }
		.font{
			float:right;
		}
		/*.iframe{
			margin-top:-10px;
			border-color: #666666;
			border:1px solid #ccc;
			display:block;
			width: 560px;
			height: 280px;
			float:left;
		}*/
    </style>
</head>
<body style="margin-top:-25px" >
<div id="content" style="padding:0px; width:1120px; margin-left:0px">
	<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Reliquidaci&oacute;n de Facturas</h3>
	<div style="text-align:center; width:1120px;">
		<form id="FMFactPend" name="FMFactPend" >
    		<div class="flexigrid" style="width:1120px">
       			<div class="mDiv">
           			<div align="left">Reliquidaci&oacute;n de Facturas  <font class="font">C&oacute;digo Inmueble:&nbsp;
						<input class="input" type="number" min="0" value="<?php echo $inmueble ?>"  required id="cod_inmueble" name="cod_inmueble"  style="width: 150px" placeholder="Ingrese el Inmueble"/>&nbsp;<i class="fa fa-search"></i></font>
						<input type="hidden" id="inpdatfacinm" value="<?php echo $inmueble;?>">
					</div>
       			</div>
			</div>
		</form>
		<?php
		$f=new facturas();
		$registros = $f->countRec($fname, $tname, $where);
		while (oci_fetch($registros)) {
			$total=oci_result($registros, 'CANTIDAD');
		}
		if($inmueble != '' && $total==0){
			echo "
			<script type='text/javascript'>
			showDialog('Error de Datos','El inmueble N&deg; $inmueble no tiene facturas pendientes para reliquidar','error');
			</script>";
		}
		?>
	</div>
	<div style="display: block;float: left">
    	<table id="flexfactreliquida" style="display:none">
    	</table>
	</div>
	<div style="display: block;float: left">
    	<table id="flexdatdetfactura" style="display:none">
        </table>
    </div>
	<div style="display: block;float: left" >
        <table id="flexdatestconcepto" style="display:none">
    	</table>
    </div>
	<script type="text/javascript">
		inicioFacturasRel();
		flexyFacRel();
		
	</script>

<!--iframe id="ifdetfactpend" style="border-left:0px solid #ccc; border-right:0px solid #ccc; margin-top:-1px" src="vista.det_factpend.php?factura" class="iframe"></iframe>
<iframe id="ifestcon" style="border-left:0px solid #ccc; border-right:0px solid #ccc; margin-top:-1px" src="vista.estado_conceptopend.php?inmueble=<?php echo $inmueble;?>" class="iframe"></iframe-->
</div>
</body>
</html>

