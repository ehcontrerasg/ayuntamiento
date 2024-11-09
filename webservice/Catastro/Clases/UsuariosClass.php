<?php
require_once 'ConexionClass.php';
/*
 * 
 */

/**
 * Description of UsuariosClass
 *
 * @author EHCONTRERASG // Edwin Hernan Contreras
 */
class UsuariosClass extends ConexionClass {
    public function __construct()
    {
         parent::__construct();
         
    }
    
      public function Usuario_Especifico( $Usuario, $Password)
    {
   
        $result2 = oci_parse($this->_db,"SELECT USR.NOM_USR, USR.APE_USR, USR.ID_USUARIO, USR.ID_PROYECTO, USR.PASS,
											MODU.ID_MODULO, SUBMOD.ID_SUBMODULO,URL_MEN, SUB.DESC_SUBMODULO,USR.LOGIN
											FROM SGC_TT_USUARIOS USR, SGC_TT_USUARIO_MODULO MODU, SGC_TT_USUARIO_SUBMODULO SUBMOD,
											SGC_TP_SUBMODULOS SUB 
											where LOGIN  = UPPER('$Usuario') AND  PASS ='$Password' AND MODU.ID_USUARIO=SUBMOD.ID_USUARIO 
											AND MODU.ID_USUARIO=USR.ID_USUARIO AND MODU.ID_MODULO=SUBMOD.ID_MODULO
											AND USR.FEC_FIN IS NULL AND
											  SUB.ACTIVO='S'   
											AND SUB.ID_MODULO=SUBMOD.ID_MODULO
											AND SUB.ID_SUBMODULO=SUBMOD.ID_SUBMODULO");
        $bandera=oci_execute($result2);
        if($bandera==TRUE)
        {
   
        return $result2;
        }
        else
        {
           
            return false;
        }
    } 
}
