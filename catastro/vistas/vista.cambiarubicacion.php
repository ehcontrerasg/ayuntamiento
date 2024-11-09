<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.inmueble.php';
    include '../../destruye_sesion.php';
    include '../clases/class.parametros.php';
    $a=new parametros();
    $codcargo=$a->ObtenerCargo($_SESSION['codigo']);

    $cod=$_SESSION['codigo'];
    if($_GET['cod_inmueble']!="")
    {
        echo $inmuebles=$_GET['cod_inmueble'];
        $arrayinm=preg_split("/,/",$inmuebles);
        $inm2=$arrayinm[1];
        $inm1=$arrayinm[0];
    }




    if (isset($_REQUEST['Cambiar']))
    {
        $inm2=$_POST['inm2'];
        $inm1=$_POST['inm1'];
        $l= new Inmnueble();
        $l->setcod_inm($inm1);
        $l->setcod_inm2($inm2);
        $l->setusuario_creacion($cod);
        $bandera=$l->CambioUb();
        if(bandera==0){
            echo "
		<script type='text/javascript'>
		if(confirm('Has cambiado exitosamente la ubicacion del predio $inm1 a $inm2')){
			window.close();
		}else{
		window.close();
		}
	
		</script>";
        }
        else{
            echo "
		<script type='text/javascript'>
		if(confirm('ERROR al cambiar la ubicacion)){
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

    <?php if($codcargo==1 or $codcargo==2 or $codcargo==3)
    {?>
        <body>
        <p>
            <form  id='formulariocli' action="vista.cambiarubicacion.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                    <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Cambiar Ubicacion</center> </h3>
                    <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
                    <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
        <p>
        <div class="input-group input-group-sm">
            <span class="input-group-addon" width="200" >Inmueble 1</span>
            <span class="input-group-addon">
								<?php echo "<input size='300'  id='inm1'  required type='text'name='inm1'  class='form-control' value='$inm1' placeholder='Inmeble 1' width='14' height='10'>";?>
							</span>
        </div>




        </div>


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
            <p>
            <div class="input-group input-group-sm">
                <span class="input-group-addon" width="200" >Inmueble 2</span>
                <span class="input-group-addon">
								<?php echo "<input size='300'  id='inm2'  required type='text'name='inm2'  class='form-control' value='$inm2' placeholder='Inmeble 2' width='14' height='10'>";?>
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
                        <input type="submit" value="Cambiar" name="Cambiar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                    </a>&nbsp;&nbsp;
                    <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                        Cancelar
                    </a>

                <p>

                    </center>


            </div>
        </div>
        </div>
        </div>
        </form>
        </body>
    <?php }?>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

