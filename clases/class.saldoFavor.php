<?php
include_once "class.conexion.php";


class SaldoFavor extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getSaldFavByInmFlexy($codinmueble,$sort,$start,$end){
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT CODIGO, TO_CHAR(FECHA,'DD/MM/YYYY HH24:MI:SS')FECHA, SUBSTR(REPLACE(REPLACE(MOTIVO,'#',''),CHR(13),''),0,100) MOTIVO, IMPORTE, VALOR_APLICADO
						  FROM SGC_TT_SALDO_FAVOR
						  WHERE INM_CODIGO=$codinmueble
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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

    public function getAplSalBySalFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT
                            F.PERIODO,F.CONSEC_FACTURA,DS.IMPORTE
                             FROM SGC_TT_DETALLE_SALDOS DS, SGC_TT_FACTURA F
                             WHERE DS.FACTURA_APLI=F.CONSEC_FACTURA
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


    public function getSaldosFavorByInm ($inmueble, $sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT P.ID_PAGO, TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS') FECHA_PAGO, P.IMPORTE, U.NOM_USR||' '||U.APE_USR ID_USUARIO, E.DESC_ENTIDAD, 
						REPLACE(REPLACE(V.DESCRIPCION,CHR(10),' '),CHR(13),' ')DESCRIPCION,
						C.DESCRIPCION DESC_CAJA, 
						DECODE(P.ESTADO,'I','Anulado','A','Activo')ESTADO, REPLACE(REPLACE(RP.MOTIVO_REV,CHR(10),' '),CHR(13),' ')MOTIVO_REV, RP.USR_REV, RP.FECHA_REV 
                        FROM SGC_TT_PAGOS P, SGC_TT_USUARIOS U, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO V, SGC_TP_ENTIDAD_PAGO E, SGC_TT_REV_PAGO RP
                        WHERE U.ID_USUARIO = P.ID_USUARIO AND P.ID_CAJA = C.ID_CAJA AND C.ID_PUNTO_PAGO = V.ID_PUNTO_PAGO
                        AND E.COD_ENTIDAD = V.ENTIDAD_COD 
                        AND P.ID_PAGO = RP.ID_PAGO(+)
                        AND INM_CODIGO = '$inmueble'
                        AND P.ID_CAJA='463'
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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


    public function ReversaSaldos($cod_pago, $observacion, $coduser){
        $sql="BEGIN SGC_P_REVERSAPAGO('$cod_pago','$observacion','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);

        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }




}
