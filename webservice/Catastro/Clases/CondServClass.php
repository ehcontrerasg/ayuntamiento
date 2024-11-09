<?php
require_once 'ConexionClass.php';

class CondicionServClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    public function  selectid($descripcion){
        $resultado = oci_parse($this->_db,"SELECT ID FROM SGC_TP_CONDICION_SERV WHERE DESCRIPCION='$descripcion' ");
        $banderas=oci_execute($resultado);

     
        if($banderas==TRUE)
        {    
            return $resultado;
        }
        else
        {
            echo "false";
            return false;
        }

    }

    
     public function Todos()
    {
        $resultado = oci_parse($this->_db,"SELECT DESCRIPCION, ID  FROM SGC_TP_CONDICION_SERV 
                                                    WHERE VISIBLE_MOVIL='S' ");
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {    
            return $resultado;
        }
        else
        {
            echo "false";
            return false;
        }
        
    }
}