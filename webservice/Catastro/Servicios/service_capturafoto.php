<?php
require_once'./../Clases/FotosMantenimientoClass.php';
$arraycarpeta=preg_split("/-/", basename( $_FILES['fotoUp']['name']));
$carpetafecha=$arraycarpeta[2];
$target_path = "./../../fotos_sgc/foto_mantenimientos/".$carpetafecha."/";
$target_path1 = $target_path.basename( $_FILES['fotoUp']['name']);
$consecutivofoto=substr($arraycarpeta[3],0,1);
$codsistema=$arraycarpeta[1];
$nombrefoto=basename( $_FILES['fotoUp']['name']);
$periodo=$arraycarpeta[0];
$urlfoto="..".substr($target_path1, 17);


if(strlen($arraycarpeta[2])==7){
	$fechaformato=substr("0".$arraycarpeta[2],4,1)."/".substr($arraycarpeta[2],5,2)."/".substr($arraycarpeta[2],0,4);
}
else{
	if(strlen($arraycarpeta[2])==8){
		$fechaformato=substr($arraycarpeta[2],4,2)."/".substr($arraycarpeta[2],6,2)."/".substr($arraycarpeta[2],0,4);
	}
}

$proyecto="SD";

$foto=new FotosMantenimientoClass();



if(!file_exists($target_path)){

	mkdir($target_path);
}

if(move_uploaded_file($_FILES['fotoUp']['tmp_name'], $target_path1)) {
    basename( $_FILES['fotoUp']['name']);
    $resultado=$foto->existefoto($consecutivofoto, $codsistema, $periodo);
    oci_fetch($resultado);
    $numerofotos= oci_result($resultado, "NUMFOTOS");
    if( $numerofotos==1){
    	$bandera=array(array('bandera'=>'1'));
    	echo json_encode($bandera);

    }
    else
    {
        $foto->CambiarEstadoFotos($codsistema);
        $foto->EliminarFotos($codsistema);
   		$fotos=$foto->insertarfoto($consecutivofoto,$codsistema,$fechaformato,$nombrefoto,$urlfoto,$periodo,$proyecto);
   		$bandera=array(array('bandera'=>'1'));
   		echo json_encode($bandera);
	} 
}else{
    echo "There was an error uploading the file, please try again!";
    echo "filename: " .  basename( $_FILES['fotoUp']['name']);
    echo "target_path1: " .$target_path1;
}