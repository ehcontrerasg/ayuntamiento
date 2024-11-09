<?php
require_once "../../clases/class.conexion.php";

class modificacion extends ConexionClass {

    public function CantidadRegistros ($fname,$tname,$where,$sort){
        $resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE  $where $sort");
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

    public function obtenerLecturas($where,$sort,$tname,$periodo){
	
		 $sql="SELECT L.COD_INMUEBLE, L.LECTURA_ACTUAL, TO_CHAR(L.FECHA_LECTURA_ORI,'DD/MM/YYYY HH24:MI:SS') FEC_LEC, L.OBSERVACION, L.COD_LECTOR,
        (SELECT SUM (D.UNIDADES) 
        FROM SGC_TT_DETALLE_FACTURA D , SGC_TT_FACTURA F
        WHERE D.CONCEPTO in (1,3) 
		AND F.CONSEC_FACTURA=D.FACTURA
		AND F.FEC_EXPEDICION IS NULL
		AND D.COD_INMUEBLE=L.COD_INMUEBLE AND D.PERIODO = L.PERIODO) CONSUMO_INM,
        L.POSIBLE_LECT, R.CONSUMO_MINIMO, R.CONSUMO_MAXIMO, L.LECTURA_ORIGINAL, L.OBSERVACION_ACTUAL ,
        TO_CHAR(mi.FECHA_INSTALACION,'YYYY-MM-DD ')   FECHA_INSTALACION,
        SI.CONSUMO_MINIMO CONSUMO,
		(SELECT LA.LECTURA_ACTUAL FROM SGC_TT_REGISTRO_LECTURAS LA WHERE LA.COD_INMUEBLE = L.COD_INMUEBLE AND LA.PERIODO = TO_CHAR(ADD_MONTHS(TO_dATE($periodo,'YYYYMM'),-1),'YYYYMM')
		)LECANTERIOR
        FROM $tname 
        WHERE $where $sort 
        ";
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
	
	
	
	
	 public function actualizaConsumo($periodo, $cod_inm, $consumo, $coduser){
		$sql="BEGIN SGC_P_MODIFICA_CMO($periodo,$cod_inm,$consumo,'$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
       	if($bandera){
        	if($this->codresult==0){
            	return true;
            }
            else{
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
	public function actualizaLectura($periodo, $cod_inm, $lectura, $observa, $coduser){
		$sql="BEGIN SGC_P_MODIFICA_LECTURA($periodo,$cod_inm,$lectura,'$observa','$coduser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        //echo $sql;
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
       	if($bandera){
        	if($this->codresult==0){
            	return true;
            }
            else{
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
	 public function obtenerObserva($periodo,$cod_inm,$where,$tname){
	 	$sql = "SELECT OBSERVACION_ACTUAL OBSERVACION
        FROM $tname 
        WHERE $where";
        $resultado = oci_parse($this->_db, $sql);
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
	
	public function obtenerLectura($periodo,$cod_inm,$where,$tname){
		$sql = "SELECT LECTURA_ACTUAL
        FROM $tname 
        WHERE $where";
		
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