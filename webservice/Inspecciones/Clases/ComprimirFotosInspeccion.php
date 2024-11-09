
<?php

/*
include_once ('../../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):*/

//Maximize script execution time
ini_set('max_execution_time', 0);
function resize($originalFile, $targetFile) {



    $info = getimagesize($originalFile);
  //  echo print_r($info).$originalFile;
    $mime = $info['mime'];


    switch ($mime) {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func = 'imagejpeg';
            $new_image_ext = '.jpg';
            $targetFile= str_replace('.jpg',$new_image_ext,$targetFile);
            break;



        default:
            throw new Exception('Unknown image type.');
    }
/*
    $image_create_func = 'imagecreatefromjpeg';
    $image_save_func = 'imagejpeg';
    $new_image_ext = '.jpg';
    $targetFile= str_replace('.jpg',$new_image_ext,$targetFile);*/

//ultima fecha de compresion  10/01/2019

    $time = strtotime('2019-01-10');
    $newformat = date('d/m/Y',$time);



    if(date ("d/m/Y",filemtime($originalFile))!=$newformat) {

        if ($mime != 'image/png') {

            $img = $image_create_func($originalFile);

            $image_save_func($img, "$targetFile", 60);
        }

    }

}


function ListFiles($dir) {
    if($dh = opendir($dir)) {
        $files = Array();
        $inner_files = Array();
        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') {
                if(is_dir($dir . "/" . $file)) {
                    $inner_files = ListFiles($dir . "/" . $file);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files);
                } else {
                    array_push($files, $dir . "/" . $file);
                }
            }
        }
        closedir($dh);
        return $files;
    }
}

$c=1;
$ce=1;



//cambiar ruta para elegir carpeta de fotos a comprimir:
foreach (ListFiles('../../fotos_sgc/foto_mantenimientos') as $key=>$file) {

        try {
         resize($file, $file);
         echo $file . ' #' . $c++ . ' resize Success!<br />';

        } catch (Exception $e) {
            echo 'Error: '.$e.' #'.$ce++.'</br>';
        }


}
?>