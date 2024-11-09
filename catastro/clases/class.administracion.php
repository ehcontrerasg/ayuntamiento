<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}
class Administracion extends ConexionClass{
	Private $id_urbanizacion;
	Private $desc_urbanizacion;
	Private $id_proyecto;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_urbanizacion="";
		$this->desc_urbanizacion="";
		$this->id_proyecto="";
		
	}
	public function setid($valor){
		$this->id_urbanizacion=$valor;
	}
	
	public function setdesc_urbanizacion($valor){
		$this->desc_urbanizacion=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
	
	
	public function obtenerurbanizacion (){
		$resultado = oci_parse($this->_db,"SELECT URB.ID_PROYECTO, URB.CONSEC_URB, URB.DESC_URBANIZACION
				 FROM SGC_TP_URBANIZACIONES URB 
				ORDER BY ID_PROYECTO DESC, DESC_URBANIZACION ASC");
		
	
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
	
	public function obtenervias (){
		$resultado = oci_parse($this->_db,"SELECT N.ID_PROYECTO, N.CONSEC_NOM_VIA, N.DESC_NOM_VIA, V.DESC_TIPO_VIA
				 FROM SGC_TP_NOMBRE_VIA N, SGC_TP_TIPO_VIA V 
				 WHERE N.ID_TIPO_VIA = V.ID_TIPO_VIA AND N.ID_PROYECTO = V.ID_PROYECTO
				ORDER BY ID_PROYECTO DESC, DESC_NOM_VIA ASC");
		
	
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
	
	
	public function obtenerciclos (){
		$resultado = oci_parse($this->_db,"SELECT ID_CICLO, DESC_CICLO
				 FROM SGC_TP_CICLOS
				ORDER BY ID_CICLO");
		
	
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
	
	
	public function obtenerzonas (){
		$resultado = oci_parse($this->_db,"SELECT ID_ZONA, ID_PROYECTO
				 FROM SGC_TP_ZONAS
				ORDER BY ID_ZONA");
		
	
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
	
	public function obtenersecrut (){
		$resultado = oci_parse($this->_db,"SELECT ID_PROYECTO, ID_SECTOR, ID_RUTA
				 FROM SGC_TP_RUTAS
				ORDER BY ID_PROYECTO, ID_SECTOR, ID_RUTA");
		
	
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
	
	 public function actualizaurba($id_urba, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE CONSEC_URB = '$id_urba'";
        $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}

    }
	
	 public function actualizavia($id_via, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE CONSEC_NOM_VIA = '$id_via'";
        $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}

    }
	
	 public function actualizaciclos($id_ciclo, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE ID_CICLO = '$id_ciclo'";
        $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}

    }
	
}
