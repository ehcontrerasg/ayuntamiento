<?php
session_start();
include '../clases/class.personal.php';
include '../../destruye_sesion.php';
$cod=$_SESSION['codigo'];


if($_POST['ETId']){
    $id=$_POST['ETId'];
    $desc=$_POST['ETDesc'];
}

if (isset($_REQUEST['Agregar']))
{
	$l= new personal();

	$bandera=$l->agregaarea($desc,$id);

	if($bandera){
		echo "
		<script type='text/javascript'>
		if(confirm('Has creado el area $desc')){
		opener.location.reload();
			window.close();
		}
		</script>";
	}
	else{
        $error=$l->getmsgresult();
		echo "
		<script type='text/javascript'>
		if(confirm('ERROR $error al crear el area $desc')){
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

		<form  id='agregacalle' action="vista.agregaarea.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary" style="border-color:rgb(172, 14, 5)">
				<h3 class="panel-heading" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)"><center>Agregar Area</center> </h3>
				<div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
				<h3 style="background-color:rgb(172, 14, 5); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos Del Area</b></h3>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
						<div class="input-group input-group-sm">
			  				<span class="input-group-addon" width="200" >Id Area</span>
							<span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='ETId'  required type='text'name='ETId'  class='form-control' value='$idarea' placeholder='Id Area' width='14' height='10'>";?>
							</span>
						</div>
					</div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Nombre Area</span>
							<span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='ETDesc'  required type='text'name='ETDesc'  class='form-control' value='$idarea' placeholder='Nombre Area' width='14' height='10'>";?>
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
                                    <input type="submit" value="Agregar" name="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)">
                                </a>&nbsp;&nbsp;
                                <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)">Cancelar</a>
                            <p>
                        </center>
                    </div>
                </div>
            </div>
    	</form>
	</body>
</html>