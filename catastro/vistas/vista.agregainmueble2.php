<?php
session_start();
include('../../destruye_sesion.php');
include '../clases/class.inmueble.php';
include '../clases/class.proyecto.php';
include '../clases/class.urbanizacion.php';
include '../clases/class.via.php';
include '../clases/class.nombrevia.php';
include '../clases/class.sector.php';
include '../clases/class.ruta.php';
include '../clases/class.zona.php';
include '../clases/class.uso.php';
include '../clases/class.actividad.php';
include '../clases/class.medidor.php';
include '../clases/class.suministro.php';
include '../clases/class.emplazamiento.php';
include '../clases/class.servicio.php';
include '../clases/class.tarifa.php';
include '../clases/class.calculo.php';
include '../clases/class.medidor_inmueble.php';
include '../clases/class.concepto_inmueble.php';
include '../clases/class.parametros.php';



$_POST['HidUso'];
$coduser = $_SESSION['codigo'];
$proyecto=$_POST['proyecto'];
$urbanizacion=$_POST['urbanizacion'];
$tipo_via=$_POST['tipo_via'];
$nombre_via=$_POST['nom_via'];
$numerocasa=$_POST['numerocasa'];
$subnumerocasa=$_POST['subnumerocasa'];
$bloquecasa=$_POST['bloquecasa'];
$apartamento=$_POST['ETapartamento'];
$complemento=$_POST['complemento'];
$catastro=$_POST['catastro'];
$sector=$_POST['sector'];
$idproceso=$_POST['idproceso'];
$ruta=$_POST['ruta'];
$zona=$_POST['zona'];
$uso=$_POST['uso'];
$actividad=$_POST['actividad'];
$estado=$_POST['estado'];
$peralta=date("Ym");
$medidor=$_POST["medidor"];
$suministro=$_POST["suministro"];
$emplazamiento=$_POST["emplazamiento"];
$concepto1=$_POST["concepto1"];
$uso1=$_POST["uso1"];
$tarifa1=$_POST["tarifa1"];
$concepto2=$_POST["concepto2"];
$uso2=$_POST["uso2"];
$tarifa2=$_POST["tarifa2"];
$concepto3=$_POST["concepto3"];
$uso3=$_POST["uso3"];
$tarifa3=$_POST["tarifa3"];
$calculo1=$_POST["calculo1"];
$calculo2=$_POST["calculo2"];
$calculo3=$_POST["calculo3"];
$unidadest1=$_POST["unidadest1"];
$unidadesh1=$_POST["unidadesh1"];
$unidadesd1=$_POST["unidadesd1"];
$proc = $_POST['proc'];
$cupobasico1 = $_POST['cupobasico'];
$cminimo= $_POST['CMinimo'];
$fianza= $_POST['fianza'];
$fianzapag= $_POST['fianzapag'];


$l=new Inmnueble();
$codigo_inm=$l->siguiente_cod();

$f = new parametros();
if ($fianza=='' || $fianza==' ' ){
	$fianza=$f->ObtenerFianza($sector, $ruta, $uso);
}


if(isset($_REQUEST['Agregar'])){
	$l=new Inmnueble();
	$l->setcatastro($catastro);
	$c=new Via();
	$c->setid($tipo_via);
	$stid=$c->obtenerdescvia($proyecto);
	oci_fetch($stid);
	$descripciontipvia = oci_result($stid, 'DESC_TIPO_VIA');
	
	
	$l->setdireccion("$descripciontipvia $nombre_via $numerocasa $subnumerocasa $bloquecasa $apartamento $complemento ");
	$l->setid_estado($estado);
	$l->setid_proceso($idproceso);
	$l->setcod_inm($codigo_inm);
	$l->setid_proyecto($proyecto);
	$l->setid_ruta($ruta);
	$l->setid_sector($sector);
	$l->setid_zona($zona);
	$l->setid_tipo_cli("CN");
	$l->setid_ciclo(substr($zona, -1));
	$l->setper_alta(date("Ym"));
	$l->setsec_actividad($actividad);
	$l->settotal_unidades($unidadest1);
	$l->setunidades_des($unidadesd1);
	$l->setunidades_hab($unidadesh1);
	$l->seturbanizacion($urbanizacion);
	$l->setusuario_creacion($coduser); 
	$l->setfianza($fianza);
	$l->setfianza_pag($fianzapag);
	$coderror= $l->NuevoInmueble();
	if($coderror==0){
		$m=new Medidor_inmueble();
		$m->setcodinmueble($codigo_inm);
		$m->setcodemplazamiento($emplazamiento);
		$m->setcodmedidor($medidor);
		$m->setfechains(date("Y/m/d"));
		$m->setusrcreacion($coduser);
		$m->setmetsum($suministro);
		$m->nuevomedidor();
		$c= new Concepto_inmueble();
		$c->setcodconcepto($concepto1);
		$c->setcodinmueble($codigo_inm);
		$c->setestado("AC");
		$c->setusrcreacion($coduser);
		$c->setcupobas($cupobasico1);
		$c->settarifa($tarifa1);
		$c->setunihab($unidadesh1);
		$c->setunitot($unidadest1);
		$c->nuevoconcepto();
		
		
		
	 	echo "
	 	<script type='text/javascript'>
	 	if(confirm('Has creado el inmueble con el codigo $codigo_inm')){
	 		window.close();
	 	}else{
	 	window.close();
	 	}
			
	 	</script>";
	}
	else{
		echo "
		<script type='text/javascript'>
		if(confirm('Error al intentar crear el inmueble : $coderror')){
		
		}else{
		
		}
			
		</script>";
	}
}

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
	<link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<script src='../../js/jquery-1.11.1.min.js'></script>
	<script src='../../js/agregainm2.js'></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script> 
</head>
<title>ACEASOFT</title>
<body>
	<p>
	<form  id="agregainm" onSubmit="return validacampos();" name="agregainm" onSubmit="return infomante();" action="vista.agregainmueble2.php" autocomplete="on" method="post" onsubmit="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<center>
		<div class="panel panel-primary" style="border-color:rgb(163,73,163)">
			<h3 class="panel-heading" style="background-color:rgb(163,73,163); border-color:rgb(163,73,163)"> Agregar Inmueble</h3>
			<div class="panel_mody" >Diligencie la informaci&oacute;n del formulario.
				<div class="container">
					
					<p>
					<h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
		  			<div class="row" >
		  				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
			           		<span class="input-group-addon" style="font-size:12px;">C&oacute;digo Sistema</span>
			           		<span class="input-group-addon"><input type="text" class="form-control" value="<?php echo $codigo_inm;?>"  readonly ></span>
		            	</div>
		            
	            		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Proyecto</span>
		            		<span class="input-group-addon" style="font-size:12px">
		            		<select id="proyecto"  name="proyecto" class="btn btn-default btn-sm dropdown-toggle type="button"  required data-toggle="dropdown" aria-expanded="true">
			            		<option></option>
			            		<?php 
						    		$p=new Proyecto();
						     	 	$stid=$p->obtenerproyectos($coduser);
						        	while (oci_fetch($stid)) 
						        	{
						            	$cod_proyecto = oci_result($stid, 'ID_PROYECTO') ;	
						            	$sigla_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;	
						            	if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
						            		else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
						        	}oci_free_statement($stid);
				    			?>		
	  						</select>
		           	 		</span>
	            		</div>
	        		</div>
	        		<p>
				
					<h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Informaci&oacute;n Vial</b></h3>
	        		<div class="row" >
	  					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Urbanizacion</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input id="urbanizacion" onblur="return agregarurb();"  type="text" class="form-control" value="<?php echo $urbanizacion;?>" name="urbanizacion" placeholder="urbanizacion" width="14" height="10" />
		            		</span>
	            		</div>
	            		
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Tipo V&iacute;a</span>
		            		<span class="input-group-addon" style="font-size:12px">
		            			<select id="tipo_via" required name="tipo_via" onChange="javascript:agregainm.submit();" class="btn btn-default btn-sm dropdown-toggle"   type="button" data-toggle="dropdown" aria-expanded="true">
			    				<option></option>
			    				<?php 
					    			$p=new Via();				   
					        		$stid = $p->obtenervia($proyecto);
					        		while (oci_fetch($stid)) 
					        		{
					            		$cod_via= oci_result($stid, 'ID_TIPO_VIA') ;	
					            		$tvia = oci_result($stid, 'DESC_TIPO_VIA') ;	
					            		if($cod_via == $tipo_via) echo "<option value='$cod_via' selected>$tvia</option>\n";
					            		else echo "<option value='$cod_via'>$tvia</option>\n";
					        		}oci_free_statement($stid);
				    				?>		
	  							</select>
		           	 		</span>
	           		 	</div>
		            
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Nombre Via</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input id="nom_via" name='nom_via' type='text' class="form-control" value="<?php echo $nombre_via;?>" onblur="return agreganomvia();" name="nom_via" placeholder="nombre via" width="14" height="10" />
		            		</span>
	            		</div>
	        		</div>
	        		<p>
	        		
	        		<div class="row" >
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
			           		<span class="input-group-addon" style="font-size:12px">N&uacute;mero</span>
			           		<span class="input-group-addon" style="font-size:12px">
			           			<input  pattern="[0-9]{1,3}$"  id="numerocasa" value="<?php echo $numerocasa; ?>" name="numerocasa"  type="text" class="form-control" placeholder="N&uacute;mero" width="14" height="10" >
			           		</span>
		           		</div>
	            
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
			           		<span class="input-group-addon" style="font-size:12px">Sub N&uacute;mero</span>
			           		<span class="input-group-addon" style="font-size:12px">
			           			<select id="subnumerocasa" required name="subnumerocasa" class="btn btn-default btn-sm dropdown-toggle"   type="button" data-toggle="dropdown" aria-expanded="true">
			    				<option></option>
			    				<?php 
					    			$p=new parametros();				   
					        		$stid = $p->obtenersubnumeros();
					        		while (oci_fetch($stid)) 
					        		{
					            		$cod_subn= oci_result($stid, 'ID_SUBNUM') ;	
					            		$subnum = oci_result($stid, 'DESCRIPCION') ;	
					            		if($cod_subn == $subnumerocasa) echo "<option value='$subnumerocasa' selected>$subnum</option>\n";
					            		else echo "<option value='$cod_subn'>$subnum</option>\n";
					        		}oci_free_statement($stid);
				    				?>		
	  							</select>
			           		</span>
		           		</div>
	            
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Bloque</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input  id="bloquecasa" value="<?php echo $bloquecasa ;?>" name="bloquecasa"  type="text" class="form-control" placeholder="Bloque" width="14" height="10" >
			            	</span>
		            	</div>
		       		</div>
		       		<p>
		       		
		       		<div class="row" >
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
			           		<span class="input-group-addon" style="font-size:12px">Apartamento</span>
			       			<span class="input-group-addon" style="font-size:12px">
			           			<input  id="ETapartamento" value="<?php echo $apartamento; ?>" onblur="return agregaapto();" name="ETapartamento"  type="text" class="form-control" placeholder="Apartamento" width="14" height="10" >
			           		</span>
		           		</div>
		            	
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
			           		<span class="input-group-addon" style="font-size:12px">Complemento</span>
			           		<span class="input-group-addon" style="font-size:12px">
			           			<input  id="complemento" value="<?php echo $complemento; ?>" name="complemento"  type="text" class="form-control" placeholder="Complemento" width="14" height="10" >
			           		</span>
		           		
		       			</div>
		       		</div>
		       		<p>
		       		
		       		
					<h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos Inmueble</b></h3>
		     
		    		<div class="row" >
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
			            	<span class="input-group-addon" style="font-size:12px">Sector</span>
			            	<span class="input-group-addon" style="font-size:12px">
			            		<select id="sector" required name="sector" type="button" class="btn btn-default btn-sm dropdown-toggle"  data-toggle="dropdown" aria-expanded="true">
					    			<option></option>
					    			<?php 
		 					    		$p=new Sector();				   
							        	$stid = $p->obtenersectores($proyecto);
							        	while (oci_fetch($stid)) 
							        	{
							            	$cod_sector= oci_result($stid, 'ID_SECTOR') ;	
		 					            	if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
		 					            	else echo "<option value='$cod_sector'>$cod_sector</option>\n";
		 					        	}oci_free_statement($stid);
					    			?>		
		  						</select>
			            	</span>
		           		</div>
		           
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Ruta</span>
		             		<span class="input-group-addon" style="font-size:12px">
			            		<select id="ruta" name="ruta" required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
				    				<option></option>
				    				<?php 
						    			$p=new Ruta();				   
						        		$stid = $p->obtenerrutas($proyecto, $sector);
						        		while (oci_fetch($stid)) 
						        		{
						            		$cod_ruta= oci_result($stid, 'ID_RUTA') ;	
						            		if($cod_ruta == $ruta) echo "<option value='$cod_ruta' selected>$cod_ruta</option>\n";
						            		else echo "<option value='$cod_ruta'>$cod_ruta</option>\n";
						        		}oci_free_statement($stid);
				    				?>		
		  						</select>
		            		</span>
		           		</div>
		           		
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Id Proceso</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input  id="idproceso" value="<?php echo $idproceso; ?>" name="idproceso" pattern="[0-9]{9,12}$" required type="text" class="form-control" placeholder="Id Proceso" width="14" height="10"  >
		            		</span> 
		           		</div>
		       		
		       		</div>
		       		<p>
		       		
		       		<div class="row" >
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Zona</span>
		            		<span class="input-group-addon" style="font-size:12px">
		            			<select id="zona" required name="zona" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    					<option></option>
			    					<?php 
	 				    				$p=new Zona();				   
					        			$stid = $p->obtenerzonas($proyecto, $sector);
					        			while (oci_fetch($stid)) 
					        			{
					            			$cod_zona= oci_result($stid, 'ID_ZONA') ;	
	 				            			if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
	 				            			else echo "<option value='$cod_zona'>$cod_zona</option>\n";
	 			        				}oci_free_statement($stid);
			    					?>		
								</select>
		            		</span>
		           		</div>
		           		
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Catastro</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input  id="catastro" value="<?php echo $catastro;?>" name="catastro" pattern="[0-9-]{15,20}$" required type="text" class="form-control" placeholder="Catastro" width="14" height="10"  >
		           			</span>
		           		</div>
		            
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Periodo Alta</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input  id="peralta" value="<?php echo $peralta; ?>" name="peralta"  type="text" class="form-control" placeholder="periodo alta" width="14" height="10"  >
		            		</span>
		           		</div>
		       		</div>
		       		<p>
		        		
		       		<div class="row" >
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Uso</span>
		            		<span class="input-group-addon" style="font-size:12px">
				            	<select id="uso" name="uso" onChange="javascript:agregainm.submit();"  class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
						    		<option></option>
						    		<?php 
							    		$p=new Uso() ;				   
								        $stid = $p->obtenerusos();
								        while (oci_fetch($stid)) 
								        {
								            $desc_uso= oci_result($stid, 'DESC_USO') ;
								            $id_uso= oci_result($stid, 'ID_USO') ;
			 					            if($id_uso == $uso) echo "<option value='$id_uso' selected>$desc_uso</option>\n";
			 					            else echo "<option value='$id_uso'>$desc_uso</option>\n";
			 					        }oci_free_statement($stid);
						    		?>		
			  					</select>
		            		</span>
		           		</div>
		           
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
				            <span class="input-group-addon" style="font-size:12px">Actividad</span>
				            <span class="input-group-addon" style="font-size:12px">
				            	<select id="actividad" name="actividad" required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
					    		<option></option>
					    		<?php 
		 					    	$p=new Actividad() ;				   
							        $stid = $p->obteneractividades($uso);
							        while (oci_fetch($stid)) 
							        {
							            $desc_actividad= oci_result($stid, 'DESC_ACTIVIDAD') ;
							            $id_actividad= oci_result($stid, 'SEC_ACTIVIDAD') ;
		 					            if($id_actividad == $actividad) echo "<option value='$id_actividad' selected>$desc_actividad</option>\n";
		 					            else echo "<option value='$id_actividad'>$desc_actividad</option>\n";
		 					        }oci_free_statement($stid);
					    		?>		
			  					</select>
				            </span>
		           		</div>
		           
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
			           		<span class="input-group-addon" style="font-size:12px">Estado</span>
			           		<span class="input-group-addon" style="font-size:12px">
				            	<select id="estado" name="estado" required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
						    		<option></option>
						    		<?php 
			 					    	$p=new parametros();				   
								        $stid = $p->obtenerestados();
								        while (oci_fetch($stid)) 
								        {
								            $desc_estado= oci_result($stid, 'DESC_ESTADO') ;
								            $id_estado= oci_result($stid, 'ID_ESTADO') ;
			 					            if($id_estado == $estado) echo "<option value='$id_estado' selected>$desc_estado</option>\n";
			 					            else echo "<option value='$id_estado'>$desc_estado</option>\n";
			 					        }oci_free_statement($stid);
						    		?>		
			  					</select>
		            		</span>
		           		</div>
		           		
		          
		           		
		       		</div>		
		        		<p><p>	
		        		
		       <div class="row" >
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Fianza</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input  id="fianza" value="<?php echo $fianza;?>" name="fianza" pattern="[0-9-]{0,6}$" required type="text" class="form-control" placeholder="Fianza" width="14" height="10"  >
		           			</span>
		           		</div>
		           		
		           	
		           		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Fianza Pagada</span>
		             		<span class="input-group-addon" style="font-size:12px">
		            			<input  id="fianzapag" value="<?php echo $fianzapag;?>" name="fianzapag" pattern="[0-9-]{0,6}$" required type="text" class="form-control" placeholder="Fianza pagada" width="14" height="10"  >
		           			</span>
		           		</div>
		        	</div>	
		        		        
		           
		           	<h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos Medidor</b></h3>
	     			<div class="row" >
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
				            <span class="input-group-addon" style="font-size:12px">Metodo de suministro</span>
				            <span class="input-group-addon" style="font-size:12px">
				            	<select id="suministro" required name="suministro"  class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
						    		<option></option>
						    		<?php 
			 					    	$p=new Suministro();				   
								        $stid = $p->obtenersuministro();
								        while (oci_fetch($stid)) 
								        {
								            $cod_suministro= oci_result($stid, 'COD_SUMINISTRO') ;
								            $desc_suministro= oci_result($stid, 'DESC_SUMINISTRO') ;
			 					  
								            if($cod_suministro == $suministro) echo "<option value='$cod_suministro' selected>$desc_suministro</option>\n";
			 					            else echo "<option value='$cod_suministro'>$desc_suministro</option>\n";
			 					        }oci_free_statement($stid);
						    		?>		
			  					</select>
				            </span>
	            		</div>
	            		
	            		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Marca</span>
		             		<span class="input-group-addon" style="font-size:12px">
			            		<select id="medidor" name="medidor"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
				    				<option></option>
						    		<?php 
			 					    	$p=new Medidor();				   
								        $stid = $p->obtenermedidores();
								        while (oci_fetch($stid)) 
								        {
								            $cod_medidor= oci_result($stid, 'CODIGO_MED') ;	
								            $desc_medidor= oci_result($stid, 'DESC_MED') ;
			 					            if($cod_medidor == $medidor) echo "<option value='$cod_medidor' selected>$desc_medidor</option>\n";
			 					            else echo "<option value='$cod_medidor'>$desc_medidor</option>\n";
			 					        }oci_free_statement($stid);
						    		?>		
		  						</select>
		            		</span>
	            		</div>
	             
	             		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
		            		<span class="input-group-addon" style="font-size:12px">Emplazamiento</span>
		             		<span class="input-group-addon" style="font-size:12px">
				            	<select id="emplazamiento" name="emplazamiento"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
						    		<option></option>
						    		<?php 
			 					    	$p=new Emplazamiento();				   
								        $stid = $p->obteneremplazamiento();
								        while (oci_fetch($stid)) 
								        {   
								            $cod_emplazamiento= oci_result($stid, 'COD_EMPLAZAMIENTO') ;	
								            $desc_emplazamiento= oci_result($stid, 'DESC_EMPLAZAMIENTO') ;
			 					            if($cod_emplazamiento == $emplazamiento) echo "<option value='$cod_emplazamiento' selected>$desc_emplazamiento</option>\n";
			 					            else echo "<option value='$cod_emplazamiento'>$desc_emplazamiento</option>\n";
			 					        }oci_free_statement($stid);
						    		?>		
			  					</select>
				            </span>
	            		</div>
	        		</div>
	        		<p>
					<h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Servicios</b></h3>
					
					<div class="row" >
		        		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size:12px"">
		        			<span class="input-group-addon"  style="font-size:12px"></span>
			        		<span class="input-group-addon">
			      				<span class="input" style="font-size:12px">Concepto</span>
			          			<select id="concepto1" name="concepto1"  required  class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    					<option></option>
							    	<?php 
				 				    	$p=new Servicio();				   
								        $stid = $p->obtenerservicio();
								        while (oci_fetch($stid)) {
								            $cod_concepto1= oci_result($stid, 'COD_SERVICIO') ;	
								            $desc_concepto1= oci_result($stid, 'DESC_SERVICIO') ;
				 				            if($cod_concepto1 == $concepto1) echo "<option value='$cod_concepto1' selected>$desc_concepto1</option>\n";
				 				            else echo "<option value='$cod_concepto1'>$desc_concepto1</option>\n";
				 				        }oci_free_statement($stid);
							    	?>		
					  			</select>
					  			<span class="input" style="font-size:12px">Uso</span>
			           			<select id="uso1" name="uso1"    disabled class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    					<option></option>
								   	<?php 
								    	$p=new Uso() ;				   
									    $stid = $p->obtenerusos();
									    while (oci_fetch($stid)) {
										    $desc_uso1= oci_result($stid, 'DESC_USO') ;
									    	$id_uso1= oci_result($stid, 'ID_USO') ;
					 				        if($id_uso1 == $uso) echo "<option value='$id_uso1' selected>$desc_uso1</option>\n";
					 				        else echo "<option value='$id_uso1'>$desc_uso1</option>\n";
					 				  	}oci_free_statement($stid);
								   	?>		
	  							</select>
			           			<span class="input" style="font-size:12px">Tarifa</span>
			            		<select id="tarifa1" name="tarifa1"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
								    <option></option>
								    <?php 
					 				   	$p=new Tarifa();
					 				   	$p->setcodconcepto($concepto1);
					 				   	$p->setcodproyecto($proyecto);
					 				   	$p->setcoduso($uso);			   
									    $stid = $p->obtenertarifa();
									    while (oci_fetch($stid)) 
									    {
									   		$cod_tarifa1= oci_result($stid, 'CONSEC_TARIFA') ;	
									        $desc_tarifa1= oci_result($stid, 'DESC_TARIFA') ;
					 				        if($cod_tarifa1== $tarifa1) echo "<option value='$cod_tarifa1' selected>$desc_tarifa1</option>\n";
					 				        else echo "<option value='$cod_tarifa1'>$desc_tarifa1</option>\n";
					 				    }oci_free_statement($stid);
								    ?>		
								</select>
		 					
		  					
		  						<span class="input" style="font-size:12px">Cupo Basico</span>
				      			<input  id="cupobasico"  value="<?php echo $cupobasico1; ?>" name="cupobasico" style='font-size:12px;' onkeyup="cambiapar()"  size='1' pattern='[0-9]{1,4}$' required type='text'  placeholder='Cupo Basico'   >
		  						
		  	
			        		</span>
			  			</div>
		    		</div>
		    	    
			   		<div class="row" >
			        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size:12px"">
			        		<span class="input-group-addon"  style="font-size:12px"></span>
				        	<span class="input-group-addon">
				      			<span class="input" style="font-size:12px">Unidades totales</span>
				          		<input  id='unidadest1'  value='<?php echo $unidadest1; ?>' name='unidadest1' style='font-size:12px;' onkeyup="cambiapar()" size='1' pattern='[0-9]{1,3}$' required type='text'  placeholder='UT' >
				           		<span class="input" style="font-size:12px">Unidades Habitadas</span>
				           		<input  id='unidadesh1'  name='unidadesh1'  value='<?php echo $unidadesh1; ?>' onkeyup="cambiapar()" style='font-size:12px;'  size='1' pattern='[0-9]{1,3}$' required type='text'  placeholder='UH'   >
				           		<span class="input" style="font-size:12px">Unidades Deshabitadas</span>
				           		<?php  echo "<input  id='unidadesd1' readonly name='unidadesd1' value='$unidadesd1' style='font-size:12px;'  size='1' pattern='[0-9]{1,3}$' required type='text'  placeholder='UD'   >"; ?>
								<span class="input" style="font-size:12px">C Minimo</span>
				           		<?php  echo "<input  id='CMinimo' readonly name='CMinimo' value='$cminimo' style='font-size:12px;'  size='1' pattern='[0-9.]{1,3}$' type='text'  placeholder='Promedio'   >"; ?>
							</span>
						</div>
			   		</div>
		    		
		    		<p><center><input type="submit" value="Agregar"  id="Agregar" name="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">&nbsp;&nbsp; <a class="btn btn-primary btn-lg" href="#" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">Cancelar</a></center></p></center></p> 
					<input type="hidden" name="proc" value="<?php echo $proc;?>">
					<input type="hidden" name="HidUso" id="HidUso">
				</div>
			</div>
		</div>
		</center>
	</form>
</body>

