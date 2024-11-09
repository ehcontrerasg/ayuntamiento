<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:14 PM
 */
include_once '../../clases/class.conexion.php';

class facturas extends ConexionClass {
    private $codresult;
    private $msgresult;

    public function getcodresult(){return $this->codresult;}
    public function getmsgresult(){return $this->msgresult;}
    public function __construct()
    {
        parent::__construct();

    }

    public function datosFacturaPdf($proyecto, $periodoInicial='',$periodoFinal='',$documento='',$grupo='',$zona=''){

        if ($grupo != '') {
            $where .= " AND C.GRUPO = '$grupo' ";
        }
        if ($zona != '') {
            $where .= " AND F.ID_ZONA = '$zona' ";
        }
        if($periodoInicial != '' && $periodoFinal != ''){
            $where .= " AND F.PERIODO >= '$periodoInicial' AND F.PERIODO <= '$periodoFinal'";
        }

        if($documento != ''){
            $where .= " AND F.DOCUMENTO_CLIENTE = '$documento'";
        }

        $sql = "
                SELECT TD.DESCRIPCION_TIPO_DOC TIPODOC,
                  TO_CHAR(f.VENCIMIENTO_NCF,'DD/MM/YYYY') VENCE_NCF,
                  i.ID_PROYECTO,
                  F.MSJ_NCF,
                  I.ID_TIPO_CLIENTE,
                  I.CODIGO_INM,
                  CONCAT(N.ID_NCF,F.NCF_CONSEC) NCF,
                  I.CATASTRO,
                  F.NOMBRE_CLIENTE,
                  I.DIRECCION,
                  U.DESC_URBANIZACION,
                  F.ID_ZONA,
                  TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FECEXP,
                  F.PERIODO,
                  I.ID_PROCESO,
                  (SELECT
                     G.DESC_GERENCIA
                   FROM SGC_TT_GERENCIA_ZONA Z, SGC_TP_GERENCIAS G
                    WHERE G.ID_GERENCIA = Z.ID_GERENCIA AND Z.ID_ZONA = I.ID_ZONA) GERENCIA,
                  E.DESC_MED,
                  M.COD_CALIBRE,
                  M.SERIAL,
                  TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VCTO,
                  TO_CHAR(FECHA_CORTE,'DD/MM/YYYY')FECCORTE,
                  F.CONSEC_FACTURA,
                    CASE F.DOCUMENTO_CLIENTE
                      WHEN '9999999' THEN ''
                      WHEN F.DOCUMENTO_CLIENTE THEN F.DOCUMENTO_CLIENTE
                    END AS RNC, I.ID_ESTADO,
                
                  (SELECT COUNT(FA.FACTURA_PAGADA)
                   FROM SGC_TT_FACTURA FA
                   WHERE FA.INMUEBLE = F.INMUEBLE AND
                         FA.FACTURA_PAGADA = 'N'  AND
                         FA.PERIODO <> F.PERIODO) FACPEND,
                  (SELECT COUNT(FA.FACTURA_PAGADA)
                   FROM SGC_TT_FACTURA FA
                   WHERE FA.INMUEBLE = F.INMUEBLE AND
                         FA.PERIODO <> F.PERIODO) FACGEN,
                  (SELECT SUM(D.VALOR)
                   FROM SGC_TT_DETALLE_FACTURA D
                   WHERE D.COD_INMUEBLE = F.INMUEBLE AND
                         D.PAGADO = 'N' AND
                         D.CONCEPTO = 10  AND
                         PERIODO <> F.PERIODO) MORA,
                
                  (SELECT SUM(FA.TOTAL)
                   FROM SGC_TT_FACTURA FA
                   WHERE FA.INMUEBLE = F.INMUEBLE AND 
                         FA.FACTURA_PAGADA = 'N'  AND 
                         FA.PERIODO <> F.PERIODO) DEUDA
                
                  FROM 
                    SGC_TT_FACTURA F,
                    SGC_TT_INMUEBLES I,
                    SGC_TP_NCF_USOS N, 
                    SGC_TT_CONTRATOS C,  
                    SGC_TP_URBANIZACIONES U, 
                    SGC_TT_MEDIDOR_INMUEBLE M, 
                    SGC_TP_MEDIDORES E,
                    SGC_TP_TIPODOC TD
                  
                  WHERE I.CODIGO_INM(+) = F.INMUEBLE
                      AND C.CODIGO_INM(+) = F.INMUEBLE
                      AND M.COD_INMUEBLE(+) = I.CODIGO_INM
                      AND F.NCF_ID = N.ID_NCF_USO
                      AND U.CONSEC_URB(+) = I.CONSEC_URB
                      AND C.FECHA_FIN(+) IS NULL
                      AND M.COD_MEDIDOR = E.CODIGO_MED(+)
                      AND M.FECHA_BAJA (+)IS NULL
                      AND TD.ID_TIPO_DOC = F.TIPO_DOCUMENTO
                      AND I.ID_PROYECTO = '$proyecto'
                $where 
                ORDER BY I.ID_PROCESO
                "
        ;

        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        //echo $banderas;
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

    public function datosServiciosPdf($codinm){
        $sql = "SELECT C.DESC_SERVICIO, U.DESC_USO, T.CODIGO_TARIFA, S.UNIDADES_TOT, T.CONSEC_TARIFA
		FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U
		WHERE C.COD_SERVICIO  = S.COD_SERVICIO 
		AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
		AND T.COD_USO = U.ID_USO
		AND S.COD_INMUEBLE = '$codinm'
		AND S.ACTIVO = 'S'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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

    /////// datos de lectura
    public function datosLecturaPdf($codinm, $periodo){
        $sql = "SELECT LECTURA_ACTUAL, TO_CHAR(FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC  FROM SGC_TT_REGISTRO_LECTURAS R
		WHERE COD_INMUEBLE = '$codinm'
		AND PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM') 
		AND PERIODO <= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),+0),'YYYYMM') ";

        $resultado = oci_parse(
            $this->_db,$sql
        );


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

    ////////////////////// detalle factura/////////////////////////////////////////
    public function detalleFacturaPdf ($factura)
    {
        $sql="SELECT S.DESC_SERVICIO CONCEPTO,RANGO,UNIDADES,VALOR,S.COD_SERVICIO
		FROM SGC_TT_DETALLE_FACTURA DF , SGC_TP_SERVICIOS S 
		WHERE  DF.CONCEPTO=S.COD_SERVICIO
		AND FACTURA='$factura'
		ORDER BY CONCEPTO, RANGO ASC";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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

    ////////////////////// VALOR RANGOS/////////////////////////////////////////
    public function valorRangosPdf ($codservicio,$rango,$codinm)
    {
        $sql="SELECT R.VALOR_METRO
        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U, SGC_TP_RANGOS_TARIFAS R
        WHERE C.COD_SERVICIO  = S.COD_SERVICIO 
        AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND R.CONSEC_TARIFA = S.CONSEC_TARIFA
        AND T.COD_USO = U.ID_USO
        AND S.COD_INMUEBLE = '$codinm'
        AND C.COD_SERVICIO = $codservicio
        AND R.RANGO IN (0,$rango)
        AND S.ACTIVO = 'S'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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