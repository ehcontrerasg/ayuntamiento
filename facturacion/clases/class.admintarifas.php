<?php
include_once "../../clases/class.conexion.php";

class tarifas extends ConexionClass {


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

   /* public function tar_especifica($consec_tar){
        $sql="SELECT * FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$consec_tar'";
        $resultado=oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE){
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }*/



   public function obtenertarifas($where,$sort,$start,$end, $tname){
        $resultado = oci_parse($this->_db,"SELECT TAR.CONSEC_TARIFA, TAR.DESC_TARIFA, TAR.COD_SERVICIO, TAR.COD_USO, TAR.CODIGO_TARIFA, TAR.CONSUMO_MIN, TAR.COD_PROYECTO,
		(SELECT RT.LIMITE_MIN FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=0 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMIN_0,
		(SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=0 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMAX_0,
        (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=0 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) VALOR_0,
		(SELECT RT.LIMITE_MIN FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=1 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMIN_1,
		(SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=1 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMAX_1,
        (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=1 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) VALOR_1,
		(SELECT RT.LIMITE_MIN FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=2 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMIN_2,
		(SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=2 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMAX_2,
        (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=2 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) VALOR_2,
		(SELECT RT.LIMITE_MIN FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=3 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMIN_3,
		(SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=3 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMAX_3,
        (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=3 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) VALOR_3,
		(SELECT RT.LIMITE_MIN FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=4 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMIN_4,
		(SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=4 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMAX_4,
        (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=4 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) VALOR_4,
		(SELECT RT.LIMITE_MIN FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=5 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMIN_5,
		(SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=5 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) LMAX_5,
        (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=5 AND RT.PERIODO = (SELECT MAX(PERIODO) FROM SGC_TP_RANGOS_TARIFAS RT)) VALOR_5
        FROM $tname TAR
        WHERE $where $sort ");
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
	
	
	 public function actualizaTarifas($id_tarifa, $tname, $field, $data){
	 	$sql = "UPDATE $tname SET $field = UPPER('$data') WHERE CONSEC_TARIFA = $id_tarifa";
        $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}

    }
	
	 public function actualizaRangosTarifas($id_tarifa, $tnamea, $field, $data, $rango){
	 	$sql = "UPDATE $tnamea SET $field = UPPER('$data') WHERE CONSEC_TARIFA = $id_tarifa AND RANGO = '$rango'";
        $resultado = oci_parse($this->_db,$sql);
       if(oci_execute($resultado)){
			oci_close($this->_db);
			return true;
		}
		else{
			oci_close($this->_db);
			echo "error con la actualizacion";
			return false;
		}

    }
	
}