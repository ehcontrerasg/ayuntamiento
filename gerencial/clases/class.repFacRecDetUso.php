<?php
include_once 'class.conexion.php';
class ReportesFacRecDetUso extends ConexionClass{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function FacturacionUsoAguaEste($proyecto, $periodo){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAguaEsteSolar($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAguaEsteZFC($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAguaEsteZFI($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcEste($proyecto, $periodo){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcEsteSolar($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD  IN (84,83)
		AND I.ZONA_FRANCA = 'N'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcEsteZFC($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcEsteZFI($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	//FACTURACION AGUA ALC NORTE
	
	public function FacturacionUsoAguaNorte($proyecto, $periodo){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAguaNorteSolar($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAguaNorteZFC($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAguaNorteZFI($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcNorte($proyecto, $periodo){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO";
		$resultado = oci_parse($this->_db,$sql);
		
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcNorteSolar($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD  IN (84,83)
		AND I.ZONA_FRANCA = 'N'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcNorteZFC($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturacionUsoAlcNorteZFI($proyecto, $periodo){
		$sql = "SELECT  SUM(DF.VALOR_ORI)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	///RECAUDACION POR GERENCIA Y USO
	
	public function RecaudoUsoAguaEste($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+) 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO (+) IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
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
	
	public function RecaudoUsoAguaEsteSolar($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO (+) IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
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
	
	public function RecaudoUsoAguaEsteZFC($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO (+) IN (1,3)
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
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
	
	public function RecaudoUsoAguaEsteZFI($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO (+) IN (1,3)
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
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
	
	
	public function RecaudoUsoAlcEste($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO (+) IN (2,4)
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
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
	
	public function RecaudoUsoAlcEsteSolar($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO (+) IN (2,4)
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
	
	public function RecaudoUsoAlcEsteZFC($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO (+)IN (2,4)
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
	
	public function RecaudoUsoAlcEsteZFI($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO (+) IN (2,4)
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
	
	///RECAUDO AGUA -ALCANTARILLADO NORTE
	
	public function RecaudoUsoAguaNorte($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO (+) IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
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
	
	public function RecaudoUsoAguaNorteSolar($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO (+) IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
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
	
	public function RecaudoUsoAguaNorteZFC($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO (+) IN (1,3)
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
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
	
	public function RecaudoUsoAguaNorteZFI($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO (+) IN (1,3)
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
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
	
	
	public function RecaudoUsoAlcNorte($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO (+) IN (2,4)
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
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
	
	public function RecaudoUsoAlcNorteSolar($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO (+) IN (2,4)
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
	
	public function RecaudoUsoAlcNorteZFC($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO (+) IN (2,4)
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
	
	public function RecaudoUsoAlcNorteZFI($proyecto, $ano, $mes, $dia){
		$sql = "SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO (+)
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO (+) IN (2,4)
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
	
	
	///CONSULTAS REPORTE CONCEPTO OFICIALES DETALLADO POR USO
	
	public function grupoConceptos(){
		$sql = "SELECT GR.ID_GRUPO, GR.DES_GRUPO
		FROM SGC_TP_SERVICIOS S, SGC_TP_GRUPOS_REPORT GR
		WHERE S.GRUPO = GR.ID_GRUPO
		AND S.REPORTE_GER IN ('S')
		GROUP BY GR.ID_GRUPO, GR.DES_GRUPO
		ORDER BY 1";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturadoConceptoOficialesTotal($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
        FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
        WHERE I.CODIGO_INM = F.INMUEBLE 
        AND GR.ID_GRUPO(+) = S.GRUPO
        AND F.CONSEC_FACTURA = D.FACTURA
        AND D.CONCEPTO = S.COD_SERVICIO
        AND I.ID_SECTOR = SE.ID_SECTOR
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
        AND F.PERIODO = $periodo
        AND A.ID_USO = 'O'
        AND GR.ID_GRUPO = $id_grupo
        --AND SE.ID_GERENCIA IN ('N','E')
        AND I.ID_PROYECTO = '$proyecto'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function RecaudadoConceptoOficialesTotal($proyecto, $ano, $mes, $dia, $id_grupo){
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
            SELECT SUM(D.PAGADO)RECAUDADO
            FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
            WHERE I.CODIGO_INM = P.INM_CODIGO
            AND GR.ID_GRUPO(+) = S.GRUPO
            AND P.ID_PAGO = D.PAGO(+)
            AND D.CONCEPTO = S.COD_SERVICIO(+)
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ESTADO NOT IN 'I'
            AND GR.ID_GRUPO = $id_grupo
            AND I.ID_PROYECTO = '$proyecto'
            AND A.ID_USO = 'O'
            --AND SE.ID_GERENCIA IN ('N','E')
            AND P.ID_CAJA NOT IN (463,391)
            UNION
            SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
            WHERE I.CODIGO_INM = O.INMUEBLE
            AND GR.ID_GRUPO(+) = S.GRUPO
            AND O.CONCEPTO = S.COD_SERVICIO(+)
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND O.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND O.ESTADO NOT IN 'I'
            AND GR.ID_GRUPO = $id_grupo
            AND I.ID_PROYECTO = '$proyecto'
            AND A.ID_USO = 'O'
            --AND SE.ID_GERENCIA IN ('N','E')
            AND O.CAJA  NOT IN (463,391))";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function FacturadoConceptoOficialesNorte($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
        FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
        WHERE I.CODIGO_INM = F.INMUEBLE 
        AND GR.ID_GRUPO(+) = S.GRUPO
        AND F.CONSEC_FACTURA = D.FACTURA
        AND D.CONCEPTO = S.COD_SERVICIO
        AND I.ID_SECTOR = SE.ID_SECTOR
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
        AND F.PERIODO = $periodo
        AND A.ID_USO = 'O'
        AND GR.ID_GRUPO = $id_grupo
        AND SE.ID_GERENCIA IN ('N')
        AND I.ID_PROYECTO = '$proyecto'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function RecaudadoConceptoOficialesNorte($proyecto, $ano, $mes, $dia, $id_grupo){
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
            SELECT SUM(D.PAGADO)RECAUDADO
            FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
            WHERE I.CODIGO_INM = P.INM_CODIGO
            AND GR.ID_GRUPO(+) = S.GRUPO
            AND P.ID_PAGO = D.PAGO(+)
            AND D.CONCEPTO = S.COD_SERVICIO(+)
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ESTADO NOT IN 'I'
            AND GR.ID_GRUPO = $id_grupo
            AND I.ID_PROYECTO = '$proyecto'
            AND A.ID_USO = 'O'
            AND SE.ID_GERENCIA IN ('N')
            AND P.ID_CAJA NOT IN (463,391)
            UNION
            SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
            WHERE I.CODIGO_INM = O.INMUEBLE
            AND GR.ID_GRUPO(+) = S.GRUPO
            AND O.CONCEPTO = S.COD_SERVICIO(+)
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND O.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND O.ESTADO NOT IN 'I'
            AND GR.ID_GRUPO = $id_grupo
            AND I.ID_PROYECTO = '$proyecto'
            AND A.ID_USO = 'O'
            AND SE.ID_GERENCIA IN ('N')
            AND O.CAJA  NOT IN (463,391))";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}

	public function FacturadoConceptoOficialesEste($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
        FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
        WHERE I.CODIGO_INM = F.INMUEBLE 
        AND GR.ID_GRUPO(+) = S.GRUPO
        AND F.CONSEC_FACTURA = D.FACTURA
        AND D.CONCEPTO = S.COD_SERVICIO
        AND I.ID_SECTOR = SE.ID_SECTOR
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
        AND F.PERIODO = $periodo
        AND A.ID_USO = 'O'
        AND GR.ID_GRUPO = $id_grupo
        AND SE.ID_GERENCIA IN ('E')
        AND I.ID_PROYECTO = '$proyecto'";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	public function RecaudadoConceptoOficialesEste($proyecto, $ano, $mes, $dia, $id_grupo){
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
            SELECT SUM(D.PAGADO)RECAUDADO
            FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
            WHERE I.CODIGO_INM = P.INM_CODIGO
            AND GR.ID_GRUPO(+) = S.GRUPO
            AND P.ID_PAGO = D.PAGO(+)
            AND D.CONCEPTO = S.COD_SERVICIO(+)
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ESTADO NOT IN 'I'
            AND GR.ID_GRUPO = $id_grupo
            AND I.ID_PROYECTO = '$proyecto'
            AND A.ID_USO = 'O'
            AND SE.ID_GERENCIA IN ('E')
            AND P.ID_CAJA NOT IN (463,391)
            UNION
            SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
            WHERE I.CODIGO_INM = O.INMUEBLE
            AND GR.ID_GRUPO(+) = S.GRUPO
            AND O.CONCEPTO = S.COD_SERVICIO(+)
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND O.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND O.ESTADO NOT IN 'I'
            AND GR.ID_GRUPO = $id_grupo
            AND I.ID_PROYECTO = '$proyecto'
            AND A.ID_USO = 'O'
            AND SE.ID_GERENCIA IN ('E')
            AND O.CAJA  NOT IN (463,391))";
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE){
			oci_close($this->_db);
			return $resultado;
		}
		else{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
}
?>