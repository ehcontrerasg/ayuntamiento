<?php
/**
 * Created by PhpStorm.
 * User: soporte
 * Date: 9/6/2018
 * Time: 3:56 PM
 */

require_once './../Clases/class.reclamacion.php';
$arraycarpeta=preg_split("/-/", basename( $_FILES['fotoUp']['name']));
$carpetafecha=$arraycarpeta[1];
$target_path = "./../../fotos_sgc/foto_incidencias/".$carpetafecha."/";
$target_path1 = $target_path.basename( $_FILES['fotoUp']['name']);
$consecutivofoto=substr($arraycarpeta[3],0,1);
$idReclamacion=$arraycarpeta[2];
$nombrefoto=basename( $_FILES['fotoUp']['name']);
$periodo=$arraycarpeta[1];
$urlfoto="..".substr($target_path1, 7);
//$fechaformato=substr($arraycarpeta[1],6,2)."/".substr($arraycarpeta[1],4,2)."/".substr($arraycarpeta[1],0,4);
$fechaformato=$periodo;
$proyecto="SD";

$foto=new Reclamacion();



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


    $foto->setidReclamo($idReclamacion);
    $foto->setConsecutivo($consecutivofoto);
    $foto->setfechaFormato($fechaformato);
    $foto->seturl($urlfoto);
    $foto->insertarfoto();

} else{
    echo "There was an error uploading the file, please try again!";
    echo "filename: " .  basename( $_FILES['fotoUp']['name']);
    echo "target_path1: " .$target_path1;
}
$bandera=array(array('bandera'=>'1'));
echo json_encode($bandera);
