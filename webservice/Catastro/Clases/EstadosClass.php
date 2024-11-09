<?php
require_once 'ConexionClass.php';

class EstadosClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }


     public function selectid($descripcion)
    {
        $resultado = oci_parse($this->_db,"SELECT ID_ESTADO FROM SGC_TP_ESTADOS_INMUEBLES WHERE DESC_ESTADO='$descripcion' ");
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
         $resultado = oci_parse($this->_db,"SELECT DESC_ESTADO DESCRIPCION,ID_ESTADO FROM SGC_TP_ESTADOS_INMUEBLES");
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