<?php
include_once "class.conexion.php";


class Uso extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getUsoCortByProPerZon($pro,$per,$zon)
    {
        $pro=addslashes($pro);
        $per=addslashes($per);
        $zon=addslashes($zon);

        $where="";
        if(trim($per)!=""){
            $where.=" AND C.ID_PERIODO='$per' ";
        }
        if(trim($zon)!=""){
            $where.=" AND I.ID_ZONA='$zon' ";
        }

        $sql="SELECT
                AC.ID_USO CODIGO,
                AC.ID_USO DESCRIPCION
              FROM
                SGC_TT_REGISTRO_CORTES C,
                SGC_tT_INMUEBLES I,
                sgc_tp_actividades ac
              WHERE
                I.CODIGO_INM=C.ID_INMUEBLE and
                AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                C.FECHA_ACUERDO IS NULL AND
                C.FECHA_REVERSION IS NULL AND
                C.FECHA_EJE IS NULL AND
                C.PERVENC='N' AND
                 I.ID_PROYECTO= '$pro'
                $where
              GROUP BY
                AC.ID_USO
              ORDER BY AC.ID_USO DESC";

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

    public function getCatCortByProPerZon($pro,$per,$zon)
    {
        $pro=addslashes($pro);
        $per=addslashes($per);
        $zon=addslashes($zon);

        $where="";
        if(trim($per)!=""){
            $where.=" AND C.ID_PERIODO='$per' ";
        }
        if(trim($zon)!=""){
            $where.=" AND I.ID_ZONA='$zon' ";
        }

         $sql="SELECT
                tar.CATEGORIA CODIGO,
                tar.CATEGORIA DESCRIPCION
              FROM
                SGC_TT_REGISTRO_CORTES C,
                SGC_tT_INMUEBLES I,
                sgc_tp_actividades ac,
                SGC_TT_SERVICIOS_INMUEBLES si,
                SGC_TP_TARIFAS    tar
                   WHERE
                I.CODIGO_INM=C.ID_INMUEBLE and
                AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                C.FECHA_ACUERDO IS NULL AND
                C.FECHA_REVERSION IS NULL AND
                C.FECHA_EJE IS NULL AND
                C.PERVENC='N' AND
                si.COD_INMUEBLE=i.CODIGO_INM and 
                si.COD_SERVICIO in (1,3) and 
                si.CONSEC_TARIFA=tar.CONSEC_TARIFA  AND        
                I.ID_PROYECTO= '$pro'
                $where
              GROUP BY
               tar.CATEGORIA
              ORDER BY tar.CATEGORIA DESC";

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


    public function getUsos()
    {

        $sql="SELECT
                US.DESC_USO DESCRIPCION,
                US.ID_USO CODIGO
              FROM
                SGC_TP_USOS US
              WHERE VISIBLE='S' ";

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


    public function getUsoCorte(){
        $sql = "select U.DESC_USO,U.ID_USO from sgc_tp_usos u
                WHERE U.OPERA_CORTE='S'";
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



    public function getCategoriaByUso($uso,$proyecto){
         $sql = "select unique T.CATEGORIA DESCRIPCION,T.CATEGORIA CODIGO from SGC_TP_TARIFAS T
                WHERE T.COD_USO='$uso' and t.COD_PROYECTO='$proyecto' AND COD_SERVICIO IN (1,3)";
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
