<?php
require_once 'ConexionClass.php';

class RutasClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    
     public function ObtenerRuta($cod_operario,$tiporuta)
    {

        $sql="SELECT CSER.DESCRIPCION CONDSER, INM.ID_ZONA, ASIG.ID_PERIODO PERIODO_EJE, INM.CODIGO_INM COD_SISTEMA, INM.DIRECCION, URB.DESC_URBANIZACION URBANIZACION, NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE,  
          DOC.DESCRIPCION_TIPO_DOC TIPO_DOCUMENTO,CLI.DOCUMENTO, CLI.TELEFONO, INM.ID_PROCESO, INM.CATASTRO, USO.DESC_USO USO_VIVIENDA,
           ACTI.DESC_ACTIVIDAD ACTIVIDAD, INM.TOTAL_UNIDADES , INM.UNIDADES_HAB,UNIDADES_DES, EST.DESC_ESTADO ESTADO
          FROM SGC_TT_ASIGNACION ASIG, SGC_TT_INMUEBLES INM, SGC_TP_URBANIZACIONES URB, SGC_TT_CLIENTES CLI, 
          SGC_TP_TIPODOC DOC, SGC_TP_USOS USO, SGC_TP_ACTIVIDADES ACTI, SGC_TP_ESTADOS_INMUEBLES EST, SGC_TT_CONTRATOS CON,
          SGC_TP_CONDICION_SERV CSER     
          WHERE ASIG.ID_OPERARIO='$cod_operario'
          AND ASIG.ID_TIPO_RUTA='$tiporuta' 
          AND CSER.ID=INM.CONDICION_SERV  
          AND ASIG.FECHA_FIN IS NULL
          AND ASIG.ID_INMUEBLE= INM.CODIGO_INM 
          AND ASIG.ID_SECTOR=INM.ID_SECTOR 
          AND ASIG.ID_RUTA=INM.ID_RUTA
		  AND ASIG.ANULADO='N'
          AND URB.CONSEC_URB= INM.CONSEC_URB 
          AND DOC.ID_TIPO_DOC(+)=CLI.TIPO_DOC 
          AND CLI.CODIGO_CLI(+)= CON.CODIGO_CLI
          AND CON.CODIGO_INM(+)=INM.CODIGO_INM
          AND USO.ID_USO= ACTI.ID_USO 
          AND EST.ID_ESTADO= INM.ID_ESTADO 
          AND ACTI.SEC_ACTIVIDAD=INM.SEC_ACTIVIDAD
          AND CON.FECHA_FIN (+) IS NULL";

         $resultado = oci_parse($this->_db,$sql);

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
    
    public function ObtenerNumeroRutas($cod_operario)
    {
        $resultado = oci_parse($this->_db,"SELECT COUNT(*) CANTIDAD FROM SGC_TT_ASIGNACION ASIG, SGC_TT_INMUEBLES INM
          WHERE ASIG.ID_OPERARIO='$cod_operario'
          AND ASIG.ID_TIPO_RUTA='1' 
          AND ASIG.FECHA_FIN IS NULL  
          AND ASIG.ID_INMUEBLE= INM.CODIGO_INM 
          AND ASIG.ID_SECTOR=INM.ID_SECTOR 
		  AND ASIG.ANULADO='N'
          AND ASIG.ID_RUTA=INM.ID_RUTA" );
        

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