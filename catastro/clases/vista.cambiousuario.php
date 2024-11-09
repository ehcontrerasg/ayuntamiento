<? session_start();
include '../clases/class.cliente.php';
include '../clases/class.contrato.php';
include '../../destruye_sesion.php';
$cod=$_SESSION['codigo'];
if($_GET['id_contrato']!=''){
	$contrato=$_GET['id_contrato'];
	$l=new Contrato();
	$l->setid_contrato($contrato);
	$registro=$l->ObtenerDatcontrato();
	oci_fetch($registro);
	$inmueble=oci_result($registro,"CODIGO_INM");
}else{
	$contrato=$_POST['contrato'];
	$documento=$_POST['documento'];
	$inmueble=$_POST['inmueble'];
	$l= new Cliente();
	$registro=$l->obtenercocli($documento);
	oci_fetch($registro);
	$codcliente=oci_result($registro,"CODIGO_CLI");
		
	if($codcliente=="" || $codcliente=="9999999" )
	{
		echo "
		<script language='JavaScript'>
		alert(' el documento $Doccli no existe');
		</script>";
	}else{
		$l= new Contrato();
		$l->setid_contrato($contrato);
		$l->Cancelar_Contrato();
		$i= new Contrato();
		$i->setid_contrato($contrato);
		$i->setcod_inm($inmueble);
		$i->setcodigocli($codcliente);
		$i->setusuario_creacion($cod);
		$i->CambioContrato();
	}
	
}
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<link href="../../css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
		<link href="../../css/css.css" rel="stylesheet" type="text/css" />
		<script src='../../js/jquery-1.11.1.min.js'></script>
		<script src='../../js/agregacliente.js'></script>
		<script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script> 
		<title>ACEASOFT</title>
	</head>
	
	
	<body>
		<p>
		<form  id='formulariocli' action="vista.cambiousuario.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary" style="border-color:rgb(163,73,163)">
				<h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Cambiar Usuario Contrato</center> </h3>
				<div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
				<h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
						<p>	
						<div class="input-group input-group-sm">
			  				<span class="input-group-addon" width="200" >Documento</span>
							<span class="input-group-addon">
								<?php echo "<input size='300' onblur='return agregadocumento();'  id='documento'  pattern='([a-zA-Z0-9-]{0,2})+([0-9-]{6,13})$' required type='text'name='documento'  class='form-control' value='$documento' placeholder='Documento' width='14' height='10'>";?>
							</span>
						</div>
						
						<p>	
						<div class="input-group input-group-sm">
			  				<span class="input-group-addon" width="200" >Cod Inmueble</span>
							<span class="input-group-addon">
								<?php echo "<input size='300'  id='inmueble' readonly type='text'name='inmueble'  class='form-control' value='$inmueble' placeholder='Cod Inmueble' width='14' height='10'>";?>
							</span>
						</div>
						
						
						
						
					</div>	
				
					
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
						<p>
						<div class="input-group input-group-sm">
				  			<span class="input-group-addon">Contrato</span>
							<span class="input-group-addon">
								<?php echo "<input id='contrato' readonly name='contrato' value='$contrato'  class='form-control' placeholder='Contrato' width='14' height='10' >";?>
							</span>
						</div>
						
					</div>
				</div>	
				
				
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">
							
							<p>
				    		<center>
				    			
				    			<P>
				    			<a>
				    				<input type="submit" value="Cambiar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
				    			</a>&nbsp;&nbsp; 
				    			<a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
				    				Cancelar
				    			</a>
				    			
				    			<p>
				    			
				    		</center>
				    		
				    		
						</div>
					</div>
				</div>
	    	</div>
    	</form>
	</body>
</html>
