<?php
require_once 'ConexionClass.php';


class TablasParametricas extends ConexionClass {
    
	public function ObtenerVersion()
    {
    	$result = oci_parse($this->_db,"SELECT VERSION, BORRA_DATOS  FROM SGC_TP_VERSION_APK WHERE VERSION=(SELECT MAX(VERSION) FROM SGC_TP_VERSION_APK)" );
    	$bandera=oci_execute($result);
    	if($bandera==TRUE)
    	{
    		return $result;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    public function ObtenerPermisos($usuario)
    {
    	$result = oci_parse($this->_db,"SELECT MODU.ID_MODULO, SUBMOD.ID_SUBMODULO FROM SGC_TT_USUARIO_MODULO MODU, SGC_TT_USUARIO_SUBMODULO SUBMOD
    			WHERE MODU.ID_USUARIO=SUBMOD.ID_USUARIO AND MODU.ID_USUARIO='$usuario' AND MODU.ID_MODULO=SUBMOD.ID_MODULO" );
    	$bandera=oci_execute($result);
    	if($bandera==TRUE)
    	{
    		return $result;
    	}
    	else
    	{
    		return false;
    	}
    }
    
}
