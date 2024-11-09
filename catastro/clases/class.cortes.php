<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

class cortes_inmuebles extends ConexionClass{
	Private $cod_inmueble;



 			

 	public function __construct()
 	{
 		parent::__construct();
 		$this->cod_inmueble="";

 	}
	public function setcodinmueble($valor){
		$this->cod_inmueble=$valor;
	}



	public function Todos ($where,$sort,$start,$end){
		$resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM
				(SELECT ID_PERIODO,TIPO_CORTE,LECTURA,IMPO_CORTE,IMPO_LECTURA,TO_CHAR(FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHA,OBS_GENERALES FROM SGC_TT_REGISTRO_CORTES
				WHERE ID_INMUEBLE=ID_INMUEBLE
			 	$where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");

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

	public function CantidadRegistros ($fname,$tname,$where,$sort){

		$resultado = oci_parse($this->_db,"SELECT count(1) CANTIDAD FROM SGC_TT_REGISTRO_CORTES  WHERE ID_INMUEBLE=ID_INMUEBLE
				 $where $sort");
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

