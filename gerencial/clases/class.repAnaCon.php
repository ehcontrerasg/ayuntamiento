<?php
include_once 'class.conexion.php';
class ReportesAnaCon extends ConexionClass{

    public function __construct()
    {
        parent::__construct();
    }

    public function getcodresult(){
        return $this->codresult;
    }

    public function getmsgresult(){
        return $this->msgresult;
    }

    //CONSULTAS ANALISIS DE CONSUMOS MEDIDOS POR SECTOR

    public function MedidosDiferencia($proyecto, $sector, $ruta, $cliente, $diametro, $perini, $perfin, $arrayperiodo){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($cliente <> '') $uso = " AND A.ID_USO = '$cliente' ";
        if($diametro <> '') $calibre = " AND C.COD_CALIBRE = $diametro ";

        $sql = "SELECT * FROM(SELECT I.CODIGO_INM, A.ID_USO, C.DESC_CALIBRE, RL.PERIODO,(CASE WHEN (RL.CONSUMO_ACT < 0) THEN 0
ELSE RL.CONSUMO_ACT END)  CONSUMO_ACT
        FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_FACTURA F, SGC_TP_OBSERVACIONES_LECT OL, SGC_TT_INMUEBLES I,
        SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES C
        WHERE I.CODIGO_INM = RL.COD_INMUEBLE
        AND F.INMUEBLE = I.CODIGO_INM
        AND F.PERIODO = RL.PERIODO
        AND F.INMUEBLE = RL.COD_INMUEBLE
        AND I.CODIGO_INM = M.COD_INMUEBLE
        AND M.COD_INMUEBLE = F.INMUEBLE
        AND M.COD_INMUEBLE = RL.COD_INMUEBLE
        AND OL.CODIGO = RL.OBSERVACION
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND M.COD_CALIBRE = C.COD_CALIBRE
        AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta
        $uso $calibre
        AND RL.PERIODO BETWEEN $perini AND $perfin 
        AND OL.CALCULO IN ('D')
        AND M.FECHA_BAJA IS NULL
        ORDER BY RL.PERIODO DESC)
        PIVOT 
                (
                   SUM(CONSUMO_ACT)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 DESC";
        //echo 'res = '.$sql;
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



    public function MedidosDiferenciaCupo($proyecto, $sector, $ruta, $cliente, $diametro, $perini, $perfin, $arrayperiodo){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($cliente <> '') $uso = " AND A.ID_USO = '$cliente' ";
        if($diametro <> '') $calibre = " AND C.COD_CALIBRE = $diametro ";

        $sql = "SELECT * FROM(SELECT I.CODIGO_INM, A.ID_USO, C.DESC_CALIBRE, RL.PERIODO,
        (F.CONSUMO_AGUA_ORI - 
        (CASE WHEN (RL.CONSUMO_ACT < 0) THEN 0 ELSE RL.CONSUMO_ACT END)) CONSUMO_ACT        
        FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_FACTURA F, SGC_TP_OBSERVACIONES_LECT OL, SGC_TT_INMUEBLES I,
        SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES C
        WHERE I.CODIGO_INM = RL.COD_INMUEBLE
        AND F.INMUEBLE = I.CODIGO_INM
        AND F.PERIODO = RL.PERIODO
        AND F.INMUEBLE = RL.COD_INMUEBLE
        AND I.CODIGO_INM = M.COD_INMUEBLE
        AND M.COD_INMUEBLE = F.INMUEBLE
        AND M.COD_INMUEBLE = RL.COD_INMUEBLE
        AND OL.CODIGO = RL.OBSERVACION
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND M.COD_CALIBRE = C.COD_CALIBRE
        AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta
        $uso $calibre
        AND RL.PERIODO BETWEEN $perini AND $perfin 
        AND OL.CALCULO IN ('D')
        AND M.FECHA_BAJA IS NULL
        ORDER BY RL.PERIODO DESC)
        PIVOT 
                (
                   SUM(CONSUMO_ACT)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 DESC";
        //echo 'res = '.$sql;
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




    public function MedidosPromedio($proyecto, $sector, $ruta, $cliente, $diametro, $perini, $perfin, $arrayperiodo){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($cliente <> '') $uso = " AND A.ID_USO = '$cliente' ";
        if($diametro <> '') $calibre = " AND C.COD_CALIBRE = $diametro ";

        $sql = "SELECT * FROM(SELECT I.CODIGO_INM, A.ID_USO, C.DESC_CALIBRE, RL.PERIODO, F.CONSUMO_AGUA_ORI
        FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_FACTURA F, SGC_TP_OBSERVACIONES_LECT OL, SGC_TT_INMUEBLES I,
        SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES C
        WHERE I.CODIGO_INM = RL.COD_INMUEBLE
        AND F.INMUEBLE = I.CODIGO_INM
        AND F.PERIODO = RL.PERIODO
        AND F.INMUEBLE = RL.COD_INMUEBLE
        AND I.CODIGO_INM = M.COD_INMUEBLE
        AND M.COD_INMUEBLE = F.INMUEBLE
        AND M.COD_INMUEBLE = RL.COD_INMUEBLE
        AND OL.CODIGO = RL.OBSERVACION
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND M.COD_CALIBRE = C.COD_CALIBRE
        AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta
        $uso $calibre
        AND RL.PERIODO BETWEEN $perini AND $perfin 
        AND OL.CALCULO IN ('P')
        AND M.FECHA_BAJA IS NULL
        ORDER BY RL.PERIODO DESC)
        PIVOT 
                (
                   SUM(CONSUMO_AGUA_ORI)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 DESC";
        //echo 'res = '.$sql;
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

    public function MedidosTodos($proyecto, $sector, $ruta, $cliente, $diametro, $perini, $perfin, $arrayperiodo){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($cliente <> '') $uso = " AND A.ID_USO = '$cliente' ";
        if($diametro <> '') $calibre = " AND C.COD_CALIBRE = $diametro ";

        $sql = "SELECT * FROM(SELECT I.CODIGO_INM, A.ID_USO, C.DESC_CALIBRE, RL.PERIODO, F.CONSUMO_AGUA_ORI
        FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_FACTURA F, SGC_TP_OBSERVACIONES_LECT OL, SGC_TT_INMUEBLES I,
        SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES C
        WHERE I.CODIGO_INM = RL.COD_INMUEBLE
        AND F.INMUEBLE = I.CODIGO_INM
        AND F.PERIODO = RL.PERIODO
        AND F.INMUEBLE = RL.COD_INMUEBLE
        AND I.CODIGO_INM = M.COD_INMUEBLE
        AND M.COD_INMUEBLE = F.INMUEBLE
        AND M.COD_INMUEBLE = RL.COD_INMUEBLE
        AND OL.CODIGO = RL.OBSERVACION
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND M.COD_CALIBRE = C.COD_CALIBRE
        AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta
        $uso $calibre
        AND RL.PERIODO BETWEEN $perini AND $perfin 
        AND OL.CALCULO IN ('D','P')
        AND M.FECHA_BAJA IS NULL
        ORDER BY RL.PERIODO DESC)
        PIVOT 
                (
                   SUM(CONSUMO_AGUA_ORI)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 DESC";
        //echo 'res = '.$sql;
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
?>