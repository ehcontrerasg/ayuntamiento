<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 26/10/2020
 * Time: 09:04
 */
require_once 'class.conexion.php';

class Empresa extends ConexionClass {
    public function __construct(){
        parent::__construct();
    }

    function getEmpresas(){
        $sql = "SELECT * FROM ACEASOFT.SGC_TP_EMPRESAS";
        $resource = oci_parse($this->_db,$sql);
        $executed = oci_execute($resource);

        if($executed){
            $json = array('status'=>200,'data'=>array());
            while($row = oci_fetch_assoc($resource)){
                $array = array('id_empresa'  =>$row['ID_EMPRESA'],
                               'desc_empresa'=>$row['DESC_EMPRESA']
                );
                array_push($json['data'],$array);
            }
            return json_encode($json);
        }else{
            return json_decode(array('status'=>500, 'message'=>'Ocurrió un error: '.oci_error($resource)));
        }
    }

    function getEmpresa($idEmpresa){
        $sql = "SELECT * FROM ACEASOFT.SGC_TP_EMPRESAS where ID_EMPRESA = $idEmpresa";
        $resource = oci_parse($this->_db,$sql);
        $executed = oci_execute($resource);

        if($executed){
            $json = array('data'=>array());
            while($row = oci_fetch_assoc($resource)){
                $array = array('id_empresa'  =>$row['ID_EMPRESA'],
                    'desc_empresa'=>$row['DESC_EMPRESA']
                );
                array_push($json['data'],$array);
            }
            return json_encode($json);
        }else{
            return json_decode(array('status'=>500, 'message'=>'Ocurrió un error: '.oci_error($resource)));
        }
    }
}