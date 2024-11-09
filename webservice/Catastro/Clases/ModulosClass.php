<?php
require_once 'ConexionClass.php';

class ModulosClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }
    
     public function permisos_menu($id)
    {
         
        $resultado = oci_parse($this->_db,"SELECT ID_MODULO FROM SGC_TT_USUARIO_MODULO  where ID_USUARIO='$id' ");
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
