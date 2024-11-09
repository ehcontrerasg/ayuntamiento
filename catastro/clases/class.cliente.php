<?php
/********************************************************************/
/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
/*  CREADO POR EDWIN HERNAN CONTRERAS								*/
/*  FECHA CREACION 24/10/2014									*/
/********************************************************************/

if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

class Cliente extends ConexionClass{
	Private $cod_cli;
	Private $nombre;
	Private $direccion;
	Private $telefono;
	Private $email;
	Private $tipodoc;
	Private $docuemnto;
	Private $codgrupo;
	Private $dircorrespondencia;
	Private $correspondencia;
	Private $fechacreacion;
	Private $usuariocreacion;
	Private $fechamod;
	Private $usuariomod;
	Private $mesrror;
	Private $coderror;
    private $contribuyente_dgii;

	public function __construct()
	{
		parent::__construct();
		$this->nombre="";
		$this->direccion="";
		$this->telefono="";
		$this->email="";
		$this->tipodoc="";
		$this->docuemnto="";
		$this->codgrupo="";
		$this->dircorrespondencia="";
		$this->correspondencia="";
		$this->fechacreacion="";
		$this->usuariocreacion=""; 
		$this->fechamod="";
		$this->usuariomod="";
		$this->mesrror="";
		$this->coderror="";
		$this->contribuyente_dgii="";


	}
	public function setcodigo($valor){
		$this->cod_cli=$valor;
	}
	public function  getcodigo(){
		return  $this->cod_cli;
	}

	public function setnombre($valor){
		$this->nombre=$valor;
	}
	public function  getnombre(){
		return  $this->nombre;
	}
	public function  geterror(){
		return  $this->mesrror;
	}
	public function  getcoderror(){
		return  $this->coderror;
	}
	public function setdireccion($valor){
		$this->direccion=$valor;
	}
	public function  getdireccion(){
		return $this->direccion;
	}
	public function settelefono($valor){
		$this->telefono=$valor;
	}
	public function gettelefono(){
		return $this->telefono;
	}
	public function setemail($valor){
		$this->email=$valor;
	}
	public function getemail(){
		return $this->email;
	}
	public function settipodoc($valor){
		$this->tipodoc=$valor;
	}
	public function gettipodoc(){
		return $this->tipodoc;
	}
	public function setdocumento($valor){
		$this->docuemnto=$valor;
	}
	public function getdocumento(){
		return $this->docuemnto;
	}
	public function setcodgrupo($valor){
		$this->codgrupo=$valor;
	}
	public function getcodgrupo(){
		return $this->codgrupo;
	}
	public function setdircorrespondencia($valor){
		$this->dircorrespondencia=$valor;
	}
	public function getdircorrespondencia(){
		return $this->dircorrespondencia;
	}
	public function setcorrespondecia($valor){
		$this->correspondencia=$valor;
	}
	public function getcorrespondencia(){
		return $this->correspondencia;
	}
	public function setfechacreacion($valor){
		$this->fechacreacion=$valor;
	}
	public function getfechacreacion(){
		return $this->fechacreacion;
	}
	public function setusuariocreacion($valor){
		$this->usuariocreacion=$valor;
	}
	public function getusuariocreacion(){
		return $this->usuariocreacion;
	}	
	public function setfechamod($valor){
		$this->fechamod=$valor;
	}
	public function getfechamod(){
		return $this->fechamod;
	}
	public function setusrmod($valor){
		$this->usuariomod=$valor;
	}
	public function getusrmod(){
		return $this->usuariomod;
	}

    public function setContribuyenteDgi($contribuyente){
        return $this->contribuyente_dgii =$contribuyente ;
    }

	public function NuevoCliente(){


        $sql       = "BEGIN SGC_P_AGRCLI('$this->nombre','$this->direccion','$this->telefono','$this->email','$this->tipodoc','$this->docuemnto','$this->codgrupo','$this->dircorrespondencia','$this->correspondencia','$this->usuariocreacion', '$this->contribuyente_dgii',:CODERROR_OUT,:MSERROR_OUT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":MSERROR_OUT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":CODERROR_OUT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }


	}
	
	
	public function obtenercocli ($doc){
		$resultado = oci_parse($this->_db,"SELECT CODIGO_CLI FROM SGC_TT_CLIENTES WHERE DOCUMENTO=REPLACE('$doc','-','')");
	
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
		$resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum 
				FROM (SELECT CLI.CODIGO_CLI ID, CLI.NOMBRE_CLI , CLI.DIRECCION DIRECCION  , CLI.TELEFONO, UPPER(CLI.EMAIL) EMAIL , DOC.DESCRIPCION_TIPO_DOC TIPO_DOC, 
				CLI.DOCUMENTO, GRU.DESC_GRUPO , UPPER(CLI.DIR_CORRESPONDENCIA) DIR_CORRESPONDENCIA, CLI.CORRESPONDENCIA  FROM SGC_TT_CLIENTES CLI , SGC_TP_TIPODOC DOC,
				SGC_TP_GRUPOS GRU WHERE CLI.TIPO_DOC= DOC.ID_TIPO_DOC AND CLI.COD_GRUPO=GRU.COD_GRUPO(+)  $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");

		// 		echo "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (SELECT USR.USERNAME, USR.EMAIL, TO_CHAR(USR.FECHA_CREACION,'dd/mm/yyyy hh24:mi:ss') FECHA_CREACION,TO_CHAR(USR.ULTIMA_VISITA,'dd/mm/yyyy hh24:mi:ss') ULTIMA_VISITA, EST.DESCRIPCION STATUS, SUPERUSER  FROM TBL_USERS USR, ESTADOS EST
		// 				WHERE USR.STATUS= EST.ID_ESTADO	 $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";

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
	
	public function especifico ($id,$inmueble,$cedula){
		
		$sql="SELECT *
		FROM (SELECT CLI.NOMBRE_CLi NOMBRE,
					 CLI.DIRECCION,
					 CLI.TELEFONO,
					 nvl(CO.EMAIL, CLI.EMAIL) EMAIL,
					 DOC.DESCRIPCION_TIPO_DOC TIPO_DOC,
					 CLI.TIPO_DOC CODIGO_DOC,
					 CLI.DOCUMENTO,
					 UPPER(CLI.DIR_CORRESPONDENCIA) DIR_CORRESPONDENCIA,
					 CLI.CORRESPONDENCIA,
					 CLI.COD_GRUPO,
					 GRU.DESC_GRUPO GRUPO,
					 CLI.CONTRIBUYENTE_DGI,
					 CO.CODIGO_INM
				FROM SGC_TT_CLIENTES           CLI,
					 SGC_TP_TIPODOC            DOC,
					 SGC_TP_GRUPOS             GRU,
					 ACEASOFT.SGC_TT_CONTRATOS CO
			   WHERE DOC.ID_TIPO_DOC(+) = CLI.TIPO_DOC
				 AND CLI.CODIGO_CLI = CO.CODIGO_CLI
				 AND GRU.COD_GRUPO(+) = CLI.COD_GRUPO
				 AND (CLI.CODIGO_CLI = '$id' OR CLI.DOCUMENTO = '$cedula' or
					  CO.CODIGO_INM = '$inmueble')
			   ORDER BY CO.FECHA_INICIO DESC)
	   WHERE ROWNUM = 1";
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
	
	
public function existe ($id){
		$resultado = oci_parse($this->_db,"SELECT * FROM SGC_TT_CLIENTES WHERE DOCUMENTO='$id'");	
	
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
		$resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE 
				CLI.TIPO_DOC= DOC.ID_TIPO_DOC AND CLI.COD_GRUPO=GRU.COD_GRUPO(+) $where $sort");
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
	
	public function ActulizarCiente(){
          $sql       = "BEGIN SGC_P_ACTCLI('$this->nombre','$this->direccion','$this->telefono','$this->email','$this->tipodoc','$this->docuemnto','$this->codgrupo','$this->dircorrespondencia','$this->correspondencia','$this->usuariocreacion','$this->cod_cli','$this->contribuyente_dgii',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
	}



    public function ActulizarCorreo($email,$inm){
        $sql="BEGIN SGC_P_ACTUALIZAR_CORREO(:CODIGO_INMUEBLE_IN,:CORREO_IN,:MSJ_OUT,:COD_OUT); COMMIT; END;";		
        $resultado = oci_parse($this->_db,$sql);

		oci_bind_by_name($resultado,':CODIGO_INMUEBLE_IN',$inm);
		oci_bind_by_name($resultado,':CORREO_IN',$email);
		oci_bind_by_name($resultado,':MSJ_OUT',$this->mesrror,500);
		oci_bind_by_name($resultado,':COD_OUT',$this->coderror,3);

        $banderas=oci_execute($resultado);
		oci_close($this->_db);
        if($banderas==TRUE){            
			if ($this->codeerror > 0) { return false; }
			return true;
        }
        else{
			echo "false";
            return false;
        }
    }
	
	public function Eliminar_cliente(){
		$resultado = oci_parse($this->_db,"DELETE FROM SGC_TT_CLIENTES WHERE CODIGO_CLI IN ($this->cod_cli)");
		
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
