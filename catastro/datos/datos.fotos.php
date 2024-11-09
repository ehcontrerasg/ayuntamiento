<?php
/**
 * Created by PhpStorm.
 * User: ehcontrerasg
 * Date: 7/4/2016
 * Time: 10:35 AM
 */

$tipo = $_POST['tip'];


if($tipo=='elFoto'){
    $url_foto=$_POST['urlf'];
    if(unlink($url_foto)){
        echo "true";
    }else{
        echo "false";
    }
}

if($tipo=='elFotoBd'){
    include_once "../clases/class.foto.php";
    $url_foto=$_POST['urlf'];
    $f=new Foto();
    $res=$f->eliminaFoto($url_foto);
    if($res){
        echo "true";
    }else{
        echo "false";
    }
}


?>