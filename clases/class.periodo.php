<?php
include_once "class.conexion.php";


class Periodo extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }


    public function obtenerMaxperiodoAseo ($zona){
        $sql="SELECT
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),1),'YYYYMM')MAXPER,
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),1),'Month','nls_date_language=spanish') MES
              FROM
                SGC_TP_PERIODO_ZONA_ASEO
              WHERE
                ID_ZONA = '$zona' AND
                FEC_CIERRE IS NOT NULL";
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


    public function getPerByPeriniPerfin ($perIni,$perFin){
         $sql="SELECT
                P.ID_PERIODO
                FROM SGC_TP_PERIODOS P
                WHERE P.ID_PERIODO>=$perIni AND P.ID_PERIODO<=$perFin";
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



    public function getPerMantCorrByZon ($zona){
        $sql="SELECT
                MAX(PERIODO) MAXPER,
                TO_CHAR(TO_DATE(MAX(PERIODO),'YYYYMM'), 'Month YYYY','nls_date_language=spanish') MES
              FROM
                SGC_TP_PERIODO_ZONA
              WHERE
                ID_ZONA = '$zona' AND
                FEC_CIERRE IS NOT NULL";
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

    public function getPerAsiLecByPro ($proyecto){
        $sql="SELECT 
                P.ID_PERIODO DESCRIPCION, 
                Z.PERIODO CODIGO
              FROM 
                SGC_TT_REGISTRO_LECTURAS L, 
                SGC_TP_PERIODO_ZONA Z, 
                SGC_TP_PERIODOS P
              WHERE L.ID_ZONA = Z.ID_ZONA 
                AND P.ID_PERIODO = Z.PERIODO
                AND FECHA_LECTURA IS NULL 
                AND Z.CODIGO_PROYECTO = '$proyecto'
                AND Z.FEC_DIFE IS NULL
              GROUP BY Z.PERIODO 
              ORDER BY Z.PERIODO DESC";
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


    public function getPerCortAbieByPer ($proyecto){
        $proyecto=addslashes($proyecto);
        $sql="SELECT
                C.ID_PERIODO CODIGO,
                C.ID_PERIODO DESCRIPCION 
              FROM
                SGC_TT_REGISTRO_CORTES C,
                SGC_tT_INMUEBLES I
              WHERE
                I.CODIGO_INM=C.ID_INMUEBLE AND
                C.FECHA_ACUERDO IS NULL AND
                C.FECHA_REVERSION IS NULL AND
                C.FECHA_EJE IS NULL AND
                C.PERVENC='N' AND
                I.ID_PROYECTO= '$proyecto'
              GROUP BY
                C.ID_PERIODO
              ORDER BY
                C.ID_PERIODO DESC";
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

    public  function getPeriodo(){
        $sql="SELECT * FROM (
            SELECT PZ.PERIODO FROM SGC_tP_PERIODO_ZONA PZ
            GROUP BY PZ.PERIODO
            ORDER BY PZ.PERIODO DESC)
            WHERE ROWNUM <200";
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

    public  function obtenerPeriodo(){
        $sql="
            SELECT PERIODO CODIGO, PERIODO DESCRIPCION FROM SGC_TT_HISTORICO_ESTADO 
            GROUP BY PERIODO
            ORDER BY PERIODO DESC";
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


    public function obtenerperiodosMed (){
        $resultado = oci_parse($this->_db,"
                SELECT DISTINCT PERIODO CODIGO, PERIODO DESCRIPCION  FROM SGC_TP_PERIODO_ZONA PZ
                ORDER BY 1 DESC
                ");

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
