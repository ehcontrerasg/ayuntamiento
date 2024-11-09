<?php

include_once '../../clases/class.conexion.php';

class Lectuas extends ConexionClass{
	

		
	public function __construct()
	{
		parent::__construct();

	}
	


	public function TodosLecHis ($codigoInmueble)
	{	
		$sql = "SELECT ROWNUM NUMERO, HL.*
		FROM (SELECT REGL.PERIODO,
					 REGL.LECTURA_ACTUAL,
					 OBSL.DESCRIPCION,
					 TO_CHAR(REGL.FECHA_LECTURA, 'dd/mm/yyyy hh24:mi:ss') FECHA,
					 (USR.NOM_USR || ' ' || USR.APE_USR) NOMBRE
				FROM SGC_TT_registro_lecturas  regl,
					 sgc_tt_usuarios           usr,
					 sgc_tp_observaciones_lect obsl
			   WHERE USR.ID_USUARIO(+) = REGL.COD_LECTOR
				 and OBSL.CODIGO = REGL.OBSERVACION
				 AND regl.cod_inmueble = $codigoInmueble
			   ORDER BY REGL.PERIODO DESC) HL";		
			
		$statement = oci_parse($this->_db,$sql);
		if (oci_execute($statement)) {
			
			$array = array();
			while ($fila = oci_fetch_assoc($statement)) {
				array_push($array, array(
					"NUMERO"=>$fila["NUMERO"], 
					"PERIODO"=>$fila["PERIODO"], 
					"LECTURA_ACTUAL"=> $fila["LECTURA_ACTUAL"], 
					"DESCRIPCION"=>$fila["DESCRIPCION"], 
					"FECHA"=>$fila["FECHA"], 
					"NOMBRE"=>$fila["NOMBRE"]));
			}	
			echo json_encode($array);
			oci_close($this->_db);
		}else{
			$error = oci_error($statement);
			return json_encode(array("message"=>$error["message"],"code"=>$error["code"]));
		}
	
	}
	
	
	
	public function CantidadLecHis ($fname,$tname,$where,$sort)
	{	
		$resultado = oci_parse($this->_db,"
				SELECT COUNT(1) CANTIDAD FROM ( 
				SELECT *  FROM SGC_TT_REGISTRO_LECTURAS REGL WHERE
				REGL.INTENTOS>-1 $where		
				) ");

			
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