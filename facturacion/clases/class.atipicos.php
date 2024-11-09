<?php
include_once "../../clases/class.conexion.php";
class Atipicos extends ConexionClass{
	private $coduser;
	private $operario_asignado;
	private $operario_desasignado;
	private $zona;
	private $ruta;
	private $periodo;
    private $codresult;
    private $msgresult;
	
	public function __construct(){
		parent::__construct();
		$this->coduser="";
		$this->operario_asignado="";
		$this->operario_desasignado="";
		$this->zona="";
		$this->ruta="";
		$this->periodo="";
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }
	
	
	 public function seleccionaProyecto($coduser){
		$sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO 
		FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
		WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
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
	
	public function seleccionaPeriodo($proyecto){
		$sql = "SELECT L.PERIODO 
        FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z,SGC_TP_PERIODO_ZONA PZ
        WHERE 
        PZ.PERIODO=L.PERIODO AND
        PZ.ID_ZONA=L.ID_ZONA
        AND PZ.FEC_APERTURA IS NOT NULL
        AND PZ.FEC_CIERRE IS NULL
              
        AND L.ID_ZONA = Z.ID_ZONA AND FECHA_LECTURA_ORI IS NOT NULL AND Z.ID_PROYECTO = '$proyecto'
        GROUP BY L.PERIODO ORDER BY L.PERIODO DESC";
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
	
	public function seleccionaSector($periodo, $proyecto){
		$sql = "SELECT L.ID_ZONA 
        FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS S , SGC_TP_PERIODO_ZONA PZ
        WHERE 
        PZ.ID_ZONA=L.ID_ZONA
        AND PZ.PERIODO='$periodo'
        AND PZ.FEC_APERTURA IS NOT NULL
        AND PZ.FEC_CIERRE IS NULL
        and L.ID_ZONA= S.ID_ZONA
        AND L.PERIODO = '$periodo' AND S.ID_PROYECTO = '$proyecto'
        AND L.FECHA_LECTURA_ORI IS NOT NULL
        GROUP BY L.ID_ZONA ORDER BY 1 ASC";
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
	
	public function seleccionaZona($periodo, $proyecto){
		$sql = "SELECT F.ID_ZONA
		FROM SGC_TT_REGISTRO_ENTREGA_FAC F, SGC_TP_ZONAS Z
		WHERE F.ID_ZONA = Z.ID_ZONA AND PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' --AND F.ID_ZONA = '$sector' 
		GROUP BY F.ID_ZONA ORDER BY 1 ASC";
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
	
	
	public function seleccionaAtipico(){
		$sql = "SELECT ID_ATIPICO, DESC_ATIPICO
		FROM SGC_TP_ATIPICOS
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
	
	public function obtenerAtipicos($proyecto, $periodo, $sector){
		$sql = "SELECT L.COD_INMUEBLE, C.CONSUMO_MINIMO, C.CONSUMO_MAXIMO, C.PROMEDIO, L.LECTURA_ACTUAL, L.OBSERVACION_ACTUAL, L.CONSUMO_ACT CONSUMO, MI.FECHA_INSTALACION
        FROM SGC_TT_RANGOS_CONSUMOS C, SGC_TT_REGISTRO_LECTURAS L, sgc_tt_factura f, sgc_tt_detalle_factura df, SGC_TT_MEDIDOR_INMUEBLE MI
        WHERE  f.periodo=$periodo
        and f.inmueble=L.COD_INMUEBLE
        and  F.FEC_EXPEDICION IS NULL
        and C.COD_INM(+) = L.COD_INMUEBLE AND C.PERIODO(+) = L.PERIODO
        AND L.PERIODO = $periodo AND F.ID_ZONA = '$sector'
        AND DF.FACTURA=F.CONSEC_FACTURA
        AND DF.CONCEPTO IN (1,3)
        AND DF.RANGO=0
        AND (
        NVL(C.CONSUMO_MAXIMO,0)<L.LECTURA_ACTUAL
        OR NVL(C.CONSUMO_MINIMO,0)>L.LECTURA_ACTUAL )
        AND MI.COD_INMUEBLE=L.COD_INMUEBLE
        AND MI.FECHA_BAJA(+) IS NULL
        ORDER BY L.COD_INMUEBLE";
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
	
	public function obtenerLecAnterior($proyecto, $periodo, $sector, $cod_inmueble){
		$sql = "SELECT L.LECTURA_ACTUAL, L.OBSERVACION
        FROM SGC_TT_REGISTRO_LECTURAS L
        WHERE L.COD_INMUEBLE = '$cod_inmueble' AND L.PERIODO = TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM') AND L.ID_ZONA = '$sector'";
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
	
	
	public function existefotolec($cod_inmueble,$periodo)
	{
		$sql="SELECT COUNT(*) CANTIDAD FROM SGC_TT_FOTOS_LECTURA FL
		WHERE FL.ID_INMUEBLE='$cod_inmueble' AND FL.ID_PERIODO = $periodo";
		//echo $sql;
		$resultado = oci_parse($this->_db,$sql);
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			while (oci_fetch($resultado)) {
				$cantidad = oci_result($resultado, 'CANTIDAD');
			}oci_free_statement($resultado);
			if($cantidad==0){
				$existe=false;
			}else{
				$existe=true;
			}
				
			oci_close($this->_db);
			return $existe;
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	
	}
	
	
}