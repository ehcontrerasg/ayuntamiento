<?php
include_once 'class.conexion.php';
class ReportesCobSec extends ConexionClass{

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

    public function facturadoSector($proyecto, $perini, $perfin, $arrayperiodo){

        $sql = "SELECT * FROM(
        SELECT  I.ID_SECTOR, F.PERIODO, SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA 
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_PROYECTO = '$proyecto'
        AND F.PERIODO BETWEEN $perini AND $perfin
		AND DF.CONCEPTO IN (1,2,3,4)
		GROUP BY I.ID_SECTOR, F.PERIODO
	    ORDER BY I.ID_SECTOR, F.PERIODO
	    )
	    PIVOT 
                (
                   SUM(FACTURADO)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 ASC";
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

    public function recaudadoSector($proyecto, $sector, $perini, $perfin, $arrayperiodo){

        $sql = "SELECT * FROM(
        SELECT I.ID_SECTOR, SUBSTR(P.FECIND,0,6)PERIODO, NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+) 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SUBSTR(P.FECIND,0,6) BETWEEN $perini AND $perfin
		AND P.ESTADO NOT IN 'I'
		AND I.ID_SECTOR = $sector
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND PD.CONCEPTO (+) IN (1,2,3,4)
        GROUP BY I.ID_SECTOR, SUBSTR(P.FECIND,0,6)
	    ORDER BY I.ID_SECTOR, SUBSTR(P.FECIND,0,6)
	     )
	    PIVOT 
                (
                   SUM(RECAUDADO)
                   FOR (PERIODO) IN ('$arrayperiodo')
                )
                ORDER BY 1 ASC";
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