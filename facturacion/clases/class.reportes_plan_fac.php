<?php

include_once '../../clases/class.conexion.php';
class Reportes_Plan_Fac extends ConexionClass{
	private $id_proyecto;
	private $id_zonini;
	private $id_zonfin;
	private $id_perini;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_zonini="";
		$this->id_zonfin="";
		$this->id_perini="";
	}

	
	public function ObtienePlanificacionRuta($perini,$proyecto,$secini,$secfin,$fecini){
		if($secini != '' && $secfin == '') $secfin = $secini;
		if($secini == '' && $secfin != '') $secini = $secfin;
		if($secini != '' && $secfin != '') $where .= " AND SUBSTR(RL.ID_ZONA,0,2) BETWEEN UPPER('$secini') AND UPPER('$secfin')";
		if($fecini != '' ) $where .= " AND FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
				AND TO_DATE('$fecini 23:59:59','YYYY-MM-DD HH24:MI:SS')";
		$sql = "SELECT USR_EJE, (U.NOM_USR||' '||U.APE_USR) USUARIO, RL.ID_ZONA, COUNT(RL.COD_INMUEBLE) CANTIDAD, USR_ASIGNA, 
				TO_CHAR(FECHA_PLANIFICACION,'DD/MM/YYYY')FECHA_PLANIFICACION
				FROM SGC_TT_REGISTRO_ENTREGA_FAC RL, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
				WHERE U.ID_USUARIO = RL.USR_EJE AND RL.COD_INMUEBLE = I.CODIGO_INM 
				AND RL.PERIODO = $perini AND I.ID_PROYECTO = '$proyecto' $where
				GROUP BY USR_EJE, (U.NOM_USR||' '||U.APE_USR), RL.ID_ZONA, USR_ASIGNA, TO_CHAR(FECHA_PLANIFICACION,'DD/MM/YYYY')
				ORDER BY ID_ZONA ";
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
	
	public function ObtieneDetalleRutas($cod_lector, $zona,$perini, $fecPla){
		 $sql = "SELECT RUTA
		FROM SGC_TT_REGISTRO_ENTREGA_FAC R
		WHERE USR_EJE = '$cod_lector' AND ID_ZONA = '$zona' AND PERIODO = $perini
		AND TO_CHAR(FECHA_PLANIFICACION,'DD/MM/YYYY')='$fecPla'
		GROUP BY RUTA ORDER BY RUTA";
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
	
	
	public function ObtieneAsignador($asignador){
		$sql = "SELECT (NOM_USR||' '||APE_USR) NOM_ASIGNADOR
		FROM SGC_TT_USUARIOS U , SGC_TT_REGISTRO_ENTREGA_FAC R
		WHERE U.ID_USUARIO = R.USR_ASIGNA AND U.ID_USUARIO = '$asignador'";
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
	
	
}
