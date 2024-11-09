<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class NombreVia extends ConexionClass{
	Private $consec_nvia;
	Private $id_nvia;
	Private $desc_nvia;
	Private $id_tvia;
	Private $id_proyecto;
	
		
	
	public function __construct()
	{
		parent::__construct();
		$this->consec_nvia="";
		$this->id_nvia="";
		$this->desc_nvia="";
		$this->id_tvia="";
		$this->id_proyecto="";
		
	}
	public function setconnvia($valor){
		$this->consec_nvia=$valor;
	}
	
	public function setdidnvia($valor){
		$this->id_nvia=$valor;
	}
	
	public function setdescnvia($valor){
		$this->desc_nvia=$valor;
	}

	public function setidtvia($valor){
		$this->id_tvia=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}

	public function NuevaCallen(){
		
 		$resultado=oci_parse($this->_db," BEGIN SGC_P_INSERTA_CALLE('$this->desc_nvia','$this->id_proyecto',$this->id_tvia,:PMSGRESULT,:PCODRESULT);COMMIT;END;");		
 		oci_bind_by_name($resultado,':PCODRESULT',$codresult,"123");
		oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,"123");
		$bandera=oci_execute($resultado);
		if($bandera=TRUE && $codresult==0 ){
			oci_close($this->_db);
			return $codresult;
		}else{
			oci_close($this->_db);
			echo "$msgresult: $codresult";
			return $codresult;
		}
	}
	
	
	public function obtenernvia ($proyecto,$id_tipovia){
		$resultado = oci_parse($this->_db,"SELECT NVIA.DESC_NOM_VIA, NVIA.CONSEC_NOM_VIA
				 FROM SGC_TP_NOMBRE_VIA NVIA 
				WHERE NVIA.ID_PROYECTO = '$proyecto'
				AND NVIA.ID_TIPO_VIA='$id_tipovia'
				ORDER BY DESC_NOM_VIA");
		
	
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
	
	
	public function obtenernonmbrevia ($secvia){
		$resultado = oci_parse($this->_db,"SELECT NVIA.DESC_NOM_VIA
				FROM SGC_TP_NOMBRE_VIA NVIA
				WHERE NVIA.CONSEC_NOM_VIA = '$secvia'
				");
	
	
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
	
	
	public function obtenernvia2 ($proyecto,$id_tipovia,$liken){
		$resultado = oci_parse($this->_db,"SELECT NVIA.DESC_NOM_VIA, NVIA.CONSEC_NOM_VIA
				 FROM SGC_TP_NOMBRE_VIA NVIA 
				WHERE NVIA.ID_PROYECTO = '$proyecto'
				AND NVIA.ID_TIPO_VIA='$id_tipovia'
				AND NVIA.DESC_NOM_VIA LIKE '%$liken%'
				ORDER BY DESC_NOM_VIA  ");
		
	
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
