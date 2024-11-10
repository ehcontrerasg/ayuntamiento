<?php
require_once  "class.conexion.php";


class Asignacion extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getCantidadBySectorRuta($sector,$ruta)
    {
        $sql="SELECT COUNT(1) CANTIDAD FROM SGC_TT_ASIGNACION WHERE FECHA_FIN IS NULL AND ANULADO='N'
        AND ID_SECTOR='$sector' AND ID_RUTA='$ruta' GROUP BY(ID_SECTOR,ID_RUTA)
	        ";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }


    }


    public function insertaAsignacion($codigo_inm,$operario,$sector,$ruta,$periodo,$coduser)
    {

        $sql = "INSERT INTO SGC_TT_ASIGNACION
            (ID_TIPO_RUTA,ID_INMUEBLE,ID_OPERARIO,ID_SECTOR,ID_RUTA,ID_PERIODO,FECHA_ASIG,ID_ASIGNADOR)
            VALUES(1,'$codigo_inm','$operario','$sector','$ruta','$periodo',sysdate,'$coduser')";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }


    }


    public function getInmXAsignarBySectorRuta($sector,$ruta)
        {
            $sql = "SELECT
                I.ID_ZONA, 
                I.CODIGO_INM , 
                I.DIRECCION, 
                U.DESC_URBANIZACION, 
                C.CODIGO_CLI, 
                C.NOMBRE_CLI, 
                C.TIPO_DOC, 
                C.DOCUMENTO, 
                C.TELEFONO, 
                I.ID_PROCESO, 
                I.CATASTRO, 
                A.ID_USO, 
                A.ID_ACTIVIDAD, 
                I.TOTAL_UNIDADES, 
                I.ID_TIPO_CLIENTE, 
                I.ID_ESTADO, 
                I.ID_PROYECTO
            FROM 
                SGC_TT_INMUEBLES I, 
                SGC_TP_URBANIZACIONES U, 
                SGC_TT_CLIENTES C, 
                SGC_TP_ACTIVIDADES A, 
                SGC_TT_CONTRATOS O
            WHERE  
                I.CONSEC_URB = U.CONSEC_URB AND 
                C.CODIGO_CLI(+)= O.CODIGO_CLI AND 
                I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND 
                O.CODIGO_INM(+) = I.CODIGO_INM AND 
                SUBSTR(I.ID_PROCESO,0,2) = '$sector' AND 
                SUBSTR(I.ID_PROCESO,3,2) = '$ruta' AND 
                O.FECHA_FIN (+) IS NULL AND 
                I.ID_ESTADO NOT IN('CB') AND
                I.CODIGO_INM NOT IN (SELECT EMC.INMUEBLE FROM SGC_TEMP_EXCL_MANT_CAT EMC WHERE EMC.FECHA_FIN>=SYSDATE)
                ORDER BY ID_PROCESO";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }


        }





}
