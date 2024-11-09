<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/29/2015
 * Time: 3:38 PM
 */
if (is_file("../../clases/class.conexion.php"))
{
    include "../../clases/class.conexion.php";
}
else if (is_file("../clases/class.conexion.php"))
{
    include "../clases/class.conexion.php";
}

class AperturaZona extends ConexionClass{
    private $codResult;
    private $msResult;

    public function __construct(){
        parent::__construct();
        $this->codResult=0;

    }

    public function getCodResult()
    {
        return $this->codResult;
    }


    public function getMsResult()
    {
        return $this->msResult;
    }

    public function AbrePeriodoAseo($zona,$periodo){


        $sql="BEGIN SGC_P_APERTURA_CICL_FACT_ASEO('$zona',$periodo,:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msResult,4000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codResult,32);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
        	if( $this->codResult>0){
            	return false;
        	}else{
        		return true;
        	}
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function BorraPeriodo($zona,$periodo,$proyecto){
        $sql="BEGIN SGC_P_BORRA_FACTURAS('$proyecto','$zona',$periodo,:PMSGRESULT,:PCODRESULT);COMMIT;END;";

        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msResult,123);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codResult,123);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codResult>0){
                return false;
            }else{
                return true;
            }
        }
        else{
            oci_close($this->_db);
            $this->msResult="error en la consulta";
            return false;
        }
    }

    public function ModificaFechas($zona,$periodo,$fecexp,$fecven,$feccor){

        if($fecexp != '')
            $sql1 = "FEC_EXPEDICION = TO_DATE('$fecexp','yyyy-mm-dd')";
        else
            $sql1 = "FEC_EXPEDICION = FEC_EXPEDICION";

        if($fecven != '')
            $sql2 = "FEC_VCTO  = TO_DATE('$fecven','yyyy-mm-dd')";
        else
            $sql2 = "FEC_VCTO  = FEC_VCTO";

        if($feccor != '')
            $sql3 = "FECHA_CORTE = TO_DATE('$feccor','yyyy-mm-dd')";
        else
            $sql3 = "FECHA_CORTE = FECHA_CORTE";

        $sql="UPDATE SGC_TT_FACTURA SET $sql1 , $sql2, $sql3
              WHERE PERIODO = $periodo AND ID_ZONA = '$zona'";

        $resultado= oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->codResult>0){
                return false;
            }else{
                return true;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

}