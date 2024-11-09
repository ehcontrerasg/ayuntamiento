<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}
class Consulta extends ConexionClass{
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function seleccionaAcueducto (){
		$sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
		FROM SGC_TP_PROYECTOS
		ORDER BY SIGLA_PROYECTO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaTipoVia (){
		$sql = "SELECT DISTINCT DESC_TIPO_VIA, ID_TIPO_VIA
		FROM SGC_TP_TIPO_VIA 
		ORDER BY DESC_TIPO_VIA";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaEstado (){
		$sql = "SELECT ID_ESTADO, DESC_ESTADO
		FROM SGC_TP_ESTADOS_INMUEBLES
		ORDER BY DESC_ESTADO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaGrupo (){
		$sql = "SELECT COD_GRUPO, DESC_GRUPO
		FROM SGC_TP_GRUPOS
		ORDER BY COD_GRUPO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaMarcaMed (){
		$sql = "SELECT CODIGO_MED, DESC_MED
		FROM SGC_TP_MEDIDORES
		ORDER BY CODIGO_MED";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaEmplazamiento (){
		$sql = "SELECT COD_EMPLAZAMIENTO, DESC_EMPLAZAMIENTO
		FROM SGC_TP_EMPLAZAMIENTO
		ORDER BY COD_EMPLAZAMIENTO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaMetodo (){
		$sql = "SELECT COD_SUMINISTRO, DESC_SUMINISTRO
		FROM SGC_TP_MET_SUMINISTRO
		ORDER BY COD_SUMINISTRO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaConcepto (){
		$sql = "SELECT COD_SERVICIO, DESC_SERVICIO
		FROM SGC_TP_SERVICIOS
		ORDER BY COD_SERVICIO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaUso (){
		$sql = "SELECT ID_USO, DESC_USO
		FROM SGC_TP_USOS
		ORDER BY DESC_USO";
		$resultado = oci_parse($this->_db, $sql);
		
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
	
	public function seleccionaTarifa (){
		$sql = "SELECT CONSEC_TARIFA, DESC_TARIFA, COD_USO
		FROM SGC_TP_TARIFAS
		ORDER BY COD_USO, DESC_TARIFA";
		$resultado = oci_parse($this->_db, $sql);
		
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






	
	public function obtenerInmuebles ($proyecto, $sector, $ruta, $sector2, $ruta2, $ciclo, $where){
		$resultado = oci_parse($this->_db,"SELECT I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO, 
		CONCAT('$sector2','$ciclo') NUEVA_ZONA,
		CONCAT('$sector2$ruta2',SUBSTR(I.ID_PROCESO,5))NUEVO_PROCESO, CONCAT('$sector2',SUBSTR(I.CATASTRO,3))NUEVO_CATASTRO
		FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U
		WHERE I.CONSEC_URB = U.CONSEC_URB $where
		ORDER BY I.ID_PROCESO ASC");
		
	
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
	
	public function verificaProceso($nuevo_proceso){
		$resultado = oci_parse($this->_db,"SELECT I.ID_PROCESO
		FROM SGC_TT_INMUEBLES I
		WHERE I.ID_PROCESO = '$nuevo_proceso'");
		
	
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
	
	public function verificaCatastro($nuevo_catastro){
		$resultado = oci_parse($this->_db,"SELECT I.CATASTRO
		FROM SGC_TT_INMUEBLES I
		WHERE I.CATASTRO = '$nuevo_catastro'");
		
	
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
