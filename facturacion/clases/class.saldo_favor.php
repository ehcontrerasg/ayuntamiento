<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/26/2015
 * Time: 4:33 PM
 */
include_once '../../clases/class.conexion.php';

class saldo_favor extends ConexionClass {
   // private $codresult;
   // private $msgresult;

    public function __construct()
    {
        parent::__construct();
    }
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }

    public function obtiene_saldos($codinmueble,$sort,$start,$end){
	 $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT CODIGO, TO_CHAR(FECHA,'DD/MM/YYYY HH24:MI:SS')FECHA, SUBSTR(REPLACE(REPLACE(MOTIVO,'#',''),CHR(13),''),0,100) MOTIVO, IMPORTE, VALOR_APLICADO
						  FROM SGC_TT_SALDO_FAVOR
						  WHERE INM_CODIGO=$codinmueble
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
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
	
}