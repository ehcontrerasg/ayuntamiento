<?php
include_once '../../clases/class.conexion.php';
class Migracion extends ConexionClass{
	Private $id_proyecto;
	Private $id_sector;
	Private $id_ruta;
	Private $codigo_sistema;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_sector="";
		$this->id_ruta="";
		$this->codigo_sistema="";
		
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
	public function setidsector($valor){
		$this->id_sector=$valor;
	}
	
	public function setidruta($valor){
		$this->id_ruta=$valor;
	}
	
	public function setcodsistema($valor){
		$this->codigo_sistema=$valor;
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }

	public function seleccionaAcueducto (){
		$resultado = oci_parse($this->_db,"SELECT ID_PROYECTO, SIGLA_PROYECTO
				 FROM SGC_TP_PROYECTOS");
		
	
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
	
	
	public function seleccionaSector ($proyecto){
		$resultado = oci_parse($this->_db,"SELECT ID_SECTOR
				 FROM SGC_TP_SECTORES
				 WHERE ID_PROYECTO = '$proyecto' ORDER BY ID_SECTOR");
		
	
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
	
	
	public function seleccionaRuta ($sector){
		$resultado = oci_parse($this->_db,"SELECT ID_RUTA
				 FROM SGC_TP_RUTAS
				 WHERE ID_SECTOR = '$sector'");
		
	
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
	
	public function seleccionaCiclo (){
		$resultado = oci_parse($this->_db,"SELECT ID_CICLO
				 FROM SGC_TP_CICLOS");
		
	
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
	
	public function obtenerInmuebles ($proyecto, $sector, $ruta, $sector2, $ruta2, $ciclo,$manzana2, $where){

	     $sql="SELECT I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO, 
		CONCAT('$sector2','$ciclo') NUEVA_ZONA,
		CONCAT('$sector2$ruta2',SUBSTR(I.ID_PROCESO,5))NUEVO_PROCESO, '$sector2'||NVL('$manzana2',SUBSTR(I.CATASTRO,3,3)) ||SUBSTR(I.CATASTRO,6) NUEVO_CATASTRO
		FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U
		WHERE I.CONSEC_URB = U.CONSEC_URB $where
		ORDER BY I.ID_PROCESO ASC";
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
	
	public function verificaProceso($nuevo_proceso, $inmueble){
		$resultado = oci_parse($this->_db,"SELECT I.ID_PROCESO
		FROM SGC_TT_INMUEBLES I
		WHERE I.ID_PROCESO = '$nuevo_proceso'
		AND I.CODIGO_INM<>$inmueble");
		
	
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
	
	public function verificaCatastro($nuevo_catastro,$inmueble){
		$resultado = oci_parse($this->_db,"SELECT I.CATASTRO
		FROM SGC_TT_INMUEBLES I
		WHERE I.CATASTRO = '$nuevo_catastro'
		AND I.CODIGO_INM<>$inmueble");
		
	
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
	
	
	 public function migraInmuebles($inm_migrado, $proyecto, $nueva_zon, $nuevo_pro, $nuevo_cat, $coduser, $sector2, $ruta2, $ciclo){
       	$sql="BEGIN SGC_P_MIGRA_INMUEBLES('$inm_migrado', '$proyecto', '$nueva_zon', '$nuevo_pro','$nuevo_cat', '$coduser', '$sector2', '$ruta2', '$ciclo', :PMSGRESULT,:PCODRESULT);COMMIT;END;";
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
