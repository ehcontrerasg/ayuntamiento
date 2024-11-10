<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.cliente.php';
    include '../clases/class.tipodoc.php';
    include '../clases/class.grupo.php';
    include '../clases/class.inmueble.php';
    include '../clases/class.contrato.php';
    include '../../destruye_sesion.php';

    if($_GET['doc']!=""){
        $inmueble=$_GET['inm'];
        $contrato=$_GET['contrato'];
        $documento=$_GET['doc'];
        $tipocre=$_GET['tip'];
        $c=new Cliente();
        $datoscli=$c->especifico('',$documento,$inmueble);
        oci_fetch($datoscli);
        $nombre=oci_result($datoscli,"NOMBRE");
        $direccion=oci_result($datoscli,"DIRECCION");
        $telefono=oci_result($datoscli,"TELEFONO");
        $email=oci_result($datoscli,"EMAIL");
        $desctipodoc=oci_result($datoscli,"TIPO_DOC");
        $codigodoc=oci_result($datoscli,"CODIGO_DOC");
        $documento2=oci_result($datoscli,"DOCUMENTO");
        $dircorres=oci_result($datoscli,"DIR_CORRESPONDENCIA");
        $codgrupo=oci_result($datoscli,"COD_GRUPO");
        $descrigrupo=oci_result($datoscli,"GRUPO");
        $correspondencia=oci_result($datoscli,"CORRESPONDENCIA");
        if($documento2<>null){
            echo "
				<script type='text/javascript'>
				window.close();
				
					
				</script>";
        }




    }

    $cod=$_SESSION['codigo'];
    if($_POST['nombre']!=""){
        $inm=new Inmnueble();
        $inmueble=$_POST['inmueble'];
        $codigoim=$inm->existe($inmueble);
        oci_fetch($codigoim);
        $codigoinmueble=oci_result($codigoim,"CODIGO_INM");
        $nombre=$_POST['nombre'];
        $direccion=$_POST['direccion'];
        $telefono=$_POST['telefono'];
        $email=$_POST['email'];
        $tipodocumento=$_POST['tipo_doc'];
        $documento=$_POST['documento'];
        $grupo=$_POST['grupo'];
        $dircorres=$_POST['dir_corre'];
        $correspondencia=$_POST['correspondencia'];
        $contribuyente_dgii=$_POST['contribuyente_dgii'];


        if($codigoinmueble==$_POST['inmueble']){

            $ced=new Cliente();
            $cedula=$ced->existe($_POST['documento']);
            oci_fetch($cedula);
            $cedulaclie=oci_result($cedula,"DOCUMENTO");
            if($cedulaclie==$_POST['documento']){
                echo " 
                <script language='JavaScript'> 
                alert('El usuario con el documento $cedulaclie ya existe'); 
                </script>";
            }else{

                $contr=new Contrato();
                $contr->setcod_inm($_POST['inmueble']);
                $numerocontratos=$contr->existe();
                if($numerocontratos>=1){
                    echo "
				<script language='JavaScript'>
				alert(' El inmueble $inmueble ya tiene un contrato activo');
				</script>";
                }
                else
                {
                    $l=new Cliente();
                    $l->setnombre(strtoupper($nombre));
                    $l->setdireccion(strtoupper($direccion));
                    $l->settelefono($telefono);
                    $l->setemail(strtoupper($email));
                    $l->settipodoc($tipodocumento);
                    $l->setdocumento($documento);
                    $l->setcodgrupo($grupo);
                    $l->setdircorrespondencia($dircorres);
                    $l->setcorrespondecia($correspondencia);
                    $l->setusuariocreacion($cod);
                    $l->setfechacreacion(date("Y/m/d H:i:s"));
                    $l->setusrmod($cod);
                    $l->setfechamod(date("Y/m/d H:i:s"));
                    $l->setContribuyenteDgi($contribuyente_dgii);
                    $result=$l->NuevoCliente();
                    $error=$l->geterror();
                    if($result==false){
                        echo "
				<script type='text/javascript'>
				if(confirm('$error ERROR')){
				        
				}else{
				window.close();
				}
					
				</script>";

                    }else{

                        $con=new Contrato();
                        $con->setcod_inm($codigoinmueble	);
                        $con->setfechaini(date("Y/m/d H:i:s"));
                        ///////////////////////obtenemos el codigo cliente////
                        $ced=new Cliente();
                        $codclie=$ced->existe($documento);					//
                        oci_fetch($codclie);								//
                        $codigoclie=oci_result($codclie,"CODIGO_CLI");

                        //////////////////////////////////////////////////////
                        $con->setcodigocli($codigoclie);
                        $con->setalias($nombre);
                        $con->setfecha_creacion(date("Y/m/d H:i:s"));
                        $con->setusuario_creacion($cod);

                        /////////////////////obtenemos datos de inmueble//////
                        $inm=new Inmnueble();								//
                        $inmueble=$_POST['inmueble'];						//
                        $codigoim=$inm->existe($inmueble);					//
                        oci_fetch($codigoim);								//
                        $proyecto=oci_result($codigoim,"ID_PROYECTO");		//
                        $idzona=oci_result($codigoim,"ID_ZONA");			//
                        $idsector=oci_result($codigoim,"ID_SECTOR");
                        $idruta=oci_result($codigoim,"ID_RUTA");		//
                        $consecutivo=$proyecto."-".$idsector."".$idruta."-";//
                        //////////////////////////////////////////////////////

                        if($inmueble!=''){
                            $con->setid_contrato($consecutivo);
                            $con->NuevoContrato();
                            $cont= new Contrato();
                            $cont->setcodigocli($codigoclie);
                            $cont->setcod_inm($codigoinmueble);
                            $codcontrato=$cont->ObtenerCodcontrato();
                            oci_fetch($codcontrato);								//
                            $contrato=oci_result($codcontrato,"ID_CONTRATO");
                            $codigoinmueble=oci_result($codigoim,"CODIGO_INM");




                            echo "
				<script type='text/javascript'>
				if(confirm('el contrato $contrato ha sido creado. Desea agregar otro cliente')){

				}else{
					window.close();
				}
			
				</script>";
                            $_POST['nombre']='';
                            $_POST['direccion']='';
                            $_POST['telefono']='';
                            $_POST['email']='';
                            $_POST['tipo_doc']='';
                            $_POST['documento']='';
                            $_POST['grupo']='';
                            $_POST['dir_corre']='';
                            $_POST['correspondencia']='';
                        }

                        echo "
				<script type='text/javascript'>
				if(confirm('el contrato $contrato ha sido creado. Desea agregar otro cliente')){
				
				}else{
				window.close();
				}
					
				</script>";
                    }
                }
            }
        }
        else{
            $codigoinm2=$_POST['inmueble'] ;
            echo "
			<script language='JavaScript'>
			alert('El Inmnueble numero $codigoinm2 no existe');
			</script>";
        }
    }

    ?>

    <html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>

    <body>
    <p>
    <form  id='formulariocli' action="vista.agregarcliente.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
            <h3 class="panel-heading" style="background-color:rgb(167,123,37);border-color:rgb(167,123,27)"><center>Agregar Cliente</center> </h3>
            <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
            <h3 style="background-color:rgb(167,123,37); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon" width="200" >Nombre</span>
                        <span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='nombre' name='nombre' required type='text' class='form-control' value='$nombre' placeholder='Nombre' width='14' height='10'>";?>
							</span>
                    </div>

                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Tel&eacute;fono</span>
                        <span class="input-group-addon">
								<?php echo "<input id='telefono' name='telefono' type='text' value='$telefono' pattern='[0-9]{7,13}$' class='form-control' placeholder='Tel&eacute;fono' width='14' height='10' >";?>
							</span>
                    </div>

                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Tipo Documento</span>
                        <span class="input-group-addon">
								<select id="tipo_doc" name="tipo_doc" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
				    			<option></option>
				    			Tipo documento
                                    <?php
                                    $d= new Documento();
                                    $documentos=$d->Todos();
                                    echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$codigodoc'>$desctipodoc</option>";
                                    while (oci_fetch($documentos)){
                                        $cod_tipodo=oci_result($documentos,"ID_TIPO_DOC");
                                        $tipodoc=oci_result($documentos,"DESCRIPCION_TIPO_DOC");
                                        echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$cod_tipodo'>$tipodoc</option>";
                                    }
                                    ?>
				  				</select>
			  				<span/>
                    </div>


                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Direcci&oacute;n correspondencia</span>
                        <span class="input-group-addon">
								<?php  echo "<input  id='dir_corre' value='$dircorres' style='text-transform:uppercase';  name='dir_corre'  type='text' class='form-control' placeholder='Direcci&oacute;n Correspondencia' width='14' height='10' >"; ?>
							</span>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Grupo</span>
                        <span class="input-group-addon">
									<select id="grupo" name="grupo" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
					   		 		Grupo
					   		 		<option></option>
                                        <?php
                                        $g= new Grupo();
                                        $grupos=$g->Todos();
                                        echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$codgrupo'>$descrigrupo</option>";
                                        while (oci_fetch($grupos)){
                                            $cod_grupo=oci_result($grupos,"COD_GRUPO");
                                            $descgrupo=oci_result($grupos,"DESC_GRUPO");
                                            echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$cod_grupo'>$descgrupo</option>";
                                        }
                                        ?>
					    			</select>
				    			</span>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Direcci&oacute;n</span>
                        <span class="input-group-addon">
								<?php echo "<input id='direccion' style='text-transform:uppercase';  value='$direccion' name='direccion' type='text' class='form-control' placeholder='Direcci&oacute;n' width='14' height='10'>";?>
							</span>
                    </div>

                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Email</span>
                        <span class="input-group-addon">
								<?php echo "<input id='email' style='text-transform:uppercase';  name='email' pattern='([a-zA-Z0-9._%+-]{3,30})+@([a-zA-Z0-9.-]{2,30})+\.[a-zA-Z]{2,4}$'  type='email' class='form-control' placeholder='Email' width='14' height='10' value'$emil' >"; ?>
                <span/>
                    </div>

                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Documento</span>
                        <span class="input-group-addon">
								<?php echo "<input id='documento' value='$documento' pattern='(([a-zA-Z0-9-]{0,2})+([0-9-]{5,13})$)|([A-Z0-9]{9})' name='documento' required type='text' class='form-control' placeholder='Documento' width='14' height='10'>";?>
							</span>
                    </div>

                    <p>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Correspondencia</span>
                        <span class="input-group-addon">
								<select  id="correspondencia" name="correspondencia" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
				    			<?php echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$correspondencia'>$correspondencia</option>"; ?>
                                    <span class="caret"></span>
				    			<option class="btn btn-default btn-sm dropdown-toggle" value='I'>I</option>
				    			<option class="btn btn-default btn-sm dropdown-toggle" value='S'>S</option>
				  				</select>
			  				</span>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Contribuyente DGII</span>
                        <span class="input-group-addon">
								<select  id="contribuyente" name="contribuyente_dgii" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" REQUIRED>
                                    <option class="btn btn-default btn-sm dropdown-toggle" value='S'>Sí</option>
				    			<option class="btn btn-default btn-sm dropdown-toggle" value='N'>No</option>
				  				</select>
			  				</span>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">

                    <p>
                        <!-- <div class="input-group input-group-sm">
                <span class="input-group-addon">Grupo</span>
                <span class="input-group-addon">
									<select id="grupo" name="grupo" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
					   		 		Grupo
					   		 		<option></option>
                                        <?php
                        /*                                        $g= new Grupo();
                                                                $grupos=$g->Todos();
                                                                echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$codgrupo'>$descrigrupo</option>";
                                                                while (oci_fetch($grupos)){
                                                                    $cod_grupo=oci_result($grupos,"COD_GRUPO");
                                                                    $descgrupo=oci_result($grupos,"DESC_GRUPO");
                                                                    echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$cod_grupo'>$descgrupo</option>";
                                                                }
                                                                */?>
					    			</select>
				    			</span>
            </div>-->

                    <h3 style="background-color:rgb(167,123,37); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos De Contrato</h3>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                            <p>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon" width="200" >Cod Sistema</span>
                                <span class="input-group-addon">
										<?php echo "<input size='300'  id='inmueble'  pattern='[0-9]{2,75}$'name='inmueble'   type='text' class='form-control' placeholder='id inmueble' width='14' height='10' value='$inmueble'>";?>
										</span>
                            </div>
                        </div>
                    </div>


                    <p>
                    <center>


                        <a>
                            <input type="submit" value="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(167,123,37);border-color:rgb(167,123,37)">
                        </a>&nbsp;&nbsp;
                        <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(167,123,37);border-color:rgb(167,123,37)">
                            Cancelar
                        </a>



                    </center>


                </div>
            </div>
        </div>
        </div>
    </form>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

