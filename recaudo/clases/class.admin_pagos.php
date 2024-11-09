<?php
include_once '../../clases/class.conexion.php';
class AdminstraPagos extends ConexionClass{

	private $codresult;
    private $msgresult;
	
	public function __construct(){
		parent::__construct();
	}
	public function getcodresult(){
    	return $this->codresult;
    }
					
    public function getmsgresult(){
    	return $this->msgresult;
    }

	public function obtienePagosInmueble($cod_inmueble){
		$sql = "SELECT 'pago' REC,P.OBSERVACION, P.ID_PAGO, TO_CHAR(P.FECHA_PAGO,'YYYY-MM-DD')FECHA_PAGO, TO_CHAR(P.FECHA_REGISTRO,'YYYY-MM-DD')FECHA_REGISTRO,
        PP.ENTIDAD_COD ENTIDAD_VIEJO, NVL(PP.COD_VIEJO,PP.ID_PUNTO_PAGO) PUNTO_VIEJO, C.NUM_CAJA, M.ID_FORM_PAGO, C.ID_CAJA, 'P' TIPO
        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TT_MEDIOS_PAGO M, SGC_TP_PUNTO_PAGO PP
        WHERE P.ID_CAJA = C.ID_CAJA
        AND PP.ID_PUNTO_PAGO=C.ID_PUNTO_PAGO
        AND P.ID_PAGO = M.ID_PAGO
        AND P.INM_CODIGO = '$cod_inmueble'
		AND P.ESTADO NOT IN ('I')
        UNION
        SELECT 'recaudo' REC,P.DESCRIPCION OBSERVACION, P.CODIGO ID_PAGO, TO_CHAR(P.FECHA_PAGO,'YYYY-MM-DD')FECHA_PAGO, TO_CHAR(P.FECHA_PAGO,'YYYY-MM-DD')FECHA_REGISTRO,
        PP.ENTIDAD_COD ENTIDAD_VIEJO, NVL(PP.COD_VIEJO,PP.ID_PUNTO_PAGO) PUNTO_VIEJO, C.NUM_CAJA, M.ID_FORM_PAGO, C.ID_CAJA, 'O' TIPO
        FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TT_MEDIOS_RECAUDO M, SGC_TP_PUNTO_PAGO PP
        WHERE P.CAJA = C.ID_CAJA
        AND PP.ID_PUNTO_PAGO=C.ID_PUNTO_PAGO
        AND P.CODIGO = M.ID_OTRREC
        AND P.INMUEBLE = '$cod_inmueble'
		AND P.ESTADO NOT IN ('I')
        ORDER BY FECHA_PAGO DESC";
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
	
	public function corrigePagos($id_pago, $tname, $field, $data){
	    if ($tname == 'SGC_TT_PAGOS') {
            if (strtoupper(trim($field)) == trim('OBSERVACION') || strtoupper(trim($field)) == trim('ID_CAJA')) {
                $sql = "UPDATE $tname SET $field ='$data' WHERE ID_PAGO = $id_pago";
            } else {
                $sql = "UPDATE $tname SET $field = TO_DATE('$data','YYYY-MM-DD') WHERE ID_PAGO = $id_pago";
            }
        }
        if ($tname == 'SGC_TT_OTROS_RECAUDOS') {
	        if($field == 'id_caja') $field = 'caja';
            if($field == 'fecha_registro') $field = 'fecha';
            if($field == 'observacion') $field = 'DESCRIPCION';
            if (strtoupper(trim($field)) == trim('DESCRIPCION') || strtoupper(trim($field)) == trim('CAJA')) {
                $sql = "UPDATE $tname SET $field ='$data' WHERE CODIGO = $id_pago";
            } else {
                $sql = "UPDATE $tname SET $field = TO_DATE('$data','YYYY-MM-DD') WHERE CODIGO = $id_pago";
            }
        }

		 $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}
	
	}
	
	public function listadoCajas(){
		$sql = "SELECT  ID_CAJA, EP.COD_ENTIDAD ID_ENTIDAD, DESC_ENTIDAD, COD_VIEJO, PP.DESCRIPCION DESC_PUNTO, CP.DESCRIPCION DESC_CAJA
		FROM SGC_TP_ENTIDAD_PAGO EP, SGC_TP_PUNTO_PAGO PP, SGC_TP_CAJAS_PAGO CP
		WHERE EP.COD_ENTIDAD = PP.ENTIDAD_COD
		AND PP.ID_PUNTO_PAGO = CP.ID_PUNTO_PAGO
		AND PP.ACTIVO = 'S'
		AND CP.DESCRIPCION LIKE '%CAJA%'
		AND EP.COD_ENTIDAD NOT IN(100,102,600,904,911,921,950,990,999)
		AND PP.ID_PUNTO_PAGO NOT IN (601,604,606)
		--AND ID_USUARIO IS NOT NULL
		ORDER BY COD_ENTIDAD, PP.COD_VIEJO, CP.NUM_CAJA";
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
	
	public function listadoCajeras($tname,$where, $sort){
		$sql = "SELECT EP.COD_ENTIDAD, PP.ID_PUNTO_PAGO, PP.COD_VIEJO, PP.DESCRIPCION, CP.ID_CAJA, CP.NUM_CAJA,(CASE WHEN CAR.ID_AREA=4 THEN 'RECAUDO' WHEN  CAR.ID_AREA=9 THEN 'SERVICIO' ELSE '' END) AREA, U.ID_USUARIO, U.NOM_USR||' '||U.APE_USR NOMBRE
		FROM $tname
		WHERE $where
		$sort";
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
	
	public function listadoCajasAcea($tname,$where, $sort){
		$sql = "SELECT EP.COD_ENTIDAD, PP.ID_PUNTO_PAGO, PP.COD_VIEJO, PP.DESCRIPCION, CP.ID_CAJA, CP.NUM_CAJA,(CASE WHEN CAR.ID_AREA=4 THEN 'RECAUDO' WHEN  CAR.ID_AREA=9 THEN 'SERVICIO' ELSE '' END) AREA, U.ID_USUARIO, U.NOM_USR||' '||U.APE_USR NOMBRE
		FROM $tname
		WHERE $where
		$sort";
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
	
	public function CantidadCajeras($fname, $tname,$where,$sort){
		$sql = "SELECT COUNT($fname) CANTIDAD
						FROM $tname
							WHERE $where
						  $sort";
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
         //   echo "false";
            return false;
        }

    }
	
	public function ActualizaCajeras($cajai, $usuarioi, $cajaf, $usuariof){
       	$sql="BEGIN SGC_P_ACTUALIZA_CAJERA_ASIG('$cajai','$usuarioi','$cajaf','$usuariof',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

	public function listadoCajerasParaIntercambio(){
		$sql = "SELECT *
		FROM (SELECT PP.ENTIDAD_COD COD_ENTIDAD,
					 CP.ID_PUNTO_PAGO,
					 PP.COD_VIEJO,
					 PP.DESCRIPCION,
					 CP.ID_CAJA,
					 CP.NUM_CAJA,
					 (SELECT DECODE(C.ID_AREA, 4, 'RECAUDO', 9, 'ATENCION')
						FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C
					   WHERE U.ID_USUARIO = CP.ID_USUARIO
						 AND C.ID_CARGO = U.ID_CARGO
						 AND C.ID_AREA IN (4, 9)) AREA,
					 (SELECT U.ID_USUARIO
						FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C
					   WHERE U.ID_USUARIO = CP.ID_USUARIO
						 AND C.ID_CARGO = U.ID_CARGO
						 AND C.ID_AREA IN (4, 9)) ID_USUARIO,
					 (SELECT U.NOM_USR || ' ' || U.APE_USR
						FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C
					   WHERE U.ID_USUARIO = CP.ID_USUARIO
						 AND C.ID_CARGO = U.ID_CARGO
						 AND C.ID_AREA IN (4, 9)) NOMBRE,
					 CP.TIPO_ATENCION
				FROM SGC_TP_CAJAS_PAGO CP, SGC_TP_PUNTO_PAGO PP
			   where (PP.ID_PUNTO_PAGO = CP.ID_PUNTO_PAGO AND PP.ACTIVO = 'S' AND
					 CP.TIPO_ATENCION IN ('A', 'C'))
			  UNION
			  SELECT NULL COD_ENTIDAD,
					 NULL ID_PUNTO_PAGO,
					 NULL COD_VIEJO,
					 NULL DESCRIPCION,
					 NULL ID_CAJA,
					 NULL NUM_CAJA,
					 DECODE(C.ID_AREA, 4, 'RECAUDO', 9, 'ATENCION') AREA,
					 U.ID_USUARIO,
					 (U.NOM_USR || ' ' || U.APE_USR) NOMBRE_USUARIO,
					 NULL
				FROM SGC_TP_CAJAS_PAGO CP, SGC_TT_USUARIOS U, SGC_TP_CARGOS C
			   WHERE U.ID_USUARIO = CP.ID_USUARIO(+)
				 AND U.FEC_FIN IS NULL
				 AND C.ID_CARGO = U.ID_CARGO
				 AND C.ID_AREA IN (4, 9)
				 AND CP.ID_CAJA IS NULL)
	   ORDER BY COD_ENTIDAD, ID_PUNTO_PAGO, NUM_CAJA ASC";

	   $statement = oci_parse($this->_db, $sql);
	   $executed = oci_execute($statement);

	   if($executed){
			oci_close($this->_db);
			return $statement;
	   }else{
			oci_close($this->_db);		   
			$error = oci_error($statement);
			echo json_encode(array('codigo'=> $error['code'], 'mensaje'=> $error['message'] .'. SqlText: '. $error['sqlText']));
	   }
	}	
	
}
