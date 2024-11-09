<?php
include 'Conexion.php';
class Usuarios extends ConexionClass{
	Private $contrato;
	Private $password;
	Private $email;
	Private $fechacreacion;
	Private $ultimavisita;
	Private $superusuario;
	Private $estado;
	public function __construct()
	{
		parent::__construct();
		$this->contrato="0000000";
		$this->email="example@gmail.com";
		$this->estado="0";
		$this->fechacreacion="00000";
		$this->ultimavisita="0000";
		$this->password0="000";
		$this->superusuario="0";		 
	}
	
	public function setcontrato($valor){
		$this->contrato=$valor;
	}	
	public function  getcontrato(){
		return  $this->contrato;
	}	
	public function setemail($valor){
		$this->email=$valor;
	}		
	public function  getemail(){
		return $this->email;
	}
	public function setestado($valor){
		$this->estado=$valor;
	}
	public function getestado(){
		return $this->estado;
	}
	public function setfechcreacion($valor){
		$this->fechacreacion=$valor;
	}
	public function getfechcreacion(){
		return $this->fechacreacion;
	}
	public function setultimavisita($valor){
		$this->ultimavisita=$valor;
	}
	public function getultimavisita(){
		return $this->ultimavisita;
	}
	public function setpassword($valor){
		$this->password=$valor;
	}	
	public function getpassword(){
		return $this->password;
	}
	public function setsuperusr($valor){
		$this->superusuario=$valor;
	}
	public function getsuperusr(){
		return $this->superusuario;
	}
	
	
	public function login(){
		$resultado = oci_parse($this->_db,"SELECT STATUS ESTADO, SUPERUSER ROL FROM  TBL_USERS WHERE USERNAME='$this->contrato' AND PASSWORD='$this->password'");
		$banderas=oci_execute($resultado);


		if($banderas==TRUE)
		{
			oci_close($this->_db);
			return $resultado;
		}
		else
		{
			echo "false";
			return false;
		}
	
	}
	
	public function NuevoUsuario(){
		$resultado = oci_parse($this->_db,"INSERT INTO TBL_USERS VALUES(TBL_USERS_SEQ.NEXTVAL,$this->contrato,$this->password,$this->email,'xxxx',$this->fechacreacion,$this->ultimavisita,$this->superusuario,$this->estado) ");
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			return $resultado;
		}
		else
		{
			echo "false";
			return false;
		}

	}
	
	public function cambiarestado (){
		//echo "UPDATE TBL_USERS SET STATUS='$this->estado' WHERE USERNAME='$this->contrato'";
  		$resultado = oci_parse($this->_db,"UPDATE TBL_USERS SET STATUS='$this->estado' WHERE USERNAME='$this->contrato'");
 
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
	
	
	
	public function Todos ($where,$sort,$start,$end){
		$resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (SELECT USR.USERNAME, LOWER(USR.EMAIL) EMAIL, TO_CHAR(USR.FECHA_CREACION,'yyyy/mm/dd hh24:mi:ss') FECHA_CREACION,TO_CHAR(USR.ULTIMA_VISITA,'yyyy/mm/dd hh24:mi:ss') ULTIMA_VISITA, EST.DESCRIPCION , SUPERUSER  FROM TBL_USERS USR, ESTADOS EST
				WHERE USR.STATUS= EST.ID_ESTADO	AND SUPERUSER='0' $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");
		
// 		echo "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (SELECT USR.USERNAME, USR.EMAIL, TO_CHAR(USR.FECHA_CREACION,'dd/mm/yyyy hh24:mi:ss') FECHA_CREACION,TO_CHAR(USR.ULTIMA_VISITA,'dd/mm/yyyy hh24:mi:ss') ULTIMA_VISITA, EST.DESCRIPCION STATUS, SUPERUSER  FROM TBL_USERS USR, ESTADOS EST
// 				WHERE USR.STATUS= EST.ID_ESTADO	 $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";

		
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			return $resultado;
		}
		else
		{
			echo "false";
			return false;
		}
	
	}
	
	
	public function CantidadRegistros ($fname,$tname,$where,$sort){
		
		$resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE USR.STATUS= EST.ID_ESTADO AND SUPERUSER='0' $where $sort");

		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			return $resultado;
		}
		else
		{
			echo "false";
			return false;
		}
	
	}

}