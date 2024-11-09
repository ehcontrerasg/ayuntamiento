<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.zona.php';
    include '../clases/class.proyecto.php';
    include '../clases/class.sector.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];
    $Sproyecto=$_POST['SProyecto'];
    if (isset($_REQUEST['Agregar']))
    {
        $SProyecto=$_POST['SProyecto'];
        $sector=$_POST['sector'];
        $ciclo=$_POST['ciclo'];
        $l= new Zona();
        $l->setidsector($sector);
        $l->setidproyecto($SProyecto);
        $l->setidciclo($ciclo);
        $bandera=$l->NuevaZona();
        if(bandera==0){
            echo "
		<script type='text/javascript'>
		if(confirm('Has creado la zona $zona')){
			window.close();
		}else{
		window.close();
		}
	
		</script>";
        }
        else{
            echo "
		<script type='text/javascript'>
		if(confirm('ERROR al crear la zona $zona')){
			window.close();
		}else{
		window.close();
		}
	
		</script>";
        }
    }





    ?>

    <html>
    <head>
        <script src="../../js/bootstrap.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>


    <body>
    <p>
        <form  id='formulariocli' action="vista.agregazona.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Agregar Zona</center> </h3>
                <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Proyecto</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="SProyecto" name="SProyecto"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true" onChange="javascript:formulariocli.submit()"">
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
                        <span class="input-group-addon" style="font-size:12px">Sector</span>
                        <span class="input-group-addon" style="font-size:12px">
		            		<div id="divsector">

								<select name='sector' id="sector" class='btn btn-default btn-sm dropdown-toggle' required>
									<option value="" selected></option>
                                    <?php
                                    $p=new Sector();
                                    $stid = $p->obtenersectores($Sproyecto);
                                    while (oci_fetch($stid)) {
                                        $cod_sector= oci_result($stid, 'ID_SECTOR') ;
                                        //$des_ciclo= oci_result($stid, 'DESC_CICLO') ;
                                        if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                                        else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
								</select>
							</div>
		            	</span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Ciclo</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="ciclo" name="ciclo"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
						<option></option>
                            <?php
                            $p=new Zona();
                            $stid = $p->obtenerciclo();
                            while (oci_fetch($stid)) {
                                $cod_ciclo= oci_result($stid, 'ID_CICLO') ;
                                //$des_ciclo= oci_result($stid, 'DESC_CICLO') ;
                                if($cod_ciclo == $ciclo) echo "<option value='$cod_ciclo' selected>$cod_ciclo</option>\n";
                                else echo "<option value='$cod_ciclo'>$cod_ciclo</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
		            	</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">
    <p>
        <center>
    <P>
        <a>
            <input type="submit" value="Agregar" name="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
        </a>&nbsp;&nbsp;
        <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
            Cancelar
        </a>

    </p>

    </center>

    </p>
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

