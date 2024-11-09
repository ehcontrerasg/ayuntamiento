<?php  
  //include_once "../../clases/class.conexion.php";
  require '../../clases/class.conexion.php';
  class cortes extends ConexionClass{
   // public $msgError;
    public $parse;
    public $cod_error;
    public $vmsj_error;
    public $where = '';
    public function getQuery($caso){
      switch ($caso) {
        case 1:
          return "SELECT OBS.ASUNTO,
					     OBS.CODIGO_OBS,
					     replace(OBS.DESCRIPCION, chr(13) || chr(10), ' ') DESCRIPCION,
					     TO_CHAR(OBS.FECHA, 'YYYY/MM/DD HH24:MI:SS') FECHA,
					     USR.LOGIN,
					     OBS.CONSECUTIVO
				    FROM SGC_TT_OBSERVACIONES_INM OBS, SGC_TT_USUARIOS USR
				   WHERE OBS.INM_CODIGO = $this->cod_inm
				     AND OBS.USR_OBSERVACION = USR.ID_USUARIO
             /*AND OBS.CODIGO_OBS = (SELECT c.codigo
                           FROM sgc_tp_obs_corte c
                          WHERE c.codigo = obs.codigo_obs
                            AND c.visible = 'S')
				--ORDER BY OBS.FECHA ASC*/";
          break;
        case 2:
          return "SELECT c.codigo, c.descripcion FROM sgc_tp_obs_corte c WHERE c.visible = 'S'";
        case 3:
          return "SELECT o.codigo, o.descripcion FROM sgc_tp_tipos_obs o WHERE o.visible = 'S'";
        case 4:
          return "SELECT u.id_usuario FROM sgc_tt_usuarios U WHERE u.login = '$this->user_creacion'";
      }

    }
    public function getProcedure($caso){
      switch ($caso) {
        case 1:
          return "BEGIN
                    AGC_P_ING_OBS(ASUNTO_IN       => '$this->asunto',
                                  CODIGOOBS_IN    => '$this->cod_observacion',
                                  DESCRIPCION_IN  => '$this->desc_opservacion',
                                  USROBS_IN       => '$this->user_creacion',
                                  INMUEBLE_IN     => $this->cod_inm,
                                  CODERROR_OUT    => :cod_error,
                                  MSERROR_OUT     => :msg_error);
                    COMMIT;
                  END;";
          break;
      }
    }
    public function setBindVar($caso) {
      switch ($caso) {
        case 1:
          oci_bind_by_name($this->parse, ':msg_error', $this->vmsj_error);
          oci_bind_by_name($this->parse, ':cod_error', $this->cod_error);
          break;
        
      }
    }    
    public function setParse($query){
      $this->parse = oci_parse($this->_db, $query);
      // $this->parse = $parse;
      if (!$this->parse) {
        $e = oci_error($this->_db);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
      }
    }
    public function exec(){
      //$this->setParse($this->getProcedure($procedure));
      //echo($this->getProcedure($procedure)).'<br><br>';
      //$this->setBindVar($procedure);
      $result = oci_execute($this->parse);
      if (!$result) {
        $e = oci_error($this->parse);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
      }
    }
    public function execQuery($query) {
      $this->parse = oci_parse($this->_db, $query);
      // $this->parse = $parse;
      if (!$this->parse) {
        $e = oci_error($this->_db);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
      }
      // Realizar la lógica de la consulta
      $result = oci_execute($this->parse);
      if (!$result) {
        $e = oci_error($parse);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
      }
      //$result = oci_fetch_array($parse);
      // oci_fetch_all($parse, $result);
      //oci_close($this->_db);
      //oci_free_statement($this->parse);
      return $this->parse;
    }
    public function execProcedure ($procedure) {
     // echo $procedure;
      $this->setParse($this->getProcedure($procedure));
      //echo($this->getProcedure($procedure)).'<br><br>';
      $this->setBindVar($procedure);
      $result = oci_execute($this->parse);
      if (!$result) {
        $e = oci_error($this->parse);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
      }else{
        return $result;
      }
    }

    function __destruct(){
      oci_free_statement($this->parse);
      oci_close($this->_db);
    }
  }

?>