<?php
include_once 'class.conexion.php';
class ReportesFacRecSecRut extends ConexionClass{

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

    public function facturadoRuta($proyecto, $sector, $rutaini, $rutafin, $uso, $periodo){

        if($uso <> '') $sqluso = " AND A.ID_USO = '$uso' ";

        $sql = "SELECT  I.ID_RUTA, SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA 
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        AND I.ID_RUTA BETWEEN $rutaini AND $rutafin
        $sqluso
        AND F.PERIODO = $periodo
		AND DF.CONCEPTO IN (1,2,3,4)
		GROUP BY I.ID_RUTA
	    ORDER BY I.ID_RUTA";
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

    public function recaudadoRuta($proyecto, $sector, $ruta, $uso, $ano, $mes, $dia){

        if($uso <> '') $sqluso = " AND A.ID_USO = '$uso' ";

        $sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+) 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND I.ID_SECTOR = $sector
		$sqluso
        AND I.ID_RUTA = $ruta
		AND PD.CONCEPTO (+) IN (1,2,3,4)";
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


    public function facturasRuta($proyecto, $sector, $rutaini, $rutafin, $uso, $periodo){

        if($uso <> '') $sqluso = " AND A.ID_USO = '$uso' ";

        $sql = "SELECT  I.ID_RUTA, COUNT(F.CONSEC_FACTURA)FACTURAS
		FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        AND I.ID_RUTA BETWEEN $rutaini AND $rutafin
        $sqluso
        AND F.PERIODO = $periodo
		GROUP BY I.ID_RUTA
	    ORDER BY I.ID_RUTA";
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

    public function pagosRuta($proyecto, $sector, $ruta, $uso, $ano, $mes, $dia){

        if($uso <> '') $sqluso = " AND A.ID_USO = '$uso' ";

        $sql = "SELECT NVL(COUNT(P.ID_PAGO),0)PAGOS
		FROM SGC_TT_PAGOS P,  SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE  I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND I.ID_SECTOR = $sector
		$sqluso
        AND I.ID_RUTA = $ruta";
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