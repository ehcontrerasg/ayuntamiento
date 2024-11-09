<?php
require_once 'ConexionClass.php';

class SubModulosClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }
    
     public function permisos_submenu($id_usuario,$id_modulo)
    {
         
        $resultado = oci_parse($this->_db,"SELECT ID_SUBMODULO FROM SGC_TT_USUARIO_SUBMODULO  where ID_USUARIO ='$id_usuario' AND ID_MODULO='$id_modulo' ");
        //echo "SELECT ID_SUBMODULO FROM SGC_TT_USUARIO_SUBMODULO  where ID_USUARIO ='$id_usuario' AND ID_MODULO='$id_modulo' ";
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




