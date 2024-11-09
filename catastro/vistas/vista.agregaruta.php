<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    /**
     * Created by Edwin Contreras.
     * User: PC
     * Date: 9/29/2015
     * Time: 10:44 AM
     */
    error_reporting(0);
    session_start();
    include '../clases/class.proyecto.php';
    include '../clases/class.ruta.php';
    include '../clases/class.sector.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];
    $Sproyecto=$_POST['SProyecto'];
    $Ssector=$_POST['SSector'];
    $ETRuta=$_POST['ETRuta'];
    if($_REQUEST['Agregar']){
        $or= new Ruta();
        $or->setidproyecto($Sproyecto);
        $or->setidruta($ETRuta);
        $or->setidsector($Ssector);
        $bandera=$or->crearruta();
        if($bandera){
            echo "
		 	<script type='text/javascript'>
		 	if(confirm('Has creado la ruta $Ssector $ETRuta ')){
		 		window.close();
		 	}
	 		</script>";
        }else{
            $error=$or->getmsresult();
            $coderror=$or->getcodresult();
            echo"
            <script type='text/javascript'>
                if(confirm('error al crear la ruta CODIGO=$coderror. MENSAJE=$error')){
		 		window.close();
		 	}else{

		 	}
            </script>";
        }
    };

    ?>

    <html>
    <head>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <script src="../../js/bootstrap.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/estilonuevo.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>


    <body>

    <form  id='agregaruta' action="vista.agregaruta.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
            <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Agregar Ruta</center> </h3>
            <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
            <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <span class="input-group-addon rotulo" style="font-size:12px; ">Proyecto</span>
                    <span class="input-group-addon" style="font-size:12px">
                            <select id="SProyecto" name="SProyecto"  onChange="javascript:agregaruta.submit()" required class="btn btn-default btn-sm dropdown-toggle taminputs" type="button" data-toggle="dropdown">
                                <option></option>
                                <?php
                                $p=new Proyecto();
                                $stid = $p->obtenerproyectos($cod);
                                while (oci_fetch($stid)) {
                                    $Sproyecto2= oci_result($stid, 'ID_PROYECTO') ;
                                    if($Sproyecto2 == $Sproyecto) echo "<option value='$Sproyecto2' selected>$Sproyecto2</option>\n";
                                    else echo "<option value='$Sproyecto2'>$Sproyecto2</option>\n";
                                }oci_free_statement($stid);
                                ?>
                            </select>
		                </span>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <span class="input-group-addon rotulo" style="font-size:12px;">Sector</span>
                    <span class="input-group-addon" style="font-size:12px">
                            <select id="SSector" name="SSector"  onChange="javascript:agregaruta.submit()" required class="btn btn-default btn-sm dropdown-toggle taminputs" type="button" data-toggle="dropdown" >
                                <option></option>
                                <?php
                                $p=new Sector();
                                $stid = $p->obtenersectores($Sproyecto);
                                while (oci_fetch($stid)) {
                                    $Ssector2= oci_result($stid, 'ID_SECTOR') ;
                                    if($Ssector2 == $Ssector) echo "<option value='$Ssector2' selected>$Ssector2</option>\n";
                                    else echo "<option value='$Ssector2'>$Ssector2</option>\n";
                                }oci_free_statement($stid);
                                ?>
                            </select>
		                </span>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <span class="input-group-addon rotulo" style="font-size:12px">Ruta</span>
                    <span class="input-group-addon" style="font-size:12px">
                            <input size='300' style='text-transform:uppercase';  id='ETRuta'  required type='text'name='ETRuta'  class='form-control taminputs' value='<?php echo $ETRuta;?>' placeholder='Ruta' >
		                </span>
                </div>


            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">
                    <center>
                        <a><input type="submit" value="Agregar" name="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"></a>&nbsp;&nbsp;
                        <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">Cancelar</a>
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

