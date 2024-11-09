<?php
include_once "../../clases/class.conexion.php";
class AnulaPagos extends ConexionClass{
	private $coduser;
	private $importe;
	private $referencia;
	private $num_caja;
	private $cod_inmueble;
	private $origen;
	private $monto;
	private $cod_pro;
	private $medio;
	private $id_pago;
    private $codresult;
    private $msgresult;
	
	public function __construct(){
		parent::__construct();
		$this->coduser="";
		$this->importe="";
		$this->referencia="";
		$this->num_caja="";
		$this->cod_inmueble="";
		$this->origen="";
		$this->monto="";
		$this->cod_pro="";
		$this->medio="";
		$this->id_pago="";
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }
	
	
	 public function seleccionaUser($coduser){
		$sql = "SELECT C.NUM_CAJA, P.ID_PUNTO_PAGO, P.DESCRIPCION, E.COD_ENTIDAD, E.DESC_ENTIDAD, R.ID_PROYECTO, R.DESC_PROYECTO, (U.NOM_USR||' '||U.APE_USR) NOMBRE
		FROM SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO P, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PROYECTOS R, SGC_TT_USUARIOS U
		WHERE C.ID_PUNTO_PAGO = P.ID_PUNTO_PAGO AND P.ENTIDAD_COD = E.COD_ENTIDAD AND E.PROYECTO = R.ID_PROYECTO 
		AND C.ID_USUARIO = U.ID_USUARIO AND C.ID_USUARIO = '$coduser'";
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
	
	
	 public function obtenerDatosCliente($cod_inmueble){
		$sql = "SELECT I.CODIGO_INM, I.DIRECCION, U.DESC_URBANIZACION, C.CODIGO_CLI, C.ALIAS, I.ID_ESTADO, A.DESC_ACTIVIDAD, A.ID_USO, I.ID_PROYECTO, P.DESC_PROYECTO, 
		CL.NOMBRE_CLI
        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TP_ACTIVIDADES A, SGC_TP_PROYECTOS P, SGC_TT_CLIENTES CL
        WHERE I.CONSEC_URB = U.CONSEC_URB AND C.CODIGO_INM = I.CODIGO_INM AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND C.CODIGO_CLI = CL.CODIGO_CLI
        AND C.FECHA_FIN IS NULL AND I.ID_PROYECTO = P.ID_PROYECTO AND I.CODIGO_INM = '$cod_inmueble'";
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
	
	
	
	
	/*public function totalfacven ($cod_inmueble)
    {
        $sql="SELECT SUM(DF.VALOR-DF.VALOR_PAGADO) DEUDA
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I 
		WHERE F.FACTURA_PAGADA = 'N'
        AND FEC_VCTO < SYSDATE
		AND F.FEC_EXPEDICION IS NOT NULL
        AND DF.FACTURA(+) = F.CONSEC_FACTURA
		AND I.CODIGO_INM = F.INMUEBLE 
		AND I.CODIGO_INM = '$cod_inmueble'";
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

    }*/
	
	 public function PagosRegistrados ($inmueble, $sort,$start,$end)
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
                         AND P.FECHA_PAGO BETWEEN (
                        SELECT SYSDATE -30 FROM DUAL)
                        AND (SELECT SYSDATE + 1 FROM DUAL)
						  $sort
						)a WHERE  ROWNUM<=1000
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
	
	 public function obtieneMedioPago($id_pago){
		$sql = "SELECT M.ID_FORM_PAGO, F.DESCRIPCION 
		FROM SGC_TT_MEDIOS_PAGO M, SGC_TP_FORMA_PAGO F
		WHERE M.ID_FORM_PAGO = F.CODIGO AND ID_PAGO = '$id_pago'";
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
	
	public function DatosFacturas ($id_pago, $sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT F.INMUEBLE, F.CONSEC_FACTURA, F.PERIODO, P.FECHA_PAGO, F.TOTAL,  M.IMPORTE, M.COMPROBANTE
						FROM SGC_TT_PAGOS P, SGC_TT_FACTURA F, SGC_TT_PAGO_FACTURAS M
						WHERE F.INMUEBLE = P.INM_CODIGO AND P.ID_PAGO = M.ID_PAGO AND M.FACTURA = F.CONSEC_FACTURA
						AND P.ID_PAGO = '$id_pago'
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
	
	
	 public function ReversaPagos($cod_pago, $observacion, $coduser){
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
	
	
	 public function RecaudosRegistrados ($inmueble, $sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT R.CODIGO, R.FECHA_PAGO, R.IMPORTE, U.NOM_USR||' '||U.APE_USR ID_USUARIO,R.CONCEPTO, S.DESC_SERVICIO, DECODE(R.ESTADO,'I','Anulado','Activo')ESTADO, 
						REPLACE(REPLACE(RR.MOTIVO_REV,CHR(10),''),CHR(13),'')MOTIVO_REV, RR.USR_REV, RR.FECHA_REV
                        FROM SGC_TT_OTROS_RECAUDOS R, SGC_TT_USUARIOS U, SGC_TP_SERVICIOS S, SGC_TT_REV_RECAUDO RR
                        WHERE U.ID_USUARIO = R.USUARIO AND S.COD_SERVICIO = R.CONCEPTO 
                        AND RR.ID_RECAUDO(+) = R.CODIGO
                        AND R.ESTADO IN ('I','A') 
                        AND R.INMUEBLE = '$inmueble'
                        AND R.FECHA_PAGO BETWEEN (
                        SELECT SYSDATE -30 FROM DUAL)
                        AND (SELECT SYSDATE + 1 FROM DUAL)
						  $sort
						)a WHERE  ROWNUM<=1000
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
	
	 public function ReversaRecaudo($cod_rec, $observacion, $coduser){
       	$sql="BEGIN SGC_P_REVERSA_REC('$cod_rec','$observacion','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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