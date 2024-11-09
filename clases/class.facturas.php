<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:14 PM
 */
include_once  ('class.conexion.php');

class facturas extends ConexionClass {

    private $codresult;
    private $msgresult;

    public function getcodresult(){return $this->codresult;}
    public function getmsgresult(){return $this->msgresult;}

    public function getMesrror(){return $this->mesrror;}

    public function getCoderror(){return $this->coderror;}

    public function __construct()
    {
        parent::__construct();

    }



    public function ConsecFact ($inmueble,$periodo)
    {
        $sql="Select f.CONSEC_FACTURA from SGC_TT_FACTURA F
                    where F.INMUEBLE=$inmueble and 
                          f.PERIODO=$periodo";

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

	public function countRec($fname, $tname, $where){
		 $sql="SELECT count($fname) CANTIDAD
		FROM $tname F, sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
						and F.NCF_ID=NU.ID_NCF_USO
						AND F.FEC_EXPEDICION IS NOT NULL
                        AND F.FECHA_PAGO IS NULL
						$where";
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
////////////////// facturas pendientes ///////////////////////

    public function facpend ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT F.CONSEC_FACTURA, (F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) TOTAL , F.PERIODO, F.FEC_EXPEDICION,concat(NU.ID_NCF,F.NCF_CONSEC) ncf FROM SGC_TT_FACTURA_ASEO F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
						and F.NCF_ID=NU.ID_NCF_USO
						AND F.FEC_EXPEDICION IS NOT NULL
                        AND F.FECHA_PAGO IS NULL
						$where
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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
	

////////////////// facturas pendientes deuda ///////////////////////

    public function facpenddeuda ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT F.CONSEC_FACTURA, (F.TOTAL - F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) TOTAL, F.PERIODO, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FEC_EXPEDICION, TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY') FEC_VCTO
						FROM SGC_TT_FACTURA_ASEO F,sgc_tp_ncf_usos_ASEO nu WHERE F.FACTURA_PAGADA='N'
						and F.NCF_ID=NU.ID_NCF_USO
						AND F.FEC_EXPEDICION IS NOT NULL
                        AND F.FECHA_PAGO IS NULL
						$where
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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


    /////////////VALOR DEUDA DE UN INMUEBLE////////
    public function valorDeuda ($codinm)
    {
        $sql="SELECT SUM(F.TOTAL - F.TOTAL_PAGADO + F.TOTAL_DEBITO - F.TOTAL_CREDITO) DEUDA
                FROM SGC_TT_FACTURA F
                WHERE F.FACTURA_PAGADA = 'N'
                AND F.INMUEBLE = '$codinm'";
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

    /////////////CATEGORIA DE UN INMUEBLE////////
    public function categoriaInmueble ($codinm)
    {
        $sql="SELECT T.CATEGORIA
                FROM SGC_TP_TARIFAS T, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I
                WHERE I.CODIGO_INM = SI.COD_INMUEBLE
                AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
                AND SI.COD_INMUEBLE = '$codinm'
                AND SI.COD_SERVICIO IN (1,3)";
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

////////////////////// detalle factura/////////////////////////////////////////
    public function detfac ($where,$sort,$start,$end,$factura)
    {

         $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select S.DESC_SERVICIO CONCEPTO,VALOR + TOTAL_DEBITO - TOTAL_CREDITO - VALOR_PAGADO VALOR,S.COD_SERVICIO
						from SGC_TT_DETALLE_FACTURA_aSEO DF , SGC_TP_SERVICIOS S 
						WHERE  DF.CONCEPTO=S.COD_SERVICIO  AND FACTURA='$factura'
						$where
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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



///////////////////////////////////////////// deuda estado por concepto//////////////////////////



    public function estcon ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (SELECT S.DESC_SERVICIO CONCEPTO, SUM(NVL(DF.VALOR-DF.VALOR_PAGADO+DF.TOTAL_DEBITO-DF.TOTAL_CREDITO,0)) VALOR, count(S.DESC_SERVICIO) NUMFAC FROM SGC_TT_FACTURA_ASEO F, SGC_TT_DETALLE_FACTURA_ASEO DF, SGC_TP_SERVICIOS S
                where
                 F.FACTURA_PAGADA='N'
                AND F.PERIODO=DF.PERIODO
                AND F.INMUEBLE=DF.COD_INMUEBLE
                AND S.COD_SERVICIO= DF.CONCEPTO
				AND F.FEC_EXPEDICION IS NOT NULL
                $where
                GROUP BY S.DESC_SERVICIO
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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


    public function estcon2 ($perini, $perfin, $inmueble)
    {
        $sql="
                 SELECT CONCEPTO,SUM(VALOR) VALOR,  COUNT(1) NUMFAC,  COD_SERVICIO FROM(
SELECT
SER.DESC_SERVICIO CONCEPTO,

(DF.VALOR-DF.VALOR_PAGADO+DF.TOTAL_DEBITO-DF.TOTAL_CREDITO
)+
NVL((SELECT DF2.VALOR  FROM SGC_TT_DETALLE_FACTURA_ASEO DF2, SGC_TP_SERVICIOS SER2  WHERE DF2.CONCEPTO=SER2.COD_SERVICIO
AND SER2.APLICA_SF=DF.CONCEPTO AND DF2.FACTURA=DF.FACTURA),0)+
NVL((SELECT DF2.VALOR  FROM SGC_TT_DETALLE_FACTURA_ASEO DF2, SGC_TP_SERVICIOS SER2  WHERE DF2.CONCEPTO=SER2.COD_SERVICIO
AND SER2.AJUSTA=DF.CONCEPTO AND DF2.FACTURA=DF.FACTURA),0)
VALOR,
SER.COD_SERVICIO COD_SERVICIO

 FROM SGC_TT_FACTURA_ASEO F, SGC_TT_DETALLE_FACTURA_ASEO DF, SGC_TP_SERVICIOS SER
 WHERE F.CONSEC_FACTURA=DF.FACTURA AND
 SER.COD_SERVICIO=DF.CONCEPTO
 AND F.FEC_EXPEDICION IS NOT NULL
 AND F.FACTURA_PAGADA='N'
 AND F.INMUEBLE=$inmueble
 AND F.PERIODO>=$perini
 AND F.PERIODO<=$perfin
 AND SER.APLICA_SF IS NULL
 AND SER.AJUSTA IS NULL
 AND
(DF.VALOR-DF.VALOR_PAGADO+DF.TOTAL_DEBITO-DF.TOTAL_CREDITO
)+
NVL((SELECT DF2.VALOR  FROM SGC_TT_DETALLE_FACTURA_ASEO DF2, SGC_TP_SERVICIOS SER2  WHERE DF2.CONCEPTO=SER2.COD_SERVICIO
AND SER2.APLICA_SF=DF.CONCEPTO AND DF2.FACTURA=DF.FACTURA),0)+
NVL((SELECT DF2.VALOR  FROM SGC_TT_DETALLE_FACTURA_ASEO DF2, SGC_TP_SERVICIOS SER2  WHERE DF2.CONCEPTO=SER2.COD_SERVICIO
AND SER2.AJUSTA=DF.CONCEPTO AND DF2.FACTURA=DF.FACTURA),0)>0)
GROUP BY CONCEPTO, COD_SERVICIO
                ";
        /*$sql = "SELECT
                S.DESC_SERVICIO CONCEPTO, 
                SUM(DF.VALOR-DF.VALOR_PAGADO+DF.TOTAL_DEBITO-DF.TOTAL_CREDITO) VALOR, 
                S.COD_SERVICIO,
                COUNT((SELECT COUNT(F2.CONSEC_FACTURA)   
                FROM SGC_TT_FACTURA F2 WHERE 
                F.CONSEC_FACTURA = F2.CONSEC_FACTURA AND F2.FACTURA_PAGADA = 'N' AND F2.FECHA_PAGO IS NULL)) NUMFAC
FROM SGC_TT_DETALLE_FACTURA DF, SGC_TT_FACTURA F, SGC_TP_SERVICIOS S
WHERE F.CONSEC_FACTURA = DF.FACTURA AND 
S.COD_SERVICIO = DF.CONCEPTO AND
DF.COD_INMUEBLE = $inmueble AND 
F.FEC_EXPEDICION IS NOT NULL AND
F.FECHA_PAGO IS NULL AND
F.FACTURA_PAGADA = 'N'
GROUP BY S.DESC_SERVICIO, S.COD_SERVICIO";*/
        //$sql;
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


    public function estcon3 ($inmueble)
    {

        $sql = "SELECT
                S.DESC_SERVICIO CONCEPTO, 
                SUM(DF.VALOR-DF.VALOR_PAGADO+DF.TOTAL_DEBITO-DF.TOTAL_CREDITO) VALOR, 
                S.COD_SERVICIO,
                COUNT((SELECT COUNT(F2.CONSEC_FACTURA)   
                FROM SGC_TT_FACTURA F2 WHERE 
                F.CONSEC_FACTURA = F2.CONSEC_FACTURA AND F2.FACTURA_PAGADA = 'N' AND F2.FECHA_PAGO IS NULL)) NUMFAC
FROM SGC_TT_DETALLE_FACTURA DF, SGC_TT_FACTURA F, SGC_TP_SERVICIOS S
WHERE F.CONSEC_FACTURA = DF.FACTURA AND 
S.COD_SERVICIO = DF.CONCEPTO AND
DF.COD_INMUEBLE = $inmueble AND 
F.FEC_EXPEDICION IS NOT NULL AND
F.FECHA_PAGO IS NULL AND
F.FACTURA_PAGADA = 'N'
GROUP BY S.DESC_SERVICIO, S.COD_SERVICIO
ORDER BY S.COD_SERVICIO";
        //$sql;
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

    public function estcon4 ($inmueble, $descuento, $codserv)
    {

        $sql = "SELECT
                S.DESC_SERVICIO CONCEPTOD, 
                ROUND(SUM((DF.VALOR-DF.VALOR_PAGADO+DF.TOTAL_DEBITO-DF.TOTAL_CREDITO)*$descuento)/100) VALORD,
                S.COD_SERVICIO COD_SERVICIOD,
                COUNT((SELECT COUNT(F2.CONSEC_FACTURA)   
                FROM SGC_TT_FACTURA F2 WHERE 
                F.CONSEC_FACTURA = F2.CONSEC_FACTURA AND F2.FACTURA_PAGADA = 'N' AND F2.FECHA_PAGO IS NULL)) NUMFAC
FROM SGC_TT_DETALLE_FACTURA DF, SGC_TT_FACTURA F, SGC_TP_SERVICIOS S
WHERE F.CONSEC_FACTURA = DF.FACTURA AND 
S.COD_SERVICIO = DF.CONCEPTO AND
DF.COD_INMUEBLE = $inmueble AND 
F.FEC_EXPEDICION IS NOT NULL AND
F.FECHA_PAGO IS NULL AND
F.FACTURA_PAGADA = 'N' AND 
F.PERIODO <= 202111 AND 
S.COD_SERVICIO = $codserv
GROUP BY S.DESC_SERVICIO, S.COD_SERVICIO
ORDER BY S.COD_SERVICIO";
        //$sql;
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


    public function detfact2 ($consecfact)
    {
        $sql="
                SELECT max(F.PERIODO) PERIODO,SUM(NVL(DF.UNIDADES,0)) CONSUMO,  SUM(NVL(DF.VALOR-DF.VALOR_PAGADO,0)) VALOR
                FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF
                where  F.CONSEC_FACTURA=DF.FACTURA
                and F.CONSEC_FACTURA=$consecfact
                and DF.CONCEPTO in (1,3)
                ";
        $sql;
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

	
	    public function estconperiodo ($inmueble,$periodoini,$periodofin)
    {
        $sql="SELECT S.DESC_SERVICIO CONCEPTO, SUM(NVL(DF.VALOR-DF.VALOR_PAGADO,0)) VALOR, count(S.DESC_SERVICIO) NUMFAC FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SERVICIOS S
                where
                 F.FACTURA_PAGADA='N'
                AND F.PERIODO=DF.PERIODO
                AND F.INMUEBLE=DF.COD_INMUEBLE
				AND F.FEC_EXPEDICION IS NOT NULL
                AND S.COD_SERVICIO= DF.CONCEPTO
                and DF.COD_INMUEBLE=$inmueble
                and F.PERIODO>=$periodoini
                and F.PERIODO<=$periodofin
                GROUP BY S.DESC_SERVICIO";
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
	
////////////////// num fac vencidas

    public function numfacven ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (SELECT COUNT(distinct F.CONSEC_FACTURA) CANTIDAD, min(F.PERIODO) PERIODOMIN,max(F.PERIODO) PERIODOMAX,SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) DEUDA FROM SGC_TT_FACTURA_ASEO F WHERE
                    F.FACTURA_PAGADA='N' AND 
                    F.FECHA_PAGO IS NULL
					AND F.FEC_EXPEDICION IS NOT NULL
                    --AND FEC_VCTO<SYSDATE
              
					$where
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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
    /////////////////////////////// facturas pagas por un inmueble


    public function getFacPagByInm($inm){
        $sql="SELECT
                F.CONSEC_FACTURA,
                F.PERIODO,
                U.ID_NCF || F.NCF_CONSEC NCF_CONSEC,
                F.TOTAL,
                F.FEC_EXPEDICION,
                ROUND(SF.SALD_FAVOR,2) SALD_FAVOR,
                OBSERVACION_REL
              FROM
                SGC_TT_FACTURA F,
                SGC_TP_NCF_USOS U,
                SGC_TT_SAL_FACTURAS SF
              WHERE
                U.ID_NCF_USO=F.NCF_ID AND
                SF.ID_FACTURA(+)=F.CONSEC_FACTURA AND
                F.FACTURA_PAGADA='S' AND
                F.INMUEBLE='$inm' AND
                TOTAL>0 AND
                NCF_CONSEC IS NOT NULL
              ORDER BY 2 DESC
        ";

        $resultado=oci_parse($this->_db,$sql);
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
	
	
	public function getSupFactByAcuZonOpeFech($proy,$zona,$operario,$fech){
        $where="";
        if ($zona<>''){
            $where.=" AND S.ID_ZONA='$zona' ";
        }
        if ($operario<>''){
            $where.=" AND S.USR_EJE='$operario' ";
        }
        if ($fech<>''){
            $where.=" AND TO_CHAR(S.FECHA_ASIGNA,'YYYY-MM-DD')='$fech' ";
        }
        $where.=" GROUP BY S.ID_ZONA, USU.LOGIN, S.RUTA, S.USR_EJE, S.FECHA_ASIGNA
                   order by S.FECHA_ASIGNA DESC ";
         $sql="select
                S.FECHA_ASIGNA,
                S.ID_ZONA,
                USU.LOGIN,
                S.RUTA,
                COUNT(1) ASIGNADOS,
                (SELECT
                COUNT(1)
                FROM SGC_TT_SUPERVISION_ENTREGA_FAC S2
                WHERE S2.ID_ZONA=S.ID_ZONA AND
                      S2.USR_EJE=S.USR_EJE AND
                      S2.RUTA=S.RUTA AND
                      S2.FECHA_ASIGNA=S.FECHA_ASIGNA AND
                      S2.FECHA_EJECUCION IS NOT NULL
                    ) EJECUTADOS,
                    (SELECT
                         COUNT(1)
                     FROM SGC_TT_SUPERVISION_ENTREGA_FAC S2
                     WHERE S2.ID_ZONA=S.ID_ZONA AND
                             S2.USR_EJE=S.USR_EJE AND
                             S2.RUTA=S.RUTA AND
                           S2.FECHA_ASIGNA=S.FECHA_ASIGNA AND
                             S2.FECHA_EJECUCION IS NOT NULL AND
                                S2.ENTREGADO='S'
                    )EXITOSOS

                    from SGC_TT_SUPERVISION_ENTREGA_FAC s,
                         SGC_TT_USUARIOS USU,
                         SGC_TT_INMUEBLES INM
                    WHERE S.USR_EJE=USU.ID_USUARIO
                    AND INM.CODIGO_INM=S.COD_INMUEBLE 
                    AND INM.ID_PROYECTO='$proy' 
                    $where                   
        ";

        $resultado=oci_parse($this->_db,$sql);
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



	////////////////// total facturas vencidas

    public function totalfacven ($where)
    {
        $sql="SELECT SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) DEUDA, I.ID_PROYECTO 
        FROM SGC_TT_FACTURA_ASEO F,  SGC_TT_INMUEBLES I 
        WHERE F.FACTURA_PAGADA = 'N' AND
        F.FECHA_PAGO IS NULL
        AND F.FEC_EXPEDICION IS NOT NULL
        AND I.CODIGO_INM = F.INMUEBLE 
		$where";
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



    ////////////////// facturas pendientes ///////////////////////

    public function factotal ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                SELECT F.CONSEC_FACTURA, F.TOTAL, F.PERIODO, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY HH24:MI') FEC_EXPEDICION,
                concat(NU.ID_NCF,F.NCF_CONSEC) ncf,F.TOTAL_PAGADO,  
				(SELECT TO_CHAR(RL.FECHA_LECTURA_ORI,'DD/MM/YYYY HH24:MI') FROM SGC_TT_REGISTRO_LECTURAS RL WHERE F.PERIODO = RL.PERIODO AND F.INMUEBLE = RL.COD_INMUEBLE)FECHA_LECTURA,
                (SELECT SUM (DF1.UNIDADES) FROM SGC_TT_DETALLE_FACTURA DF1 WHERE DF1.CONCEPTO (+) IN (1,3) AND DF1.FACTURA=DF.FACTURA) LECTURA
				, TRUNC(NVL(F.FECHA_PAGO,SYSDATE)-F.FEC_EXPEDICION) DIAS ,TO_CHAR(F.FECHA_PAGO,'DD/MM/YYYY HH24:MI') FECHA_PAGO
                 FROM

                SGC_TT_FACTURA F,sgc_tp_ncf_usos nu, SGC_TT_DETALLE_FACTURA DF WHERE
                   F.NCF_ID=NU.ID_NCF_USO(+)

                   AND DF.FACTURA(+)=F.CONSEC_FACTURA
                   AND DF.RANGO(+)=0
                   AND DF.CONCEPTO (+) IN (1,3)
				   
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


    ///////////////// detalle de la lectura


    public function detallelectura ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select RL.LECTURA_ORIGINAL,RL.FECHA_LECTURA,RL.OBSERVACION_ACTUAL ,U.LOGIN, RL.CONSUMO  from sgc_tt_registro_lecturas rl, SGC_TT_FACTURA f, sgc_tt_usuarios u
                          where
                          RL.COD_INMUEBLE=F.INMUEBLE
                          and RL.PERIODO=F.PERIODO
                          and U.ID_USUARIO=RL.COD_LECTOR
                   $where
                   $sort
                   )where  rownum<13
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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


    ///////////////// detalle de la entrega d ela factura


    public function detalleentfactura($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select EF.ENTREGADO,EF.FECHA_EJECUCION, nvl(OFA.DESCRIPCION,'Entregada') OBS_NOENTREGA,US.LOGIN  from sgc_tt_registro_entrega_fac ef,
                            sgc_tt_usuarios us,
                            SGC_TT_FACTURA f,
                            sgc_tp_observaciones_fac ofa where
                             F.INMUEBLE=EF.COD_INMUEBLE
                            and F.PERIODO=EF.PERIODO
                            and OFA.CODIGO(+)=EF.OBS_NOENTREGA
                            and US.ID_USUARIO=EF.USR_EJE
                            $where
                            $sort
                   )where  rownum<13
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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


    ///////////////////////facturas reliquidadas///////////////


    public function Obtenerfacrel ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select FR.PERIODO,FR.VERSION_FACTURA,FR.TIPO_AJUSTE, FR.FECHA_REL FEC_EXPEDICION, FR.DESCRIPCION, FR.VALOR_TOTAL , concat(NU.ID_NCF,FR.NCF_CONSEC) ncf

                    from sgc_tt_factura_rel fr , sgc_tp_ncf_usos nu where
                      NU.ID_NCF_uso(+)=FR.ID_NCF
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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

/////////////////////VERIFICA QUE LA FACTURA TIENE UNA FACTURA RELIQUIDADA/////////////
    public function verificarel ($factura)
    {
        $sql="select count (1) CANTIDAD from sgc_tt_factura_rel f where F.FACTURA=$factura ";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $cantidad = oci_result($resultado, 'CANTIDAD');
            if($cantidad==0){
                return false;
            }else {
                return true;
            }
        }
        else
        {


            return false;
        }

    }
/////// obtiene el periodo minimo en el que hay deuda

    public function minper ($inmueble)
    {
        $sql="SELECT NVL(min(periodo),0) MINPER FROM SGC_TT_FACTURA_ASEO F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
                        and F.NCF_ID=NU.ID_NCF_USO
                        AND F.FEC_EXPEDICION IS NOT NULL
                        AND F.FECHA_PAGO IS NULL
                        and F.INMUEBLE=$inmueble";
    // $sql;

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $cantidad = oci_result($resultado, 'MINPER');
            return $cantidad;
        }
        else
        {
            return false;
        }

    }


    /////// obtiene el periodo maximo en el que hay deuda

    public function maxper ($inmueble)
    {
        $sql="SELECT NVL(max(periodo),0) MAXPER FROM SGC_TT_FACTURA_ASEO F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
                        and F.NCF_ID=NU.ID_NCF_USO
                        AND F.FEC_EXPEDICION IS NOT NULL
                        AND F.FECHA_PAGO IS NULL
                        and F.INMUEBLE=$inmueble";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $cantidad = oci_result($resultado, 'MAXPER');
            return $cantidad;
        }
        else
        {
            return false;
        }

    }

/////// datos de conceptos de diferido

public function datosConceptos($coddiferido){
        $sql = "SELECT C.DESCRIPCION, S.COD_SERVICIO, S.DESC_SERVICIO, S.LIMITE_MAX_CUOTAS 
        FROM SGC_TP_CONCEPTO_DIF C, SGC_TP_SERVICIOS S 
        WHERE C.COD_CONCEPTO = S.COD_SERVICIO
		AND C.CODIGO = '$coddiferido'";
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

  /////// aplica Acuerdo de Pago Concepto 50

    public function aplicaDiferido($codinmueble,$valfinancia,$numcuotas,$coduser,$codfacturacion,$pagini){
         $sql="BEGIN SGC_P_REFDEUDA_ASEO($codinmueble,$valfinancia,$numcuotas,'$coduser',$codfacturacion,$pagini,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
		//echo $sql;
       if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                ECHO $this->msgresult;
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }



    public function aplicaDiferidoAmnistia($codinmueble,$valfinancia,$numcuotas,$coduser,$codfacturacion,$pagini){
        $sql="BEGIN SGC_P_REFDEUDA_AMNISTIA($codinmueble,$valfinancia,$numcuotas,'$coduser',$codfacturacion,$pagini,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        //echo $sql;
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                ECHO $this->msgresult;
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }




    /////// genera listado de supervision

    public function asignaSupervision($zona,$periodo,$ruta,$muestra,$operario,$asignador){
        $sql="BEGIN SGC_P_ASIGNA_SUPERVISION('$zona',$periodo,$ruta,'$muestra','$operario','$asignador',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        //echo $sql;
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                ECHO $this->msgresult;
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
	 /////// aplica diferidos

public function aplicaDiferido2($codinmueble,$valfinancia,$numcuotas,$coduser,$codfacturacion,$coddiferido){

        echo $sql="BEGIN SGC_P_ADDDIF_ASEO($codinmueble,$valfinancia,$numcuotas,'$coduser','$codfacturacion','$coddiferido',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);




    if ($bandera == true) {
        if ($this->codresult > 0) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
    }
	
	  /////// CORRE PROCESO DE MORA

    public function corremora($zona,$periodo,$usr){
         $sql="BEGIN SGC_P_CORRE_MORA ( '$zona', $periodo, '$usr',:PMSGRESULT, :PCODRESULT );COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }



    /////// CORRE CIERRE DE ZONA

    public function cierrezona($zona,$periodo){
        $sql="BEGIN SGC_P_CIERRA_PERIODO ( '$zona', $periodo, :PMSGRESULT, :PCODRESULT );COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,1000);
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,10000);
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
///////consultas para factura digital
	public function zonasCerradas($hoy){
		$sql = "SELECT P.PERIODO, P.ID_ZONA
		FROM SGC_TP_PERIODO_ZONA P
		WHERE P.FEC_CIERRE BETWEEN TO_DATE('$hoy 00:00:00','DD/MM/YYYY HH24:MI:SS')
		AND TO_DATE('$hoy 23:59:59','DD/MM/YYYY HH24:MI:SS')";
		
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
	
	public function facturaDigital($periodo, $zona){
		$sql = "SELECT F.CONSEC_FACTURA, C.EMAIL
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C 
		WHERE F.INMUEBLE = I.CODIGO_INM
		AND I.CODIGO_INM = C.CODIGO_INM
		AND C.FECHA_FIN (+) IS NULL
		AND I.FAC_DIGITAL = 'S'
		AND F.PERIODO = $periodo
		AND F.ID_ZONA = '$zona'
		ORDER BY F.CONSEC_FACTURA";
		
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
	
	 public function datosEnvioFacDig($factura, $periodo, $email, $enviado, $error, $acueducto){
        $sql="BEGIN SGC_P_INGRESA_FAC_DIGITAL('$factura','$periodo','$email','$enviado','$error','$acueducto',:PMSGRESULT,:PCODRESULT);
		COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        //oci_bind_by_name($resultado,":PROY",$VACIO);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }
	
	public function facDigitalPendientes(){
		$sql = "SELECT E.CONSEC_FACTURA, E.PERIODO, E.EMAIL, E.ACUEDUCTO, E.INTENTOS_ENVIO, F.INMUEBLE, C.ALIAS
                FROM SGC_TT_ENVIO_FACDIGITAL E, SGC_TT_FACTURA F, SGC_TT_CONTRATOS C
                WHERE E.CONSEC_FACTURA = F.CONSEC_FACTURA
                  AND C.CODIGO_INM = F.INMUEBLE
                      AND ENVIADO = 'N'
                AND C.FECHA_FIN IS NULL
                ORDER BY CONSEC_FACTURA ASC";
		
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


    public function EncuestasPendientes(){
        $sql = "SELECT C.EMAIL, C.CODIGO_INM INMUEBLE, I.ID_PROYECTO ACUEDUCTO
    FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C
    WHERE I.CODIGO_INM = C.CODIGO_INM
    AND C.FECHA_FIN IS NULL
    AND I.ENCUESTA IN ('S','P')
    AND C.EMAIL IS NOT NULL
    AND I.ID_SECTOR IN (13,14,28,51,25,24,21)";

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

	public function flayerPendientes($acueducto){
		$sql = "SELECT DISTINCT EMAIL, ID_PROYECTO
        FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C
        WHERE I.CODIGO_INM = C.CODIGO_INM
        AND C.FECHA_FIN IS NULL
        AND I.ID_PROYECTO = '$acueducto'
        AND C.EMAIL IS NOT NULL
        AND C.FLYER = 'N'
		ORDER BY EMAIL ASC";

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

	public function datosEnvioFacDigPend($factura, $periodo, $enviado, $error, $intentos){
        $sql="BEGIN SGC_P_ACTUALIZA_FAC_DIGITAL('$factura','$periodo','$enviado','$error','$intentos',:PMSGRESULT,:PCODRESULT);
		COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        //oci_bind_by_name($resultado,":PROY",$VACIO);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }


    public function datosEncuesta($codinm, $enviado){
       // $sql="BEGIN SGC_P_ACTUALIZA_FAC_DIGITAL('$factura','$periodo','$enviado','$error','$intentos',:PMSGRESULT,:PCODRESULT);
		//COMMIT;END;";

        $sql="UPDATE SGC_TT_INMUEBLES SET ENCUESTA = '$enviado' WHERE CODIGO_INM = $codinm";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        //oci_bind_by_name($resultado,":PROY",$VACIO);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }


public function actEnvFlayer($factura, $periodo){
        echo $sql="BEGIN SGC_P_ACTUALIZA_ENV_FLAYER('$factura','$periodo',:PMSGRESULT,:PCODRESULT);
		COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }


	/////// datos de factura
public function datosFacturaPdf($factura){
        $sql = "SELECT
                                                                                                                TD.DESCRIPCION_TIPO_DOC TIPODOC,
                                                                                                                TO_CHAR(f.VENCIMIENTO_NCF,'DD/MM/YYYY') VENCE_NCF,i.ID_TIPO_CLIENTE,
I.CODIGO_INM, F.DOCUMENTO_CLIENTE DOCUMENTO,  CONCAT(N.ID_NCF,F.NCF_CONSEC)NCF, I.CATASTRO, F.NOMBRE_CLIENTE ALIAS , I.DIRECCION, U.DESC_URBANIZACION, I.ID_ZONA,
                                                                                                                TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FECEXP, F.PERIODO, I.ID_PROCESO, (SELECT G.DESC_GERENCIA FROM SGC_TT_GERENCIA_ZONA Z, SGC_TP_GERENCIAS G
WHERE G.ID_GERENCIA = Z.ID_GERENCIA AND Z.ID_ZONA = I.ID_ZONA) GERENCIA, E.DESC_MED, CA.DESC_CALIBRE, M.SERIAL, TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VCTO,
                                                                                                                TO_CHAR(FECHA_CORTE,'DD/MM/YYYY')FECCORTE, I.ID_PROYECTO, F.MSJ_NCF, F.MSJ_PERIODO, F.MSJ_FACTURA, F.MSJ_ALERTA, F.MSJ_BURO
FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_NCF_USOS N,  SGC_TP_URBANIZACIONES U, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES E, SGC_TP_CALIBRES CA,SGC_TP_TIPODOC TD
WHERE I.CODIGO_INM(+) = F.INMUEBLE
      AND CA.COD_CALIBRE(+) = M.COD_CALIBRE
      AND M.COD_INMUEBLE(+) = I.CODIGO_INM
      AND F.NCF_ID = N.ID_NCF_USO
      AND U.CONSEC_URB(+) = I.CONSEC_URB
      AND M.COD_MEDIDOR = E.CODIGO_MED(+)
      AND TD.ID_TIPO_DOC = F.TIPO_DOCUMENTO
      AND M.FECHA_BAJA(+) IS NULL
      AND F.CONSEC_FACTURA = '$factura'";
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


    /////DATOS CONSUMO FACTURADO
    public function datosConsumoFactPdf($factura){
        $sql = "SELECT SUM(DF.UNIDADES) CONSUMO
        FROM SGC_TT_DETALLE_FACTURA DF
        WHERE DF.FACTURA = $factura
        AND DF.CONCEPTO IN (1,3)";
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


    /////// datos de servicios
public function datosServiciosPdf($codinm){
        $sql = "SELECT C.DESC_SERVICIO, U.DESC_USO, T.CODIGO_TARIFA, S.UNIDADES_TOT, T.CONSEC_TARIFA, U.OPERA_CORTE, C.COD_SERVICIO, S.CUPO_BASICO
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
         $sql = "SELECT CASE LECTURA_ACTUAL WHEN -1 THEN NULL ELSE LECTURA_ACTUAL END LECTURA_ACTUAL, TO_CHAR(FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC  FROM SGC_TT_REGISTRO_LECTURAS R
		WHERE COD_INMUEBLE = '$codinm'
		AND PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE('$periodo','YYYYMM'),-1),'YYYYMM') 
		AND PERIODO <= TO_CHAR(ADD_MONTHS(TO_DATE('$periodo','YYYYMM'),+0),'YYYYMM') ";
       //  echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );
//echo $sql;

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
        AND R.RANGO IN ($rango)
        AND S.ACTIVO = 'S'";
       // echo  $sql;
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


///////////////////////////////////////todas las facturas (para estado de cuenta)/////

    public function todasFacturas ($codinm)
    {
        $sql="SELECT TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY') FEC_EXPEDICION , to_char(TO_DATE(F.PERIODO,'YYYYMM'),'DD-MM-YYYY') FECHCOMP2 , to_char(TO_DATE(F.PERIODO,'YYYYMM'),'YYYYMMDD') FECHCOMP, 'fac '||F.CONSEC_FACTURA||' '||to_char(to_date(F.PERIODO,'YYYYMM'),'mon/yyyy') DESCRIPCION,F.TOTAL+F.TOTAL_DEBITO-F.TOTAL_CREDITO TOTAL 
            FROM SGC_TT_FACTURA F
              WHERE F.INMUEBLE='$codinm'
              and  F.FEC_EXPEDICION is not null
			  --AND F.FEC_EXPEDICION > TO_DATE('31/12/2010','DD/MM/YYYY')
              ORDER BY F.PERIODO ASC";
       // echo $sql;
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
	
	
	 public function ObtieneInmueble($factura)
    {
        $sql="SELECT INMUEBLE
        FROM SGC_TT_FACTURA_ASEO
        WHERE CONSEC_FACTURA = '$factura'";
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
///////////////////////////////////////obtiene acueducto/////	
	 public function obtieneProyecto($codinmueble)
    {
        $sql="SELECT ID_PROYECTO
        FROM SGC_TT_INMUEBLES
        WHERE CODIGO_INM = '$codinmueble'";
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


    /////////////////////////////////// OBTIENE DATOS DE RANGO DE FACTURAS

    public function getValPerPagFactByRangFact($facturas)
    {
        $sql="SELECT
                SUM(F.TOTAL_PAGADO) VALOR,
                MIN(F.PERIODO)||' - '||MAX(F.PERIODO) PERIODO,
                MIN(F.FECHA_PAGO)||' - '||MAX(F.FECHA_PAGO) FECHA,
                count(*) CANTIDAD
              FROM
                SGC_TT_FACTURA F
              WHERE
                F.CONSEC_FACTURA IN ($facturas)";
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



    public function getValPerFactPendByInm($inm)
    {
        $sql="SELECT
                (MIN(F.PERIODO)||' - '||MAX(F.PERIODO)) PERIODO,
                SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) DEUDA,
                COUNT(1) CANTIDAD
              FROM
                SGC_TT_FACTURA F
              WHERE
                F.INMUEBLE='$inm' AND
                F.FACTURA_PAGADA='N'AND
                F.TOTAL>0 AND
                F.FECHA_PAGO IS NULL";
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


    public function IngresaSalFavFac($inm,$fac,$obs,$numFac,$cod,$sald){

       $sql="BEGIN SGC_P_HISTSALFAV ( $inm,'$fac', $numFac, $sald, '$cod', '$obs',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }


    public function IngresaSalFavInm($importe,$referencia,$num_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda){
        $sql="BEGIN SGC_P_INGRESA_PAGO_VIRTUAL('$importe','$referencia','$num_caja','$cod_inmueble','$origen','$coduser','$monto',:PROY,'$deuda',:PMSGRESULT,:PCODRESULT);
		COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        oci_bind_by_name($resultado,":PROY",$VACIO);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }




    }
    function ref(&$var,$varATomar)
    {
        $var=  $varATomar;
        return $var;
    }

	
}