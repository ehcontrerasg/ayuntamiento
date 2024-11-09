<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.cliente.php';
    include '../clases/class.inmueble.php';
    include '../clases/class.contrato.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];
    $Doccli=$_POST['documento'];
    $AlCli=$_POST['alias'];
    $FecCon=$_POST['fecsol'];
    $Sinmueble2=$_POST['SInmueble'];
    $l= new Cliente();
    $registro=$l->obtenercocli($Doccli);
    oci_fetch($registro);
    $codcliente=oci_result($registro,"CODIGO_CLI");
    $i=new Inmnueble();
    $registro2=$i->existe($Sinmueble2);
    oci_fetch($registro2);
    $codinmueble=oci_result($registro2,"CODIGO_INM");

    if($Doccli!=""){
        if($codcliente=="")
        {
            echo " 
	                <script language='JavaScript'> 
	                alert(' el documento $Doccli no existe'); 
	                </script>";
        }else{
            if($codinmueble==$Sinmueble2){
                $c=new Contrato();
                $c->setcod_inm($codinmueble);
                $numerocontratos=$c->existe();
                if($numerocontratos>=1){
                    echo "
				<script language='JavaScript'>
				alert(' el inmueble $inmueble ya tiene un contrato activo');
				</script>";
                }
                else{

                    $inm=new Inmnueble();								//
                    $inmueble=$_POST['SInmueble'];						//
                    $codigoim=$inm->existe($inmueble);					//
                    oci_fetch($codigoim);								//
                    $proyecto=oci_result($codigoim,"ID_PROYECTO");		//
                    $idruta=oci_result($codigoim,"ID_RUTA");			//
                    $idsector=oci_result($codigoim,"ID_SECTOR");		//
                    $consecutivo=$proyecto."-".$idsector."".$idruta."-";//
                    //////////////////////////////////////////////////////

                    /*$FecSol = explode('-',$FecCon);
                    $yy = $FecSol[0];
                    $mm = $FecSol[1];
                    $dd = $FecSol[2];
                    $FecCon = $yy.'/'.$dd.'/'.$mm;
                    */
                    $con=new Contrato();
                    $con->setcod_inm($inmueble);
                    $con->setfechaini(date("Y/m/d H:i:s"));
                    $con->setcodigocli($codcliente);
                    $con->setfecha_creacion(date("Y/m/d H:i:s"));
                    $con->setusuario_creacion($cod);
                    $con->setid_contrato($consecutivo);
                    $con->setalias(strtoupper ($AlCli));
                    $con->setfecsol($FecCon);
                    $con->NuevoContrato();

                    $cont= new Contrato();
                    $cont->setcod_inm($inmueble);
                    $cont->setcodigocli($codcliente);
                    $codcontrato=$cont->ObtenerCodcontrato();
                    oci_fetch($codcontrato);								//
                    $contrato=oci_result($codcontrato,"ID_CONTRATO");
                    $codigoinmueble=oci_result($codigoim,"CODIGO_INM");

                    echo "
			<script type='text/javascript'>
			if(confirm('el contrato $contrato ha sido creado.')){
			window.close();
			}else{
			
			}
				
			</script>";

                }

            }
            else{
                echo  " 
	                <script language='JavaScript'> 
	                alert(' el codigo de inmueble $inmueble no existe'); 
	                </script>";

            }
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
        <form  id='formulariocon' action="vista.agregarcontrato.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Asociar contrato</center> </h3>
                <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Documento</span>
                            <span class="input-group-addon">
								<input size='300'  id="documento" onBlur="return agregadocumento();"  pattern='{6,13}$' required type='text'name="documento"  class='form-control' value='<?php echo $Doccli; ?>' placeholder='Documento' width='14' height='10'>
							</span>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <span class="input-group-addon" style="font-size:12px">Cod Inmueble</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="SInmueble" name="SInmueble"  required class="form-control" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Contrato();
                            $stid = $p->inmueble_sin_contrato();
                            while (oci_fetch($stid)) {
                                $Sinmueble2= oci_result($stid, 'CODIGO_INM') ;
                                if($Sinmueble2 == $Sinmueble) echo "<option value='$Sinmueble2' selected>$Sinmueble2</option>\n";
                                else echo "<option value='$Sinmueble2'>$Sinmueble2</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
		            </span>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">


                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Alias</span>
                            <span class="input-group-addon">
								<?php echo "<input size='300'  id='alias' style='text-transform:uppercase';     type='text'name='alias'  class='form-control' value='$AlCli' placeholder='Alias' width='14' height='10'>";?>
							</span>




                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">


                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Fecha Solicitud</span>
                            <span class="input-group-addon">
							<?php echo "<input id='fecsol' type='date' name='fecsol' required class='form-control' value='$FecCon' placeholder='Fecha Solicitud' width='14' height='10'>";?>
							</span>




                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">

    <p>
        <center>

    <P>

    </P>
    <a>
        <input type="submit" value="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
    </a>&nbsp;&nbsp;
    <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
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

    <script type="text/javascript">
        function agregadocumento(){

            upCli();
        }



        function upCli() {
            if( $("#documento").val().substring(0,4)!='9999'){

                popup2("vista.agregarcliente.php?doc="+$("#documento").val()+"&contrato="+$("#contrato").val()+"&tip=CB",600,300,'yes');
            }else{

            }

        }


        function popup2(uri, awid, ahei, scrollbar) {
            var params;
            var popped = null;
            if (uri != "") {
                if (popped && !popped.closed) {
                    popped.location.href = uri;
                    popped.focus();
                }
                else {
                    params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                    popped = window.open(uri, "popup5", params);
                }
            }
        }
    </script>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

