<?php
require_once 'ConexionClass.php';

class UrbanizacionesClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }


    public function selectid($urbanizacion)
    {
        $resultado = oci_parse($this->_db, "SELECT ID_URBANIZACION FROM SGC_TP_URBANIZACIONES WHERE DESC_URBANIZACION='$urbanizacion'");
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
         $resultado = oci_parse($this->_db,"SELECT * FROM SGC_TP_URBANIZACIONES ORDER BY DESC_URBANIZACION ");
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