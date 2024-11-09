<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 11/2/2015
 * Time: 3:47 PM
 */
include_once '../../clases/class.conexion.php';
class Pago extends ConexionClass {
    public function __construct()
    {
        parent::__construct();
    }

    ////////////////// facturas pendientes ///////////////////////

    public function pagTotal ($where,$sort,$start,$end)
    {
       $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT P.ID_PAGO CODIGO, P.FECHA_PAGO , REPLACE(REPLACE(REPLACE(P.REFERENCIA,CHR(10),' ') ,CHR(13),' ') ,'  ',' ')  REFERENCIA , P.IMPORTE,
						 REPLACE(REPLACE(R.MOTIVO_REV,CHR(10),' '),CHR(13),' ') MOTIVO_REV, R.FECHA_REV, R.USR_REV  
						FROM SGC_TT_PAGOS P, SGC_TT_REV_PAGO R
                        WHERE P.ID_PAGO = R.ID_PAGO(+)
                        $where
                        $sort
                   )where  rownum<1000
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }



    public function formpago ($codpago)
    {
        $sql="SELECT FP.DESCRIPCION  FROM  SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP
              WHERE MP.ID_PAGO=$codpago
              AND  FP.CODIGO=MP.ID_FORM_PAGO";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function lugPago ($codpago)
    {
        $sql="SELECT CP.DESCRIPCION DESC_CAJA,PP.DESCRIPCION DESC_PUNTO , EP.DESC_ENTIDAD FROM  sgc_tt_pagos p, sgc_tp_cajas_pago cp, sgc_tp_punto_pago pp, sgc_tp_entidad_pago ep
                WHERE  P.ID_CAJA=cp.id_caja
                and PP.ID_PUNTO_PAGO=CP.ID_PUNTO_PAGO
                and EP.COD_ENTIDAD=PP.ENTIDAD_COD
                and P.ID_PAGO=$codpago";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }



    public function pagApliAfacTotal ($where,$sort,$start,$end)
    {
       $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT F.PERIODO,F.CONSEC_FACTURA,F.TOTAL,PF.IMPORTE FROM SGC_TT_PAGO_FACTURAS PF, SGC_TT_FACTURA F
                        WHERE
                        F.CONSEC_FACTURA=PF.FACTURA

                        $where
                        $sort
                   )where  rownum<1000
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }




    public function pagApliADifTotal ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT  DD.CONCEPTO_DIF,DD.CODIGO,P.FECHA_PAGO, DF.PAGADO
                            FROM SGC_TT_PAGO_DETALLEFAC DF, SGC_TT_PAGOS P ,SGC_TT_DIFERIDOS DD

                            WHERE
                            P.ID_PAGO=DF.PAGO
                            AND DD.INMUEBLE=P.INM_CODIGO
                            AND DF.CONCEPTO=DD.CONCEPTO


                        $where
                        $sort
                   )where  rownum<1000
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    ///////////////////////////////////////todos los pagos (para estado de cuenta)/////

    public function todosPagos ($codinm)
    {
        $sql="SELECT TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY')FECHA_PAGO,to_char(P.FECHA_PAGO,'YYYYMMDD')  FECHCOMP , to_char(P.FECHA_PAGO,'DD-MM-YYYY') FECHCOMP2  , 'pago '||P.ID_PAGO||' '||FP.DESCRIPCION||' '||EP.DESC_ENTIDAD ||' '|| PP.DESCRIPCION ||' '||CP.DESCRIPCION DESCRIPCION,P.IMPORTE  FROM SGC_TT_PAGOS P,SGC_TT_MEDIOS_PAGO MP, SGC_TP_FORMA_PAGO FP,SGC_TP_CAJAS_PAGO CP,
                SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP
                WHERE P.INM_CODIGO=$codinm
                AND MP.ID_PAGO(+)=P.ID_PAGO
                AND FP.CODIGO(+)=MP.ID_FORM_PAGO
                AND P.ESTADO<>'I'
                AND CP.ID_CAJA(+)=P.ID_CAJA
                AND PP.ID_PUNTO_PAGO(+)=CP.ID_PUNTO_PAGO
                AND EP.COD_ENTIDAD(+)=PP.ENTIDAD_COD
				--AND P.FECHA_PAGO > TO_DATE('31/12/2010','DD/MM/YYYY')
                ORDER BY P.FECHA_PAGO ASC";
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


    public function todosSaldos ($codinm)
    {
        $sql="SELECT
            TO_CHAR(SF.FECHA,'DD/MM/YYYY') FECHA,
            to_char(SF.FECHA,'YYYYMMDD')  FECHCOMP,
            to_char(SF.FECHA,'DD-MM-YYYY') FECHCOMP2,
            SF.MOTIVO DESCRIPCION,
            SF.IMPORTE
             FROM SGC_TT_SALDO_FAVOR SF
             WHERE SF.INM_CODIGO='$codinm' AND 
             SF.ESTADO<>'I'
             ORDER BY SF.FECHA ASC";
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