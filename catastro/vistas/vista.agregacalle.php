<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.nombrevia.php';
    include '../clases/class.proyecto.php';
    include '../clases/class.via.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];

    if ($_POST['SProyecto']!=null);{
        $Sproyecto=$_POST['SProyecto'];
        $STipoVia=$_POST['STipoVia'];
        $nombrecalle=$_POST['ETNombreCalle'];
    }
    if (isset($_REQUEST['Agregar']))
    {
        $l= new NombreVia();
        $l->setidtvia($STipoVia);
        $l->setdescnvia($nombrecalle);
        $l->setidproyecto($Sproyecto);
        $bandera=$l->NuevaCallen();
        if(bandera==0){
            echo "
		<script type='text/javascript'>
		if(confirm('Has creado la calle $nombrecalle')){
		opener.location.reload();
			window.close();
		}else{
		opener.location.reload();
		window.close();
		}
	
		</script>";
        }
        else{
            echo "
		<script type='text/javascript'>
		if(confirm('ERROR al crear la calle $nombrecalle')){
		opener.location.reload();
			window.close();
		}else{
		opener.location.reload(); 
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

    <form  id='agregacalle' action="vista.agregacalle.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
            <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Agregar Calle</center> </h3>
            <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
            <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">

                    <div class="input-group input-group-sm">
                        <span class="input-group-addon" width="200" >Nombre Calle</span>
                        <span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='ETNombreCalle'  required type='text'name='ETNombreCalle'  class='form-control' value='$nombrecalle' placeholder='Nombre Via' width='14' height='10'>";?>
							</span>
                    </div>




                </div>


                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <span class="input-group-addon" style="font-size:12px">Proyecto</span>
                    <span class="input-group-addon" style="font-size:12px">
		            	<select id="SProyecto" name="SProyecto"  onChange="javascript:agregacalle.submit()" required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
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
            </div>


            <div class="row">

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                    <span class="input-group-addon" style="font-size:12px">Tipo Via</span>
                    <span class="input-group-addon" style="font-size:12px">
		            	<select id="STipoVia" name="STipoVia"  required class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
			    		<option></option>
                            <?php
                            $p=new Via();
                            $stid = $p->obtenervia($Sproyecto);
                            while (oci_fetch($stid)) {
                                $STipovis2= oci_result($stid, 'ID_TIPO_VIA') ;
                                $SDescTipVia= oci_result($stid, 'DESC_TIPO_VIA') ;
                                if($STipovis2 == $STipoVia) echo "<option value='$STipovis2' selected>$SDescTipVia</option>\n";
                                else echo "<option value='$STipovis2'>$SDescTipVia</option>\n";
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

                    <p>

                        </center>


                </div>
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

