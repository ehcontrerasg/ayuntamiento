<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.concepto_inmueble.php';
    include '../clases/class.servicio.php';
    include '../clases/class.tarifa.php';
    include '../clases/class.actividad.php';
    include '../clases/class.uso.php';
    include '../../destruye_sesion.php';

    $cod= $_SESSION['codigo'];
    if($_GET['inmueble']!=''){
        $inmueble_ori=$_GET['inmueble'];
        $servicio_ori=$_GET['codser'];
        $servicio_viejo=$_GET['codser'];
        $l=new Concepto_inmueble();
        $l->setcodconcepto($servicio_ori);
        $l->setcodinmueble($inmueble_ori);
        $datos_servicios=$l->Especifico();
        oci_fetch($datos_servicios);
        $unidades_tot=oci_result($datos_servicios,"UNIDADES_TOT");
        $unidades_hab=oci_result($datos_servicios,"UNIDADES_HAB");
        $cupo_basico=oci_result($datos_servicios,"CUPO_BASICO");
        $promedio=oci_result($datos_servicios,"PROMEDIO");
        $consmin=oci_result($datos_servicios,"CONSUMO_MINIMO");
        $consectarifa=oci_result($datos_servicios,"CONSEC_TARIFA");
        $uso=oci_result($datos_servicios,"ID_USO");
        $actividad=oci_result($datos_servicios,"SEC_ACTIVIDAD");

    }else{
        $servicio_viejo=$_POST['Hserviejo'];
        $inmueble_ori=$_POST['HInmueble'];
        $servicio_ori=$_POST['SServicio'];
        $unidades_tot=$_POST['ETUnidadesTot'];
        $unidades_hab=$_POST['ETUnidadesHab'];
        $cupo_basico=$_POST['ETCupoBas'];
        $promedio=$_POST['HPromedio'];
        $consmin=$_POST['ETConsMin'];
        $consectarifa=$_POST['STarifa'];
        $uso=$_POST['SUso'];
        $actividad=$_POST['SActividad'];
    }
    if (isset($_REQUEST['actualizar']))
    {
        $b= new Concepto_inmueble();
        $datos=$b->obtenerSaldo($inmueble_ori);
        while(oci_fetch($datos)) {
            $saldo = oci_result($datos, 'SALDO');
        }oci_free_statement($datos);

        if($saldo <= 30) {
            $l = new Concepto_inmueble();
            $l->setcodconcepto($servicio_ori);
            $l->setcodconceptov($servicio_viejo);
            $l->setcodinmueble($inmueble_ori);
            $l->setcupobas($cupo_basico);
            $l->settarifa($consectarifa);
            $l->setunihab($unidades_hab);
            $l->setunitot($unidades_tot);
            $l->setusrcreacion($cod);
            $l->setactividad($actividad);
            $bandera = $l->actualizar_servicio();
            if ($bandera) {
                echo "
            <script type='text/javascript'>
            if(confirm('Has actualizado correctamente el servicio')){
                window.close();
            }else{
            }
        
            </script>";
            } else {
                $err = $l->getMsError();
                echo "
            <script type='text/javascript'>
            if(confirm('ERROR al actualizar el servicio')){
                window.close();
            }else{
            }
        
            </script>";
            }
        }
        else{
            echo "
            <script type='text/javascript'>
            if(confirm('ERROR: El inmueble tiene un saldo a favor activo. No se pueden modificar los datos')){
                window.close();
            }
            </script>";
        }
    }
    ?>

    <html>
    <head>
        <script src="../../js/jquery.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/script.js?1"></script>
        <script language="javascript" src="js/jquery.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>


    <body>
    <p>
        <form  id='FMEditSer' action="vista.editarservicio.php" autocomplete="on" onSubmit="return validacampos();" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <td align="center"><input type="hidden" id='HInmueble' name="HInmueble" value="<? echo $inmueble_ori;?>"></td>
                <td align="center"><input type="hidden"  id='HPromedio' name="HPromedio" value="<? echo $promedio;?>"></td>
                <td align="center"><input type="hidden"  id='Hserviejo' name="Hserviejo" value="<? echo $servicio_viejo;?>"></td>
                <td align="center"><input type="hidden" id='HUso' name="HUso" value="<? echo $uso;?>"></td>
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Modificar Servicio</center> </h3>
                <div class="panel_mody" ><center>Actualice la siguiente informacion.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Uso</span>
                        <span class="input-group-addon" style="font-size:12px">
			            		<select id="SUso" name="SUso" onChange="javascript:FMEditSer.submit();"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
				    				<option></option>
                                    <?php
                                    $p=new Uso() ;
                                    $stid = $p->obtenerusos();
                                    while (oci_fetch($stid)) {
                                        $cod_uso1= oci_result($stid, 'ID_USO');
                                        $desc_uso1= oci_result($stid, 'DESC_USO');
                                        if($cod_uso1 == $uso) echo "<option value='$cod_uso1' selected>$desc_uso1</option>\n";
                                        else echo "<option value='$cod_uso1'>$desc_uso1</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
		  						</select>
		            		</span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Actividad</span>
                        <span class="input-group-addon" style="font-size:12px">
			            		<select id="SActividad" name="SActividad"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
				    				<option></option>
                                    <?php
                                    $p=new Actividad() ;
                                    $stid = $p->obteneractividades($uso);
                                    while (oci_fetch($stid)) {
                                        $cod_actividad1= oci_result($stid, 'SEC_ACTIVIDAD');
                                        $desc_actividad1= oci_result($stid, 'DESC_ACTIVIDAD');
                                        if($cod_actividad1 == $actividad) echo "<option value='$cod_actividad1' selected>$desc_actividad1</option>\n";
                                        else echo "<option value='$cod_actividad1'>$desc_actividad1</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
		  						</select>
		            		</span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Servicio</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="SServicio" name="SServicio"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Servicio() ;
                            $stid = $p->obtenerservicio();
                            while (oci_fetch($stid)) {
                                $cod_servicio1= oci_result($stid, 'COD_SERVICIO');
                                $desc_servicio1= oci_result($stid, 'DESC_SERVICIO');
                                if($cod_servicio1 == $servicio_ori) echo "<option value='$cod_servicio1' selected>$desc_servicio1</option>\n";
                                else echo "<option value='$cod_servicio1'>$desc_servicio1</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
		            </span>

                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Tarifa</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="STarifa" name="STarifa"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Tarifa();
                            $p->setcodconcepto($servicio_ori);
                            $p->setcodproyecto('SD');
                            $p->setcoduso($uso);
                            $stid = $p->obtenertarifainm($inmueble_ori);
                            while (oci_fetch($stid)) {
                                $Starifa2= oci_result($stid, 'CONSEC_TARIFA') ;
                                $Sdesctarifa2= oci_result($stid, 'DESC_TARIFA') ;
                                if($Starifa2 == $consectarifa) echo "<option value='$Starifa2' selected>$Sdesctarifa2</option>\n";
                                else echo "<option value='$Starifa2'>$Sdesctarifa2</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
		            </span>

                    </div>



                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Unidades Totales</span>
                            <span class="input-group-addon">
								<?php echo "<input size='300'  id='ETUnidadesTot'  required type='text'name='ETUnidadesTot'  class='form-control' value='$unidades_tot' placeholder='Unidades Totales' width='14' height='10'>";?>
							</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Unidades Habitadas</span>
                            <span class="input-group-addon">
								<input size='300'  id='ETUnidadesHab' onkeyup="cambiapar()" required type='text'name='ETUnidadesHab'  class='form-control' value='<?php echo $unidades_hab?>' placeholder='Unidades Habitadas' width='14' height='10'>
							</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Cupo Basico</span>
                            <span class="input-group-addon">
								<input size="300"  id="ETCupoBas"  required type="text" name="ETCupoBas"   onkeyup="cambiapar()" class="form-control" value="<?php echo $cupo_basico?>" placeholder="Cupo Basico" width="14" height="10">
							</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Consumo Minimo</span>
                            <span class="input-group-addon">
								<?php echo "<input size='300'  id='ETConsMin'  required type='text'name='ETConsMin' readonly  class='form-control' value='$consmin' placeholder='Consumo Minimo' width='14' height='10'>";?>
							</span>
                        </div>
                    </div>

                    <?php echo "<input size='300'  id='inmueble'  required type='hidden'name='inmueble' readonly  class='form-control' value='$inmueble_ori' placeholder='Consumo Minimo' width='14' height='10'>";?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">

    <p>
        <center>

    <P>
        <a>
            <input type="submit" value="actualizar"  name="actualizar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
        </a>&nbsp;&nbsp;
        <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
            Cancelar
        </a>

    </p>

    </center>


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

