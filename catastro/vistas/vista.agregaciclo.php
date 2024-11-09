<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.ciclos.php';
    include '../clases/class.proyecto.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];
    if (isset($_REQUEST['Agregar']))
    {
        $ciclo=$_POST['ciclo'];
        $SProyecto=$_POST['SProyecto'];
        $l= new Ciclos();
        $l->setdesc_ciclo($ciclo);
        $l->setidproyecto($SProyecto);
        $bandera=$l->NuevoCiclo();
        if(bandera==0){
            echo "
		<script type='text/javascript'>
		if(confirm('Has creado el ciclo $ciclo')){
			window.close();
		}else{
		window.close();
		}
	
		</script>";
        }
        else{
            echo "
		<script type='text/javascript'>
		if(confirm('ERROR al crear el ciclo $ciclo')){
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
        <script src="../../js/ajax2.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <title>ACEASOFT</title>
    </head>


    <body>
    <p>
        <form  id='formulariocli' action="vista.agregaciclo.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Agregar Ciclos</center> </h3>
                <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <span class="input-group-addon" style="font-size:12px">Proyecto</span>
                        <span class="input-group-addon" style="font-size:12px">
		            	<select id="SProyecto" name="SProyecto"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true" onChange="load(this.value)">
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

                        <div >
                            <span class="input-group-addon" style="font-size:12px">Ciclo</span>
                            <span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase' id='ciclo' required type='text' name='ciclo' class='form-control' value='$ciclo' placeholder='Ciclo' width='14' height='10'>";?>
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

