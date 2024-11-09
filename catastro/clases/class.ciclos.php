<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}
    else if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}

class Ciclos extends ConexionClass{
	private $id_ciclo;
	private $desc_ciclo;
	private $id_proyecto;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_ciclo="";
		$this->desc_ciclo="";
		$this->id_proyecto="";
	}
	public function setid($valor){
		$this->id_ciclo=$valor;
	}
	
	public function setdesc_ciclo($valor){
		$this->desc_ciclo=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}

	

	public function NuevoCiclo(){
		
 		$resultado=oci_parse($this->_db," BEGIN SGC_P_INSERTA_CICLO('$this->desc_ciclo',:PMSGRESULT,:PCODRESULT);COMMIT;END;");
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
	
}
