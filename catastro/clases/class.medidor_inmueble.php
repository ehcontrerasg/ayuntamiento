<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Medidor_inmueble extends ConexionClass{
	Private $cod_inmueble;
	Private $cod_medidor;
 	Private $cod_emplazamiento;
 	Private $fecha_instalacion;
 	Private $fecha_baja;
 	Private $usr_creacion;
 	private $metodo_suministro;
 
 	
 			
	
 	public function __construct()
 	{
 		parent::__construct();
 		$this->cod_inmueble="";
 		$this->cod_medidor="";
 		$this->cod_emplazamiento="";
 		$this->fecha_instalacion="";
 		$this->fecha_baja="";
 		$this->usr_creacion="";
 		$this->metodo_suministro="";
 	}
	public function setcodinmueble($valor){
		$this->cod_inmueble=$valor;
	}
	
	public function setcodmedidor($valor){
		$this->cod_medidor=$valor;
	}
	
	public function setcodemplazamiento($valor){
		$this->cod_emplazamiento=$valor;
	}
	public function setfechains($valor){
		$this->fecha_instalacion=$valor;
	}
	
	public function setfechabaja($valor){
		$this->fecha_baja=$valor;
	}
	
	public function setusrcreacion($valor){
		$this->usr_creacion=$valor;
	}
	public function setmetsum($valor){
		$this->metodo_suministro=$valor;
	}
	
	public function Todos ($where,$sort,$start,$end){
		$resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM
				(SELECT MI.COD_INMUEBLE,  M.DESC_MED, ESM.DESCRIPCION, EM.DESC_EMPLAZAMIENTO, CA.DESC_CALIBRE,MI.SERIAL, TO_CHAR(MI.FECHA_INSTALACION,'DD/MM/YYYY HH24:MI:SS') FECHA_INSTALACION,
				MI.FECHA_BAJA,MI.METODO_SUMINISTRO
				FROM SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_ESTADOS_MEDIDOR ESM, SGC_TP_EMPLAZAMIENTO EM, SGC_TP_MEDIDORES M, SGC_TP_CALIBRES CA
				WHERE  MI.COD_MEDIDOR=M.CODIGO_MED 
				AND ESM.CODIGO=MI.ESTADO_MEDIDOR
				AND MI.COD_EMPLAZAMIENTO=EM.COD_EMPLAZAMIENTO AND MI.COD_CALIBRE=CA.COD_CALIBRE $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");
			
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
	
		$resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_EMPLAZAMIENTO EM, SGC_TP_MEDIDORES M, SGC_TP_CALIBRES CA WHERE MI.COD_MEDIDOR=M.CODIGO_MED 
				AND MI.COD_EMPLAZAMIENTO=EM.COD_EMPLAZAMIENTO AND MI.COD_CALIBRE=CA.COD_CALIBRE  $where $sort");
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
	

	

	
	public function nuevomedidor (){
		$resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_MEDIDOR_INMUEBLE VALUES ('$this->cod_inmueble',
				'$this->cod_medidor','$this->cod_emplazamiento','0','',TO_DATE('$this->fecha_instalacion','yyyy/mm/dd hh24:mi:ss'),
				'','$this->usr_creacion',TO_DATE(SYSDATE,'dd/mm/yy hh24:mi:ss'),'$this->metodo_suministro')");

		
		
	
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
