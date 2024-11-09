<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 26/10/2020
 * Time: 09:51
 */
require_once 'class.conexion.php';

class Documento extends ConexionClass {
    public function __construct(){
        parent::__construct();
    }

    function getDocumentos($tipoDocumentos=[]){

        $where = $this->condicionIN($tipoDocumentos);

        $sql = "SELECT * 
                FROM ACEASOFT.SGC_TP_TIP_DOCUMENTOS TD".$where."
                order by DESC_DOCUMENTO";

        $resource = oci_parse($this->_db,$sql);
        $executed = oci_execute($resource);

        if($executed){
            $json = array('status'=>200,'data'=>array());
            while($row = oci_fetch_assoc($resource)){
                $array = array('id'=>$row['TIP_DOCUMENTOS'],
                               'descripcion'=> $row['DESC_DOCUMENTO']
                );

                array_push($json['data'],$array);
            }

            return json_encode($json);
        }else{
            return json_encode(array('status'=>500,'message'=>'Ocurrió un error: '.oci_error($resource)));
        }
    }

    private function condicionIN($arrayTipoDocumentos){

        $cantidadTipoDocumentos = count($arrayTipoDocumentos);

        if($cantidadTipoDocumentos > 0){
            $in = "";
            foreach ($arrayTipoDocumentos as &$tipoDocumento)
                $in .= $tipoDocumento.',';


            //Eliminamos la última coma.
            $tipDocumentos = substr($in,0,-1);

            return ' WHERE TD.TIP_DOCUMENTOS IN('. $tipDocumentos .') ';
        }

        return '';
    }

    public function obtenerTipoDoc()

    {
        $sql="SELECT TD.ID_TIPO_DOC CODIGO, TD.DESCRIPCION_TIPO_DOC DESCRIPCION
                FROM ACEASOFT.SGC_TP_TIPODOC TD";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }


    }


    public function obtenerEnCalidad()

    {
        $sql="SELECT EC.IDENTIFICADOR CODIGO, EC.DESCRIPCION DESCRIPCION
                FROM SGC_TP_EN_CALIDAD EC";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }


    }

}