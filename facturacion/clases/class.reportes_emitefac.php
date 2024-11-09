<?php
include_once '../../clases/class.conexion.php';
class ReportesEmiteFac extends ConexionClass{
	private $id_proyecto;
	private $id_zonini;
	private $id_perini;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_zonini="";
		$this->id_perini="";
	}

	public function DatosArchivo($perini,$zonini,$proyecto,$tipo,$usoini){

	    $sql = "SELECT I.ID_PROCESO,
       I.CODIGO_INM, 
       I.CATASTRO, 
       C.ALIAS, 
       I.DIRECCION, 
       U.DESC_URBANIZACION, 
       F.CONSEC_FACTURA, 
       I.ID_ZONA, 
       TO_CHAR(F.FEC_EXPEDICION, 'DD/MM/YYYY') FECEXP, 
       F.PERIODO, 
       NVL(MD.DESC_MED, 'Sin Medidor') MEDIDOR, 
       NVL(CA.DESC_CALIBRE, 0) CALIBRE, 
       M.SERIAL, 
       (SELECT TO_CHAR(FECHA_LECTURA_ORI, 'DD/MM/YYYY') 
          FROM SGC_TT_REGISTRO_LECTURAS R1 
         WHERE R1.COD_INMUEBLE = R.COD_INMUEBLE 
           AND TO_DATE(R1.PERIODO, 'YYYYMM') = ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), -1)) FECANT, 
       (SELECT LECTURA_ACTUAL 
          FROM SGC_TT_REGISTRO_LECTURAS R1 
         WHERE R1.COD_INMUEBLE = R.COD_INMUEBLE 
           AND TO_DATE(R1.PERIODO, 'YYYYMM') = ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), -1)) LECANT, 
       TO_CHAR(FECHA_LECTURA_ORI, 'DD/MM/YYYY') FECACT, 
       LECTURA_ACTUAL LECACT, 
       NVL(CONSUMO, 0) CONSUMO, 
       (SELECT SI.CONSUMO_MINIMO 
          FROM SGC_TT_SERVICIOS_INMUEBLES SI, 
               SGC_TT_DETALLE_FACTURA D 
         WHERE SI.COD_INMUEBLE = F.INMUEBLE 
           AND D.CONCEPTO = SI.COD_SERVICIO 
           AND D.PERIODO = F.PERIODO 
           AND F.CONSEC_FACTURA = D.FACTURA 
           AND I.CODIGO_INM = F.INMUEBLE 
           AND I.CODIGO_INM = SI.COD_INMUEBLE 
           AND SI.COD_INMUEBLE = F.INMUEBLE 
           AND (SI.COD_SERVICIO = 1 
                 OR SI.COD_SERVICIO = 3) 
           AND D.RANGO = 0) CONSUMO_MIN, 
       DECODE(M.COD_MEDIDOR, '', 'Promedio', 'Diferencia Lectura') METODO, 
       NVL((SELECT SUM(SF.IMPORTE) 
               FROM SGC_TT_SALDO_FAVOR SF 
              WHERE SF.ESTADO IN('A') 
                AND SF.INM_CODIGO = F.INMUEBLE), 
       0) + NVL((SELECT SUM(O.IMPORTE - O.APLICADO) 
               FROM SGC_TT_OTROS_RECAUDOS O 
              WHERE O.ESTADO IN('A') 
                AND O.INMUEBLE = F.INMUEBLE), 
       0) SALDOFAVOR, 
       NVL((SELECT ROUND(SUM(DIF.VALOR_DIFERIDO) - SUM(DIF.VALOR_PAGADO)) 
               FROM SGC_TT_DIFERIDOS DIF 
              WHERE DIF.ACTIVO = 'S' 
                AND DIF.INMUEBLE = F.INMUEBLE), 
       0) DIFERIDO, 
       (SELECT ROUND(SUM(VALOR)) 
          FROM SGC_TT_DETALLE_FACTURA D 
         WHERE PERIODO = $perini + UID * 0 
           AND (CONCEPTO = 1 
                 OR CONCEPTO = 2 
                 OR CONCEPTO = 3 
                 OR CONCEPTO = 4) 
           AND D.COD_INMUEBLE = F.INMUEBLE 
           AND D.PERIODO = F.PERIODO 
           AND D.FACTURA = F.CONSEC_FACTURA) VALORCON, 
       (SELECT ROUND(SUM(VALOR)) 
          FROM SGC_TT_DETALLE_FACTURA D 
         WHERE D.PERIODO = $perini 
           AND CONCEPTO <> 1 
           AND CONCEPTO <> 2 
           AND CONCEPTO <> 3 
           AND CONCEPTO <> 4 
           AND D.COD_INMUEBLE = F.INMUEBLE 
           AND D.FACTURA = F.CONSEC_FACTURA 
           AND D.PERIODO = F.PERIODO) VALOROTROS, 
       NVL((SELECT SUM(FA.TOTAL) 
          FROM SGC_TT_FACTURA FA 
         WHERE FA.INMUEBLE = F.INMUEBLE 
           AND FA.FACTURA_PAGADA = 'N' 
           AND FA.PERIODO <> $perini),0) DEUDA, 
       (SELECT COUNT(FA.FACTURA_PAGADA) 
          FROM SGC_TT_FACTURA FA 
         WHERE FA.INMUEBLE = F.INMUEBLE 
           AND FA.FACTURA_PAGADA = 'N' 
           AND FA.PERIODO <> $perini) FACPEND, 
       F.TOTAL, 
       TO_CHAR(F.FEC_VCTO, 'DD/MM/YYYY') FECVCTO, 
       I.CODIGO_INM CODBARRA, 
       CU.DESC_USO, 
       CONCAT(N.ID_NCF, F.NCF_CONSEC) NCF, 
       NU.DESCRIPCION NCF_MSJ, 
       CASE CL.DOCUMENTO WHEN '9999999' THEN '' 
                         WHEN CL.DOCUMENTO THEN REPLACE(CL.DOCUMENTO,'-','') END AS RNC, 
       TO_CHAR(F.FECHA_CORTE, 'DD/MM/YYYY') FECCORTE, 
       (SELECT SUM(D.VALOR) 
          FROM SGC_TT_DETALLE_FACTURA D 
         WHERE D.COD_INMUEBLE = F.INMUEBLE 
           AND D.PAGADO = 'N' 
           AND D.CONCEPTO = 10 
           AND PERIODO <> $perini) MORA, 
       I.ID_ESTADO, 
       (SELECT COUNT(FA.FACTURA_PAGADA) 
          FROM SGC_TT_FACTURA FA 
         WHERE FA.INMUEBLE = F.INMUEBLE 
           AND FA.PERIODO <> $perini) FACGEN, 
       (SELECT SUM(D.VALOR) 
          FROM SGC_TT_DETALLE_FACTURA D 
         WHERE D.COD_INMUEBLE = F.INMUEBLE 
           AND D.FACTURA = F.CONSEC_FACTURA 
           AND D.PERIODO = F.PERIODO 
           AND D.PAGADO = 'N' 
           AND D.CONCEPTO = 10 
           AND F.PERIODO = $perini) MORA_PERIODO, 
       F.MSJ_PERIODO, 
       F.MSJ_ALERTA, 
       F.MSJ_BURO, 
       F.MSJ_FACTURA,
       TO_CHAR(F.VENCIMIENTO_NCF,'DD/MM/YYYY') FECVENCE,
       TD.DESCRIPCION_TIPO_DOC TIPO_DOC,
        
       (SELECT SI.CUPO_BASICO
        FROM SGC_TT_SERVICIOS_INMUEBLES SI,
             SGC_TT_DETALLE_FACTURA D
        WHERE SI.COD_INMUEBLE = F.INMUEBLE
          AND D.CONCEPTO = SI.COD_SERVICIO
          AND D.PERIODO = F.PERIODO
          AND F.CONSEC_FACTURA = D.FACTURA
          AND I.CODIGO_INM = F.INMUEBLE
          AND I.CODIGO_INM = SI.COD_INMUEBLE
          AND SI.COD_INMUEBLE = F.INMUEBLE
          AND (SI.COD_SERVICIO = 1
            OR SI.COD_SERVICIO = 3)
          AND D.RANGO = 0) CUPO_BASICO,

       (SELECT TAR.CATEGORIA
        FROM SGC_TT_SERVICIOS_INMUEBLES SI,
             SGC_TT_DETALLE_FACTURA D,
             SGC_TP_TARIFAS TAR
        WHERE SI.COD_INMUEBLE = F.INMUEBLE
          AND D.CONCEPTO = SI.COD_SERVICIO
          AND D.PERIODO = F.PERIODO
          AND F.CONSEC_FACTURA = D.FACTURA
          AND I.CODIGO_INM = F.INMUEBLE
          AND I.CODIGO_INM = SI.COD_INMUEBLE
          AND SI.COD_INMUEBLE = F.INMUEBLE
          AND (SI.COD_SERVICIO = 1
            OR SI.COD_SERVICIO = 3
              )
          AND D.RANGO = 0
           AND TAR.CONSEC_TARIFA=SI.CONSEC_TARIFA
           ) ESTRATO,
       (SELECT PAG.IMPORTE FROM SGC_TT_PAGOS PAG
         WHERE PAG.INM_CODIGO=F.INMUEBLE AND
            PAG.ID_PAGO=(
                SELECT MAX(PAG2.ID_PAGO) FROM SGC_TT_PAGOS PAG2
                WHERE PAG2.INM_CODIGO=PAG.INM_CODIGO
                )
           ) VALOR_ULTIMO_PAGO,
       
        DESC_SERVICIO,
       (SELECT PAG.FECHA_PAGO FROM SGC_TT_PAGOS PAG
        WHERE PAG.INM_CODIGO=F.INMUEBLE AND
                PAG.ID_PAGO=(
                SELECT MAX(PAG2.ID_PAGO) FROM SGC_TT_PAGOS PAG2
                WHERE PAG2.INM_CODIGO=PAG.INM_CODIGO
            )
       ) FECHA_ULTIMO_PAGO


  FROM SGC_TT_INMUEBLES I, 
       SGC_TT_FACTURA F, 
       SGC_TT_CONTRATOS C, 
       SGC_TT_CLIENTES CL, 
       SGC_TT_MEDIDOR_INMUEBLE M, 
       SGC_TT_REGISTRO_LECTURAS R, 
       SGC_TP_URBANIZACIONES U, 
       SGC_TP_MEDIDORES MD, 
       SGC_TP_CALIBRES CA, 
       SGC_TP_ACTIVIDADES A, 
       SGC_TP_USOS CU, 
       SGC_TP_NCF_USOS N, 
       SGC_TP_NCF NU,
       SGC_TP_TIPODOC TD,
       SGC_TT_SERVICIOS_INMUEBLES SI,
       SGC_TP_SERVICIOS SER 
 WHERE I.CODIGO_INM = F.INMUEBLE 
   AND C.CODIGO_INM (+) = I.CODIGO_INM 
   AND M.COD_INMUEBLE (+) = F.INMUEBLE 
   AND R.COD_INMUEBLE (+) = F.INMUEBLE 
   AND N.ID_NCF = NU.ID_NCF 
   AND C.CODIGO_CLI = CL.CODIGO_CLI (+) 
   AND R.PERIODO (+) = F.PERIODO 
   AND I.CONSEC_URB = U.CONSEC_URB 
   AND MD.CODIGO_MED (+) = M.COD_MEDIDOR 
   AND CA.COD_CALIBRE (+) = M.COD_CALIBRE 
   AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD 
   AND F.NCF_ID = N.ID_NCF_USO 
   AND CU.ID_USO = A.ID_USO 
   AND F.PERIODO = $perini 
   AND M.FECHA_BAJA(+) IS NULL
   AND I.ID_ZONA = UPPER('$zonini') 
   AND I.ID_PROYECTO = '$proyecto'
   AND TD.ID_TIPO_DOC = F.TIPO_DOCUMENTO 
   AND SI.COD_INMUEBLE=F.INMUEBLE
   AND SI.COD_SERVICIO=SER.COD_SERVICIO
   AND SI.COD_SERVICIO IN (1,3) ";
        if($usoini<>''){
           $sql .= "AND A.ID_USO = '$usoini'";
       }
       $sql .= "AND C.FECHA_FIN IS NULL  
                ORDER BY I.ID_PROCESO";
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

	public function DatosServicios($inmueble){
		$sql = "SELECT S.DESC_SERVICIO, T.CODIGO_TARIFA, SI.UNIDADES_HAB, T.CONSEC_TARIFA
        FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_SERVICIOS S, SGC_TP_TARIFAS T
        WHERE SI.COD_SERVICIO = S.COD_SERVICIO 
        AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND SI.COD_SERVICIO IN ('1','2','3','4')
        AND SI.COD_INMUEBLE = '$inmueble'";
		/*"SELECT S.DESC_SERVICIO, T.CODIGO_TARIFA, SI.UNIDADES_HAB, T.CONSEC_TARIFA, RT.RANGO, 
		DECODE(RT.RANGO,'1','Consumo Adicional 1','2','Consumo Adicional 2','3','Consumo Adicional 3','4','Consumo Adicional 4','5','Consumo Adicional 5')DESC_RANGO, 
		RT.VALOR_METRO, RT.LIMITE_MAX
        FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_SERVICIOS S, SGC_TP_TARIFAS T, SGC_TP_RANGOS_TARIFAS RT
        WHERE SI.COD_SERVICIO = S.COD_SERVICIO 
        AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND RT.CONSEC_TARIFA = SI.CONSEC_TARIFA
		AND SI.COD_SERVICIO IN ('1','2','3','4')
        AND SI.COD_INMUEBLE = '$inmueble'";*/
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


	public function DatosRangosConceptos($inmueble,$perini){
		$sql = "SELECT /*+ INDEX_JOIN(D) */ CONCEPTO, 
       S.DESC_SERVICIO, 
       RANGO, 
       DECODE(RANGO, '0', 'Consumo Basico', '1', 'Consumo Adicional 1', '2', 'Consumo Adicional 2', '3', 'Consumo Adicional 3', '4', 'Consumo Adicional 4', '5', 'Consumo Adicional 5') DESCRANGO, 
       UNIDADES, 
       ROUND(VALOR / DECODE(UNIDADES, 0, 1, UNIDADES), 2) PRECIO, 
       VALOR 
		  FROM SGC_TT_DETALLE_FACTURA D, 
			   SGC_TP_SERVICIOS S 
		 WHERE S.COD_SERVICIO = D.CONCEPTO + 0 
		   AND D.COD_INMUEBLE = '$inmueble' 
		   AND D.PERIODO = '$perini' 
		   AND D.CONCEPTO IN ('1', '2', '3', '4') 
		 ORDER BY CONCEPTO, RANGO
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

	public function DatosRangosOtrosConceptos($inmueble,$perini){
		$sql = "SELECT CONCEPTO, S.DESC_SERVICIO, ROUND(VALOR,2)VALOR
		        FROM SGC_TT_DETALLE_FACTURA D, SGC_TP_SERVICIOS S
		        WHERE D.CONCEPTO = S.COD_SERVICIO
		          AND D.COD_INMUEBLE = '$inmueble'
		          AND D.PERIODO = '$perini'
		          AND D.CONCEPTO NOT IN ('1','2','3','4')
		        ORDER BY CONCEPTO, RANGO";
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

	
	/*public function DatosConceptos($inmueble, $tarifa){
		$sql = "SELECT S.DESC_SERVICIO, RT.RANGO, DECODE(RT.RANGO,'1','Consumo Adicional 1','2','Consumo Adicional 2','3','Consumo Adicional 3','4','Consumo Adicional 4','5',
        'Consumo Adicional 5')DESC_RANGO, RT.VALOR_METRO, NVL(RT.LIMITE_MAX,10000000) LIMITE_MAX
        FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_SERVICIOS S, SGC_TP_TARIFAS T, SGC_TP_RANGOS_TARIFAS RT
        WHERE SI.COD_SERVICIO = S.COD_SERVICIO 
        AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND RT.CONSEC_TARIFA = SI.CONSEC_TARIFA
        AND SI.COD_INMUEBLE = '$inmueble' AND SI.CONSEC_TARIFA = '$tarifa'
        ORDER BY RT.RANGO";
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
	}*/
}
	
