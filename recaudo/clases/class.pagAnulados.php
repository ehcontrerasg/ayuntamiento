<?php 
	/**
	* 
	*/
	require_once 'class.conexion.php';
	class pagAnulados extends ConexionClass
	{
		private $_parse;
    	// private $_query;
		public function _getQuery($query) {
			switch ($query) {
				case 1:
					return "SELECT P.id_pago,
								   p.inm_codigo,
							       p.importe,
							       (select u.nom_usr||' '||u.ape_usr from sgc_tt_usuarios u where u.ID_USUARIO = p.id_usuario) id_usuario,
							       p.fecha_pago,
							       r.fecha_rev,
							       r.motivo_rev,
							       (select u.nom_usr||' '||u.ape_usr from sgc_tt_usuarios u where u.ID_USUARIO = r.usr_rev) usr_rev
							FROM   sgc_tt_pagos p, sgc_tt_rev_pago r
							WHERE r.id_pago = p.id_pago
							order by p.fecha_pago asc
							  -- AND r.motivo_rev LIKE '%/%'
							   --AND p.inm_codigo = 194775
						      /*AND p.fecha_pago LIKE trunc(to_date('$this->fech_pago', 'DD-MM-YYYY'))
						      AND r.fecha_rev LIKE trunc(to_date('$this->fech_rev', 'DD-MM-YYYY'))
						      AND p.id_usuario = '223-0169775-5'
					    	  AND r.usr_rev = '80095626'*/
						  	  ";
					break;
				case 2:
					return "SELECT u.id_usuario,
						       u.nom_usr nombre,
						       u.ape_usr apellido,
						       u.email_usr,
						       (select c.desc_cargo
						          from sgc_tp_cargos c
						         where c.id_cargo = u.id_cargo) cargo
						  	from sgc_tt_usuarios u";
					break;
				default:
					# code...
					break;
			}
		}
	    public function _consultar($sql) {
	        // $link = new OracleConn(UserGeneral, PassGeneral)->link;
	        // Preparar la sentencia
	        //echo $sql;
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
		function __destruct(){
	        oci_close($this->_db);
	        oci_free_statement($this->_parse);
	    }
	}

 ?>