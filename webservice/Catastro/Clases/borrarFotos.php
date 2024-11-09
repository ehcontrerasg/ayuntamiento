
<?php
require_once '../../../clases/class.conexion.php';

class borrarFotos extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();

    }


    public function EliminarFotos()
    {
        $sql = "SELECT FM.URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO FM
WHERE FM.ELIMINADA='S'";

      //  ../foto_mantenimientos/20150127/201501-1017459-20150127-1.jpg

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $nombreFoto = oci_result($resultado, 'URL_FOTO');

                $urlFoto= str_replace('../','../../fotos_sgc/',$nombreFoto);
              //  echo $urlFoto;
             //   $urlFoto = "../" . $nombreFoto;
                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                        echo "\n\nFoto eliminada con exito: ".$urlFoto;
                    } else {
                        echo "\n\nError no se pudo borrar la foto: ".$urlFoto;
                    }
                } else {
                    echo "\n\n El archivo no existe: ".$urlFoto;
                }
            }
        } else
            echo "Error en la consulta";

        oci_free_statement($resultado);
    }



}

$a=new borrarFotos();
$a->EliminarFotos();

?>