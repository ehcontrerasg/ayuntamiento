<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}
class Calle extends ConexionClass
{
	private $id_calle;
	private $desc_calle;
	private $id_proyecto;
	private $id_tipovia;
	private $codError;
	private $msError;
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_calle="";
		$this->desc_calle="";
		$this->id_proyecto="";
		$this->id_tipovia="";
		
	}
	
	public function getMsError(){
		return $this->msError;
	}
		
	public function getCodError(){
		return $this->codError;
	}
	
	public function setid($valor){
		$this->id_calle=$valor;
	}
	
	public function setdesc_calle($valor){
		$this->desc_calle=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
	
	public function setidtipovia($valor){
		$this->id_tipovia=$valor;
	}
	
	public function NuevaCalle()
	{	
		echo $sql=" BEGIN SGC_P_INSERTA_CALLE('$this->desc_calle','$this->id_proyecto','$this->id_tipovia',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
 		$resultado=oci_parse($this->_db,$sql);
		//echo "BEGIN SGC_P_INSERTA_CALLE('$this->desc_calle','$this->id_proyecto','$this->id_tipovia',:PMSGRESULT,:PCODRESULT)";
		oci_bind_by_name($resultado,':PCODRESULT',$this->codError,"123");
		oci_bind_by_name($resultado,':PMSGRESULT',$this->msError,"123");
		$bandera=oci_execute($resultado);
		if($bandera)
		{
			if($this->codError>0)
			{
				ECHO "FALSE 1";
				oci_close($this->_db);
				return false;
			}else
			{
				echo "true";
				oci_close($this->_db);
				return true;
			}
		}
		else
		{
			echo "false 2";
			oci_close($this->_db);
			return false;
		}
	}
}
