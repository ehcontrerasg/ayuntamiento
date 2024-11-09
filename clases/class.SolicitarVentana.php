<?php

include_once "class.conexion.php";
 Class SolicitarVentana extends ConexionClass
 {
     private $mesrror;
     private $coderror;

     public function __construct()
     {
         parent::__construct();
     }

     public function InsertarSolicitud($url,$codUsuario)
     {
         $sql       = "BEGIN SGC_P_INSERT_SOLICITUD_VENTANA('$url','$codUsuario',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
         $resultado = oci_parse($this->_db,$sql);

         oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
         oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
         $banderas=oci_execute($resultado);
         if($banderas==TRUE)
         {
             oci_close($this->_db);
             return $resultado;
         }
         else
         {
             oci_close($this->_db);
             echo "false";
             return false;
         }

     }


 }