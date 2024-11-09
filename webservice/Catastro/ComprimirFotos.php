
<?php
/*
include_once ('../../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):*/
require_once 'ConexionClass.php';

class ComprimirFotos extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();

    }

	function compress($source, $destination, $quality) {

		//$info = getimagesize($source);

	//	if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);

			/*
		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);
*/
		imagejpeg($image, $destination, $quality);

		return $destination;
	}



    public function comprimir()
    {
        $sql = "SELECT FM.URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO FM
WHERE FM.ELIMINADA='S'";

      //  ../foto_mantenimientos/20150127/201501-1017459-20150127-1.jpg 211287

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $nombreFoto = oci_result($resultado, 'URL_FOTO');

                $urlFoto= str_replace('../','../../fotos_sgc/',$nombreFoto);
                echo $urlFoto;
             //   $urlFoto = "../" . $nombreFoto;
                if (file_exists($urlFoto)) {
                    $source_img = '../fotos_sgc/foto_reconexion/20190104/201901-211287-20190104-1.jpg';
                    $destination_img = '../fotos_sgc/foto_reconexion/20190104/201901-211287-20190104-1.jpg';
                    $b=new ComprimirFotos();
                    $b->compress($source_img, $destination_img, 70);
                } else {
                    echo "\n\n El archivo no existe: ".$urlFoto;
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
endif;
if ($verificarPermisos==false):
    include "../../../general/vistas/vista.PlantillaError.php";
endif;*/
?>