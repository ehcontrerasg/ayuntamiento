<?php  
  //include_once "../../clases/class.conexion.php";
  require '../../clases/class.conexion.php';
  class fn_oracle extends ConexionClass{
   // public $msgError;
    public $parse;
    public $vmsj_error;
    public $where = '';
    //public $cod_calibre =
    public function getQuery($caso){
      switch ($caso) {
        case 1:
          return "SELECT u.desc_uso, r.valor_tarifa, c.desc_calibre, r.medidor
				  FROM sgc_tp_tarifas_reconexion r, sgc_tp_calibres c, sgc_tp_usos u
				 WHERE/* (u.id_uso = 'R' OR u.id_uso = 'I' OR u.id_uso = 'C' OR
				       u.id_uso = 'M')
				   AND */u.id_uso = r.codigo_uso
				   AND u.id_uso = '$this->id_uso'				   
				   AND u.visible ='S'
				   --AND u.id_uso = 'R'
				   --AND c.cod_calibre != 0
				   AND c.cod_calibre = r.codigo_calibre
				   AND r.proyecto = 'SD'
				 ORDER BY c.desc_calibre, r.medidor ASC";
          break;
        case 2:
        	return "SELECT c.cod_calibre, c.desc_calibre FROM sgc_tp_calibres c ORDER BY cod_calibre ASC";
        	break;
       	case 3:
       		return "SELECT u.id_uso, u.desc_uso FROM sgc_tp_usos u WHERE u.visible ='S'";
        case 4:
          return"SELECT i.codigo_inm, i.id_zona, i.id_proyecto, f.consec_factura, f.total
                   FROM sgc_tt_inmuebles i, sgc_tt_factura f
                  where /*(I.ID_ZONA = '51A' OR I.ID_ZONA = '51B' OR I.ID_ZONA = '51C' OR
                            I.ID_ZONA = '61A' OR I.ID_ZONA = '61B' OR
                            I.ID_TIPO_CLIENTE = 'GC')*/
                  i.codigo_inm in
                  (SELECT fd.cod_inmueble
                     FROM sgc_tt_detalle_factura     fd,
                          SGC_TP_RANGOS_TARIFAS      RT,
                          sgc_tt_servicios_inmuebles sim
                    where fd.cod_inmueble IN
                          (select i.codigo_inm
                             from sgc_tt_inmuebles i, sgc_tt_servicios_inmuebles sm
                            where sm.cod_inmueble = i.codigo_inm
                              AND (i.id_zona LIKE '%60%' or i.id_sector LIKE '%52%' OR
                                  I.ID_TIPO_CLIENTE = 'GC')
                              AND (sm.cod_servicio = 2 or sm.cod_servicio = 4)
                              AND I.ID_PROYECTO = 'SD')
                      and sim.cod_inmueble = fd.cod_inmueble
                      and sim.cod_servicio = fd.concepto
                      and fd.periodo = 201709
                      and (fd.concepto = 2 or fd.concepto = 4)
                      and rt.consec_tarifa = sim.consec_tarifa
                      and rt.rango = fd.rango
                      and fd.valor != ROUND(rt.valor_metro * fd.unidades))
                  and f.inmueble = i.codigo_inm
                  and f.periodo = 201709
                  order by i.id_proyecto, i.id_zona DESC";
        case 5:
          return " SELECT s.desc_servicio, fd.unidades, rt.valor_metro, fd.valor
                     from sgc_tt_detalle_factura     fd,
                          sgc_tt_servicios_inmuebles sm,
                          sgc_tp_rangos_tarifas      rt,
                          sgc_tp_servicios           s
                    where fd.factura = $this->factura
                      and sm.cod_inmueble = fd.cod_inmueble
                      and sm.cod_servicio = fd.concepto
                      and rt.consec_tarifa = sm.consec_tarifa
                      and rt.rango = fd.rango
                      and s.cod_servicio = sm.cod_servicio";
      }
    }
    public function getProcedure($caso){
      switch ($caso) {
        case 1:
          return "";
          break;
      }
    }
    public function setBindVar($caso) {
      switch ($caso) {
        case 1:
          oci_bind_by_name($this->parse, ':vmsj_error', $this->vmsj_error);
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
    	/*echo($query);
      echo "<br><br>";*/
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
      $this->getProcedure($procedure);
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