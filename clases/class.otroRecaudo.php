<?php
include_once "class.conexion.php";


class OtroRecaudo extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getOtrosRecByInmAsc ($codinm)
    {
        $sql="SELECT TO_CHAR(ORE.FECHA,'DD/MM/YYYY HH24:MI')FECHA, TO_CHAR(ORE.FECHA,'YYYYMMDD') FECHCOMP , to_char(ORE.FECHA,'DD-MM-YYYY') FECHCOMP2  ,'pago por concepto: '||S.DESC_SERVICIO DESCRIPCION ,ORE.IMPORTE FROM SGC_TT_OTROS_RECAUDOS ORE, sgc_tp_servicios s
                WHERE ORE.INMUEBLE=$codinm
                and S.COD_SERVICIO=ORE.CONCEPTO
                and S.COD_SERVICIO=ORE.CONCEPTO
				--AND ORE.FECHA_PAGO > TO_DATE('31/12/2010','DD/MM/YYYY')
                ORDER BY ORE.FECHA ASC";
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

    public function getOtrosRecByInmFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT ORE.CODIGO,TO_CHAR(ORE.FECHA,'DD/MM/YYYY HH24:MI') FECHADIG,ORE.FECHA_PAGO FECHAPAG, S.DESC_SERVICIO , ORE.IMPORTE,U.LOGIN,REV.FECHA_REV, (SELECT U2.LOGIN FROM
                        SGC_TT_USUARIOS U2 WHERE U2.ID_USUARIO=REV.USR_REV) USRREV FROM SGC_TT_OTROS_RECAUDOS ORE, SGC_TP_SERVICIOS S, SGC_TT_USUARIOS U,
                        SGC_TT_REV_RECAUDO REV
                        WHERE
                        S.COD_SERVICIO=ORE.CONCEPTO
                        AND U.ID_USUARIO=ORE.USUARIO
                        AND REV.ID_RECAUDO(+)=ORE.CODIGO
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
    public function getAplOreByOreFlexy ($where,$sort,$start,$end)
    {
         $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT
                          F.PERIODO,AORE.FACTURA,SUM(AORE.IMPORTE) IMPORTE
                        FROM
                          SGC_TT_APLICA_OTROSREC AORE, SGC_TT_FACTURA F
                        WHERE
                          AORE.FACTURA=F.CONSEC_FACTURA
                        $where
                        GROUP BY F.PERIODO,AORE.FACTURA
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

    public function getForOtrRecByOre ($codpago)
    {
        $sql="SELECT FP.DESCRIPCION  FROM  SGC_TT_MEDIOS_recaudo MP, SGC_TP_FORMA_PAGO FP
              WHERE MP.id_otrrec =$codpago
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



    public function getUbiOtrRecByOre ($codpago)
    {
        $sql="SELECT CP.DESCRIPCION DESC_CAJA,PP.DESCRIPCION DESC_PUNTO , EP.DESC_ENTIDAD FROM  sgc_tt_otros_recaudos p, sgc_tp_cajas_pago cp, sgc_tp_punto_pago pp, sgc_tp_entidad_pago ep
                WHERE  P.caja=cp.id_caja
                and PP.ID_PUNTO_PAGO=CP.ID_PUNTO_PAGO
                and EP.COD_ENTIDAD=PP.ENTIDAD_COD
                and P.codigo='$codpago'";
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




}
