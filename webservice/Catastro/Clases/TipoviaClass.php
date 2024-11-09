<?php
require_once 'ConexionClass.php';

class TipoviaClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    
     public function Todos($fecha)
    {
         $resultado = oci_parse($this->_db,"SELECT DESC_TIPO_VIA, ID_TIPO_VIA, ID_PROYECTO FROM SGC_TP_TIPO_VIA WHERE 
         		TO_DATE(FECHA_CREACION, 'DD/MM/YYYY') >= TO_DATE('$fecha', 'DD/MM/YYYY') ");
  	
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


    public function selectid($tipovia,$proyecto)
    {
        $resultado = oci_parse($this->_db, "SELECT ID_VIA FROM SGC_TP_TIPO_VIA WHERE DESC_VIA='$tipovia' AND ID_PROYECTO='$proyecto'");
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