<?php
session_start();

include '../clases/class.tarifa.php';
include '../../destruye_sesion.php';
$cod= $_SESSION['codigo'];

if($_GET['id_tarifa']!=''){
    $secser=$_GET['id_tarifa'];
    $consecutivos=preg_split('/,/',$secser);
    $l=new tarifa();
    $datos_tarifas=$l->tar_especifica($consecutivos{0});
    oci_fetch($datos_tarifas);
    $descripcion=oci_result($datos_tarifas,"DESC_TARIFA");
    $servicio=oci_result($datos_tarifas,"COD_SERVICIO");
    $uso=oci_result($datos_tarifas,"COD_USO");
    $codtar=oci_result($datos_tarifas,"CODIGO_TARIFA");
    $consmin=oci_result($datos_tarifas,"CONSUMO_MIN");


}else{
    $secser=$_POST['listtar'];
    $descripcion=$_POST['ETDesc'];
    $servicio=$_POST['ETSer'];
    $uso=$_POST['ETUso'];
    $codtar=$_POST['ETCodTar'];
    $consmin=$_POST['ETConMin'];

}
if (isset($_REQUEST['actualizar'])) {
    $vectorsec = preg_split('/,/', $secser);
    $lisitem = '';
    count($vectorsec);
    for ($x = 1; $x < count($vectorsec); $x++) {
        $lisitem .= $vectorsec{$x} . ',';

    }
    if (count($vectorsec) == 1) {
        echo "
		<script type='text/javascript'>
		    window.close();
		</script>";
    }
    else
    {
        $lisitem = substr($lisitem, 0, -1);
        echo "<script languaje='javacript'>
                    window.location.replace('vista.editartarifa.php?id_tarifa='+'$lisitem');
        </script>";
    }

//
//    $l= new Concepto_inmueble();
//    $l->setcodconcepto($servicio_ori);
//    $l->setcodconceptov($servicio_viejo);
//    $l->setcodinmueble($inmueble_ori);
//    $l->setcupobas($cupo_basico);
//    $l->settarifa($consectarifa);
//    $l->setunihab($unidades_hab);
//    $l->setunitot($unidades_tot);
//    $l->setusrcreacion($cod);
//    $l->setactividad($actividad);
//    $bandera=$l->actualizar_servicio();
//    if(bandera==0){
//        echo "
//		<script type='text/javascript'>
//		if(confirm('Has actualizado correctamente el servicio')){
//			window.close();
//		}else{
//		window.close();
//		}
//
//		</script>";
//    }
//    else{
//        echo "
//		<script type='text/javascript'>
//		if(confirm('ERROR al actualizar el servicio')){
//			window.close();
//		}else{
//		window.close();
//		}
//
//		</script>";
//    }
}
?>

<html>
<head>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script language="javascript" src="js/jquery.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <title>ACEASOFT</title>
</head>


<body>
<p>
<form  id='FMEditSer' action="vista.editartarifa.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="panel panel-primary" style="border-color:rgb(163,73,163)">

        <h3 class="panel-heading" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)"><center>Modificar Tarifas</center> </h3>
        <div class="panel_mody" ><center>Actualice la siguiente informacion.</center></div>
        <h3 style="background-color:rgb(163,73,163); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos B&aacute;sicos</b></h3>
        <div class="row">

            <td align="center"><input type="hidden" id='listtar' name="listtar" value="<? echo $secser;?>"></td>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                <span class="input-group-addon" width="14" style="font-size:12px">Descripcion</span>
                    <span class="input-group-addon" style="font-size:12px">
                        <input size="300" readonly id="ETDesc"  required type="text" name="ETDesc"  class="form-control" value="<?php echo $descripcion?>" placeholder="Descripcion" width="14" height="10">
                    </span>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                <span class="input-group-addon" width="14" style="font-size:12px">Servicio</span>
		             		<span class="input-group-addon" style="font-size:12px">
			            		 <input size="300" readonly id="ETSer"  required type="text" name="ETSer"  class="form-control" value="<?php echo $servicio?>" placeholder="Servicio" width="14" height="10">
		            		</span>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                <span class="input-group-addon" width="14" style="font-size:12px">Uso</span>
                    <span class="input-group-addon" style="font-size:12px">
		            	<input size="300" readonly id="ETUso"  required type="text" name="ETUso"  class="form-control" value="<?php echo $uso?>" placeholder="Uso" width="1" height="10">
		            </span>

            </div>


            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                <span class="input-group-addon" style="font-size:12px">Codigo Tarifa</span>
		             <span class="input-group-addon" style="font-size:12px">
		            	<input size="300" readonly id="ETCodTar"  required type="text" name="ETCodTar"  class="form-control" value="<?php echo $codtar?>" placeholder="Codigo Tarifa" width="1" height="10">
		            </span>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                <span class="input-group-addon" style="font-size:12px">Cons Min</span>
		             <span class="input-group-addon" style="font-size:12px">
		            	<input size="300"  id="ETConMin"  required type="text" name="ETConMin"  class="form-control" value="<?php echo $consmin?>" placeholder="Consumo MInimo" width="1" height="10">
		            </span>

            </div>



            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">

                    <p>
                    <center>

                        <P>
                            <a>
                                <input type="submit" value="actualizar"  name="actualizar" class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                            </a>&nbsp;&nbsp;
                            <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163)">
                                Cancelar
                            </a>

                        <p>

                    </center>


                </div>
            </div>
        </div>

</form>
</body>
</html>