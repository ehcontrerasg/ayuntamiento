<?php
include_once '../../clases/class.conexion.php';
class ReportesHisFac extends ConexionClass{

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

        //echo $sql;
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
	//CONSULTAS HISTORICO DE FACTURAS POR SECTOR
	
	public function historicoFacturasSectorTotal($proyecto, $periodo){
		$sql = "SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(F.CONSEC_FACTURA)FACTURAS, NVL(SUM(F.TOTAL_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		GROUP BY SUBSTR(I.ID_ZONA,0,2)
		ORDER BY 1 ASC";
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
	
	public function historicoFacturasSectorNorte($proyecto, $periodo){
		$sql = "SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(F.CONSEC_FACTURA)FACTURAS, NVL(SUM(F.TOTAL_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA = 'N'
		GROUP BY SUBSTR(I.ID_ZONA,0,2)
		ORDER BY 1 ASC";

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
	
	public function historicoFacturasSectorEste($proyecto, $periodo){
		$sql = "SELECT SUBSTR(I.ID_ZONA,0,2) SECTOR, COUNT(F.CONSEC_FACTURA)FACTURAS, NVL(SUM(F.TOTAL_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA = 'E'
		GROUP BY SUBSTR(I.ID_ZONA,0,2)
		ORDER BY 1 ASC";
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
	
	//CONSULTAS HISTORICO DE FACTURAS POR CONCEPTO
	
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
	
	public function historicoFacturasConceptoTotal($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND GR.ID_GRUPO = S.GRUPO(+)
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO(+) = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		--AND S.REPORTE_GER = 'S'
		AND GR.ID_GRUPO = $id_grupo";
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
	
	public function historicoFacturasConceptoNorte($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND GR.ID_GRUPO = S.GRUPO(+) 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO(+) = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND SE.ID_GERENCIA = 'N'
		--AND S.REPORTE_GER = 'S'
		AND GR.ID_GRUPO = $id_grupo";
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
	
	public function historicoFacturasConceptoEste($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND GR.ID_GRUPO = S.GRUPO(+) 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO(+) = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND SE.ID_GERENCIA = 'E'
		--AND S.REPORTE_GER = 'S'
		AND GR.ID_GRUPO = $id_grupo";
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
	
	//CONSULTAS HISTORICO DE FACTURAS POR USO
	
	public function historicoFacturasUsoTotal($proyecto, $periodo){
    $sql = "SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(F.CONSEC_FACTURA)FACTURAS, NVL(SUM(F.TOTAL_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND I.ZONA_FRANCA = 'N'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";

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

    public function historicoFacturasUsoTotalSinMora($proyecto, $periodo){
        $sql = "SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO, NVL(SUM(DF.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_DETALLE_FACTURA DF
		WHERE I.CODIGO_INM = F.INMUEBLE(+)
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.CONSEC_FACTURA = DF.FACTURA(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND DF.CONCEPTO NOT IN (10)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";

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
	
	public function historicoFacturasUsoTotalZF($proyecto, $periodo){

		$sql="SELECT * FROM   (SELECT  COUNT(F.CONSEC_FACTURA) FACTURAS
                               FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1
                               WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                                       F.PERIODO =$periodo  AND
                                       A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                                       I1.ZONA_FRANCA = 'S' AND
                                       I1.ID_PROYECTO = '$proyecto')CF,
                              (SELECT  NVL(SUM(F.TOTAL_ORI),0) FACTURADO
                               FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1
                               WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                                       F.PERIODO =$periodo AND
                                       A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                                       I1.ZONA_FRANCA = 'S' AND
                                       I1.ID_PROYECTO = '$proyecto')F,
                              (SELECT NVL(SUM(DF1.VALOR_ORI),0) SIN_MORA
                               FROM   SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1,SGC_TT_DETALLE_FACTURA DF1
                               WHERE  I1.CODIGO_INM = F.INMUEBLE AND
                                      F.PERIODO = $periodo AND
                                      A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                                      I1.ZONA_FRANCA = 'S' AND
                                      I1.ID_PROYECTO = '$proyecto' AND
                                      F.CONSEC_FACTURA = DF1.FACTURA AND
                                      DF1.CONCEPTO NOT IN (10))SM";

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

    public function historicoFacturasUsoTotalZFSinMora($proyecto, $periodo){
        $sql = "SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') , NVL(SUM(DF.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_DETALLE_FACTURA DF
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.CONSEC_FACTURA = DF.FACTURA(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND DF.CONCEPTO NOT IN (10)
		AND I.ZONA_FRANCA = 'S'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";
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
	
	public function historicoFacturasUsoNorte($proyecto, $periodo){
		$sql = "SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(F.CONSEC_FACTURA)FACTURAS, NVL(SUM(F.TOTAL_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA = 'N'
		AND I.ZONA_FRANCA = 'N'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";
        /*$sql="SELECT U.ID_USO,U.DESC_USO USO,
                    (SELECT  COUNT(F.CONSEC_FACTURA)
                    FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
                    WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                            F.PERIODO = $periodo AND
                            A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                            A1.ID_USO = U.ID_USO AND
                            I1.ZONA_FRANCA = 'N' AND
                            I1.ID_PROYECTO = '$proyecto' AND
                            I1.ID_SECTOR = S1.ID_SECTOR AND
                            S1.ID_GERENCIA = 'N')FACTURAS,
                    NVL(( SELECT  SUM(F.TOTAL_ORI)
                    FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
                    WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                            F.PERIODO = $periodo AND
                            A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                            A1.ID_USO = U.ID_USO AND
                            I1.ZONA_FRANCA = 'N' AND
                            I1.ID_PROYECTO = '$proyecto' AND
                            I1.ID_SECTOR = S1.ID_SECTOR AND
                            S1.ID_GERENCIA = 'N'),0)FACTURADO
                    FROM SGC_TP_USOS U
                    WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
                    ORDER BY  U.DESC_USO";*/
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

    public function historicoFacturasUsoNorteSinMora($proyecto, $periodo){
        $sql = "SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido'), NVL(SUM(DF.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_DETALLE_FACTURA DF
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.CONSEC_FACTURA = DF.FACTURA(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA = 'N'
		AND I.ZONA_FRANCA = 'N'
		AND DF.CONCEPTO NOT IN (10)
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";

        /*$sql="SELECT U.ID_USO,U.DESC_USO USO,
                NVL(( SELECT  SUM(DF1.VALOR_ORI)
                      FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1,SGC_TT_DETALLE_FACTURA DF1
                      WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                              F.PERIODO = 201901 AND
                              A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                              A1.ID_USO = U.ID_USO AND
                              I1.ZONA_FRANCA = 'N' AND
                              I1.ID_PROYECTO = 'SD' AND
                              F.CONSEC_FACTURA = DF1.FACTURA AND
                              DF1.CONCEPTO NOT IN (10)
                    ),0)FACTURADO
                FROM SGC_TP_USOS U
                WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
                ORDER BY  U.DESC_USO";*/
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
	
	public function historicoFacturasUsoNorteZF($proyecto, $periodo){

		$sql="SELECT NVL(CF.FACTURAS,0) FACTURAS, NVL(F.FACTURADO,0) FACTURADO,NVL(SM.SIN_MORA,0) SIN_MORA
              FROM   (SELECT  COUNT(F.CONSEC_FACTURA) FACTURAS
              FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
              WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                      F.PERIODO =$periodo  AND
                      A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                      I1.ZONA_FRANCA = 'S' AND
                      I1.ID_PROYECTO = '$proyecto' AND
                      I1.ID_SECTOR = S1.ID_SECTOR AND
                      S1.ID_GERENCIA = 'N'
                      )
              CF,
               (SELECT  SUM(F.TOTAL_ORI) FACTURADO
               FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
               WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                       F.PERIODO =$periodo AND
                       A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                       I1.ZONA_FRANCA = 'S' AND
                       I1.ID_PROYECTO = '$proyecto' AND
                       I1.ID_SECTOR = S1.ID_SECTOR AND
                       S1.ID_GERENCIA = 'N')
              F,
              (SELECT SUM(DF1.VALOR_ORI) SIN_MORA
              FROM   SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1,SGC_TT_DETALLE_FACTURA DF1, SGC_TP_SECTORES S1
              WHERE  I1.CODIGO_INM = F.INMUEBLE AND
                     F.PERIODO = $periodo AND
                     A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                     I1.ZONA_FRANCA = 'S' AND
                     I1.ID_PROYECTO = '$proyecto' AND
                     F.CONSEC_FACTURA = DF1.FACTURA AND
                     DF1.CONCEPTO NOT IN (10) AND
                     I1.ID_SECTOR = S1.ID_SECTOR AND
                     S1.ID_GERENCIA = 'N' )SM";
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

    public function historicoFacturasUsoNorteZFSinMora($proyecto, $periodo){
        $sql = "SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, NVL(SUM(DF.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_DETALLE_FACTURA DF
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.CONSEC_FACTURA = DF.FACTURA(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA = 'N'
		AND DF.CONCEPTO NOT IN (10)
		AND I.ZONA_FRANCA = 'S'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";

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
	
	public function historicoFacturasUsoEste($proyecto, $periodo){
    $sql = "SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO,COUNT(F.CONSEC_FACTURA)FACTURAS, NVL(SUM(F.TOTAL_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA = 'E'
		AND I.ZONA_FRANCA = 'N'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";
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

    public function historicoFacturasUsoEsteSinMora($proyecto, $periodo){
        $sql = "SELECT NVL(A.ID_USO,'UI'), NVL(U.DESC_USO,'Uso Indefinido') USO, NVL(SUM(DF.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_DETALLE_FACTURA DF
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.CONSEC_FACTURA = DF.FACTURA(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND DF.CONCEPTO NOT IN (10)
		AND S.ID_GERENCIA = 'E'
		AND I.ZONA_FRANCA = 'N'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";
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
	
	public function historicoFacturasUsoEsteZF($proyecto, $periodo){

		$sql="SELECT NVL(CF.FACTURAS,0) FACTURAS, NVL(F.FACTURADO,0) FACTURADO,NVL(SM.SIN_MORA,0) SIN_MORA
              FROM   (SELECT  COUNT(F.CONSEC_FACTURA) FACTURAS
              FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
              WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                      F.PERIODO =$periodo  AND
                      A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                      I1.ZONA_FRANCA = 'S' AND
                      I1.ID_PROYECTO = '$proyecto' AND
                      I1.ID_SECTOR = S1.ID_SECTOR AND
                      S1.ID_GERENCIA = 'E'
                      )
              CF,
               (SELECT  SUM(F.TOTAL_ORI) FACTURADO
               FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
               WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                       F.PERIODO =$periodo AND
                       A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                       I1.ZONA_FRANCA = 'S' AND
                       I1.ID_PROYECTO = '$proyecto' AND
                       I1.ID_SECTOR = S1.ID_SECTOR AND
                       S1.ID_GERENCIA = 'E' )
              F,
              (SELECT SUM(DF1.VALOR_ORI) SIN_MORA
              FROM   SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1,SGC_TT_DETALLE_FACTURA DF1, SGC_TP_SECTORES S1
              WHERE  I1.CODIGO_INM = F.INMUEBLE AND
                     F.PERIODO = $periodo AND
                     A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                     I1.ZONA_FRANCA = 'S' AND
                     I1.ID_PROYECTO = '$proyecto' AND
                     F.CONSEC_FACTURA = DF1.FACTURA AND
                     DF1.CONCEPTO NOT IN (10) AND
                     I1.ID_SECTOR = S1.ID_SECTOR AND
                     S1.ID_GERENCIA = 'E')SM";
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


    public function historicoFacturasUsoEsteZFSinMora($proyecto, $periodo){
        $sql = "SELECT NVL(A.ID_USO,'UI'), NVL('ZF '||U.DESC_USO,'Uso Indefinido') USO, NVL(SUM(DF.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S, SGC_TT_DETALLE_FACTURA DF
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.CONSEC_FACTURA = DF.FACTURA(+)
		AND I.ID_SECTOR = S.ID_SECTOR
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND DF.CONCEPTO NOT IN (10)
		AND S.ID_GERENCIA = 'E'
		AND I.ZONA_FRANCA = 'S'
		GROUP BY A.ID_USO, U.DESC_USO
		ORDER BY U.DESC_USO ASC";
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
	
	//CONSULTAS HISTORICO DE FACTURAS MEDIDOS POR CONCEPTO
	
	public function historicoFacturasConceptoTotalMedido($proyecto, $periodo, $id_grupo){
		 $sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND GR.ID_GRUPO = S.GRUPO(+) 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND I.FACTURAR = 'D'
		AND S.REPORTE_GER = 'S'
		AND GR.ID_GRUPO = $id_grupo";

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
	
	public function historicoFacturasConceptoNorteMedido($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
		WHERE I.CODIGO_INM = F.INMUEBLE
		AND GR.ID_GRUPO = S.GRUPO(+) 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND SE.ID_GERENCIA = 'N'
		AND I.FACTURAR = 'D'
		AND S.REPORTE_GER = 'S'
		AND GR.ID_GRUPO = $id_grupo";

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
	
	public function historicoFacturasConceptoEsteMedido($proyecto, $periodo, $id_grupo){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A, SGC_TP_GRUPOS_REPORT GR
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND GR.ID_GRUPO = S.GRUPO(+) 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND SE.ID_GERENCIA = 'E'
		AND I.FACTURAR = 'D'
		AND S.REPORTE_GER = 'S'
		AND GR.ID_GRUPO = $id_grupo";

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
	
	//CONSULTAS HISTORICO DE FACTURAS AGRUPADO POR CONCEPTOS
	
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
	
	public function historicoFacturasConceptoAgrupado($proyecto, $periodo, $des_nivel){
		$sql = "SELECT NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.NIVEL_AGRUPA = '$des_nivel'
		ORDER BY 1 ASC";
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
	
	public function historicoFacturasConceptoAgrupadoN($proyecto, $periodo, $des_nivel){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND SE.ID_GERENCIA = 'N'
		AND S.NIVEL_AGRUPA = '$des_nivel'
		ORDER BY 1 ASC";
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



	public function historicoFacturasConceptoAgrupadoE($proyecto, $periodo, $des_nivel){
		$sql = "SELECT  NVL(SUM(D.VALOR_ORI),0)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TT_INMUEBLES I, SGC_TP_SERVICIOS S, SGC_TP_SECTORES SE, SGC_TP_ACTIVIDADES A
		WHERE I.CODIGO_INM = F.INMUEBLE 
		AND F.CONSEC_FACTURA = D.FACTURA
		AND D.CONCEPTO = S.COD_SERVICIO
		AND I.ID_SECTOR = SE.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND SE.ID_GERENCIA = 'E'
		AND S.NIVEL_AGRUPA = '$des_nivel'
		ORDER BY 1 ASC";
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


    public function InsertaDatosFacturacionNorte($des_nivel,$periodo,$facturadon,$proyecto){
        $sql="BEGIN SGC_P_INSERTA_FACT_NORTE('$des_nivel',$periodo,$facturadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function InsertaDatosFacturacionEste($des_nivel,$periodo,$facturadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERTA_FACT_ESTE('$des_nivel',$periodo,$facturadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function InsertaDatosFacturacionSectorNorte($sectorn,$periodo,$facturasn,$facturadon,$proyecto){
        $sql="BEGIN SGC_P_INSERT_FACT_SECTOR_NORTE($sectorn,$periodo,$facturasn,$facturadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
       // echo $sql;
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

    public function InsertaDatosFacturacionSectorEste($sectore,$periodo,$facturase,$facturadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERT_FACT_SECTOR_ESTE($sectore,$periodo,$facturase,$facturadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
       // echo $sql;
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


    public function InsertaDatosFacturacionUsoNorte($uson,$periodo,$facturasn,$facturadon,$proyecto){
        $sql="BEGIN SGC_P_INSERT_FACT_USO_NORTE('$uson',$periodo,$facturasn,$facturadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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


    public function InsertaDatosFacturacionUsoEste($usoe,$periodo,$facturase,$facturadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERT_FACT_USO_ESTE('$usoe',$periodo,$facturase,$facturadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        // echo $sql;
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


    public function InsertaDatosFacturacionConceptoNorte($id_grupo,$periodo,$facturadon,$proyecto){
        $sql="BEGIN SGC_P_INSERTA_FACT_CONC_NORTE($id_grupo,$periodo,$facturadon,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function InsertaDatosFacturacionConceptoEste($id_grupo,$periodo,$facturadoe,$proyecto){
        $sql="BEGIN SGC_P_INSERTA_FACT_CONC_ESTE($id_grupo,$periodo,$facturadoe,'$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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
    public function historicoFacturasDetalladoUsoTotal($proyecto, $periodo,$id_uso){
        $sql="SELECT * FROM
              (SELECT  COUNT(F.CONSEC_FACTURA) FACTURAS
               FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1
               WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                       F.PERIODO = $periodo AND
                  A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                  A1.ID_USO = '$id_uso' AND
                  I1.ZONA_FRANCA = 'N' AND
                  I1.ID_PROYECTO = '$proyecto'
              )F,
              ( SELECT  NVL(SUM(F.TOTAL_ORI),0) FACTURADO
                FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1
                WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                        F.PERIODO = $periodo AND
                  A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                  A1.ID_USO = '$id_uso' AND
                  I1.ZONA_FRANCA = 'N' AND
                  I1.ID_PROYECTO = '$proyecto'
              )FO,
              ( SELECT  NVL(SUM(DF1.VALOR_ORI),0) SIN_MORA
                FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1,SGC_TT_DETALLE_FACTURA DF1
                WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                        F.PERIODO = $periodo AND
                  A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                  A1.ID_USO = '$id_uso' AND
                  I1.ZONA_FRANCA = 'N' AND
                  I1.ID_PROYECTO = '$proyecto' AND
                  F.CONSEC_FACTURA = DF1.FACTURA AND
                  DF1.CONCEPTO NOT IN (10)
              )SM";

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
    public function historicoFacturasDetalladoUsoTotalSinMora($proyecto, $periodo){
        $sql="SELECT 
                     NVL(( SELECT  SUM(DF1.VALOR_ORI)
                       FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1,SGC_TT_DETALLE_FACTURA DF1
                       WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                               F.PERIODO = $periodo AND
                               A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
                               A1.ID_USO = U.ID_USO AND
                               I1.ZONA_FRANCA = 'N' AND
                               I1.ID_PROYECTO = '$proyecto' AND
                               F.CONSEC_FACTURA = DF1.FACTURA AND
                               DF1.CONCEPTO NOT IN (10)
                     ),0)FACTURADO
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
    public function historicoFacturasDetalladoSectorNorte($proyecto, $periodo,$id_uso){
        $sql="SELECT * FROM
       (SELECT  COUNT(F.CONSEC_FACTURA) FACTURAS
        FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
        WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                F.PERIODO = $periodo AND
           A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
           A1.ID_USO = '$id_uso' AND
           I1.ZONA_FRANCA = 'N' AND
           I1.ID_PROYECTO = '$proyecto' AND
           I1.ID_SECTOR = S1.ID_SECTOR AND
           S1.ID_GERENCIA = 'N')F ,
      ( SELECT  NVL(SUM(F.TOTAL_ORI),0) FACTURADO
             FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
             WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                     F.PERIODO = $periodo AND
           A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
           A1.ID_USO ='$id_uso' AND
           I1.ZONA_FRANCA = 'N' AND
           I1.ID_PROYECTO = '$proyecto' AND
           I1.ID_SECTOR = S1.ID_SECTOR AND
           S1.ID_GERENCIA = 'N')FO,
      ( SELECT  NVL(SUM(DF1.VALOR_ORI),0) SIN_MORA
             FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1,SGC_TT_DETALLE_FACTURA DF1
             WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                     F.PERIODO = $periodo AND
           A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
           A1.ID_USO = '$id_uso' AND
           I1.ZONA_FRANCA = 'N' AND
           I1.ID_PROYECTO = '$proyecto' AND
           I1.ID_SECTOR = S1.ID_SECTOR AND
           S1.ID_GERENCIA = 'N' AND
           F.CONSEC_FACTURA = DF1.FACTURA AND
           DF1.CONCEPTO NOT IN (10))SM";

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
    public function historicoFacturasDetalladoSectorEste($proyecto, $periodo,$id_uso){
        $sql="SELECT * FROM
       (SELECT  COUNT(F.CONSEC_FACTURA) FACTURAS
        FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
        WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                F.PERIODO = $periodo AND
           A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
           A1.ID_USO = '$id_uso' AND
           I1.ZONA_FRANCA = 'N' AND
           I1.ID_PROYECTO = '$proyecto' AND
           I1.ID_SECTOR = S1.ID_SECTOR AND
           S1.ID_GERENCIA = 'E')F ,
      ( SELECT  NVL(SUM(F.TOTAL_ORI),0) FACTURADO
             FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1
             WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                     F.PERIODO = $periodo AND
           A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
           A1.ID_USO ='$id_uso' AND
           I1.ZONA_FRANCA = 'N' AND
           I1.ID_PROYECTO = '$proyecto' AND
           I1.ID_SECTOR = S1.ID_SECTOR AND
           S1.ID_GERENCIA = 'E')FO,
      ( SELECT  NVL(SUM(DF1.VALOR_ORI),0) SIN_MORA
             FROM    SGC_TT_FACTURA F,SGC_TT_INMUEBLES I1,SGC_TP_ACTIVIDADES A1, SGC_TP_SECTORES S1,SGC_TT_DETALLE_FACTURA DF1
             WHERE   I1.CODIGO_INM = F.INMUEBLE AND
                     F.PERIODO = $periodo AND
           A1.SEC_ACTIVIDAD=I1.SEC_ACTIVIDAD AND
           A1.ID_USO = '$id_uso' AND
           I1.ZONA_FRANCA = 'N' AND
           I1.ID_PROYECTO = '$proyecto' AND
           I1.ID_SECTOR = S1.ID_SECTOR AND
           S1.ID_GERENCIA = 'E' AND
           F.CONSEC_FACTURA = DF1.FACTURA AND
           DF1.CONCEPTO NOT IN (10))SM";

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

    public function grupoUsos(){
        $sql="SELECT U.ID_USO,U.DESC_USO
              FROM SGC_TP_USOS U
              WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
              ORDER BY U.DESC_USO ASC
              ";

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
}
?>