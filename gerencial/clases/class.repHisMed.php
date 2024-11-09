<?php
include_once 'class.conexion.php';
class ReportesHisMed extends ConexionClass{

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

    public function InmueblesMedidores($proyecto, $sector, $ruta, $inmueble){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($inmueble <> '') $sqlinmueble = " AND I.CODIGO_INM = $inmueble ";

        $sql = "SELECT I.CODIGO_INM
        FROM SGC_TT_INMUEBLES I
        WHERE I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta
        AND I.ID_ESTADO NOT IN ('CC','CB','CT','CK')
        $sqlinmueble
        ORDER BY CODIGO_INM";
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

    public function historicoMedidores( $inmueble, $perini, $perfin, $arrayperiodo){


        $sql = "SELECT *
        FROM (SELECT F.PERIODO, F.CONSUMO_AGUA_ORI
        FROM SGC_TT_FACTURA F
        WHERE F.INMUEBLE = $inmueble
        AND F.PERIODO BETWEEN $perini AND $perfin
        ORDER BY PERIODO DESC)
         PIVOT 
                (
                   SUM(CONSUMO_AGUA_ORI)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 ASC";
       // echo 'res = '.$sql;
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