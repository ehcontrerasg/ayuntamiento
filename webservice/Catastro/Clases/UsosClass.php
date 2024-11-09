<?php
require_once 'ConexionClass.php';

class UsoClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    public function selectid($tipouso){
         $resultado = oci_parse($this->_db,"SELECT ID_USO FROM SGC_TP_USOS WHERE DESC_USO='$tipouso'");
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
    
     public function Todos($fecha)
    {
        $resultado = oci_parse($this->_db,"SELECT ID_USO CODIGO, DESC_USO DESCRIPCION FROM SGC_TP_USOS  WHERE 
        	TO_DATE(FECHA_CREACION, 'DD/MM/YYYY') >= TO_DATE ('$fecha', 'DD/MM/YYYY') 
			AND VISIBLE='S'");
        
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