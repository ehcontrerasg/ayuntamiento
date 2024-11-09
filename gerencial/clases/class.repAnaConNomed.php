<?php
include_once 'class.conexion.php';
class ReportesAnaConNomed extends ConexionClass{

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

    public function NoMedidosPromedio($proyecto, $sector, $ruta, $cliente, $diametro, $perini, $perfin, $arrayperiodo){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($cliente <> '') $uso = " AND A.ID_USO = '$cliente' ";
        if($diametro <> '') $calibre = " AND C.COD_CALIBRE = $diametro ";

        $sql = "SELECT * FROM(SELECT I.CODIGO_INM, A.ID_USO, C.DESC_CALIBRE, F.PERIODO, F.CONSUMO_AGUA_ORI 
        FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES C
        WHERE F.INMUEBLE = I.CODIGO_INM
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
        AND I.COD_DIAMETRO = C.COD_CALIBRE
        AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta
        $uso $calibre
        AND F.PERIODO BETWEEN $perini AND $perfin 
        AND I.FACTURAR = 'P'
        ORDER BY F.PERIODO DESC)
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