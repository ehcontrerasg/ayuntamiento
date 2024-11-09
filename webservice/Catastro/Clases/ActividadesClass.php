<?php
require_once 'ConexionClass.php';
require_once 'UsosClass.php';

class ActividadesClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    public function  selectid($iduso, $descripcion){
        $resultado = oci_parse($this->_db,"SELECT ID_ACTIVIDAD FROM SGC_TP_ACTIVIDADES WHERE DESC_ACTIVIDAD='$descripcion' AND ID_USO='$iduso' ");
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

    public function actividadporuso($uso)
    {
         $resultado = oci_parse($this->_db,"SELECT DESC_ACTIVIDAD DESCRIPCION,  FROM SGC_TP_ACTIVIDADES WHERE ID_USO='$uso' ORDER BY (DESC_ACTIVIDAD) ");
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
        $resultado = oci_parse($this->_db,"SELECT DESC_ACTIVIDAD DESCRIPCION, ID_USO USO_CODIGO, ID_ACTIVIDAD SEC_ACTIVIDAD FROM SGC_TP_ACTIVIDADES ");
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