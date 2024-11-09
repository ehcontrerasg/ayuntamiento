<?php

include_once '../../clases/class.conexion.php';

class Reportes_Inc_Datos extends ConexionClass{



    public function __construct()
    {
        parent::__construct();

    }

    public function obtieneDatos($proyecto)
    {
        $sql="SELECT I.CODIGO_INM, AC.ID_USO,TAR.COD_USO,SI.COD_SERVICIO FROM
  SGC_TT_INMUEBLES I ,
  SGC_TP_ACTIVIDADES AC,
  SGC_TT_SERVICIOS_INMUEBLES SI,
  SGC_TP_TARIFAS TAR
WHERE AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
      SI.COD_INMUEBLE=I.CODIGO_INM AND
      SI.CONSEC_TARIFA=TAR.CONSEC_TARIFA AND
      AC.ID_USO<>TAR.COD_USO AND
      I.ID_ESTADO NOT IN ('CC','CT','CB') AND 
      I.ID_PROYECTO = '$proyecto'";

        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);



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