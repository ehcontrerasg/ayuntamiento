<?php
date_default_timezone_set('America/Santo_Domingo');
include_once '../../clases/class.conexion.php';
class Usuario extends ConexionClass{
	Private $cosusuario;
	Private $nombre;
	Private $apellido;
	private $email;
	private $celular;
	private $login;
	private $pass;
	private $cargo;
	private $fechainicio;
	private $fechafin;
	private $proyecto;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->cosusuario="";
		$this->nombre="";
		$this->apellido="";
		$this->email="";
		$this->celular="";
		$this->login="";
		$this->pass="123";
		$this->cargo="";
		$this->fechainicio=date("d/m/Y H:i:s");
		$this->fechafin="";
		$this->proyecto="";
		
	}
	
	public function setcodusuario	($valor){$this->usuario=$valor;}
	public function setnombre		($valor){$this->nombre=$valor;}
	public function setapellido		($valor){$this->apellido=$valor;}
	public function setemail		($valor){$this->email=$valor;}
	public function setcelular		($valor){$this->celular=$valor;}
	public function setlogin		($valor){$this->login=$valor;}
	public function setpass			($valor){$this->pass=$valor;}
	public function setcargo		($valor){$this->cargo=$valor;}
	public function setfechainicio	($valor){$this->fechainicio=$valor;}
	public function setfechafin		($valor){$this->fechafin=$valor;}
	public function setproyecto		($valor){$this->proyecto=$valor;}
	
		
	public function obtenerusuario (){
		$resultado = oci_parse($this->_db,"SELECT ID_USUARIO, NOM_USR, APE_USR  FROM SGC_TT_USUARIOS
				WHERE ID_PROYECTO='$this->proyecto' AND ID_CARGO='$this->cargo'");

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
	
	
	public function obtenernomoperario ($operario){
		$resultado = oci_parse($this->_db,"SELECT ID_USUARIO, NOM_USR, APE_USR  FROM SGC_TT_USUARIOS
				WHERE ID_USUARIO='$operario' ");
	
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			while (oci_fetch($resultado)) {
				$nombre = oci_result($resultado, 'NOM_USR');
				$apellido = oci_result($resultado, 'APE_USR');
				
			}oci_free_statement($resultado);
			oci_close($this->_db);
			return $nombre." ".$apellido;
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	
	}
	
		
}
