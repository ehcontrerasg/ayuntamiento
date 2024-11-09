
<?php


include_once ('../../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):

//Maximize script execution time
ini_set('max_execution_time', 0);

//obtiene tamaño en formato humano
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    $result=sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) /*. @$sz[$factor]*/;
   if( @$sz[$factor]=='M')
       $result=$result*1024;
    return $result;
}

function resize($originalFile, $targetFile,$contador) {



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

            case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            $image_save_func = 'imagepng';
            $new_image_ext = '.png';
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



$imageFileSize=human_filesize(filesize($originalFile),2);

    $time = strtotime('2019-01-07');
    $newformat = date('d/m/Y',$time);

    $time2 = strtotime('2019-01-10');
    $newformat2 = date('d/m/Y',$time2);

  //  echo $imageFileSize.'</br>';

    if ($imageFileSize>1800) {


        //if (date("d/m/Y", filemtime($originalFile)) > $newformat) {

          //  if ($mime != 'image/png') {

                   $img = $image_create_func($originalFile);

                 $image_save_func($img, "$targetFile", 70);

        echo $originalFile . ' #' . $contador++ . ' resize Success!<br />';
        echo 'size: '.$imageFileSize.'</br>';
    }

      //  }
   // }
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


//$size=count(ListFiles('../../fotos_sgc/foto_inspeccion/20121231'));

//for($i=0;$i<$size;$i++)

foreach (ListFiles('../../fotos_sgc/foto_incidencias') as $key=>$file) {
    // echo "<div class=\"box\"><img src=\"$file\"/></div>";
//echo ListFiles('../../fotos_sgc/foto_inspeccion/20131231')[$i].$c++.'</br>';

    //  $ruta= ListFiles('../../fotos_sgc/foto_inspeccion/20121231')[$i];

    //compress( $ruta,  $ruta, 60);
    //  echo "<br>Comprimida: $file ".$c++."</br>";


    try {
        resize($file, $file, 1);

    } catch (Exception $e) {
        echo 'Error: ' . $e . ' #' . $ce++ . '</br>';
    }


}

  /*    if(resizeImage($file,$file,$NewImageWidth,$NewImageHeight,$Quality))
        {
            echo $file.' #'.$c++.'resize Success!<br />';



        }else{
            echo $file.' resize Failed!<br />';
        }*/

endif;
if ($verificarPermisos==false):
    include "../../../general/vistas/vista.PlantillaError.php";
endif;
?>