<?php
require_once './../Clases/class.foto.php';
$arraycarpeta=preg_split("/-/", basename( $_FILES['fotoUp']['name']));
$carpetafecha=$arraycarpeta[1];
$target_path = "./../../fotos_sgc/foto_inspeccion_med/".$carpetafecha."/";
$target_path1 = $target_path.basename( $_FILES['fotoUp']['name']);
$consecutivofoto=substr($arraycarpeta[2],0,1);
$codsistema=$arraycarpeta[0];
$nombrefoto=basename( $_FILES['fotoUp']['name']);
$periodo=substr($arraycarpeta[1],0,6);
$urlfoto="..".substr($target_path1, 7);
$fechaformato=substr($arraycarpeta[1],4,2)."/".substr($arraycarpeta[1],6,2)."/".substr($arraycarpeta[1],0,4);
$foto=new Fotos();



if(!file_exists($target_path)){

 	if(mkdir($target_path))
 	{

 	}
 	else{
 		echo "no se pudo crear la carpeta ".$target_path;
 	}
 }
 
 
 if(move_uploaded_file($_FILES['fotoUp']['tmp_name'], $target_path1)) {
 	basename( $_FILES['fotoUp']['name']);

 	$resultado=$foto->existefotoEst($consecutivofoto, $codsistema, $periodo);
 	oci_fetch($resultado);
 	$numerofotos= oci_result($resultado, "NUMFOTOS");
 	if( $numerofotos==1){
 		$bandera=array(array('bandera'=>'1'));
 		echo json_encode($bandera);
 	}
 	else
 	{
 
 		$foto->setconsecutivo($consecutivofoto);
    $foto->setcodsistema($codsistema);
    $foto->setfecha($fechaformato);
    $foto->setnombre($nombrefoto);
    $foto->seturl($urlfoto);
    $foto->setperiodo($periodo);
    $fotos=$foto->insertarfotoest();
 		$bandera=array(array('bandera'=>'1'));
 		echo json_encode($bandera);
 	}
 }else{
 	echo "There was an error uploading the file, please try again!";
 	echo "filename: " .  basename( $_FILES['fotoUp']['name']);
 	echo "target_path1: " .$target_path1;
 }
 





