<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include('../../destruye_sesion.php');
    include '../clases/class.inmueble.php';
    include '../clases/class.proyecto.php';
    include '../clases/class.urbanizacion.php';
    include '../clases/class.via.php';
    include '../clases/class.parametros.php';
    include '../clases/class.nombrevia.php';
    include '../clases/class.sector.php';
    include '../clases/class.ruta.php';
    include '../clases/class.zona.php';
    include '../clases/class.uso.php';
    include '../clases/class.actividad.php';
    include '../clases/class.medidor.php';
    include '../clases/class.suministro.php';
    include '../clases/class.emplazamiento.php';
    include '../clases/class.concepto.php';
    include '../clases/class.tarifa.php';
    include '../clases/class.calculo.php';
    include '../clases/class.medidor_inmueble.php';
    include '../clases/class.concepto_inmueble.php';
    include '../clases/class.grupo.php';

    $a=new parametros();
    $codcargo=$a->ObtenerCargo($_SESSION['codigo']);
    if($_GET['cod_sistema']!=""){
        $codigo_sistema=$_GET['cod_sistema'];
        $l=new Inmnueble();
        $datos_inm=$l->inmespecifico($codigo_sistema);
        oci_fetch($datos_inm);
        $proyecto=oci_result($datos_inm,"SIGLA_PROYECTO");
        $id_proyecto=oci_result($datos_inm,"ID_PROYECTO");
        $sector=oci_result($datos_inm,"ID_SECTOR");
        $cod_ruta=oci_result($datos_inm,"ID_RUTA");
        $grupo=oci_result($datos_inm,"GRUPO");
        $cod_zona=oci_result($datos_inm,"ID_ZONA");
        $idproceso=oci_result($datos_inm,"ID_PROCESO");
        $catastro=oci_result($datos_inm,"CATASTRO");
        $peralta=oci_result($datos_inm,"PER_ALTA");
        $diametro=oci_result($datos_inm,"COD_DIAMETRO");
        $cod_urbanizacion=oci_result($datos_inm,"CONSEC_URB");
        $estado=oci_result($datos_inm,"ID_ESTADO");
        $urbanizaciones=oci_result($datos_inm,"DESC_URBANIZACION");
        $telefono=oci_result($datos_inm,"TELEFONO");
        $tipo_via=oci_result($datos_inm,"CALLE");
        $nombre_via=oci_result($datos_inm,"NOM_CALLE");
        $numerocasa=oci_result($datos_inm,"NUMERO");
        $nombrecli=oci_result($datos_inm,"ALIAS");
        $documento=oci_result($datos_inm,"DOCUMENTO");
        $codigocli=oci_result($datos_inm,"CODIGO_CLI");
        $fianza=oci_result($datos_inm,"FIANZA_PAGA");
        $tipoCli=oci_result($datos_inm,"ID_TIPO_CLIENTE");
        $zonFra=oci_result($datos_inm,"ZONA_FRANCA");
        if($documento=='9999999' || $documento=='0000000'){
            $documento='SIN DOCUMENTO';
        }
        if($numerocasa==0){
            $numerocasa="";
        }
        $subnumerocasa=oci_result($datos_inm,"SUB_NUMERO");
        if($subnumerocasa==0){
            $subnumerocasa="";
        }

        $bloquecasa=oci_result($datos_inm,"BLOQUE");
        $apto=oci_result($datos_inm,"APARTAMENTO");
        $complemento=oci_result($datos_inm,"COMPLEMENTO");
        $direccion=oci_result($datos_inm,"DIRECCION");
    }
    else{
        $documento=$_POST['documento'];
        $codigo_sistema=$_POST['cod_sistema'];
        $l=new Inmnueble();
        $datos_inm=$l->inmespecifico($codigo_sistema);
        oci_fetch($datos_inm);
        $proc = $_POST['proc'];
        $fianza=$_POST['fianza'];
        $nombrecli = $_POST['nombrecli'];
        $id_proyecto=oci_result($datos_inm,"ID_PROYECTO");
        $proyecto=oci_result($datos_inm,"SIGLA_PROYECTO");
        $peralta=$_POST['peralta'];
        $direccion=oci_result($datos_inm,"DIRECCION");
        $urbanizacion=$_POST['urbanizacion'];
        $tipo_via=$_POST['tipo_via'];
        $nombre_via=$_POST['nom_via'];
        $numerocasa=$_POST['numerocasa'];
        $subnumerocasa=$_POST['subnumerocasa'];
        $bloquecasa=$_POST['bloquecasa'];
        $apto=$_POST['apartamento'];
        $estado=$_POST['estado'];
        $complemento=$_POST['complemento'];
        $sector=$_POST['sector'];
        $ruta=$_POST['ruta'];
        $idproceso=$_POST['idproceso'];
        $catastro=$_POST['catastro'];
        $zona=$_POST['zona'];
        $telefono=$_POST['telefono'];
        $diametro=$_POST['diametro'];
        $tipoCli=$_POST['tipCli'];
        $grupo=$_POST['grupo'];
        $zonFra=$_POST['zonFra'];





        if(trim($tipo_via)!=""){
            $t=new parametros();
            $direcciondef=$t->ObtenerDesTipovia('SD', $tipo_via)." "/*.$nombre_via." ".$numerocasa." ".$subnumerocasa." ".$bloquecasa." ".$apto." ".$complemento*/;
        }

        if(trim($nombre_via)!=""){
            $t=new parametros();
            $direcciondef=$direcciondef.$t->ObtenerDesNomvia($nombre_via)." "/*.$nombre_via." ".$numerocasa." ".$subnumerocasa." ".$bloquecasa." ".$apto." ".$complemento*/;
        }

        if(trim($numerocasa)!=""){
            $direcciondef=$direcciondef."No. ".$numerocasa." "/*.$subnumerocasa." ".$bloquecasa." ".$apto." ".$complemento*/;
        }

        if(trim($subnumerocasa)!=""){
            $t=new parametros();
            $direcciondef=$direcciondef.$t->ObtenerDescsubnum($subnumerocasa)." "/*.$subnumerocasa." ".$bloquecasa." ".$apto." ".$complemento*/;
        }

        if(trim($bloquecasa)!=""){
            $direcciondef=$direcciondef."BLOQUE."." ".$bloquecasa." "/*.$apto." ".$complemento*/;
        }

        if(trim($apto)!=""){
            $direcciondef=$direcciondef."APTO."." ".$apto." "/*.$apto." ".$complemento*/;
        }

        if(trim($complemento)!=""){
            $direcciondef=$direcciondef.$complemento/*.$apto." ".$complemento*/;
        }



        $Continuar =$_POST['Continuar'];
        if($proc==1){
            $a=new Inmnueble();
            $a->setcod_inm($codigo_sistema);
            $a->setdireccion($direcciondef);
            $a->seturbanizacion($urbanizacion);
            $a->setid_sector($sector);
            $a->setid_ruta($ruta);
            $a->setid_proceso($idproceso);
            $a->setcatastro($catastro);
            $a->setid_zona($zona);
            $a->setusuario_creacion($_SESSION['codigo']);
            $a->setid_ciclo(substr($zona, -1));
            $a->settipovia($tipo_via);
            $a->setnomvia($nombre_via);
            $a->setnumero($numerocasa);
            $a->setsubnum($subnumerocasa);
            $a->setbloque($bloquecasa);
            $a->setapartamento($apto);
            $a->setcomplemento($complemento);
            $a->setid_estado($estado);
            $a->setper_alta($peralta);
            $a->settelefono($telefono);
            $bandera=$a->actualizar_inmueble($fianza,$diametro,$tipoCli,$grupo,$zonFra);
            $mensaje=$a->getMsError();
            if($a->getCodRes()>0){


                echo "
			<script type='text/javascript'>
			if(confirm('Error al intentar actualizar el inmueble $codigo_sistema contacte a sistemas $mensaje. ')){
			window.close();
			}else{
			window.close();
			}
				
			</script>";

            }
            else{
                echo '<script> opener.document.location.reload(); </script>';
                echo "
		<script type='text/javascript'>
		if(confirm('el inmueble con codigo de sistema $codigo_sistema ha sido actuaizado.')){
		window.close();
		}else{
		window.close();
		}
			
		</script>";
            }
        }




    }




    ?>


    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link href="../../css/estilonuevo.css" rel="stylesheet">
        <title>ACEASOFT</title>
    <body >
    <p>
        <form  id="agregainm" name="agregainm" onSubmit="return infomante();" action="vista.actualizarinmueble.php" autocomplete="on" method="post"  class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <center><div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                    <h3 class="panel-heading" style="background-color:rgb(163,73,163); border-color:rgb(163,73,163)"> Actualizar datos Inmueble</h3>

                    <div class="panel_mody" >Diligencie la informaci&oacute;n del formulario.
                        <div class="container">
    <p><p>

    <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
    <div class="row" >
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">C&oacute;digo Sistema</span>
            <span class="input-group-addon"><input type="text"  id='cod_sistema' name='cod_sistema' class="form-control taminputs2" value="<?php echo $codigo_sistema;?>"  readonly ></span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Proyecto</span>

            <span class="input-group-addon"><input type="text" class="form-control taminputs2" value="<?php echo $proyecto;?>"  readonly ></input></span>
            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px;">Nombre</span>
            <span class="input-group-addon"><input type="text"  class="form-control taminputs2" style="font-size:10px; text-align:center" id='nombrecli' name='nombrecli'  value="<?php echo $nombrecli;?>"  readonly ></input></span>
            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Documento</span>
            <span class="input-group-addon"><input type="text"  ondblclick="actualizarcli(<?php echo $codigocli;?>, <?php echo $codigo_sistema?>);"  class="form-control taminputs2" id='documento' name='documento'  value="<?php echo $documento;?>"  readonly ></input></span>
            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Grupo</span>
            <span class="input-group-addon">

                            <?php
                            if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                echo "<select id='grupo' required name='grupo'  class='btn btn-default btn-sm dropdown-toggle taminputs'   type='button' data-toggle='dropdown' aria-expanded='true'>";
                            }else{
                                echo "<select id='grupo' required name='grupo' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'    class='btn input-des  btn-sm dropdown-toggle taminputs'   type='button' data-toggle='dropdown' aria-expanded='true'>";
                            }

                            ?>

                <option></option>
                <?php

                echo "<option value='$cod_grupo' selected>$grupo</option>\n";
                $p=new Grupo();

                $stid = $p->Todos();
                while (oci_fetch($stid)) {
                    $cod_grupo = oci_result($stid, 'COD_GRUPO') ;
                    $grupos = oci_result($stid, 'DESC_GRUPO') ;
                    if($cod_grupo == $grupo) echo "<option value='$cod_grupo' selected>$grupos</option>\n";
                    else echo "<option value='$cod_grupo'>$grupos</option>\n";
                }oci_free_statement($stid);
                ?>
                </select></span>
            </span>
        </div>



    </div>

    <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Informaci&oacute;n Vial</b></h3>


    <div class="row" >
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;">Direccion</span>
            <span class="input-group-addon"><input style='text-transform:uppercase';  type="text" class="form-control" value="<?php echo $direccion;?>" readonly ></span>
        </div>
        <p>
    </div>

    <div class="row" >

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">


            <span class="input-group-addon" style="font-size:12px;width:130px">Urbanizaci&oacute;n</span>
            <span class="input-group-addon" style="font-size:12px">

		             <?php
                     if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                         echo "<select id='urbanizacion' required name='urbanizacion'  class='btn btn-default btn-sm dropdown-toggle taminputs'   type='button' data-toggle='dropdown' aria-expanded='true'>";
                     }else{
                         echo "<select id='urbanizacion' required name='urbanizacion' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'    class='btn input-des  btn-sm dropdown-toggle taminputs'   type='button' data-toggle='dropdown' aria-expanded='true'>";
                     }

                     ?>

                <option></option>
                <?php

                echo "<option value='$cod_urbanizacion' selected>$urbanizaciones</option>\n";
                $p=new Urbanizacion();

                $stid = $p->obtenerurbanizacion($id_proyecto);
                while (oci_fetch($stid)) {
                    $cod_urbanizacion = oci_result($stid, 'CONSEC_URB') ;
                    $urbanizaciones = oci_result($stid, 'DESC_URBANIZACION') ;
                    if($cod_urbanizacion == $urbanizacion) echo "<option value='$cod_urbanizacion' selected>$urbanizaciones</option>\n";
                    else echo "<option value='$cod_urbanizacion'>$urbanizaciones</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>
		            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">


            <span class="input-group-addon" style="font-size:12px;width:130px">Tipo V&iacute;a</span>
            <span class="input-group-addon" style="font-size:12px">
		            <?php
                    if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                        echo "<select id='tipo_via'  name='tipo_via' class='btn btn-default btn-sm dropdown-toggle taminputs' required onChange='javascript:agregainm.submit();'  type='button' data-toggle='dropdown' aria-expanded='true'>";
                    }else{
                        echo "<select id='tipo_via'  onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' name='tipo_via' class='btn input-des btn-sm dropdown-toggle taminputs' required onChange='javascript:agregainm.submit();'  type='button' data-toggle='dropdown' aria-expanded='true'>";
                    }

                    ?>

                <option></option>
                <?php
                $p=new Via();
                $stid = $p->obtenervia($id_proyecto);
                while (oci_fetch($stid)) {
                    $cod_via= oci_result($stid, 'ID_TIPO_VIA') ;
                    $tvia = oci_result($stid, 'DESC_TIPO_VIA') ;
                    if($cod_via == $tipo_via){ echo "<option value='$cod_via' selected>$tvia</option>\n";}
                    else echo "<option value='$cod_via'>$tvia</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>
		            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">

            <span class="input-group-addon" style="font-size:12px;width:130px">Nombre Via</span>
            <span class="input-group-addon" style="font-size:12px">
		            	 <?php
                         if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                             echo "<select   id='nom_via' name='nom_via' class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='false'>";
                         }else{
                             echo "<select  id='nom_via' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' name='nom_via' class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='false'>";
                         }

                         ?>


                <option></option>
                <?php
                $p=new NombreVia();
                $stid = $p->obtenernvia($id_proyecto, $tipo_via);
                while (oci_fetch($stid)) {
                    $cod_nvia= oci_result($stid, 'CONSEC_NOM_VIA') ;
                    $nvia = oci_result($stid, 'DESC_NOM_VIA') ;
                    if($cod_nvia == $nombre_via){ echo "<option value='$cod_nvia' selected>$nvia</option>\n"; }
                    else echo "<option value='$cod_nvia'>$nvia</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>
		            </span>
        </div>
    </div>

    <div class="row" >
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">N&uacute;mero</span>
            <span class="input-group-addon" style="font-size:12px">

		            		 <?php


                             if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                 echo "<input  pattern='[0-9]{1,4}$'  id='numerocasa' value='$numerocasa' name='numerocasa'  type='text' class='form-control taminputs' placeholder='N&uacute;mero' width='14' height='10' >";
                             }else{
                                 echo "<input  pattern='[0-9]{1,4}$'  readonly id='numerocasa' value='$numerocasa' name='numerocasa'  type='text' class='form-control taminputs' placeholder='N&uacute;mero' width='14' height='10' >";
                             }

                             ?>

		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Sub N&uacute;mero</span>
            <span class="input-group-addon" style="font-size:12px">
		              <?php
                      if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                          echo "<select id='subnumerocasa'   name='subnumerocasa' class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";
                      }else{
                          echo "<select id='subnumerocasa'  onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'  name='subnumerocasa' class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";

                      }

                      ?>


                <option></option>
                <?php
                $p=new parametros();
                $stid = $p->obtenersubnumeros();
                while (oci_fetch($stid)) {
                    $cod_subnum= oci_result($stid, 'ID_SUBNUM') ;
                    $nsubnum = oci_result($stid, 'DESCRIPCION') ;
                    if($cod_subnum == $subnumerocasa){ echo "<option value='$cod_subnum' selected>$nsubnum</option>\n"; }
                    else echo "<option value='$cod_subnum'>$nsubnum</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>

		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Bloque</span>
            <span class="input-group-addon" style="font-size:12px">

		                <?php
                        if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                            echo "<input  id='bloquecasa'  style='text-transform:uppercase';  value='$bloquecasa' name='bloquecasa'  type='text' class='form-control taminputs' placeholder='Bloque' width='14' height='10' >";
                        }else{

                            echo "<input  id='bloquecasa'  readonly style='text-transform:uppercase';  value='$bloquecasa' name='bloquecasa'  type='text' class='form-control taminputs' placeholder='Bloque' width='14' height='10' >";


                        }

                        ?>




		            </span>
        </div>
    </div>

    <div class="row" >
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Apartamento</span>
            <span class="input-group-addon" style="font-size:12px">

		            	      <?php
                              if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                  echo "<input  id='apartamento' style='width:130px' style='text-transform:uppercase';  value='$apto' name='apartamento'  type='text' class='form-control taminputs' placeholder='Apartamento' width='14' height='10' >";
                              }else{

                                  echo "<input  id='apartamento' style='text-transform:uppercase';  value='$apto' name='apartamento' readonly  type='text' class='form-control taminputs' placeholder='Apartamento' width='14' height='10' >";
                              }

                              ?>


		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Complemento</span>
            <span class="input-group-addon" style="font-size:12px">
		             	      <?php
                              if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                  echo "<input  style='text-transform:uppercase'  id='complemento' value='$complemento' name='complemento'  type='text' class='form-control taminputs' placeholder='Complemento' width='14' height='10' >";
                              }else{

                                  echo "<input  style='text-transform:uppercase';  id='complemento' value='$complemento' name='complemento' readonly type='text' class='form-control taminputs' placeholder='Complemento' width='14' height='10' >";
                              }

                              ?>

		            </span>
        </div>
    </div>

    <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos Inmueble</b></h3>
    <div class="row" >
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Sector</span>
            <span class="input-group-addon" style="font-size:12px">
		              <?php
                      if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                          echo "<select id='tipo_doc' required name='sector' onChange='javascript:agregainm.submit();' type='button' class='btn btn-default btn-sm dropdown-toggle taminputs'  data-toggle='dropdown' aria-expanded='true'>";
                      }else{
                          echo "<select id='tipo_doc'  onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' required name='sector' onChange='javascript:agregainm.submit();' type='button' class='btn input-des btn-sm dropdown-toggle taminputs'  data-toggle='dropdown' aria-expanded='true'>";
                      }

                      ?>


                <?php
                echo "<option value='$id_sector'>$id_sector</option>\n";
                $p=new Sector();
                $stid = $p->obtenersectores($id_proyecto);
                while (oci_fetch($stid)) {
                    $cod_sector= oci_result($stid, 'ID_SECTOR') ;
                    if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                    else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>
		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Ruta</span>
            <span class="input-group-addon" style="font-size:12px">

		               <?php
                       if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                           echo "<select id='ruta' name='ruta' onChange='javascript:agregainm.submit();' required class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";
                       }else{
                           echo " <select id='ruta'  onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' name='ruta' onChange='javascript:agregainm.submit();' required class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";
                       }

                       ?>


                <?php
                echo "<option value='$cod_ruta'>$cod_ruta</option>\n";
                $p=new Ruta();
                $stid = $p->obtenerrutas($id_proyecto, $sector);
                while (oci_fetch($stid)) {
                    $cod_ruta= oci_result($stid, 'ID_RUTA') ;
                    if($cod_ruta == $ruta) echo "<option value='$cod_ruta' selected>$cod_ruta</option>\n";
                    else echo "<option value='$cod_ruta'>$cod_ruta</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>
		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Id Proceso</span>
            <span class="input-group-addon" style="font-size:12px">

		            		 <?php


                             if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                 echo "<input  id='idproceso' value='$idproceso' name='idproceso' pattern='[0-9]{9,12}$' required type='text' class='form-control taminputs' placeholder='Id Proceso' width='14' height='10'  >";
                             }else{
                                 echo "<input  id='idproceso'  value='$idproceso' name='idproceso' pattern='[0-9]{9,12}$' required readonly type='text' class='form-control taminputs' placeholder='Id Proceso' width='14' height='10'  >";
                             }

                             ?>

                             <?php  ?>
		            </span>
        </div>
    </div>

    <div class="row" >
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Zona</span>
            <span class="input-group-addon" style="font-size:12px">

		              <?php
                      if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                          echo "<select id='zona' required  name='zona' class='btn btn-default btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";
                      }else{
                          echo "<select id='zona' required  name='zona' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;' class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";

                      }

                      ?>


                <?php
                echo "<option value='$cod_zona'>$cod_zona</option>\n";
                $p=new Zona();
                $stid = $p->obtenerzonas($id_proyecto, $sector);
                while (oci_fetch($stid)) {
                    $cod_zona= oci_result($stid, 'ID_ZONA') ;
                    if($cod_zona == $zona){ echo "<option value='$cod_zona' selected>$cod_zona</option>\n";}
                    else echo "<option value='$cod_zona'>$cod_zona</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>
		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Catastro</span>
            <span class="input-group-addon" style="font-size:12px">

		            		 <?php


                             if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                 echo "<input  id='catastro' value='$catastro'  name='catastro'  pattern='[0-9-]{15,20}$' required type='text' class='form-control taminputs' placeholder='Catastro' width='14' height='10'  >";
                             }else{
                                 echo "<input  id='catastro' value='$catastro'  name='catastro'  pattern='[0-9-]{15,20}$' readonly required type='text' class='form-control taminputs' placeholder='Catastro' width='14' height='10'  >";
                             }

                             ?>
                             <?php   ?>
		            </span>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Periodo Alta</span>
            <span class="input-group-addon" style="font-size:12px">

		            		 <?php


                             if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                 echo "<input  id='peralta'  value='$peralta' name='peralta'  type='text' class='form-control taminputs' placeholder='periodo alta' width='14' height='10' >";
                             }else{
                                 echo "<input  id='peralta'  value='$peralta' name='peralta' readonly  type='text' class='form-control taminputs' placeholder='periodo alta' width='14' height='10' >";
                             }

                             ?>


		            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Estado</span>
            <span class="input-group-addon" style="font-size:12px">

		               <?php
                       if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                           echo "<select id='estado' required  name='estado' class='btn btn-default btn-sm dropdown-toggle taminputs ' type='button' data-toggle='dropdown' aria-expanded='true'>";
                       }else{
                           echo "<select id='estado' required  name='estado' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'  class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";

                       }

                       ?>


                <?php

                $p=new parametros();
                $stid = $p->obtenerestados();
                while (oci_fetch($stid)) {
                    $cod_estado= oci_result($stid, 'ID_ESTADO') ;
                    $desc_estado= oci_result($stid, 'DESC_ESTADO') ;
                    if($cod_estado == $estado){ echo "<option value='$cod_estado' selected>$desc_estado</option>\n";}
                    else echo "<option value='$cod_estado'>$desc_estado</option>\n";
                }oci_free_statement($stid);
                ?>
                </select>

		            </span>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Telefono</span>
            <span class="input-group-addon" style="font-size:12px">

		            		 <?php

                             if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                 echo "<input  id='telefono'  value='$telefono'  name='telefono' pattern='[0-9]{0,12}$'  type='text' class='form-control taminputs' placeholder='Telefono' width='14' height='10' >";
                             }else{
                                 echo "<input  id='telefono' value='$telefono'  name='telefono' pattern='[0-9]{0,12}$' readonly  type='text' class='form-control taminputs' placeholder='Telefono' width='14' height='10' >";
                             }

                             ?>

		           			</span>
        </div>



        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Fianza</span>
            <span class="input-group-addon" style="font-size:12px">

		            		 <?php

                             if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                                 echo "<input  id='fianza'  value='$fianza'  name='fianza' pattern='[0-9]{0,12}$'  type='text' class='form-control taminputs' placeholder='fianza' width='14' height='10' >";
                             }else{
                                 echo "<input  id='fianza' value='$fianza'  name='fianza' pattern='[0-9]{0,12}$' readonly  type='text' class='form-control taminputs' placeholder='fianza' width='14' height='10' >";
                             }

                             ?>
                            </span>

        </div>



    </div>

    <div class="row" >
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Diametro</span>
            <span class="input-group-addon" style="font-size:12px">

		               <?php
                       if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                           echo "<select id='diametro' required  name='diametro' class='btn btn-default btn-sm dropdown-toggle taminputs ' type='button' data-toggle='dropdown' aria-expanded='true'>";
                       }else{
                           echo "<select id='diametro' required  name='diametro' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'  class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";

                       }


                       $p=new parametros();
                       $stid = $p->obtenereDiametros();
                       while (oci_fetch($stid)) {
                           $cod_diametro= oci_result($stid, 'COD_CALIBRE') ;
                           $desc_diametro= oci_result($stid, 'DESC_CALIBRE') ;
                           if($cod_diametro == $diametro){ echo "<option value='$cod_diametro' selected>$desc_diametro</option>";}
                           else echo "<option value='$cod_diametro'>$desc_diametro</option>";
                       }oci_free_statement($stid);
                       ?>



                </select>

		            </span>
        </div>


        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Tipo cliente</span>
            <span class="input-group-addon" style="font-size:12px">

		               <?php
                       if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                           echo "<select id='tipCli' required  name='tipCli' class='btn btn-default btn-sm dropdown-toggle taminputs ' type='button' data-toggle='dropdown' aria-expanded='true'>";
                       }else{
                           echo "<select id='tipCli' required  name='tipCli' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'  class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";

                       }


                       $p=new parametros();
                       $stid = $p->obtenererTipoCliente();
                       while (oci_fetch($stid)) {
                           $cod_tipoCli= oci_result($stid, 'CODIGO') ;
                           $desc_tipoCli= oci_result($stid, 'DESCRIPCION') ;
                           if($cod_tipoCli == $tipoCli){ echo "<option value='$cod_tipoCli' selected>$desc_tipoCli</option>";}
                           else echo "<option value='$cod_tipoCli'>$desc_tipoCli</option>";
                       }oci_free_statement($stid);
                       ?>



                </select>

		            </span>
        </div>



        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align:left">
            <span class="input-group-addon" style="font-size:12px;width:130px">Zona Franca</span>
            <span class="input-group-addon" style="font-size:12px">

		               <?php
                       if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
                           echo "<select id='zonFra' required  name='zonFra' class='btn btn-default btn-sm dropdown-toggle taminputs ' type='button' data-toggle='dropdown' aria-expanded='true'>";
                       }else{
                           echo "<select id='zonFra' required  name='zonFra' onfocus='this.defaultIndex=this.selectedIndex;' onchange='this.selectedIndex=this.defaultIndex;'  class='btn input-des btn-sm dropdown-toggle taminputs' type='button' data-toggle='dropdown' aria-expanded='true'>";

                       }


                       if($zonFra=='S'){
                           echo "<option value='S' selected>SI</option>";
                           echo "<option value='N'>NO</option>";
                       }
                       if($zonFra=='N'){
                           echo "<option value='S' >SI</option>";
                           echo "<option value='N' selected>NO</option>";
                       }
                       ?>



                </select>

		            </span>
        </div>



    </div>





    <p>
    <center><input type="submit" value="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">&nbsp;&nbsp; <a class="btn btn-primary btn-lg" href="#" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">Cancelar</a></center></p></center></p>
    <input type="hidden" name="proc" value="<?php echo $proc;?>">
    </div>
    </div>
    </center>
    </p>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    </form>

    </body>
    </html>

    <script language="javascript">

        function infomante(){
            document.agregainm.proc.value = 1;
            return true;
        }


        function actualizarcli(id,id2)
        {

            popup("vista.actualizarcliente.php?cod_cliente="+id+"&inm="+id2,800,600,'yes');
        }

        var popped = null;
        function popup(uri2, awid, ahei, scrollbar) {

            var params;
            if (uri2 != "") {
                if (popped && !popped.closed) {

                    popped.location.href = uri2;
                    popped.focus();
                }
                else {

                    params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                    popped = window.open(uri2, "popup", params);
                }
            }
        }


        $(document).ready(function(){

            $(document).bind("contextmenu",function(e)
            {
                return false;
            });

        });

    </script>




<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

