<?php
include_once 'class.conexion.php';
class observacion extends ConexionClass{
	private $fecha;
	private $descripcion;
	private $id_operario;
	
	public function __construct(){
		parent::__construct();
		$this->fecha="";	
	}
	
	public function setfecha($valor)		{$this->fecha=$valor;}
	public function setdescripcion($valor)	{$this->descripcion=$valor;}
	public function setoperario($valor)		{$this->id_operario=$valor;}
	
	public function obtenertiporeconexion(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION
				FROM SGC_TP_TIPO_RECONEXION  ");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener llos tipos de corte";
			return false;
		}
		
	}
	
	
	public function obtenerobsreconexion(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION, TIPO
				FROM SGC_TP_OBS_RECONEXION ");

		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener llos tipos de corte";
			return false;
		}
	
	}

	
	public function obtenermateriales(){
		$resultado=oci_parse($this->_db,"SELECT MPC.COD_RECONEXION, MPC.ID_MATERIAL, MA.DESCRIPCION,  UM.DESCRIPCION UNIDAD FROM SGC_TP_MATERIAL_X_RECONEXION MPC, 
				SGC_TP_UNIDADES_MEDIDA UM, SGC_TP_MATERIALES MA
				WHERE 
				MPC.ID_MATERIAL= MA.ID_MATERIAL
				AND UM.CODIGO=MA.UNIDAD_MEDIDA 
				");
	
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener los materiales de reconexion";
			return false;
		}
	
	}
	
	public function obtenermedidores()
	{
		$resultado=oci_parse($this->_db,"SELECT CODIGO_MED, DESC_MED FROM
			SGC_TP_MEDIDORES
			WHERE
			ESTADO_MED='S'
			AND TO_DATE(FECHA_CREA, 'DD/MM/YYYY') >= TO_DATE('$this->fecha', 'DD/MM/YYYY')");
			$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else
			
		{
			oci_close($this->_db);
			echo "Error al obtener los medidores";
			return false;
		}
	}
	
	public function obtenercalibres()
	{
		$resultado=oci_parse($this->_db,"SELECT COD_CALIBRE, DESC_CALIBRE FROM
				SGC_TP_CALIBRES
				WHERE
				TO_DATE(FECHA_CREA, 'DD/MM/YYYY') >= TO_DATE('$this->fecha', 'DD/MM/YYYY')");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else
			
		{
			oci_close($this->_db);
			echo "Error al obtener los medidores";
			return false;
		}
	}
	
	
	public function obtenercortesant(){

        $sql="select  
                RC.ORDEN ORDEN, 
                TO_CHAR(RC.FECHA_EJE,'YYYYMM') PERIODO, 
                RC.ID_INMUEBLE, 
                RC.TIPO_CORTE,
                TO_CHAR(RC.FECHA_EJE,'YYYY/MM/DD') FECREA 
            from 
                SGC_TT_REGISTRO_CORTES RC
            where 
                RC.FECHA_EJE IS NOT NULL AND
                RC.ID_INMUEBLE IN (
                select
                        RR.ID_INMUEBLE 
                       from sgc_tt_registro_reconexion rr
                       where RR.FECHA_EJE is null
                       AND rr.ID_INMUEBLE=RC.ID_INMUEBLE
                       and RR.USR_EJE='$this->id_operario')         
                       ORDER BY RC.FECHA_EJE DESC";
		$resultado=oci_parse($this->_db,$sql);
	
				$bandera=oci_execute($resultado);
				if($bandera){
				oci_close($this->_db);
				return $resultado;
	}else{
				oci_close($this->_db);
				echo "Error al obtener los cortes anteriores";
				return false;
		}
	
    }




}