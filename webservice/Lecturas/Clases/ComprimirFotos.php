
<?php

/*
include_once ('../../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):*/
require_once 'class.conexion.php';

class ComprimirFotos extends ConexionClass
{
    public function __construct()
    {
        parent::__construct();

    }

    function compress($source, $destination, $quality) {

        $info = getimagesize($source);
        //echo print_r($info);

        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);


        imagejpeg($image, $destination, $quality);

        // Liberar memoria
        imagedestroy($image);
        //  return true;
    }



    public function comprimir()
    {
        $sql = "SELECT FM.URL_FOTO FROM SGC_TT_FOTOS_LECTURA FM
WHERE FM.ELIMINADA='N' /*and ID_PERIODO =201804*/ and URL_FOTO like '%20171223%'";

        //  ../foto_mantenimientos/20150127/201501-1017459-20150127-1.jpg 211287

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        $c=1;
        $r=1;
        $rn=1;
        if ($banderas == true) {
            while (oci_fetch($resultado)) {

                $nombreFoto = oci_result($resultado, 'URL_FOTO');

                $urlFoto= str_replace('../','../../',$nombreFoto);
                //   $info = getimagesize($urlFoto);
                //   echo "\n".$info['mime']."\n";
                // echo $urlFoto;
                //   $urlFoto = "../" . $nombreFoto;
                if (file_exists($urlFoto)) {
                    //      $filesize=filesize($urlFoto);
                    //  $filesize=$filesize/1024;
                    //     echo "<br>Encontrado: $urlFoto ".$c++."</br>";
                    echo "\n\n archivo encontrado: ".$urlFoto;
                    // $b=new ComprimirFotos();
                    // echo "\n\n\n Normal: ".$urlFoto." ".$filesize." KB ";
                    // $info = getimagesize($urlFoto);
                    //  echo "\n".$info['mime']."\n";

                    try {
                        $time = strtotime('2019-01-08');
                        $newformat = date('d/m/Y',$time);
                        if(date ("d/m/Y",filemtime($urlFoto))!=$newformat){
                            //  echo "<br>"."fecha modificacion $urlFoto: ".date ("d/m/Y",filemtime($urlFoto))." == ".date_format("d/m/Y",$test)."</br>";
                            $this-> compress($urlFoto, $urlFoto, 60);
                            echo "<br>Comprimida: $urlFoto ".$c++."</br>";
                        }else
                            echo "<br>"."fecha modificacion $urlFoto: ".date ("d/m/Y",filemtime($urlFoto))." != ".$newformat."</br>";
                    } catch (Exception $e) {
                        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                    }

                    /* if($this-> compress($urlFoto, $urlFoto, 50))
                         //   echo "\n\n archivo comprido: ".$urlFoto;
                         echo "<br>Comprimida: $urlFoto ".$c++."</br>";
                     else
                         echo "<br>No comprimida: $urlFoto </br>";*/


                    //     $filesize=filesize($urlFoto);
                    //   $filesize=$filesize/1024;
                    // echo "\n\n\n Comprimida: ".$urlFoto." ".$filesize." KB ";
                    //   $c++;
                } else {

                    echo "<br>El archivo no existe: ".$nombreFoto."</br>";

                    /*        $sql2 = "UPDATE SGC_TT_FOTOS_MANTENIMIENTO FM
                                    SET ELIMINADA='S'
                                    WHERE FM.ELIMINADA='N' and FM.URL_FOTO='$nombreFoto'";

                            //  ../foto_mantenimientos/20150127/201501-1017459-20150127-1.jpg 211287

                            $resultado2 = oci_parse($this->_db, $sql2);

                            $banderas2 = oci_execute($resultado2);

                            if ($banderas2 == true) {
                                echo "<br>registro actualizado: $nombreFoto #".$r++."</br>";
                            }
                            else {
                                echo "<br>No actuaizado: $nombreFoto #".$rn++."</br>";
                            }*/

                }
            }
        } else
            echo "Error en la consulta";


        oci_free_statement($resultado);

    }



}

$a=new ComprimirFotos();
$a->comprimir();


/*
$source_img = '../../fotos_sgc/foto_reconexion/20190104/201901-211287-20190104-2.jpg';
$destination_img = '../../fotos_sgc/foto_reconexion/20190104/201901-211287-20190104-2.jpg';
$info = getimagesize($source_img);
        echo print_r($info);
        /*
$b=new ComprimirFotos();
$b->compress($source_img, $destination_img, 50);

 /*
endif;
if ($verificarPermisos==false):
    include "../../../general/vistas/vista.PlantillaError.php";
endif;*/

?>