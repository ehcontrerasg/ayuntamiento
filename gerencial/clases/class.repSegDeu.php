<?php
include_once '../../clases/class.conexion.php';
class ReportesSegDeu extends ConexionClass{

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

    public function seguimientoDeudaSinMora($proyecto, $periodo){
        $sql = "SELECT INMUEBLE, VALOR
        FROM SGC_TT_DEUDA_COBRAR
        WHERE PROYECTO = '$proyecto'
        AND PERIODO = $periodo
        AND MORA = 'N'
        ORDER BY INMUEBLE";
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

    public function seguimientoDeudaConMora($proyecto, $periodo){
        $sql = "SELECT INMUEBLE, VALOR
        FROM SGC_TT_DEUDA_COBRAR
        WHERE PROYECTO = '$proyecto'
        AND PERIODO = $periodo
        AND MORA = 'S'
        ORDER BY INMUEBLE";
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