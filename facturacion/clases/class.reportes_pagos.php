<?php
include_once '../../clases/class.conexion.php';
class Reportes extends ConexionClass{
	private $id_proyecto;
	private $id_zonini;
	private $id_zonfin;
	private $id_lecini;
	//private $id_lecfin;
	private $id_perini;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_zonini="";
		$this->id_zonfin="";
		$this->id_lecini="";
		//$this->id_lecfin="";
		$this->id_perini="";
	}

	public function seleccionaAcueducto (){
		$sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
		FROM SGC_TP_PROYECTOS
		ORDER BY SIGLA_PROYECTO";
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
	
	public function seleccionaZonas ($user_input2){
		$sql = "SELECT ID_SECTOR 
	   	FROM SGC_TP_SECTORES
		WHERE ID_SECTOR LIKE UPPER('$user_input2%') ";

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
	
	public function seleccionaLectores ($user_input2){
		$sql = "SELECT ID_USUARIO, LOGIN
	   	FROM SGC_TT_USUARIOS
		WHERE LOGIN LIKE '$user_input2%' AND ID_CARGO = '6' 
		ORDER BY LOGIN ASC";

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
	
	public function seleccionaCantObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT COUNT(*)CANTIDAD FROM(
		SELECT R.OBSERVACION_ACTUAL
		FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z 
		WHERE R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto' AND R.PERIODO = '$perini' AND R.OBSERVACION_ACTUAL IS NOT NULL $where
		GROUP BY R.OBSERVACION_ACTUAL ORDER BY R.OBSERVACION)";
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
	
	
	public function seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT R.OBSERVACION_ACTUAL
		FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
		WHERE  R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto' 
		AND R.PERIODO = '$perini' $where AND R.OBSERVACION_ACTUAL IS NOT NULL
		GROUP BY R.OBSERVACION_ACTUAL
		ORDER BY R.OBSERVACION_ACTUAL";
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
	
	public function ObtieneZonasObslec($perini,$zonini,$zonfin,$lecini,$proyecto){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT DISTINCT R.ID_ZONA
        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
        WHERE  R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto' 
		AND R.PERIODO = '$perini' AND R.OBSERVACION_ACTUAL IS NOT NULL $where
        GROUP BY R.ID_ZONA
        ORDER BY R.ID_ZONA";
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
	
	public function ObtieneCantLector($perini,$proyecto,$zona,$lecini){
		if($lecini != '') $where .= " AND R.COD_LECTOR = '$lecini'";
		$sql = "SELECT COUNT(*)CANTIDAD FROM(SELECT DISTINCT R.COD_LECTOR_ORI
        FROM SGC_TT_REGISTRO_LECTURAS R
        WHERE R.PERIODO = '$perini' AND R.ID_ZONA = '$zona' AND R.COD_LECTOR_ORI IS NOT NULL $where
        GROUP BY R.COD_LECTOR_ORI
        ORDER BY R.COD_LECTOR_ORI)";
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
	
	
	public function ObtieneLectorObslec($perini,$proyecto,$zona,$lecini){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		$sql = "SELECT DISTINCT R.COD_LECTOR_ORI, U.NOM_USR, U.APE_USR
        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TT_USUARIOS U
        WHERE R.COD_LECTOR_ORI = U.ID_USUARIO
		AND R.PERIODO = '$perini' AND R.OBSERVACION_ACTUAL IS NOT NULL
		AND R.ID_ZONA = '$zona' $where
        GROUP BY R.COD_LECTOR_ORI, U.NOM_USR, U.APE_USR
        ORDER BY R.COD_LECTOR_ORI, U.NOM_USR, U.APE_USR";
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
	
	public function ObtieneObservaciones($perini,$lector,$proyecto,$zona,$observacionop){
		if($lector != '') $where .= " AND R.COD_LECTOR_ORI = '$lector'";
		$sql = "SELECT OBSERVACION_ACTUAL, COUNT(R.OBSERVACION_ACTUAL)CANTIDAD
		FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
		WHERE Z.ID_ZONA = R.ID_ZONA
		AND Z.ID_PROYECTO = '$proyecto' AND R.OBSERVACION_ACTUAL IS NOT NULL
		AND R.ID_ZONA = '$zona' AND R.OBSERVACION_ACTUAL = '$observacionop'
		AND R.PERIODO = '$perini' $where
		GROUP BY OBSERVACION_ACTUAL
		ORDER BY OBSERVACION_ACTUAL";
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
	
	
	public function ObtieneTotalesObservacion($perini,$proyecto,$observacionop,$zonini,$zonfin,$lecini){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT COUNT(R.OBSERVACION_ACTUAL)CANTIDAD
        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
        WHERE  R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto'
		AND R.OBSERVACION_ACTUAL = '$observacionop' AND R.OBSERVACION_ACTUAL IS NOT NULL
		AND R.PERIODO = '$perini'
		$where ";
		//echo $sql.'<br>';
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
	
	
	public function seleccionaPagosEntidad($proyecto,$entini,$entfin,$fecpagini,$fecpagfin,$puntPagIni,$puntPagFin,$cajaini,$cajafin){
        $where="";
        if($entini != '' && $entfin == '') $entfin = $entini;
		if($entini == '' && $entfin != '') $entini = $entfin;
		if($entini != '' && $entfin != '') $where .= " AND E.COD_ENTIDAD BETWEEN $entini AND $entfin";
		if($puntPagIni != '' && $puntPagFin != '') $where .= " AND NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO) BETWEEN $puntPagIni AND $puntPagFin";
		if($cajaini != '' && $cajafin != '') $where .= " AND C.NUM_CAJA BETWEEN $cajaini AND $cajafin";
		if($proyecto != '') $where .= " AND I.ID_PROYECTO = '$proyecto'";
//		$sql = "SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(MP.VALOR)IMPORTE, (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD)ENTIDAD, (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION)PUNTO, C.DESCRIPCION,FP.DESCRIPCION MEDIO
//        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_pago mp, SGC_TP_FORMA_PAGO FP
//        WHERE C.ID_CAJA = P.ID_CAJA
//        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
//        AND FP.CODIGO=MP.ID_FORM_PAGO
//        AND MP.ID_PAGO=P.ID_PAGO
//        AND R.ENTIDAD_COD=E.COD_ENTIDAD
//        AND E.ACTIVO='S'
//        AND I.CODIGO_INM = P.INM_CODIGO
//        AND P.FECHA_PAGO BETWEEN TO_DATE('$fecpagini 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fecpagfin 23:59:59','YYYY-MM-DD hh24:mi:ss')
//        $where
//        GROUP BY (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD), (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION), C.DESCRIPCION,FP.DESCRIPCION
//        ORDER BY (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD), (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION), C.DESCRIPCION ,FP.DESCRIPCION";
		//echo $sql.'<br>';


        		$sql = "select  *
                        from (SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(MP.VALOR)IMPORTE, (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD)ENTIDAD, (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION)PUNTO, C.DESCRIPCION,FP.DESCRIPCION MEDIO,'PAGO' TIPO
                        ,R.ID_PUNTO_PAGO PPAGOCOMP
                        FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_pago mp, SGC_TP_FORMA_PAGO FP
                        WHERE C.ID_CAJA = P.ID_CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND FP.CODIGO=MP.ID_FORM_PAGO
                        AND MP.ID_PAGO=P.ID_PAGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.ACTIVO='S'
                        AND P.ESTADO='A'
                        AND I.CODIGO_INM = P.INM_CODIGO
                        AND P.FECHA_PAGO BETWEEN TO_DATE('$fecpagini 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fecpagfin 23:59:59','YYYY-MM-DD hh24:mi:ss')
                        $where
                        GROUP BY (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD), (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION), C.DESCRIPCION,FP.DESCRIPCION,R.ID_PUNTO_PAGO

                        UNION   all

                        SELECT COUNT(P.IMPORTE)CANTIDAD, SUM(MP.VALOR)IMPORTE, (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD)ENTIDAD, (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION)PUNTO, C.DESCRIPCION,FP.DESCRIPCION MEDIO,'OTRO RECAUDO' TIPO,R.ID_PUNTO_PAGO
                        FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TT_INMUEBLES I,sgc_tt_medios_RECAUDO mp, SGC_TP_FORMA_PAGO FP
                        WHERE C.ID_CAJA = P.CAJA
                        AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                        AND FP.CODIGO=MP.ID_FORM_PAGO
                        AND MP.ID_OTRREC=P.CODIGO
                        AND R.ENTIDAD_COD=E.COD_ENTIDAD
                        AND E.ACTIVO='S'
                        AND P.ESTADO IN ('T','A')
                        AND I.CODIGO_INM = P.INMUEBLE
                        AND P.FECHA_PAGO BETWEEN TO_DATE('$fecpagini 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE ('$fecpagfin 23:59:59','YYYY-MM-DD hh24:mi:ss')
                        $where
                        GROUP BY (E.COD_ENTIDAD||' - '||E.DESC_ENTIDAD), (NVL(R.COD_VIEJO,R.ID_PUNTO_PAGO)||' - '||R.DESCRIPCION), C.DESCRIPCION,FP.DESCRIPCION,R.ID_PUNTO_PAGO PPAGOCOMP
                        )
                        ORDER BY 3,4,5,7 desc ,6";




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
