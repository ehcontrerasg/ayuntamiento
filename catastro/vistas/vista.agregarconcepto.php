<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include('../../destruye_sesion.php');
    include '../clases/class.suministro.php';
    include '../clases/class.emplazamiento.php';
    include '../clases/class.concepto.php';
    include '../clases/class.tarifa.php';
    include '../clases/class.calculo.php';
    include '../clases/class.concepto_inmueble.php';
    include '../clases/class.uso.php';
    include '../clases/class.inmueble.php';
    session_start();
    if($_GET['inmueble']!=""){
        $inmueble=$_GET['inmueble'];
        $_SESSION['inmueble']=$_GET['inmueble'];

    }else{
        echo $inmueble=$_SESSION['inmueble'];
    }
    $m=new Inmnueble();
    $proyecto=$m->obtenerProy($inmueble);
    $coduser = $_SESSION['codigo'];

    $concepto1=$_POST["concepto1"];
    $uso1=$_POST["uso1"];
    $tarifa1=$_POST["tarifa1"];
    if($concepto1!="" && $uso1!="" && $tarifa1!=""){

        $c= new Concepto_inmueble();
        $c->setcodconcepto($concepto1);
        $c->setcodinmueble($inmueble);
        $c->setestado("AC");
        $c->setusrcreacion($coduser);
        $c->settarifa($tarifa1);
        $bandera=$c->nuevoconcepto2();

        if($bandera==false){
            echo "
		<script type='text/javascript'>
		alert('error desconocido contacte a sistemas');
		
		</script>";
        }else{
            echo "
		<script type='text/javascript'>
		if(confirm('Has agregado un concepto al inmueble con el codigo $inmueble')){
		window.close();
		}else{
		window.close();
		}
		
		</script>";
        }
    }


    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <title>ACEASOFT</title>
    <body>
    <p>
        <form  id="agregaconcepto" action="vista.agregarconcepto.php" autocomplete="on" method="post" onsubmit="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <center><div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                    <h3 class="panel-heading" style="background-color:rgb(163,73,163); border-color:rgb(163,73,163)"> Agregar Concepto</h3>
                    <div class="panel_mody" >Diligencie la informaci&oacute;n del formulario.
                        <div class="container">
    <p><p>

    <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Servicios</b></h3>
    <div class="row" >
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size:12px"">
        <span class="input" style="font-size:12px"></span>
        <span class="input-group-addon">
			      		<span class="input" style="font-size:12px">Concepto</span>
			          	<select id="concepto1" name="concepto1"  required  onChange="javascript:agregaconcepto.submit();" class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Concepto();
                            $stid = $p->obtenerconcepto();
                            while (oci_fetch($stid)) {
                                $cod_concepto1= oci_result($stid, 'COD_SERVICIO') ;
                                $desc_concepto1= oci_result($stid, 'DESC_SERVICIO') ;
                                if($cod_concepto1 == $concepto1) echo "<option value='$cod_concepto1' selected>$desc_concepto1</option>\n";
                                else echo "<option value='$cod_concepto1'>$desc_concepto1</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
			           	<span class="input" style="font-size:12px">Uso</span>
			           	<select id="uso1" name="uso1" required onChange="javascript:agregaconcepto.submit();"  class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Uso() ;
                            $stid = $p->obtenerusos();
                            while (oci_fetch($stid)) {
                                $desc_uso1= oci_result($stid, 'DESC_USO') ;
                                $id_uso1= oci_result($stid, 'ID_USO') ;
                                if($id_uso1 == $uso1) echo "<option value='$id_uso1' selected>$desc_uso1</option>\n";
                                else echo "<option value='$id_uso1'>$desc_uso1</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
			           	<span class="input" style="font-size:12px">Tarifa</span>
			            <select id="tarifa1" name="tarifa1"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
			    		<option value='9999'>Sin definir</option>
                            <?php
                            $p=new Tarifa();
                            $p->setcodconcepto($concepto1);
                            $p->setcodproyecto($proyecto);
                            $p->setcoduso($uso1);
                            $stid = $p->obtenertarifa();
                            while (oci_fetch($stid)) {
                                $cod_tarifa1= oci_result($stid, 'CONSEC_TARIFA') ;
                                $desc_tarifa1= oci_result($stid, 'DESC_TARIFA') ;
                                if($cod_tarifa1== $tarifa1) echo "<option value='$cod_tarifa1' selected>$desc_tarifa1</option>\n";
                                else echo "<option value='$cod_tarifa1'>$desc_tarifa1</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>




    </div>

    </div>



    <p><center><input type="submit" value="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">&nbsp;&nbsp; <a class="btn btn-primary btn-lg" href="#" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">Cancelar</a></center></p></center></p>





    </div>
    </div>
    </center>
    </p>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    </form>

    </body>
    </html>




<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

