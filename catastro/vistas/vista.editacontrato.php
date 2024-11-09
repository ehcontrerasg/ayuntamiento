<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <? session_start();
    include '../clases/class.cliente.php';
    include '../clases/class.contrato.php';
    include '../../destruye_sesion.php';
    $cod=$_SESSION['codigo'];
    if($_GET['id_contrato']!=''){
        $contrato=$_GET['id_contrato'];
        $l=new Contrato();
        $l->setid_contrato($contrato);
        $registro=$l->ObtenerDatcontrato();
        oci_fetch($registro);
        $inmueble=oci_result($registro,"CODIGO_INM");
        $codcli=oci_result($registro,"CODIGO_CLI");
        $alias=oci_result($registro,"ALIAS");

    }else{
        $contrato=$_POST['contrato'];
        $alias=strtoupper($_POST['alias']);
        $inmueble=$_POST['inmueble'];
        $i= new Contrato();
        $i->setalias($alias);
        $i->setusuario_mod($cod);
        $i->setid_contrato($contrato);
        $i->ActualizaAlias();

        echo "
	 	<script type='text/javascript'>
	 	opener.location.reload();
	 	window.close();

    </script>";


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
        <form  id='formulariocli' action="vista.editacontrato.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-primary" style="border-color:rgb(163,73,163)">
                <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Cambiar Usuario Contrato</center> </h3>
                <div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
                <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
    <p>
    <div class="input-group input-group-sm">
        <span class="input-group-addon" width="200" >Alias</span>
        <span class="input-group-addon">
								<?php echo "<input size='300'  id='alias' style='text-transform:uppercase';    type='text'name='alias' maxlength='66'  max='66' class='form-control' value='$alias' placeholder='Alias' width='14' height='10'>";?>
							</span>
    </div>

    <p>
    <div class="input-group input-group-sm">
        <span class="input-group-addon" width="200" >Cod Inmueble</span>
        <span class="input-group-addon">
								<?php echo "<input size='300'  id='inmueble' readonly type='text'name='inmueble'  class='form-control' value='$inmueble' placeholder='Cod Inmueble' width='14' height='10'>";?>
							</span>
    </div>




    </div>


    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
        <p>
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Contrato</span>
            <span class="input-group-addon">
								<?php echo "<input id='contrato' readonly name='contrato' value='$contrato'  class='form-control' placeholder='Contrato' width='14' height='10' >";?>
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
                    <input type="submit" value="Cambiar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                </a>&nbsp;&nbsp;
                <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                    Cancelar
                </a>

            </p>

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

