<?php  
  //include_once "../../clases/class.conexion.php";
  //require("../clases/class.conexion.php");
  class conexiones{
   // public $msgError;
    private $parse;
    public $_db;
    public $vmsj_error;
    public $where = '';

    
    public function __construct() {
      $str_conexion = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.1.192)(PORT = 1521)))(CONNECT_DATA=(SID=ACEASOFT)))";
      $this->_db = oci_connect('ACEASOFT', 'acea', $str_conexion, 'AL32UTF8');
      if ( $this->_db==FALSE )
      {
        oci_close($this->_db);
        echo "Fallo al conectar la base";
       
       return false;
      }

    }

    public function getQuery($caso){
      switch ($caso) {
        case 1:
          return "";
    
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


  }

?>
