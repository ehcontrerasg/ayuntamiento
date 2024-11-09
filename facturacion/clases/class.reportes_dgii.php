<?php

include_once '../../clases/class.conexion.php';

class Reportes_Dgii extends ConexionClass{



    public function __construct()
    {
        parent::__construct();

    }


    public function obtieneRegistros($proyecto, $periodo, $cabecera, $formato,$agno,$mes,$dia,$periodoFin)
    {
       if($proyecto == 'SD'){
           if($cabecera == 'B01'){
              $ncfs = 'AND NCF_ID IN (21,23,25,27)';
           }
           else if($cabecera == 'B02'){
               $ncfs = 'AND NCF_ID IN (22,24,28,29)';
           }
           else if($cabecera == 'B14'){
               $ncfs = 'AND NCF_ID IN (30,47)';
           }
           else if($cabecera == 'B15'){
               $ncfs = 'AND NCF_ID IN (26)';
           }
           else if($cabecera == 'B04'){
               $ncfs = 'AND NCF_ID IN (42)';
           }
           else if($cabecera == 'B03'){
               $ncfs = 'AND NCF_ID IN (44)';
           }
           else{
               $ncfs = 'AND NCF_ID NOT IN 45';
           }
       }

        if($proyecto == 'BC'){
            if($cabecera == 'B01'){
                $ncfs = 'AND NCF_ID IN (31,33,35,37)';
            }
            else if($cabecera == 'B02'){
                $ncfs = 'AND NCF_ID IN (32,34,38,39)';
            }
            else if($cabecera == 'B14'){
                $ncfs = 'AND NCF_ID IN (40, 48)';
            }
            else if($cabecera == 'B15'){
                $ncfs = 'AND NCF_ID IN (36)';
            }
            else if($cabecera == 'B04'){
                $ncfs = 'AND NCF_ID IN (41)';
            }
            else if($cabecera == 'B03'){
                $ncfs = 'AND NCF_ID IN (43)';
            }
            else{
                $ncfs = 'AND NCF_ID NOT IN 46';;
            }
        }

        if($formato == 1){
            $sql="SELECT I.ID_PROCESO, I.CODIGO_INM, TRIM(REPLACE(REPLACE(F.DOCUMENTO_CLIENTE,'-',''),'9999999','')) DOCUMENTO, F.CONSEC_FACTURA,
            TO_CHAR(F.FEC_EXPEDICION, 'YYYYMMDD')FECHA_COMPROBANTE, CONCAT(U.ID_NCF,F.NCF_CONSEC)NCF, REGEXP_REPLACE(F.NOMBRE_CLIENTE, '[^A-Za-z0-9ÁÉÍÓÚáéíóú ]', '') AS ALIAS,
            (SELECT LPAD(SUM(DF.VALOR_ORI),14,'0')||'.00' FROM SGC_TT_DETALLE_FACTURA DF WHERE F.CONSEC_FACTURA = DF.FACTURA AND DF.CONCEPTO NOT IN (200,401,402,403,404,410,411,412,413,420,421,424,425,427,428,430,450,452,453,494,499,501,502,503,528,593,594,595)) TOTAL,
            '0000000000000.00' ITBIS,DECODE(F.TIPO_DOCUMENTO,'N','1','C','2','99','2','P','3')TIPODOC
            FROM  SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_NCF_USOS U
            WHERE 
             F.NCF_ID = U.ID_NCF_USO AND
             F.INMUEBLE=I.CODIGO_INM
            AND I.TOTALIZADOR NOT IN ('S')
            AND F.PERIODO = $periodo
            --AND TO_CHAR(F.FEC_EXPEDICION,'YYYYMM')>=$periodo 
            --AND TO_CHAR(F.FEC_EXPEDICION,'YYYYMM')<$periodoFin+1  
            AND I.ID_PROYECTO = '$proyecto' $ncfs
            ORDER BY CONCAT(U.ID_NCF,F.NCF_CONSEC) ASC";
        }

        else if($formato == 2){
            $sql="SELECT I.ID_PROCESO, I.CODIGO_INM, TRIM(REPLACE(F.DOCUMENTO_CLIENTE,'-','')) DOCUMENTO, F.CONSEC_FACTURA,
            TO_CHAR(F.FEC_EXPEDICION, 'YYYYMMDD')FECHA_COMPROBANTE, CONCAT(U.ID_NCF,F.NCF_CONSEC)NCF, REGEXP_REPLACE(F.NOMBRE_CLIENTE, '[^A-Za-z0-9ÁÉÍÓÚáéíóú ]', '') AS ALIAS,
            (SELECT LPAD(SUM(DF.VALOR_ORI),14,'0')||'.00' FROM SGC_TT_DETALLE_FACTURA DF WHERE F.CONSEC_FACTURA = DF.FACTURA AND DF.CONCEPTO NOT IN (200,401,402,403,404,410,411,412,413,420,421,424,425,427,428,430,450,452,453,494,499,501,502,503,528,593,594,595)) TOTAL,
            DECODE(F.TIPO_DOCUMENTO,'N','1','C','2','99','2','P','3')TIPODOC
            FROM  SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_NCF_USOS U
            WHERE F.INMUEBLE = I.CODIGO_INM
            AND F.NCF_ID = U.ID_NCF_USO
            AND I.TOTALIZADOR NOT IN ('S')
            AND F.PERIODO = $periodo
             --AND TO_CHAR(F.FEC_EXPEDICION,'YYYYMM')>=$periodo 
            --AND TO_CHAR(F.FEC_EXPEDICION,'YYYYMM')<$periodoFin+1  
            AND I.ID_PROYECTO = '$proyecto' $ncfs
            ORDER BY CONCAT(U.ID_NCF,F.NCF_CONSEC) ASC";
        }

       // echo $sql;
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


    public function obtieneRecibos($proyecto, $periodo)
    {
        $sql="SELECT I.ID_PROCESO, I.CODIGO_INM, P.ID_PAGO, TO_CHAR(P.FECHA_PAGO,'YYYYMMDD')FECRECIBO, LPAD(F.IMPORTE,14,'0')||'.00' IMPORTE, F.FACTURA, REGEXP_REPLACE(O.ALIAS, '[^A-Za-z0-9ÁÉÍÓÚáéíóú ]', '') ALIAS
        FROM SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TT_CLIENTES C, SGC_TT_CONTRATOS O, SGC_TT_PAGO_FACTURAS F
        WHERE I.CODIGO_INM = O.CODIGO_INM
        AND C.CODIGO_CLI = O.CODIGO_CLI
        AND I.CODIGO_INM = P.INM_CODIGO
        AND P.ID_PAGO = F.ID_PAGO
        AND SUBSTR(P.FECIND,0,6) = $periodo
        AND O.FECHA_FIN IS NULL
        AND P.ID_CAJA NOT IN (463,391)
        AND P.ESTADO NOT IN 'I'
        AND I.ID_PROYECTO = '$proyecto'
        ORDER BY P.ID_PAGO";

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


    public function obtieneNotas($proyecto, $periodo,$periodoFin)
    {
        $sql="SELECT I.ID_PROCESO, I.CODIGO_INM, N.DOCUMENTO, N.ID_NOTA, TO_CHAR(N.FECHA_EMISION,'YYYYMMDD')FECNOTA, CONCAT(UN.ID_NCF,N.NCF_CONSEC)NCF, LPAD(N.TOTAL_NOTA,14,'0')||'.00' VALOR, N.FACTURA_APLICA, CONCAT(U.ID_NCF,F.NCF_CONSEC)NCF_MOD, trim(N.NOMBRE_CLIENTE) NOMBRE_CLIENTE
         ,DECODE(N.TIPO_DOC,'N','1','C','2','99','2','P','3') TIPO_DOC
        FROM SGC_TT_INMUEBLES I, SGC_TT_NOTAS_FACTURAS N, SGC_TT_FACTURA F, SGC_TP_NCF_USOS U, SGC_TP_NCF_USOS UN
        WHERE I.CODIGO_INM = F.INMUEBLE
        AND N.FACTURA_APLICA = F.CONSEC_FACTURA
        AND F.NCF_ID = U.ID_NCF_USO
        AND N.ID_NCF = UN.ID_NCF_USO
        AND TO_CHAR(N.FECHA_EMISION,'YYYYMM')+0 >= $periodo+0
        AND TO_CHAR(N.FECHA_EMISION,'YYYYMM')+0 < $periodoFin+1
        AND I.ID_PROYECTO = '$proyecto'
        AND N.FECHA_ANULACION IS NULL
        and N.ANULADA='N'    
        AND (CONCAT(UN.ID_NCF,N.NCF_CONSEC) NOT LIKE '%B99%' AND CONCAT(UN.ID_NCF,N.NCF_CONSEC) NOT LIKE '%B98%')
        ORDER BY N.ID_NOTA";

//        echo $sql;
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


    public function obtieneTotal($numfac)
    {
        $sql="SELECT SUM(DF.VALOR) SUMADETALLE
        FROM SGC_TT_DETALLE_FACTURA DF
        WHERE DF.FACTURA = $numfac
        AND DF.CONCEPTO IN (1,2,3,4,11,50)
        ";
        /*$sql="SELECT SUM(DF.VALOR) SUMADETALLE
        FROM SGC_TT_DETALLE_FACTURA DF
        WHERE DF.FACTURA = $numfac
        AND DF.CONCEPTO IN (1,2,3,4,11,50,20)
        ";*/
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



    public function obtienePago($numfac)
    {
        $sql="SELECT DISTINCT PAGO
        FROM SGC_TT_PAGO_DETALLEFAC PD
        WHERE FACTURA = $numfac
        ";

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

    public function obtieneMediosPago($numpago)
    {
        $sql="SELECT ID_FORM_PAGO, VALOR
        FROM SGC_TT_MEDIOS_PAGO MP
        WHERE ID_PAGO = $numpago
        ";

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