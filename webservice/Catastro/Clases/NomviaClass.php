<?php
require_once 'ConexionClass.php';

class NomviaClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

     public function nomviaportipovia($id_tipovia,$proyecto)
    {
        $resultado = oci_parse($this->_db,"SELECT DESC_NOM_VIA FROM SGC_TP_NOMBRE_VIA WHERE ID_TIPO_VIA='$id_tipovia' AND ID_PROYECTO='$proyecto' 
            ORDER BY (DESC_NOM_VIA) ");
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
        $resultado = oci_parse($this->_db,"SELECT DESC_NOM_VIA ,  ID_TIPO_VIA, ID_PROYECTO FROM SGC_TP_NOMBRE_VIA WHERE 
        		TO_DATE(FECHA_CREACION, 'DD/MM/YYYY') >= TO_DATE('$fecha', 'DD/MM/YYYY') ORDER BY (DESC_NOM_VIA) ");
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
