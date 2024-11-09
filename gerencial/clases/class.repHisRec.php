<?php
ini_set('memory_limit', '-1');
//set_time_limit(3600);
include_once '../../clases/class.conexion.php';
class ReportesHisRec extends ConexionClass{

	public function __construct()
	{
		parent::__construct();
	}

    public function getcodresult(){
        return $this->codresult;
    }

    public function getmsgresult(){
        return $this->msgresult;
    }

    public function verificaCierreZonas($proyecto, $periodo){
        $sql = "SELECT COUNT(*)CANTIDAD
                FROM SGC_TP_PERIODO_ZONA PZ
                WHERE PZ.PERIODO = $periodo
                        AND PZ.FEC_CIERRE IS NULL
                        AND PZ.CODIGO_PROYECTO = '$proyecto'";
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
	//CONSULTAS HISTORICO DE RECAUDO POR SECTOR
	
	public function historicoRecaudoSectorTotal($proyecto, $fecini, $fecfin){
		 $sql = "SELECT SECTOR, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND ID_CAJA NOT IN (463,391) 
			GROUP BY SUBSTR(I.ID_ZONA,0,2)
			UNION
			SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.ID_SECTOR = S.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND CAJA NOT IN (463,391)
			GROUP BY SUBSTR(I.ID_ZONA,0,2)
        )
        GROUP BY SECTOR
        ORDER BY SECTOR";
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
	
	public function historicoRecaudoSectorNorte($proyecto, $fecini, $fecfin){
		$sql = "SELECT SECTOR, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND S.ID_GERENCIA = 'N'
			AND I.ID_PROYECTO = '$proyecto'
			AND ID_CAJA NOT IN (463,391) 
			GROUP BY SUBSTR(I.ID_ZONA,0,2)
			UNION
			SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.ID_SECTOR = S.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND S.ID_GERENCIA = 'N'
			AND I.ID_PROYECTO = '$proyecto'
			AND CAJA NOT IN (463,391)
			GROUP BY SUBSTR(I.ID_ZONA,0,2)
        )
        GROUP BY SECTOR
        ORDER BY SECTOR";
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
	
	public function historicoRecaudoSectorEste($proyecto, $fecini, $fecfin){
		$sql = "SELECT SECTOR, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND S.ID_GERENCIA = 'E'
			AND I.ID_PROYECTO = '$proyecto'
			AND ID_CAJA NOT IN (463,391) 
			GROUP BY SUBSTR(I.ID_ZONA,0,2)
			UNION
			SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.ID_SECTOR = S.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND S.ID_GERENCIA = 'E'
			AND I.ID_PROYECTO = '$proyecto'
			AND CAJA NOT IN (463,391)
			GROUP BY SUBSTR(I.ID_ZONA,0,2)
        )
        GROUP BY SECTOR
        ORDER BY SECTOR";
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
	
	//CONSULTAS HISTORICO DE RECAUDO POR CONCEPTO
	
	public function grupoConceptos(){
		$sql = "SELECT GR.ID_GRUPO, GR.DES_GRUPO
		FROM SGC_TP_SERVICIOS S, SGC_TP_GRUPOS_REPORT GR
		WHERE S.GRUPO = GR.ID_GRUPO
		AND S.REPORTE_GER IN ('S')
		GROUP BY GR.ID_GRUPO, GR.DES_GRUPO
		ORDER BY 1";
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
	
	public function historicoRecaudoConceptoTotal($proyecto, $fecini, $fecfin, $id_grupo){
		
		$sql="SELECT  NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT NVL(SUM(D.PAGADO),0)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND GR.ID_GRUPO = S.GRUPO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND GR.ID_GRUPO = $id_grupo
			UNION
			SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND GR.ID_GRUPO = S.GRUPO
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA  NOT IN (463,391)
			AND GR.ID_GRUPO = $id_grupo)";

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
	
	public function historicoRecaudoSaldofavorTotal($proyecto, $fecini, $fecfin){
		
		$sql="SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_SALDO_FAVOR O, SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
            WHERE I.CODIGO_INM = O.INM_CODIGO 
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.ID_PAGO = O.CODIGO_PAG
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ID_CAJA NOT IN (463,391)
            AND P.ESTADO NOT IN 'I'
            AND I.ID_PROYECTO = '$proyecto'";

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
	
	public function historicoRecaudoConceptoTotalN($proyecto, $fecini, $fecfin, $id_grupo){
		
		$sql="SELECT  NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT NVL(SUM(D.PAGADO),0)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND GR.ID_GRUPO = S.GRUPO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND SE.ID_GERENCIA = 'N'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND GR.ID_GRUPO = $id_grupo
			UNION
			SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND GR.ID_GRUPO = S.GRUPO
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND SE.ID_GERENCIA = 'N'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA  NOT IN (463,391)
			AND GR.ID_GRUPO = $id_grupo)";

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
	
	public function historicoRecaudoSaldofavorTotalN($proyecto, $fecini, $fecfin){
		
		$sql="SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_SALDO_FAVOR O, SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
            WHERE I.CODIGO_INM = O.INM_CODIGO 
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.ID_PAGO = O.CODIGO_PAG
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ID_CAJA NOT IN (463,391)
            AND P.ESTADO NOT IN 'I'
			AND SE.ID_GERENCIA = 'N'
            AND I.ID_PROYECTO = '$proyecto'";

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
	
	public function historicoRecaudoConceptoTotalE($proyecto, $fecini, $fecfin, $id_grupo){
		
		$sql="SELECT  NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT NVL(SUM(D.PAGADO),0)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND GR.ID_GRUPO = S.GRUPO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND SE.ID_GERENCIA = 'E'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND GR.ID_GRUPO = $id_grupo
			UNION
			SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND GR.ID_GRUPO = S.GRUPO
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND SE.ID_GERENCIA = 'E'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA  NOT IN (463,391)
			AND GR.ID_GRUPO = $id_grupo)";

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
	
	public function historicoRecaudoSaldofavorTotalE($proyecto, $fecini, $fecfin){
		
		$sql="SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_SALDO_FAVOR O, SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
            WHERE I.CODIGO_INM = O.INM_CODIGO 
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.ID_PAGO = O.CODIGO_PAG
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ID_CAJA NOT IN (463,391)
            AND P.ESTADO NOT IN 'I'
			AND SE.ID_GERENCIA = 'E'
            AND I.ID_PROYECTO = '$proyecto'";

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
	
	//CONSULTAS HISTORICO DE RECAUDO POR USO
	
	public function historicoRecaudoUsoTotal($proyecto, $fecini, $fecfin){
		
		/*$sql = "SELECT CODUSO, USO, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";*/

		$sql="SELECT U.ID_USO, U.DESC_USO, (
                             (SELECT COUNT(P1.ID_PAGO)PAGOS
                             FROM SGC_TT_PAGOS P1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1
                             WHERE I1.CODIGO_INM = P1.INM_CODIGO
                               AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
                               AND A1.ID_USO = U.ID_USO
                               AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                       AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                               AND P1.ESTADO NOT IN 'I'
                               AND I1.ID_PROYECTO = '$proyecto'
                               AND P1.ID_CAJA NOT IN (463,391)
                               AND I1.ZONA_FRANCA = 'N')
                             +
                             (SELECT COUNT(O1.CODIGO)PAGOS
                             FROM SGC_TT_OTROS_RECAUDOS O1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1
                             WHERE I1.CODIGO_INM = O1.INMUEBLE
                               AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
                               AND A1.ID_USO = U.ID_USO
                               AND O1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                       AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                               AND O1.ESTADO NOT IN 'I'
                               AND I1.ID_PROYECTO = '$proyecto'
                               AND O1.CAJA NOT IN (463,391)
                               AND I1.ZONA_FRANCA = 'N'
                             )
    ) PAGOS,(
    (SELECT NVL(SUM(P1.IMPORTE),0)PAGOS
     FROM SGC_TT_PAGOS P1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1
     WHERE I1.CODIGO_INM = P1.INM_CODIGO
       AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
       AND A1.ID_USO = U.ID_USO
       AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
               AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
       AND P1.ESTADO NOT IN 'I'
       AND I1.ID_PROYECTO = '$proyecto'
       AND P1.ID_CAJA NOT IN (463,391)
       AND I1.ZONA_FRANCA = 'N')
      +
    (SELECT NVL(SUM(O1.IMPORTE),0)PAGOS
     FROM SGC_TT_OTROS_RECAUDOS O1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1
     WHERE I1.CODIGO_INM = O1.INMUEBLE
       AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
       AND A1.ID_USO = U.ID_USO
       AND O1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
               AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
       AND O1.ESTADO NOT IN 'I'
       AND I1.ID_PROYECTO = '$proyecto'
       AND O1.CAJA NOT IN (463,391)
       AND I1.ZONA_FRANCA = 'N'
    )
    ) RECAUDOS,
    (
    (SELECT NVL(SUM(PD.PAGADO),0) RECAUDADO
    FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_PAGO_DETALLEFAC PD
    WHERE I.CODIGO_INM = P.INM_CODIGO
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
      AND PD.PAGO(+) = P.ID_PAGO
      AND A.ID_USO = U.ID_USO
      AND PD.CONCEPTO NOT IN (10)
      AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
              AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
      AND P.ESTADO NOT IN 'I'
      AND I.ID_PROYECTO = '$proyecto'
      AND P.ID_CAJA NOT IN (463,391)
      AND I.ZONA_FRANCA = 'N')
    +
    (
    SELECT  NVL(SUM(O.IMPORTE),0)RECAUDADO
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A
    WHERE I.CODIGO_INM = O.INMUEBLE
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
      AND A.ID_USO = U.ID_USO
      AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
              AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
      AND O.ESTADO NOT IN 'I'
      AND I.ID_PROYECTO = '$proyecto'
      AND O.CAJA NOT IN (463,391)
      AND I.ZONA_FRANCA = 'N'
    )
    ) SIN_MORA
FROM SGC_TP_USOS U
WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
ORDER BY  U.DESC_USO ASC";

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


    public function historicoRecaudoUsoTotalSinMora($proyecto, $fecini, $fecfin){

        $sql = "SELECT CODUSO, USO,  SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL(U.DESC_USO,'Uso Indefinido') USO, SUM(PD.PAGADO) RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND PD.PAGO(+) = P.ID_PAGO
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND PD.CONCEPTO NOT IN (10)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido'), SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";

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
	
	public function historicoRecaudoUsoTotalZF($proyecto, $fecini, $fecfin){
	
		/*$sql = "SELECT CODUSO, USO, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO,COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO,COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";*/
		$sql="SELECT (p1.PAGOS+p2.PAGOS) PAGOS, (R1.RECAUDADO+R2.RECAUDADO) RECAUDADO, (SM1.RECAUDADO+SM2.RECAUDADO) SIN_MORA FROM
              (SELECT COUNT(P.ID_PAGO)PAGOS
FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I
WHERE I.CODIGO_INM = P.INM_CODIGO
  AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND P.ESTADO NOT IN 'I'
  AND I.ID_PROYECTO = '$proyecto'
  AND P.ID_CAJA NOT IN (463,391)
  AND I.ZONA_FRANCA = 'S') p1,

                  (SELECT COUNT(O.CODIGO)PAGOS
FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I
                   WHERE I.CODIGO_INM = O.INMUEBLE
  AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND O.ESTADO NOT IN 'I'
  AND I.ID_PROYECTO = '$proyecto'
  AND O.CAJA NOT IN (463,391)
  AND I.ZONA_FRANCA = 'S')P2, (SELECT NVL(SUM(P.IMPORTE),0)RECAUDADO
                                          FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I
                                          WHERE I.CODIGO_INM = P.INM_CODIGO
                                            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                                    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                            AND P.ESTADO NOT IN 'I'
                                            AND I.ID_PROYECTO = '$proyecto'
                                            AND P.ID_CAJA NOT IN (463,391)
                                            AND I.ZONA_FRANCA = 'S') R1,
                                         (SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
                                          FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I
                                          WHERE I.CODIGO_INM = O.INMUEBLE
                                            AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                                    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                            AND O.ESTADO NOT IN 'I'
                                            AND I.ID_PROYECTO = '$proyecto'
                                            AND O.CAJA NOT IN (463,391)
                                            AND I.ZONA_FRANCA = 'S') R2,(
    SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
    FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
    WHERE I.CODIGO_INM = P.INM_CODIGO
    AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
    AND PD.PAGO(+) = P.ID_PAGO
    AND A.ID_USO = U.ID_USO(+)
    AND I.ID_SECTOR = S.ID_SECTOR
    AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND P.ESTADO NOT IN 'I'
    AND PD.CONCEPTO NOT IN (10)
    AND I.ID_PROYECTO = '$proyecto'
    AND P.ID_CAJA NOT IN (463,391)
    AND I.ZONA_FRANCA = 'S') SM1,
    (SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I
    WHERE I.CODIGO_INM = O.INMUEBLE
    AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND O.ESTADO NOT IN 'I'
    AND I.ID_PROYECTO = '$proyecto'
    AND O.CAJA NOT IN (463,391)
    AND I.ZONA_FRANCA = 'S') SM2";

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


    public function historicoRecaudoUsoTotalZFSinMora($proyecto, $fecini, $fecfin){

        $sql = "SELECT CODUSO, USO, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, SUM(PD.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND PD.PAGO(+) = P.ID_PAGO
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND PD.CONCEPTO NOT IN (10)
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";

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

	public function historicoRecaudoUsoNorte($proyecto, $fecini, $fecfin){
		/*$sql = "SELECT CODUSO, USO, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";*/
		$sql= "SELECT U.ID_USO, U.DESC_USO, (
                             (SELECT COUNT(P1.ID_PAGO)PAGOS
                             FROM SGC_TT_PAGOS P1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
                             WHERE I1.CODIGO_INM = P1.INM_CODIGO
                               AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
                               AND A1.ID_USO = U.ID_USO
                               AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                       AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                               AND P1.ESTADO NOT IN 'I'
                               AND I1.ID_PROYECTO = '$proyecto'
                               AND P1.ID_CAJA NOT IN (463,391)
                               AND I1.ZONA_FRANCA = 'N'
                               AND I1.ID_SECTOR = S.ID_SECTOR
                               AND S.ID_GERENCIA = 'N'
                             )
                             +
                             (SELECT COUNT(O1.CODIGO)PAGOS
                             FROM SGC_TT_OTROS_RECAUDOS O1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
                             WHERE I1.CODIGO_INM = O1.INMUEBLE
                               AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
                               AND A1.ID_USO = U.ID_USO
                               AND O1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                       AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                               AND O1.ESTADO NOT IN 'I'
                               AND I1.ID_PROYECTO = '$proyecto'
                               AND O1.CAJA NOT IN (463,391)
                               AND I1.ZONA_FRANCA = 'N'
                               AND I1.ID_SECTOR = S.ID_SECTOR
                               AND S.ID_GERENCIA = 'N'
                             )
    ) PAGOS,(
    (SELECT NVL(SUM(P1.IMPORTE),0)PAGOS
     FROM SGC_TT_PAGOS P1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
     WHERE I1.CODIGO_INM = P1.INM_CODIGO
       AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
       AND A1.ID_USO = U.ID_USO
       AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
               AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
       AND P1.ESTADO NOT IN 'I'
       AND I1.ID_PROYECTO = '$proyecto'
       AND P1.ID_CAJA NOT IN (463,391)
       AND I1.ZONA_FRANCA = 'N'
       AND I1.ID_SECTOR = S.ID_SECTOR
       AND S.ID_GERENCIA = 'N'
    )
      +
    (SELECT NVL(SUM(O1.IMPORTE),0)PAGOS
     FROM SGC_TT_OTROS_RECAUDOS O1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
     WHERE I1.CODIGO_INM = O1.INMUEBLE
       AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
       AND A1.ID_USO = U.ID_USO
       AND O1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
               AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
       AND O1.ESTADO NOT IN 'I'
       AND I1.ID_PROYECTO = '$proyecto'
       AND O1.CAJA NOT IN (463,391)
       AND I1.ZONA_FRANCA = 'N'
       AND I1.ID_SECTOR = S.ID_SECTOR
       AND S.ID_GERENCIA = 'N'
    )
    ) RECAUDOS,
    (
    (SELECT NVL(SUM(PD.PAGADO),0) RECAUDADO
    FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_PAGO_DETALLEFAC PD, SGC_TP_SECTORES S
    WHERE I.CODIGO_INM = P.INM_CODIGO
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
      AND PD.PAGO(+) = P.ID_PAGO
      AND A.ID_USO = U.ID_USO
      AND PD.CONCEPTO NOT IN (10)
      AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
              AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
      AND P.ESTADO NOT IN 'I'
      AND I.ID_PROYECTO = '$proyecto'
      AND P.ID_CAJA NOT IN (463,391)
      AND I.ZONA_FRANCA = 'N'
      AND I.ID_SECTOR = S.ID_SECTOR
      AND S.ID_GERENCIA = 'N'
    )
    +
    (
    SELECT  NVL(SUM(O.IMPORTE),0)RECAUDADO
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S
    WHERE I.CODIGO_INM = O.INMUEBLE
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
      AND A.ID_USO = U.ID_USO
      AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
              AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
      AND O.ESTADO NOT IN 'I'
      AND I.ID_PROYECTO = '$proyecto'
      AND O.CAJA NOT IN (463,391)
      AND I.ZONA_FRANCA = 'N'
      AND I.ID_SECTOR = S.ID_SECTOR
      AND S.ID_GERENCIA = 'N'
    )
    ) SIN_MORA
FROM SGC_TP_USOS U
WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
ORDER BY  U.DESC_USO ASC";

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


    public function historicoRecaudoUsoNorteSinMora($proyecto, $fecini, $fecfin){
        $sql = "SELECT CODUSO, USO, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL(U.DESC_USO,'Uso Indefinido') USO, SUM(PD.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND PD.PAGO(+) = P.ID_PAGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND PD.CONCEPTO NOT IN (10)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";

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
	
	public function historicoRecaudoUsoNorteZF($proyecto, $fecini, $fecfin){
		/*$sql = "SELECT CODUSO, USO, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO,COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO,COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";*/

		$sql= "SELECT (p1.PAGOS+p2.PAGOS) PAGOS, (R1.RECAUDADO+R2.RECAUDADO) RECAUDADO, (SM1.RECAUDADO+SM2.RECAUDADO) SIN_MORA FROM
              (SELECT COUNT(P.ID_PAGO)PAGOS
FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I,SGC_TP_SECTORES S
WHERE I.CODIGO_INM = P.INM_CODIGO
  AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND P.ESTADO NOT IN 'I'
  AND I.ID_PROYECTO = '$proyecto'
  AND P.ID_CAJA NOT IN (463,391)
  AND I.ZONA_FRANCA = 'S'
  AND S.ID_SECTOR = I.ID_SECTOR
  AND S.ID_GERENCIA = 'N'
            ) p1,

                  (SELECT COUNT(O.CODIGO)PAGOS
FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I,SGC_TP_SECTORES S
                   WHERE I.CODIGO_INM = O.INMUEBLE
  AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND O.ESTADO NOT IN 'I'
  AND I.ID_PROYECTO = '$proyecto'
  AND O.CAJA NOT IN (463,391)
  AND I.ZONA_FRANCA = 'S'
  AND S.ID_SECTOR = I.ID_SECTOR
  AND S.ID_GERENCIA = 'N')P2, (SELECT NVL(SUM(P.IMPORTE),0)RECAUDADO
                                          FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S
                                          WHERE I.CODIGO_INM = P.INM_CODIGO
                                            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                                    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                            AND P.ESTADO NOT IN 'I'
                                            AND I.ID_PROYECTO = '$proyecto'
                                            AND P.ID_CAJA NOT IN (463,391)
                                            AND I.ZONA_FRANCA = 'S'
                                            AND S.ID_SECTOR = I.ID_SECTOR
                                            AND S.ID_GERENCIA = 'N'
                              ) R1,
                                         (SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
                                          FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I,SGC_TP_SECTORES S
                                          WHERE I.CODIGO_INM = O.INMUEBLE
                                            AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                                    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                            AND O.ESTADO NOT IN 'I'
                                            AND I.ID_PROYECTO = '$proyecto'
                                            AND O.CAJA NOT IN (463,391)
                                            AND I.ZONA_FRANCA = 'S'
                                            AND S.ID_SECTOR = I.ID_SECTOR
                                            AND S.ID_GERENCIA = 'N'
                                         ) R2,(
    SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
    FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
    WHERE I.CODIGO_INM = P.INM_CODIGO
    AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
    AND PD.PAGO(+) = P.ID_PAGO
    AND A.ID_USO = U.ID_USO(+)
    AND I.ID_SECTOR = S.ID_SECTOR
    AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND P.ESTADO NOT IN 'I'
    AND PD.CONCEPTO NOT IN (10)
    AND I.ID_PROYECTO = '$proyecto'
    AND P.ID_CAJA NOT IN (463,391)
    AND I.ZONA_FRANCA = 'S'
    AND S.ID_GERENCIA = 'N') SM1,
    (SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S
    WHERE I.CODIGO_INM = O.INMUEBLE
    AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND O.ESTADO NOT IN 'I'
    AND I.ID_PROYECTO = '$proyecto'
    AND O.CAJA NOT IN (463,391)
    AND I.ZONA_FRANCA = 'S'
    AND S.ID_SECTOR = I.ID_SECTOR
    AND S.ID_GERENCIA = 'N') SM2";

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

    public function historicoRecaudoUsoNorteZFSinMora($proyecto, $fecini, $fecfin){
        $sql = "SELECT CODUSO, USO, SUM(RECAUDADO)SIN_MORA FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, SUM(PD.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND PD.PAGO(+) = P.ID_PAGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND PD.CONCEPTO NOT IN (10)
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'N'
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";

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
	
	public function historicoRecaudoUsoEste($proyecto, $fecini, $fecfin){
		/*$sql = "SELECT CODUSO, USO, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";*/

		$sql="SELECT U.ID_USO, U.DESC_USO, (
                             (SELECT COUNT(P1.ID_PAGO)PAGOS
                             FROM SGC_TT_PAGOS P1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
                             WHERE I1.CODIGO_INM = P1.INM_CODIGO
                               AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
                               AND A1.ID_USO = U.ID_USO
                               AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                       AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                               AND P1.ESTADO NOT IN 'I'
                               AND I1.ID_PROYECTO = '$proyecto'
                               AND P1.ID_CAJA NOT IN (463,391)
                               AND I1.ZONA_FRANCA = 'N'
                               AND I1.ID_SECTOR = S.ID_SECTOR
                               AND S.ID_GERENCIA = 'E'
                             )
                             +
                             (SELECT COUNT(O1.CODIGO)PAGOS
                             FROM SGC_TT_OTROS_RECAUDOS O1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
                             WHERE I1.CODIGO_INM = O1.INMUEBLE
                               AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
                               AND A1.ID_USO = U.ID_USO
                               AND O1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                       AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                               AND O1.ESTADO NOT IN 'I'
                               AND I1.ID_PROYECTO = '$proyecto'
                               AND O1.CAJA NOT IN (463,391)
                               AND I1.ZONA_FRANCA = 'N'
                               AND I1.ID_SECTOR = S.ID_SECTOR
                               AND S.ID_GERENCIA = 'E'
                             )
    ) PAGOS,(
    (SELECT NVL(SUM(P1.IMPORTE),0)PAGOS
     FROM SGC_TT_PAGOS P1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
     WHERE I1.CODIGO_INM = P1.INM_CODIGO
       AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
       AND A1.ID_USO = U.ID_USO
       AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
               AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
       AND P1.ESTADO NOT IN 'I'
       AND I1.ID_PROYECTO = '$proyecto'
       AND P1.ID_CAJA NOT IN (463,391)
       AND I1.ZONA_FRANCA = 'N'
       AND I1.ID_SECTOR = S.ID_SECTOR
       AND S.ID_GERENCIA = 'E'
    )
      +
    (SELECT NVL(SUM(O1.IMPORTE),0)PAGOS
     FROM SGC_TT_OTROS_RECAUDOS O1, SGC_TT_INMUEBLES I1, SGC_TP_ACTIVIDADES A1,SGC_TP_SECTORES S
     WHERE I1.CODIGO_INM = O1.INMUEBLE
       AND I1.SEC_ACTIVIDAD = A1.SEC_ACTIVIDAD(+)
       AND A1.ID_USO = U.ID_USO
       AND O1.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
               AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
       AND O1.ESTADO NOT IN 'I'
       AND I1.ID_PROYECTO = '$proyecto'
       AND O1.CAJA NOT IN (463,391)
       AND I1.ZONA_FRANCA = 'N'
       AND I1.ID_SECTOR = S.ID_SECTOR
       AND S.ID_GERENCIA = 'E'
    )
    ) RECAUDOS,
    (
    (SELECT NVL(SUM(PD.PAGADO),0) RECAUDADO
    FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_PAGO_DETALLEFAC PD, SGC_TP_SECTORES S
    WHERE I.CODIGO_INM = P.INM_CODIGO
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
      AND PD.PAGO(+) = P.ID_PAGO
      AND A.ID_USO = U.ID_USO
      AND PD.CONCEPTO NOT IN (10)
      AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
              AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
      AND P.ESTADO NOT IN 'I'
      AND I.ID_PROYECTO = '$proyecto'
      AND P.ID_CAJA NOT IN (463,391)
      AND I.ZONA_FRANCA = 'N'
      AND I.ID_SECTOR = S.ID_SECTOR
      AND S.ID_GERENCIA = 'E'
    )
    +
    (
    SELECT  NVL(SUM(O.IMPORTE),0)RECAUDADO
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S
    WHERE I.CODIGO_INM = O.INMUEBLE
      AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
      AND A.ID_USO = U.ID_USO
      AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
              AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
      AND O.ESTADO NOT IN 'I'
      AND I.ID_PROYECTO = '$proyecto'
      AND O.CAJA NOT IN (463,391)
      AND I.ZONA_FRANCA = 'N'
      AND I.ID_SECTOR = S.ID_SECTOR
      AND S.ID_GERENCIA = 'E'
    )
    ) SIN_MORA
FROM SGC_TP_USOS U
WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
ORDER BY  U.DESC_USO ASC";

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

    public function historicoRecaudoUsoEsteSinMora($proyecto, $fecini, $fecfin){
        $sql = "SELECT CODUSO, USO, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL(U.DESC_USO,'Uso Indefinido') USO, SUM(PD.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND PD.PAGO(+) = P.ID_PAGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND PD.CONCEPTO NOT IN (10)
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND I.ZONA_FRANCA = 'N'
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";

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
	
	public function historicoRecaudoUsoEsteZF($proyecto, $fecini, $fecfin){
		/*$sql = "SELECT CODUSO, USO, SUM(PAGOS)PAGOS, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO,COUNT(P.ID_PAGO)PAGOS, SUM(P.IMPORTE)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO,COUNT(O.CODIGO)PAGOS, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";*/
		$sql="SELECT (p1.PAGOS+p2.PAGOS) PAGOS, (R1.RECAUDADO+R2.RECAUDADO) RECAUDADO, (SM1.RECAUDADO+SM2.RECAUDADO) SIN_MORA FROM
              (SELECT COUNT(P.ID_PAGO)PAGOS
FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I,SGC_TP_SECTORES S
WHERE I.CODIGO_INM = P.INM_CODIGO
  AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND P.ESTADO NOT IN 'I'
  AND I.ID_PROYECTO = '$proyecto'
  AND P.ID_CAJA NOT IN (463,391)
  AND I.ZONA_FRANCA = 'S'
  AND S.ID_SECTOR = I.ID_SECTOR
  AND S.ID_GERENCIA = 'E'
            ) p1,

                  (SELECT COUNT(O.CODIGO)PAGOS
FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I,SGC_TP_SECTORES S
                   WHERE I.CODIGO_INM = O.INMUEBLE
  AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
          AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND O.ESTADO NOT IN 'I'
  AND I.ID_PROYECTO = '$proyecto'
  AND O.CAJA NOT IN (463,391)
  AND I.ZONA_FRANCA = 'S'
  AND S.ID_SECTOR = I.ID_SECTOR
  AND S.ID_GERENCIA = 'E')P2, (SELECT NVL(SUM(P.IMPORTE),0)RECAUDADO
                                          FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S
                                          WHERE I.CODIGO_INM = P.INM_CODIGO
                                            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                                    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                            AND P.ESTADO NOT IN 'I'
                                            AND I.ID_PROYECTO = '$proyecto'
                                            AND P.ID_CAJA NOT IN (463,391)
                                            AND I.ZONA_FRANCA = 'S'
                                            AND S.ID_SECTOR = I.ID_SECTOR
                                            AND S.ID_GERENCIA = 'E'
                              ) R1,
                                         (SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
                                          FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I,SGC_TP_SECTORES S
                                          WHERE I.CODIGO_INM = O.INMUEBLE
                                            AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                                                    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                                            AND O.ESTADO NOT IN 'I'
                                            AND I.ID_PROYECTO = '$proyecto'
                                            AND O.CAJA NOT IN (463,391)
                                            AND I.ZONA_FRANCA = 'S'
                                            AND S.ID_SECTOR = I.ID_SECTOR
                                            AND S.ID_GERENCIA = 'E'
                                         ) R2,(
    SELECT NVL(SUM(PD.PAGADO),0)RECAUDADO
    FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
    WHERE I.CODIGO_INM = P.INM_CODIGO
    AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
    AND PD.PAGO(+) = P.ID_PAGO
    AND A.ID_USO = U.ID_USO(+)
    AND I.ID_SECTOR = S.ID_SECTOR
    AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND P.ESTADO NOT IN 'I'
    AND PD.CONCEPTO NOT IN (10)
    AND I.ID_PROYECTO = '$proyecto'
    AND P.ID_CAJA NOT IN (463,391)
    AND I.ZONA_FRANCA = 'S'
    AND S.ID_GERENCIA = 'E') SM1,
    (SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
    FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S
    WHERE I.CODIGO_INM = O.INMUEBLE
    AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
    AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND O.ESTADO NOT IN 'I'
    AND I.ID_PROYECTO = '$proyecto'
    AND O.CAJA NOT IN (463,391)
    AND I.ZONA_FRANCA = 'S'
    AND S.ID_SECTOR = I.ID_SECTOR
    AND S.ID_GERENCIA = 'E') SM2";

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

    public function historicoRecaudoUsoEsteZFSinMora($proyecto, $fecini, $fecfin){
        $sql = "SELECT CODUSO, USO, SUM(RECAUDADO)RECAUDADO FROM (
			SELECT NVL(A.ID_USO,'UI')CODUSO, NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, SUM(PD.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_PAGO_DETALLEFAC PD
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND PD.PAGO(+) = P.ID_PAGO
			AND I.ID_SECTOR = S.ID_SECTOR
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND PD.CONCEPTO NOT IN (10)
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
			UNION
			SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND A.ID_USO = U.ID_USO(+)
			AND I.ID_SECTOR = S.ID_SECTOR
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			AND S.ID_GERENCIA = 'E'
			AND I.ZONA_FRANCA = 'S'
			AND A.ID_USO IN ('C','I','PC')
			GROUP BY A.ID_USO, U.DESC_USO
		)
		GROUP BY CODUSO, USO
		ORDER BY USO ASC";

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
	
	//CONSULTAS HISTORICO DE RECAUDO USUARIOS MEDIDOS
	
	public function historicoRecaudoConceptoTotalMedido($proyecto, $fecini, $fecfin, $id_grupo){
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT SUM(D.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND GR.ID_GRUPO = S.GRUPO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			AND I.FACTURAR = 'D'
			AND GR.ID_GRUPO = $id_grupo
			UNION
			SELECT SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND GR.ID_GRUPO = S.GRUPO
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA  NOT IN (463,391)
			AND I.FACTURAR = 'D'
			AND GR.ID_GRUPO = $id_grupo)";

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
	
	public function historicoRecaudoConceptoNorteMedido($proyecto, $fecini, $fecfin, $id_grupo){
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT SUM(D.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND GR.ID_GRUPO = S.GRUPO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			--AND I.FACTURAR = 'D'
			AND SE.ID_GERENCIA = 'N'
			AND GR.ID_GRUPO = $id_grupo
			UNION
			SELECT SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND GR.ID_GRUPO = S.GRUPO
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA NOT IN (463,391)
			--AND I.FACTURAR = 'D'
			AND SE.ID_GERENCIA = 'N'
			AND GR.ID_GRUPO = $id_grupo)";

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
	
	public function historicoRecaudoConceptoEsteMedido($proyecto, $fecini, $fecfin, $id_grupo){
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT SUM(D.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND GR.ID_GRUPO = S.GRUPO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			--AND I.FACTURAR = 'D'
			AND SE.ID_GERENCIA = 'E'
			AND GR.ID_GRUPO = $id_grupo
			UNION
			SELECT SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE, SGC_TP_GRUPOS_REPORT GR
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND GR.ID_GRUPO = S.GRUPO
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA  NOT IN (463,391)
			--AND I.FACTURAR = 'D'
			AND SE.ID_GERENCIA = 'E'
			AND GR.ID_GRUPO = $id_grupo)";

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
	
	public function historicoRecaudoSaldofavorTotalMedido($proyecto, $fecini, $fecfin, $id_grupo){
		$sql = "SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_SALDO_FAVOR O, SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
            WHERE I.CODIGO_INM = O.INM_CODIGO 
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.ID_PAGO = O.CODIGO_PAG
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ID_CAJA NOT IN (463,391)
            AND P.ESTADO NOT IN 'I'
            AND I.ID_PROYECTO = '$proyecto'
			AND I.FACTURAR = 'D'";

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
	
	public function historicoRecaudoSaldoFavorNorteMedido($proyecto, $fecini, $fecfin, $id_grupo){
		$sql = "SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_SALDO_FAVOR O, SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
            WHERE I.CODIGO_INM = O.INM_CODIGO 
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.ID_PAGO = O.CODIGO_PAG
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ID_CAJA NOT IN (463,391)
            AND P.ESTADO NOT IN 'I'
            AND I.ID_PROYECTO = '$proyecto'
			AND SE.ID_GERENCIA = 'N'
			AND I.FACTURAR = 'D'";

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
	
	public function historicoRecaudoSaldoFavorEsteMedido($proyecto, $fecini, $fecfin, $id_grupo){
		$sql = "SELECT NVL(SUM(O.IMPORTE),0)RECAUDADO
            FROM SGC_TT_SALDO_FAVOR O, SGC_TT_INMUEBLES I, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
            WHERE I.CODIGO_INM = O.INM_CODIGO 
            AND I.ID_SECTOR = SE.ID_SECTOR
            AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
            AND P.ID_PAGO = O.CODIGO_PAG
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ID_CAJA NOT IN (463,391)
            AND P.ESTADO NOT IN 'I'
            AND I.ID_PROYECTO = '$proyecto'
			AND SE.ID_GERENCIA = 'E'
			AND I.FACTURAR = 'D'";

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
	
	//CONSULTAS HISTORICO DE RECAUDO AGRUPADO POR CONCEPTOS
	
	public function grupoNiveles(){
		$sql = "SELECT  S.NIVEL_AGRUPA
		FROM SGC_TP_SERVICIOS S, SGC_TP_GRUPOS_REPORT GR
		WHERE S.GRUPO = GR.ID_GRUPO
		AND S.REPORTE_GER IN ('S')
		GROUP BY  S.NIVEL_AGRUPA
		ORDER BY 1";
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
	
	public function historicoRecaudoConceptoAgrupadoTotal($proyecto, $fecini, $fecfin, $des_nivel){
		
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT SUM(D.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND S.NIVEL_AGRUPA = '$des_nivel'
			AND I.ID_PROYECTO = '$proyecto'
			AND P.ID_CAJA NOT IN (463,391)
			UNION
			SELECT SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND S.NIVEL_AGRUPA = '$des_nivel'
			AND I.ID_PROYECTO = '$proyecto'
			AND O.CAJA  NOT IN (463,391))";

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
	
	public function historicoRecaudoConceptoAgrupadoNorte($proyecto, $fecini, $fecfin, $des_nivel){
		
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT SUM(D.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND S.NIVEL_AGRUPA = '$des_nivel'
			AND I.ID_PROYECTO = '$proyecto'
			AND SE.ID_GERENCIA = 'N'
			AND P.ID_CAJA NOT IN (463,391)
			UNION
			SELECT SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND S.NIVEL_AGRUPA = '$des_nivel'
			AND I.ID_PROYECTO = '$proyecto'
			AND SE.ID_GERENCIA = 'N'
			AND O.CAJA  NOT IN (463,391))";

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
	
	public function historicoRecaudoConceptoAgrupadoEste($proyecto, $fecini, $fecfin, $des_nivel){
		
		$sql = "SELECT NVL(SUM(RECAUDADO),0)RECAUDADO FROM (
			SELECT SUM(D.PAGADO)RECAUDADO
			FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
			WHERE I.CODIGO_INM = P.INM_CODIGO
			AND P.ID_PAGO = D.PAGO(+)
			AND D.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND S.NIVEL_AGRUPA = '$des_nivel'
			AND I.ID_PROYECTO = '$proyecto'
			AND SE.ID_GERENCIA = 'E'
			AND P.ID_CAJA NOT IN (463,391)
			UNION
			SELECT SUM(O.IMPORTE)RECAUDADO
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES SE
			WHERE I.CODIGO_INM = O.INMUEBLE
			AND O.CONCEPTO = S.COD_SERVICIO(+)
			AND I.ID_SECTOR = SE.ID_SECTOR
			AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
			AND O.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND O.ESTADO NOT IN 'I'
			AND S.NIVEL_AGRUPA = '$des_nivel'
			AND I.ID_PROYECTO = '$proyecto'
			AND SE.ID_GERENCIA = 'E'
			AND O.CAJA  NOT IN (463,391))";

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

    public function InsertaDatosRecaudoSectorNorte($sectorn,$periodo,$pagosn,$recaudadon,$proyecto){
        $sql="BEGIN SGC_P_INSERT_REC_SECTOR_NORTE($sectorn,$periodo,$pagosn,$recaudadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function InsertaDatosRecaudoSectorEste($sectore,$periodo,$pagose,$recaudadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERT_REC_SECTOR_ESTE($sectore,$periodo,$pagose,$recaudadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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


    public function InsertaDatosRecaudoUsoNorte($uson,$periodo,$pagosn,$recaudadon,$proyecto){
        $sql="BEGIN SGC_P_INSERT_REC_USO_NORTE('$uson',$periodo,$pagosn,$recaudadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function InsertaDatosRecaudoUsoEste($usoe,$periodo,$pagose,$recaudadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERT_REC_USO_ESTE('$usoe',$periodo,$pagose,$recaudadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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


    public function InsertaDatosRecaudoConAgrNorte($des_nivel,$periodo,$recaudadoagrupnor,$proyecto){
        $sql="BEGIN SGC_P_INSERT_REC_CONAGR_NORTE('$des_nivel',$periodo,$recaudadoagrupnor,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function InsertaDatosRecaudoConAgrEste($des_nivel,$periodo,$recaudadoagrupest,$proyecto){
        $sql="BEGIN SGC_P_INSERT_REC_CONAGR_ESTE('$des_nivel',$periodo,$recaudadoagrupest,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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


    public function InsertaDatosRecaudoConceptoNorte($id_grupo,$periodo,$recaudadon,$proyecto){
        $sql="BEGIN SGC_P_INSERTA_REC_CONC_NORTE($id_grupo,$periodo,$recaudadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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

    public function InsertaDatosRecaudoConceptoEste($id_grupo,$periodo,$recaudadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERTA_REC_CONC_ESTE($id_grupo,$periodo,$recaudadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

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