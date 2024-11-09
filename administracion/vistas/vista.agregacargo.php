<?php
session_start();
include '../clases/class.personal.php';
include '../../destruye_sesion.php';
$cod=$_SESSION['codigo'];


if($_POST['ETId']){
    $id=$_POST['ETId'];
    $desc=$_POST['ETDesc'];
    $idarea=$_POST['SIdarea'];
}

if (isset($_REQUEST['Agregar']))
{
	$l= new personal();

	$bandera=$l->agregacargo($desc,$idarea,$id);

	if($bandera){
		echo "
		<script type='text/javascript'>
		if(confirm('Has creado el cargo $desc')){
		opener.location.reload();
			window.close();
		}
		</script>";
	}
	else{
        $error=$l->getmsgresult();
		echo "
		<script type='text/javascript'>
		if(confirm('ERROR $error al crear el cargo $desc')){
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

		<form  id='agregacalle' action="vista.agregacargo.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary" style="border-color:rgb(172, 14, 5)">
				<h3 class="panel-heading" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)"><center>Agregar Cargo</center> </h3>
				<div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
				<h3 style="background-color:rgb(172, 14, 5); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos Del Area</b></h3>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
						<div class="input-group input-group-sm">
			  				<span class="input-group-addon" width="200" >Id Cargo</span>
							<span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='ETId'  required type='text'name='ETId'  class='form-control' value='$idarea' placeholder='Id Area' width='14' height='10'>";?>
							</span>
						</div>
					</div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Id Area</span>
							<span class="input-group-addon">
								<select id="SIdarea" name="SIdarea"  required  type='button' class='form-control' data-toggle='dropdown' aria-expanded='true'>
                                    <option></option>
                                    <?php
                                    $p=new personal();
                                    $stid = $p->obtenerareas();
                                    while (oci_fetch($stid)) {
                                        $cod_area1= oci_result($stid, 'ID_AREA') ;
                                        $desc_area1= oci_result($stid, 'DESC_AREA') ;
                                        if($cod_area1 == $idarea) echo "<option value='$cod_area1' selected>$desc_area1</option>\n";
                                        else
                                            echo "<option value='$cod_area1'>$desc_area1</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
							</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Nombre Cago</span>
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