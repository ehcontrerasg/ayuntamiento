<?php
require_once './../Clases/class.foto.php';
$arraycarpeta=preg_split("/-/", basename( $_FILES['fotoUp']['name']));
$carpetafecha=$arraycarpeta[2];
$target_path = "./../../fotos_sgc/foto_ayuntamiento/".preg_split("/_/",$carpetafecha)[0]."/";
$target_path1 = $target_path.basename( $_FILES['fotoUp']['name']);
$consecutivofoto=substr($arraycarpeta[3],0,1);
$cliente=$arraycarpeta[1];
$nombrefoto=basename( $_FILES['fotoUp']['name']);
$agno=$arraycarpeta[0];
$urlfoto="..".substr($target_path1, 7);
$fechaformato=$arraycarpeta[2];



$foto=new FotosAyuntamientoClass();



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


    $foto->setConsecutivo($consecutivofoto);
    $foto->setCliente($cliente);
    $foto->setAgno($agno);
    $foto->setFecha($fechaformato);
    $foto->setNombre($nombrefoto);
    $foto->setUrl($urlfoto);
    $foto->setproyecto('BC');
    $fotos=$foto->insertarfoto();
} else{
    echo "There was an error uploading the file, please try again!";
    echo "filename: " .  basename( $_FILES['fotoUp']['name']);
    echo "target_path1: " .$target_path1;
}
$bandera=array(array('bandera'=>'1'));
echo json_encode($bandera);
