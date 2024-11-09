
<?php

include_once ('../../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):
require_once 'ConexionClass.php';

class ActualizarFotosGeneral extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();

    }





    public function comprimir()
    {

        $sql = "SELECT FM.URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO FM
WHERE FM.ELIMINADA='S'";

        //  ../foto_mantenimientos/20150127/201501-1017459-20150127-1.jpg 211287

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        $c=0;
        $r=0;
        $rn=0;
        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $nombreFoto = oci_result($resultado, 'URL_FOTO');

                $urlFoto= str_replace('../','../../fotos_sgc/',$nombreFoto);

                if (file_exists($urlFoto)) {

                    echo "<br>Encontrado: $urlFoto ".$c++."</br>";


                    $sql2 = "UPDATE SGC_TT_FOTOS_MANTENIMIENTO FM
                            SET ELIMINADA='N' 
                            WHERE FM.ELIMINADA='S' and FM.URL_FOTO='$nombreFoto'";


                    $resultado2 = oci_parse($this->_db, $sql2);

                    $banderas2 = oci_execute($resultado2);

                    if ($banderas2 == true) {
                        echo "<br>registro actualizado: $nombreFoto #".$r++."</br>";
                    }
                    else {
                        echo "<br>No actuaizado: $nombreFoto #".$rn++."</br>";
                    }

        
                } else {

                    echo "<br>El archivo no existe: ".$nombreFoto."</br>";



                }
            }
        } else
            echo "Error en la consulta";


        oci_free_statement($resultado);
        //   echo "Cantidad comprimidos: ".$c;

    }



}

$a=new ActualizarFotosGeneral();
$a->comprimir();

/*
$source_img = '../../fotos_sgc/foto_reconexion/20190104/201901-211287-20190104-2.jpg';
$destination_img = '../../fotos_sgc/foto_reconexion/20190104/201901-211287-20190104-2.jpg';
$info = getimagesize($source_img);
        echo print_r($info);
        /*
$b=new ComprimirFotos();
$b->compress($source_img, $destination_img, 50);

 */
endif;
if ($verificarPermisos==false):
    include "../../../general/vistas/vista.PlantillaError.php";
endif;
?>

