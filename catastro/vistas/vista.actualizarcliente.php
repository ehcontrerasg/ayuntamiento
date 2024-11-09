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
    include '../clases/class.parametros.php';
    include '../../destruye_sesion.php';

    $cod=$_SESSION['codigo'];
    $codcli=$_GET['cod_cliente'];
    $codcliente=$_POST['codigocli'];
    $inmueble=$_GET['inm'];
    $a=new parametros();
    $codcargo=$a->ObtenerCargo($_SESSION['codigo']);

    $c=new Cliente();
    $datoscli=$c->especifico($codcli,$inmueble,'');
    oci_fetch($datoscli);
    $nombre=oci_result($datoscli,"NOMBRE");
    $direccion=oci_result($datoscli,"DIRECCION");
    $telefono=oci_result($datoscli,"TELEFONO");
    $email=oci_result($datoscli,"EMAIL");
    $desctipodoc=oci_result($datoscli,"TIPO_DOC");
    $codigodoc=oci_result($datoscli,"CODIGO_DOC");
    $documento=oci_result($datoscli,"DOCUMENTO");
    $dircorrespondecia=oci_result($datoscli,"DIR_CORRESPONDENCIA");
    $codgrupo=oci_result($datoscli,"COD_GRUPO");
    $descrigrupo=oci_result($datoscli,"GRUPO");
    $correspondencia=oci_result($datoscli,"CORRESPONDENCIA");
    $contribuyente=oci_result($datoscli,"CONTRIBUYENTE_DGI");
    $inmueble=oci_result($datoscli,"CODIGO_INM");

    if($_POST['nombre']!=""){
        $n=new Cliente();
        $n->setcodigo($codcliente);
        $n->setnombre(strtoupper($_POST['nombre']));
        $n->setdireccion($_POST['direccion']);
        $n->settelefono($_POST['telefono']);
        $n->setemail($_POST['email']);
        $n->settipodoc($_POST['tipo_doc']);
        $n->setdocumento($_POST['documento']);
        $n->setcodgrupo($_POST['grupo']);
        $n->setdircorrespondencia($_POST['dir_corre']);
        $n->setcorrespondecia($_POST['correspondencia']);
        $n->setusrmod($cod);
        $n->setusuariocreacion($cod);
        $n->setfechamod(date("Y/m/d H:i:s"));
        $n->setContribuyenteDgi($_POST['contribuyente_dgii']);
        $res=$n->ActulizarCiente();
        if($res or $codcliente=='9999999' ){
            if (trim($_POST['email']<>'')){
                $c= new Cliente();
                $correoActualizado = $c->ActulizarCorreo($_POST['email'],$_POST["codigoinm"]);
                if (!$correoActualizado) {
                    $ms=$n->geterror();
                    echo "<script languaje='javascript' type='text/javascript'>
                    alert('$ms');
                    </script>";
                    return false;
                }
            }

            if (trim($_POST['telefono']<>'')){
                $c= new Inmnueble();
                $c->ActulizarTel($_POST['telefono'],$_POST["codigoinm"]);
            }


            echo "<script languaje='javascript' type='text/javascript'>
			javascript:window.opener.document.location.reload();
            javascript:window.close();
            </script>";
        }else{
            $ms=$n->geterror();
            echo "<script languaje='javascript' type='text/javascript'>
			alert('$ms');
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
        <link href="../../css/estilonuevo.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>


    <body  				  >
    <p>
        <form  action="vista.actualizarcliente.php?" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Actualizar Cliente</center> </h3>
                <div class="panel_mody" ><center>Remplace los datos que desea actualizar.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <span class="input-group-addon rotulo">Nombre</span>
                        <span class="input-group-addon">
							<?php
                            if(($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5) and $documento<>'9999999' and $documento<>'0000000')
                            {
                                echo "<input style='text-transform:uppercase'  id='nombre' value='$nombre'  name='nombre'  required type='text' class='form-control taminputs ' placeholder='Sin Datos'  >";
                            }else
                            {
                                echo "<input style='text-transform:uppercase'  id='nombre' value='$nombre'  name='nombre'  required type='text' readonly class='form-control taminputs ' placeholder='Sin Datos'  >";
                            }
                            ?>

							</span>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">Tel&eacute;fono</span>
                        <span class="input-group-addon">
									<?php
                                    if($documento<>'0000000')
                                    {
                                        echo"<input id='telefono' name='telefono' type='text' pattern='[0-9]{7,13}$' class='form-control taminputs' placeholder='Sin Telefono' width='14' height='10' value='$telefono'>";
                                    }else
                                    {
                                        echo"<input readonly id='telefono' name='telefono' type='text' pattern='[0-9]{7,13}$' class='form-control taminputs' placeholder='Sin Telefono' width='14' height='10' value='$telefono'>";
                                    }
                                    ?>


							</span>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">Tipo Documento</span>
                        <span class="input-group-addon">
							 <?php
                             if(($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5 or $codcargo==300 or $codcargo==301) and $documento!='9999999' and $documento!='0000000')
                             {
                                 echo "<select id='tipo_doc' required  name='tipo_doc' class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";
                             }else
                             {
                                 echo "<select id='tipo_doc' required  name='tipo_doc' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' class='btn btn-default btn-sm dropdown-toggle taminputs input-des' type='button' data-toggle='dropdown' aria-expanded='true'>";
                             }

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

                            </span>
                    </div>



                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">Direcci&oacute;n correspondencia</span>
                        <span class="input-group-addon">
								<?php
                                if(($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5) and $documento<>'9999999' and $documento<>'0000000')
                                {
                                    echo "<input style='text-transform:uppercase';   id='dir_corre' name='dir_corre'  type='text' class='form-control taminputs' placeholder='Sin Datos' width='14' height='10' value='$dircorrespondecia'>";
                                }else
                                {
                                    echo "<input style='text-transform:uppercase';   id='dir_corre' name='dir_corre'  type='text' class='form-control taminputs' placeholder='Sin Datos' width='14' readonly height='10' value='$dircorrespondecia'>";
                                }

                                ?>
							</span>
                    </div>



                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <span class="input-group-addon rotulo">Direcci&oacute;n</span>
                        <span class="input-group-addon">
								<?php
                                if(($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5) and $documento<>'9999999' and $documento<>'0000000')
                                {
                                    echo" <input style='text-transform:uppercase';  id='direccion' value='$direccion' name='direccion' type='text' class='form-control taminputs' placeholder='Sin Datos' width='14' height='10'>";
                                }else
                                {
                                    echo" <input style='text-transform:uppercase';  id='direccion' readonly value='$direccion' name='direccion' type='text' class='form-control taminputs' placeholder='Sin Datos' width='14' height='10'>";
                                }

                                ?>
							</span>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">Email</span>
                        <span class="input-group-addon">
								<?php
                                if($documento<>'0000000')
                                {
                                    echo " <input style='text-transform:uppercase';  id='email' name='email' pattern='([a-zA-Z0-9._%+-]{2,30})+@([a-zA-Z0-9.-]{2,30})+\.[a-zA-Z]{2,4}$'  type='email' class='form-control taminputs' placeholder='Sin Datos' width='14' height='10' value='$email'>";
                                }else
                                {
                                    echo " <input style='text-transform:uppercase';  id='email' name='email' pattern='([a-zA-Z0-9._%+-]{2,30})+@([a-zA-Z0-9.-]{2,30})+\.[a-zA-Z]{2,4}$'  type='email' class='form-control taminputs'  placeholder='Sin Datos' width='14' height='10' value='$email'>";
                                }
                                ?>
                            <span/>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">Documento</span>
                        <span class="input-group-addon">
								<?php
                                if(($codcargo==1 or $codcargo==5 or $codcargo==300 or $codcargo==301) and $documento<>'9999999' and $documento<>'0000000')
                                {
                                    echo "<input id='documento' value='$documento'  name='documento' pattern='([a-zA-Z0-9-]{0,2})+([0-9-]{5,13})$'required type='text' class='form-control taminputs' placeholder='Sin Datos' width='14' height='10' '>";
                                }else
                                {
                                    echo "<input id='documento' value='$documento' readonly name='documento' pattern='([a-zA-Z0-9-]{0,2})+([0-9-]{5,13})$' required type='text' readonly class='form-control taminputs' placeholder='Sin Datos' width='14' height='10' '>";
                                }
                                ?>
							</span>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">Correspondencia</span>
                        <span class="input-group-addon">
								<?php
                                if(($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5) and $documento<>'9999999' and $documento<>'0000000')
                                {
                                    echo "<select  id='correspondencia' name='correspondencia' class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='false'>";
                                }else
                                {
                                    echo "<select  id='correspondencia' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' name='correspondencia' class='btn btn-default btn-sm dropdown-toggle taminputs input-des' type='button' data-toggle='dropdown' aria-expanded='false'";
                                }
                                ?>

                            <span class="caret"></span>
                            <?php echo "<option class='btn btn-default btn-sm dropdown-toggle' value='$correspondencia'>$correspondencia</option>"; ?>
                            <option class="btn btn-default btn-sm dropdown-toggle" value='I'>I</option>
				    			<option class="btn btn-default btn-sm dropdown-toggle" value='S'>S</option>
                            </select>
			  				</span>
                    </div>





                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon rotulo">grupo</span>
                        <span class="input-group-addon">

									<?php
                                    if(($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5) and $documento<>'9999999' and $documento<>'0000000')
                                    {
                                        echo "<select  id='grupo' name='grupo' class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='false'>";
                                    }else
                                    {
                                        echo " <select  id='grupo' name='grupo' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'  class='btn btn-sm dropdown-toggle taminputs input-des ' type='button' data-toggle='dropdown' aria-expanded='false'>";
                                    }






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
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <span class="input-group-addon">Contribuyente DGII</span>
                        <span class="input-group-addon">
								<select  id="contribuyente" name="contribuyente_dgii" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"  REQUIRED>
                                    <option class="btn btn-default btn-sm dropdown-toggle" value=''></option>
                                    <?if($contribuyente=="S"){?>
                                        <option class="btn btn-default btn-sm dropdown-toggle" value='S' selected>Sí</option>
                                    <?}else{?>
                                        <option class="btn btn-default btn-sm dropdown-toggle" value='S'>Sí</option>
                                    <?}?>
                                    <?if($contribuyente=="N"){?>
                                        <option class="btn btn-default btn-sm dropdown-toggle" value='N' selected >No</option>
                                    <?}else{?>
                                        <option class="btn btn-default btn-sm dropdown-toggle" value='N' >No</option>
                                    <?}?>


                            </select>
				    			</span>
                    </div>
                </div>

    <p>
        <center>

            <a>
                <input type="submit" value="Actualizar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
            </a>&nbsp;&nbsp;
            <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                Cancelar
            </a>
        </center>





        </div>
        <?php echo"<input  style='visibility:hidden' id='codigocli' name='codigocli' type='text' class='form-control' placeholder='Sin Datos' width='14' height='10' value='$codcli'>";?>
        <?php echo"<input  style='visibility:hidden' id='codigoinm' name='codigoinm' type='text' class='form-control' placeholder='Sin Datos' width='14' height='10' value='$inmueble'>";?>
        </form>
    </body>
    </html>

    <script language="javascript">


    </script>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

