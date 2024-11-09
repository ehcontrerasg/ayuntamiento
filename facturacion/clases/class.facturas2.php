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
						SELECT F.CONSEC_FACTURA, (F.TOTAL-F.TOTAL_PAGADO) TOTAL , F.PERIODO, F.FEC_EXPEDICION,concat(NU.ID_NCF,F.NCF_CONSEC) ncf FROM SGC_TT_FACTURA F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
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
						SELECT F.CONSEC_FACTURA, (F.TOTAL - F.TOTAL_PAGADO) TOTAL, F.PERIODO, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FEC_EXPEDICION, TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY') FEC_VCTO
						FROM SGC_TT_FACTURA F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
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

////////////////////// detalle factura/////////////////////////////////////////
    public function detfac ($where,$sort,$start,$end,$factura)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select S.DESC_SERVICIO CONCEPTO,RANGO,UNIDADES,VALOR,S.COD_SERVICIO
						from SGC_TT_DETALLE_FACTURA DF , SGC_TP_SERVICIOS S 
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
					FROM (SELECT S.DESC_SERVICIO CONCEPTO, SUM(NVL(DF.VALOR-DF.VALOR_PAGADO,0)) VALOR, count(S.DESC_SERVICIO) NUMFAC FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SERVICIOS S
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


    public function estcon2 ($perini,$perfin,$inmueble)
    {
        $sql="
                  SELECT S.DESC_SERVICIO CONCEPTO, SUM(NVL(DF.VALOR-DF.VALOR_PAGADO,0))+NVL((SELECT SUM(NVL(DF2.VALOR-DF2.VALOR_PAGADO,0)) VALOR FROM SGC_TT_FACTURA F2, SGC_TT_DETALLE_FACTURA DF2, SGC_TP_SERVICIOS S2
                where
                 F2.FACTURA_PAGADA='N'
                AND F2.CONSEC_FACTURA=DF2.FACTURA
                AND S2.COD_SERVICIO= DF2.CONCEPTO
                AND S2.AJUSTA=S.COD_SERVICIO
                AND F2.FEC_EXPEDICION IS NOT NULL
                AND F2.PERIODO>=$perini
				AND S2.DESCUENTO='N'
                AND F2.PERIODO<=$perfin
                AND F2.INMUEBLE=$inmueble
                GROUP BY S2.DESC_SERVICIO,s2.COD_SERVICIO),0) VALOR,
                count(S.DESC_SERVICIO)-NVL((
                 SELECT count(S3.DESC_SERVICIO)  FROM SGC_TT_FACTURA F3, SGC_TT_DETALLE_FACTURA DF3, SGC_TP_SERVICIOS S3
                where
                 F3.FACTURA_PAGADA='N'
                AND F3.CONSEC_FACTURA=DF3.FACTURA
                AND S3.COD_SERVICIO= DF3.CONCEPTO
               AND S3.AJUSTA=S.COD_SERVICIO
			   AND S3.DESCUENTO='N'
                AND F3.FEC_EXPEDICION IS NOT NULL
                AND F3.PERIODO>=$perini
                AND F3.PERIODO<=$perfin
                AND F3.INMUEBLE=$inmueble
                GROUP BY S3.DESC_SERVICIO,s3.COD_SERVICIO

                ),0) NUMFAC,S.COD_SERVICIO FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SERVICIOS S
                where
                 F.FACTURA_PAGADA='N'
                AND F.CONSEC_FACTURA=DF.FACTURA
                AND S.COD_SERVICIO= DF.CONCEPTO and S.AJUSTA is  null
				AND S.DESCUENTO='N'
                AND F.FEC_EXPEDICION IS NOT NULL
                AND F.PERIODO>=$perini
                AND F.PERIODO<=$perfin
                AND F.INMUEBLE=$inmueble
                GROUP BY S.DESC_SERVICIO,s.COD_SERVICIO
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
					FROM (SELECT COUNT(distinct F.CONSEC_FACTURA) CANTIDAD, min(F.PERIODO) PERIODOMIN,max(F.PERIODO) PERIODOMAX,SUM(F.TOTAL-F.TOTAL_PAGADO) DEUDA FROM SGC_TT_FACTURA F WHERE
                    F.FACTURA_PAGADA='N'
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
	
	
	
	////////////////// total facturas vencidas

    public function totalfacven ($where)
    {
        $sql="SELECT SUM(F.TOTAL-F.TOTAL_PAGADO) DEUDA, I.ID_PROYECTO 
        FROM SGC_TT_FACTURA F,  SGC_TT_INMUEBLES I 
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
                concat(NU.ID_NCF,F.NCF_CONSEC) ncf,F.TOTAL_PAGADO, TO_CHAR(DF.FECHA,'DD/MM/YYYY') FECHA_LECTURA,
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
        $sql="SELECT NVL(min(periodo),0) MINPER FROM SGC_TT_FACTURA F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
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
        $sql="SELECT NVL(max(periodo),0) MAXPER FROM SGC_TT_FACTURA F,sgc_tp_ncf_usos nu WHERE F.FACTURA_PAGADA='N'
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

public function aplicaDiferido($codinmueble,$valfinancia,$numcuotas,$coduser,$codfacturacion){
        $sql="BEGIN SGC_P_REFDEUDA($codinmueble,$valfinancia,$numcuotas,'$coduser',$codfacturacion,:PMSGRESULT,:PCODRESULT);COMMIT;END;"; 
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
        $sql="BEGIN SGC_P_ADDDIF($codinmueble,$valfinancia,$numcuotas,'$coduser',$codfacturacion,'$coddiferido',:PMSGRESULT,:PCODRESULT);COMMIT;END;"; 
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
	
	/////// datos de factura
public function datosFacturaPdf(){
        $sql = "SELECT I.CODIGO_INM,  CONCAT(N.ID_NCF,F.NCF_CONSEC)NCF, I.CATASTRO, C.ALIAS, I.DIRECCION, U.DESC_URBANIZACION, I.ID_ZONA, 
		TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FECEXP, F.PERIODO, I.ID_PROCESO, (SELECT G.DESC_GERENCIA FROM SGC_TT_GERENCIA_ZONA Z, SGC_TP_GERENCIAS G 
		WHERE G.ID_GERENCIA = Z.ID_GERENCIA AND Z.ID_ZONA = I.ID_ZONA) GERENCIA, E.DESC_MED, M.COD_CALIBRE, M.SERIAL, TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VCTO,
		TO_CHAR(FECHA_CORTE,'DD/MM/YYYY')FECCORTE, I.ID_PROYECTO, F.CONSEC_FACTURA
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_NCF_USOS N, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES E
		WHERE I.CODIGO_INM(+) = F.INMUEBLE
		AND C.CODIGO_INM(+) = F.INMUEBLE
		AND M.COD_INMUEBLE(+) = I.CODIGO_INM
		AND F.NCF_ID = N.ID_NCF_USO
		AND U.CONSEC_URB(+) = I.CONSEC_URB
		AND M.COD_MEDIDOR = E.CODIGO_MED(+)
		AND M.FECHA_BAJA IS NULL
		AND F.PERIODO = 201609
		AND C.FECHA_FIN IS NULL 
		AND F.INMUEBLE IN (453856,
		1025503,
		1025504,
		1025505,
		1025506,
		280488,
		279883,
		1031758,
		1046889,
		1046649,
		1026597,
		1046205,
		1046989,
		279717,
		1027497,
		1046949,
		1045249,
		1046890,
		378290,
		1048710,
		388220,
		378092)";
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


///////////////////////////////////////todas las facturas (para estado de cuenta)/////

    public function todasFacturas ($codinm)
    {
        $sql="SELECT TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY') FEC_EXPEDICION , to_char(F.FEC_EXPEDICION,'DD-MM-YYYY') FECHCOMP2 , to_char(F.FEC_EXPEDICION,'YYYYMMDD') FECHCOMP, 'fac '||F.CONSEC_FACTURA||' '||to_char(to_date(F.PERIODO,'YYYYMM'),'mon/yyyy') DESCRIPCION,F.TOTAL FROM SGC_TT_FACTURA F
              WHERE F.INMUEBLE='$codinm'
              and  F.FEC_EXPEDICION is not null
              ORDER BY F.FEC_EXPEDICION ASC";
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
	
	
	 public function ObtieneInmueble($factura)
    {
        $sql="SELECT INMUEBLE
        FROM SGC_TT_FACTURA
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
                SUM(F.TOTAL-F.TOTAL_PAGADO) DEUDA,
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


	
}