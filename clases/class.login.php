<?php 
	/**
	* 
	*/

	include_once 'class.conexion.php';
	class Login extends ConexionClass
	{
		private $_pass;
		private $_user;
		private $_parse;
		public $data;

		private function _consultar($sql) {
	        // $link = new OracleConn(UserGeneral, PassGeneral)->link;
	        // Preparar la sentencia
	        ($this->_db);
	        $parse = oci_parse($this->_db, $sql);
	        if (!$parse) {
	            $e = oci_error($this->_db);
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }
	        // Realizar la lógica de la consulta
	        $result = oci_execute($parse);
	        if (!$result) {
	            $e = oci_error($result);
	            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	        }else{
	        	$this->_parse = $parse;
	            return $parse;
	        }

	        //oci_close($this->_db);
	        //oci_free_statement($parse);
	        //$result = oci_fetch_array($parse);
	        // oci_fetch_all($parse, $result);
	        
	    }

		
	    private function _getQuery($query) {
	    	switch ($query) {
	    		case 1:
					return "SELECT id_usuario, nom_usr, ape_usr, pass, pass1, login, pass_vence
					FROM sgc_tt_usuarios
				   WHERE login = '".$this->_user."'
					 AND pass1 = '".$this->_pass."'
					 AND fec_fin IS NULL
					 AND (pass_vence IS NULL OR pass_vence >= sysdate)";
	    			break;
	    	}
	    }
	    public function isUser($data){
	    	if (($data['LOGIN'][0]==$this->_user) and ($data['PASS1'][0]==$this->_pass)) {
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }
		public function setSession($data) {
    		$_SESSION['tiempo'] = time();
			$_SESSION["usuario"] = $data['LOGIN'][0];
			//echo ' sesion :'.$data['LOGIN'][0].' sesion';
			$_SESSION["contrasena"] = $data['PASS1'][0];
			$_SESSION["codigo"] = $data['ID_USUARIO'][0];	
			$_SESSION["nombre"] = $data['NOM_USR'][0]." ".$data['APE_USR'][0];
			$_SESSION["pass_vence"] = $data['pass_vence'][0];
		}

		function __construct($user, $pass) {
			parent::__construct();
			$this->_user = $user;
			$this->_pass = md5($pass);
			$this->data = $this->_consultar($this->_getQuery(1));
		}
		function __destruct(){
			
	        oci_close($this->_db);
	        oci_free_statement($this->_parse);
	    }
	}


 ?>