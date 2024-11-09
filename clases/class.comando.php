<?php

require_once 'class.conexion.php';

abstract class Comando extends ConexionClass {

    public function __construct(){
        parent::__construct();
    }

    public function EjecutarConsulta($sql){

        $resultado = oci_parse($this->_db,$sql);
        $bandera   = oci_execute($resultado);

        if($bandera == true){
            return $resultado;
        }else{
            return false;
        }
    }

    public function EjecutarProcedimiento($sql,$parametros_in,$parametros_out=[]){


        $resultado = oci_parse($this->_db,$sql);

        for($i=0;$i<count($parametros_in);$i++){
            $parametroProcedimiento = ":".$parametros_in[$i]["nombreParametro"];
            $var = ${$parametros_in[$i]["nombreParametro"]};
            $var = $parametros_in[$i]["valorParametro"];
            //$var = ${$parametros_in[$i]["valorParametro"]};
            //var_dump($var);
            oci_bind_by_name($resultado,$parametroProcedimiento,$var);
        }

        $bandera   = oci_execute($resultado);
        print_r($bandera);
        return true;
        if($bandera == true){
            /*return [
                    "CODE"   => $codresult,
                    "STATUS" => $msjError
                   ];*/
            return true;
        }else{
            return false;
        }

    }
}