<?php

include_once '../../clases/class.conexion.php';

class Reportes_Cat_No_Activo extends ConexionClass{
	

		
	public function __construct()
	{
		parent::__construct();

	}
	
	public function obtieneCantidadInmuebles($proyecto)
	{
		$sql="SELECT A.ID_USO, COUNT(I.CODIGO_INM)CANTIDAD
			FROM SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A 
			WHERE I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD 
			AND I.ID_ESTADO = 'CT'
			AND I.ID_PROYECTO = '$proyecto'
			GROUP BY A.ID_USO";
	
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
		
}