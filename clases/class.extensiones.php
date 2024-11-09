<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 19/6/2019
 * Time: 16:08
 */

include_once  'class.conexion.php';

class Extensiones extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function GetExtensions()
    {

        $sql = " SELECT LE.ID ID_EXTENSION, LE.EXTENSION,A.DESC_AREA DEPARTAMENTO, U.NOM_USR||' '|| U.APE_USR USUARIO, LE.DESCRIPCION
                 FROM   SGC_TT_DIRECTORIO_TELEFONICO LE,SGC_TT_USUARIOS U, SGC_TP_AREAS A
                 WHERE  U.ID_USUARIO(+) = LE.ID_USUARIO
                 AND    A.ID_AREA = LE.ID_AREA";
        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return $result;

        }

        return false;
    }

    public function InsertarExtension($extension, $usuario, $area, $descripcion)
    {

        //COMMIT;
        $sql = "BEGIN SGC_P_INSERTAREXTENSION(:EXTENSION_IN,:USUARIO_IN,:AREA_IN,:DESCRIPCION_IN,:MSERROR,:CODERROR_OUT); END; ";

        $result = oci_parse($this->_db, $sql);
        oci_bind_by_name($result, ':EXTENSION_IN', $extension, 4);
        oci_bind_by_name($result, ':USUARIO_IN', $usuario, 15);
        oci_bind_by_name($result, ':AREA_IN', $area, 8);
        oci_bind_by_name($result, ':DESCRIPCION_IN', $descripcion, 50);
        oci_bind_by_name($result, ':MSERROR', $msgError, 500);
        oci_bind_by_name($result, ':CODERROR_OUT', $codError, 8);

        if (oci_execute($result)) {
            return array('CODERROR'=>$codError,
                         'MSGERROR'=>$msgError);
        } else {
            return array($codError, 'Error al insertar la extensión ' . $msgError);
        }
        return false;

    }

    public function EditarExtension($id_extension, $extension, $usuario, $area, $descripcion){

        $sql = "BEGIN SGC_P_ACTUALIZAEXTENSION(:ID_EXTENSION_IN,:EXTENSION_IN,:USUARIO_IN,:AREA_IN,:DESCRIPCION_IN,:MSERROR,:CODERROR_OUT); END; ";

        $result = oci_parse($this->_db, $sql);
        oci_bind_by_name($result, ':ID_EXTENSION_IN', $id_extension, 10);
        oci_bind_by_name($result, ':EXTENSION_IN', $extension, 4);
        oci_bind_by_name($result, ':USUARIO_IN', $usuario, 15);
        oci_bind_by_name($result, ':AREA_IN', $area, 8);
        oci_bind_by_name($result, ':DESCRIPCION_IN', $descripcion, 50);
        oci_bind_by_name($result, ':MSERROR', $msgError, 500);
        oci_bind_by_name($result, ':CODERROR_OUT', $codError, 8);

        $bandera = oci_execute($result);
        if ($bandera) {
            oci_close($this->_db);
            return array("CODERROR" => $codError,
                "MSGERROR" => $msgError);

        } else {
            oci_close($this->_db);
            return array("CODERROR" => 2,
                "MSGERROR" => 'Error del servidor ' . $msgError);
        }

    }

    public function  EliminarExtension($id_extension){
        $sql = "BEGIN SGC_P_ELIMINAEXTENSION(:ID_EXTENSION_IN,:MSERROR,:CODERROR_OUT); END; ";

        echo $id_extension;

        $result = oci_parse($this->_db, $sql);
        oci_bind_by_name($result, ':ID_EXTENSION_IN', $id_extension, 10);
        oci_bind_by_name($result, ':MSERROR', $msgError, 500);
        oci_bind_by_name($result, ':CODERROR_OUT', $codError, 8);

        $bandera = oci_execute($result);
        if ($bandera) {
            oci_close($this->_db);
            return array("CODERROR" => $codError,
                         "MSGERROR" => $msgError);

        } else {
            oci_close($this->_db);
            return array("CODERROR" => $codError,
                         "MSGERROR" => 'Error del servidor ' . $msgError);
        }
    }
}