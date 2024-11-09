<?php 
class materiales extends ConexionClass{
	private $idinmueble;
	private $tiporec;
	private $cod_material;
	private $unidades;
	private $periodo;
	private $fechacor;
	private $orden;
	
	
	public function __construct(){
		parent::__construct();
		$this->idinmueble="";
		$this->tiporec="";
		$this->cod_material="";
		$this->unidades="";
		$this->periodo="";
		$this->fechacor="";
		$this->orden="";
	}
	
	public function setinmueble			($valor){$this->idinmueble=$valor;}
	public function settiporec			($valor){$this->tiporec=$valor;}
	public function setmaterial			($valor){$this->cod_material=$valor;}
	public function setunidades			($valor){$this->unidades=$valor;}
	public function setfecha			($valor){$this->fechacor=$valor;}
	public function setperiodo			($valor){$this->periodo=$valor;}
	public function setorden			($valor){$this->orden=$valor;}
	

	
	public function guardamaterial(){
		$resultado=oci_parse($this->_db,"INSERT INTO SGC_TT_MAT_USADO_RECONEXION (COD_INMU,COD_RECONEXION,COD_MATERIAL,UNIDADES,PERIODO,ORDEN)  VALUES(
				'$this->idinmueble','$this->tiporec','$this->cod_material','$this->unidades','$this->periodo','$this->orden')");	
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return true;
		}else{
			oci_close($this->_db);
			echo "Error al guardar el corte";
			return false;
		}
	
	}
	
}
