
<?php
require_once 'ConexionClass.php';

class TipoDocumentoClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    public function selectid($tipodocumento){
     

        $resultado = oci_parse($this->_db, " SELECT ID_TIPO_DOC  FROM  SGC_TP_TIPODOC   WHERE  DESCRIPCION_TIPO_DOC  = '$tipodocumento' ");
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
         $resultado = oci_parse($this->_db,"SELECT DESCRIPCION_TIPO_DOC,ID_TIPO_DOC FROM SGC_TP_TIPODOC  ");
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