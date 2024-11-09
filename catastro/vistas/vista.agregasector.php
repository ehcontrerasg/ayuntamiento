<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.sector.php';
    include '../clases/class.proyecto.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];
    if (isset($_REQUEST['Agregar']))
    {
        $SProyecto=$_POST['SProyecto'];
        $gerencia=$_POST['gerencia'];
        $sector=$_POST['sector'];
        $l= new Sector();
        $l->setdesc_sector($sector);
        $l->setidproyecto($SProyecto);
        $l->setgerencia($gerencia);
        $bandera=$l->NuevoSector();
        if(bandera==0){
            echo "
		<script type='text/javascript'>
		if(confirm('Has creado el sector $sector')){
			window.close();
		}else{
		window.close();
		}
	
		</script>";
        }
        else{
            echo "
		<script type='text/javascript'>
		if(confirm('ERROR al crear el sector $sector')){
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>


    <body>
    <p>
        <form  id='formulariocli' action="vista.agregasector.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Agregar Sector</center> </h3>
                <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Proyecto</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="SProyecto" name="SProyecto"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
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
                        <span class="input-group-addon" style="font-size:12px">Gerencia</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="gerencia" name="gerencia"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Sector();
                            $stid = $p->obtenergerencias();
                            while (oci_fetch($stid)) {
                                $cod_gerencia= oci_result($stid, 'ID_GERENCIA') ;
                                $des_gerencia= oci_result($stid, 'DESC_GERENCIA') ;
                                if($cod_gerencia == $gerencia) echo "<option value='$cod_gerencia' selected>$des_gerencia</option>\n";
                                else echo "<option value='$cod_gerencia'>$des_gerencia</option>\n";
                            }oci_free_statement($stid);
                            ?>
	  					</select>
		            	</span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                        <div >
                            <span class="input-group-addon" style="font-size:12px">Sector</span>
                            <span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase' id='sector' required type='text' name='sector' class='form-control' value='$sector' placeholder='Sector' width='14' height='10'>";?>
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

