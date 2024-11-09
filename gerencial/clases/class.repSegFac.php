<?php
include_once '../../clases/class.conexion.php';
class ReportesSegFac extends ConexionClass{

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

    public function seguimientoFacturasClienteConcepto($proyecto, $periodo){
        $sql = "SELECT * FROM (
                  SELECT I.CODIGO_INM, CONCEPTO, VALOR_ORI
                  FROM SGC_TT_DETALLE_FACTURA DF,
                       SGC_TT_INMUEBLES I
                  WHERE DF.COD_INMUEBLE = I.CODIGO_INM
                    AND FECHA BETWEEN TO_DATE('2022-04-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
                    AND TO_DATE('2022-04-03 23:59:59','YYYY-MM-DD HH24:MI:SS')
                    --AND PERIODO = $periodo
                    AND DF.CONCEPTO IN (21,1,3,2,4,50,20,93,22,11,10,101,28,128,27,30,421,401,403,424,404,402,502,593,450,594,528,428,494,503,425,411,427,410,499,453,595,452,555,501,430,420,413,412)
                    AND I.ID_PROYECTO = '$proyecto'
              )
PIVOT(
    SUM (VALOR_ORI)
    FOR (CONCEPTO) IN (21,1,3,2,4,50,20,93,22,11,10,101,28,128,27,30,421,401,403,424,404,402,502,593,450,594,528,428,494,503,425,411,427,410,499,453,595,452,555,501,430,420,413,412)
        )
ORDER BY 1 DESC";
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

    public function seguimientoFacturasConcepto($proyecto, $periodo){
        $sql = "SELECT  S.COD_CONT CONCEPTO,  SUM(DF.VALOR_ORI) FACTURADO
FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SERVICIOS S
WHERE I.CODIGO_INM = F.INMUEBLE
AND F.CONSEC_FACTURA = DF.FACTURA
AND S.COD_SERVICIO = DF.CONCEPTO
AND F.PERIODO = $periodo
AND I.ID_PROYECTO = '$proyecto'
GROUP BY S.COD_CONT
ORDER BY S.COD_CONT";
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