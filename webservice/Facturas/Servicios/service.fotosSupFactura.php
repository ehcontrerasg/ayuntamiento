<?php
require_once './../Clases/class.foto.php';
$arraycarpeta=preg_split("/-/", basename( $_FILES['fotoUp']['name']));
$carpetafecha=$arraycarpeta[2];
$target_path = "./../../fotos_sgc/foto_sup_facturas/".$carpetafecha."/";
$target_path1 = $target_path.basename( $_FILES['fotoUp']['name']);
$consecutivofoto=substr($arraycarpeta[3],0,1);
$codsistema=$arraycarpeta[1];
$nombrefoto=basename( $_FILES['fotoUp']['name']);
$periodo=$arraycarpeta[0];
$urlfoto="..".substr($target_path1, 7);
$fechaformato=substr($arraycarpeta[2],4,2)."/".substr($arraycarpeta[2],6,2)."/".substr($arraycarpeta[2],0,4);
$proyecto="SD";
$foto=new FotosFacturaClass();



if(!file_exists($target_path)){

 	if(mkdir($target_path))
 	{
 		chmod($target_path, 0777);
 	}
 	else{
 		echo "no se pudo crear la carpeta ".$target_path;
 	}
 }
 
 
 if(move_uploaded_file($_FILES['fotoUp']['tmp_name'], $target_path1)) {
 	basename( $_FILES['fotoUp']['name']);
 	chmod ($target_path1, 0777);
 	$resultado=$foto->existeFotoSup($consecutivofoto, $codsistema, $periodo);
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
        $foto->setproyecto($proyecto);
        $fotos=$foto->insertarFotoSup();
        $bandera=array(array('bandera'=>'1'));
        echo json_encode($bandera);
 	}
 }else{
 	echo "There was an error uploading the file, please try again!";
 	echo "filename: " .  basename( $_FILES['fotoUp']['name']);
 	echo "target_path1: " .$target_path1;
 }
 





