<?php

//if (is_file("../clases/class.conexion.php"))
//{
    include_once "../../clases/class.conexion.php";
/*}
if (is_file("clases/class.conexion.php"))
{
    include "clases/class.conexion.php";
}*/


class tarifa extends ConexionClass {

    public function CantidadRegistros ($fname,$tname,$where,$sort){

        $resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE TAR.CONSEC_TARIFA=TAR.CONSEC_TARIFA $where $sort");
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

    public function tar_especifica($consec_tar){
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

    }



    public function obtenertarifas($where,$sort,$start,$end){
        $resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM
          (SELECT TAR.CONSEC_TARIFA, TAR.DESC_TARIFA,TAR.COD_SERVICIO,TAR.COD_USO,TAR.CODIGO_TARIFA,TAR.CONSUMO_MIN,
          (SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=1) RANGO_1,
          (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=1) VALOR_1,
          (SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=2) RANGO_2,
          (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=2) VALOR_2,
          (SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=3) RANGO_3,
          (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=3) VALOR_3,
          (SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=4) RANGO_4,
          (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=4) VALOR_4,
          (SELECT RT.LIMITE_MAX FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=5) RANGO_5,
          (SELECT RT.VALOR_METRO FROM SGC_TP_RANGOS_TARIFAS RT WHERE TAR.CONSEC_TARIFA=RT.CONSEC_TARIFA AND RT.RANGO=5) VALOR_5
          FROM SGC_TP_TARIFAS TAR
        WHERE TAR.CONSEC_TARIFA=TAR.CONSEC_TARIFA $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");
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