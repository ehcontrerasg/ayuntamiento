<?php
include_once '../../clases/class.conexion.php';
class ReportesSegCon extends ConexionClass{

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


    public function verificaCierreZonas($proyecto, $periodo){
        $sql = "SELECT COUNT(*)CANTIDAD
                FROM SGC_TP_PERIODO_ZONA PZ
                WHERE PZ.PERIODO = $periodo
                        AND PZ.FEC_CIERRE IS NULL
                        AND PZ.CODIGO_PROYECTO = '$proyecto'";

        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
    //CONSULTAS HISTORICO DE FACTURAS POR SECTOR

    public function seguimientoConciliacionF($proyecto, $ano){
        $per01 = $ano.'01';$per02 = $ano.'02';$per03 = $ano.'03';$per04 = $ano.'04';$per05 = $ano.'05';$per06 = $ano.'06';
        $per07 = $ano.'07';$per08 = $ano.'08';$per09 = $ano.'09';$per10 = $ano.'10';$per11 = $ano.'11';$per12 = $ano.'12';

        $sql = "SELECT * FROM (
            SELECT ORDEN, DESC_CONCILIA, PERIODO, VALOR
            FROM SGC_TT_CONCILIACIONES C, SGC_TP_CONCILIACIONES O
            WHERE C.DESC_CONCILIA = O.DESC_CON
            AND PERIODO BETWEEN $per01 AND $per12
              AND TIPO = 'F'
              AND PERIODO IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
              AND ID_PROYECTO = '$proyecto'
        )
            PIVOT(
            SUM (VALOR)
            FOR (PERIODO) IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
            )
        ORDER BY ORDEN";
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

    public function seguimientoConciliacionNC($proyecto, $ano){
        $per01 = $ano.'01';$per02 = $ano.'02';$per03 = $ano.'03';$per04 = $ano.'04';$per05 = $ano.'05';$per06 = $ano.'06';
        $per07 = $ano.'07';$per08 = $ano.'08';$per09 = $ano.'09';$per10 = $ano.'10';$per11 = $ano.'11';$per12 = $ano.'12';

        $sql = "SELECT * FROM (
            SELECT ORDEN, DESC_CONCILIA, PERIODO, VALOR
            FROM SGC_TT_CONCILIACIONES C, SGC_TP_CONCILIACIONES O
            WHERE C.DESC_CONCILIA = O.DESC_CON
            AND PERIODO BETWEEN $per01 AND $per12
              AND TIPO = 'C'
              AND PERIODO IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
              AND ID_PROYECTO = '$proyecto'
        )
            PIVOT(
            SUM (VALOR)
            FOR (PERIODO) IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
            )
        ORDER BY ORDEN";
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

    public function seguimientoConciliacionND($proyecto, $ano){
        $per01 = $ano.'01';$per02 = $ano.'02';$per03 = $ano.'03';$per04 = $ano.'04';$per05 = $ano.'05';$per06 = $ano.'06';
        $per07 = $ano.'07';$per08 = $ano.'08';$per09 = $ano.'09';$per10 = $ano.'10';$per11 = $ano.'11';$per12 = $ano.'12';

        $sql = "SELECT * FROM (
            SELECT ORDEN, DESC_CONCILIA, PERIODO, VALOR
            FROM SGC_TT_CONCILIACIONES C, SGC_TP_CONCILIACIONES O
            WHERE C.DESC_CONCILIA = O.DESC_CON
            AND PERIODO BETWEEN $per01 AND $per12
              AND TIPO = 'D'
              AND PERIODO IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
              AND ID_PROYECTO = '$proyecto'
        )
            PIVOT(
            SUM (VALOR)
            FOR (PERIODO) IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
            )
        ORDER BY ORDEN";
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

    public function seguimientoConciliacionR($proyecto, $ano){
        $per01 = $ano.'01';$per02 = $ano.'02';$per03 = $ano.'03';$per04 = $ano.'04';$per05 = $ano.'05';$per06 = $ano.'06';
        $per07 = $ano.'07';$per08 = $ano.'08';$per09 = $ano.'09';$per10 = $ano.'10';$per11 = $ano.'11';$per12 = $ano.'12';

        $sql = "SELECT * FROM (
            SELECT ORDEN, DESC_CONCILIA, PERIODO, VALOR
            FROM SGC_TT_CONCILIACIONES C, SGC_TP_CONCILIACIONES O
            WHERE C.DESC_CONCILIA = O.DESC_CON
            AND PERIODO BETWEEN $per01 AND $per12
              AND TIPO = 'R'
              AND PERIODO IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
              AND ID_PROYECTO = '$proyecto'
        )
            PIVOT(
            SUM (VALOR)
            FOR (PERIODO) IN ($per01,$per02,$per03,$per04,$per05,$per06,$per07,$per08,$per09,$per10,$per11,$per12)
            )
        ORDER BY ORDEN";
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
?>