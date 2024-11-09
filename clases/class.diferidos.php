<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 11/2/2015
 * Time: 3:47 PM
 */
include_once '../../clases/class.conexion.php';
class diferidos extends ConexionClass {
    public function __construct(){
        parent::__construct();
    }

	public function getCodResult(){
        return $this->codResult;
    }

    public function getMsResult(){
        return $this->msResult;
    }
	
	public function ReversaDiferido($diferido, $cod, $motivo){
       $sql="BEGIN SGC_P_REVERSA_DIF_ASEO($diferido,'$cod','$motivo',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msResult,123);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codResult,123);
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
            echo "error en la consulta";
            return false;
        }
    }


    public function Obtenerresdif ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                         select codigo, S.DESC_SERVICIO, dif.valor_diferido,numero_cuotas,round(valor_cuota)valor_cuota,cuotas_pagadas,valor_pagado,(dif.valor_diferido-valor_pagado) pendiente
                          from sgc_tt_diferidos_aseo dif, sgc_tp_servicios s
                          where DIF.CONCEPTO = S.COD_SERVICIO
                          AND  dif.activo='S'
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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


    public function detalledif ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select DIF.COD_DIFERIDO, DIF.PERIODO,NUM_CUOTA,VALOR,VALOR_PAGADO,FECHA_PAGO
                          from sgc_tt_DETALLE_DIFERIDOS DIF
                           where ID_DET_DIF>0
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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

    public function todos_dif($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          SELECT D.CODIGO, S.DESC_SERVICIO,D.FECHA_APERTURA,D.ACTIVO,D.CUOTAS_PAGADAS,D.VALOR_DIFERIDO,D.VALOR_PAGADO,D.NUMERO_CUOTAS,D.PER_INI, D.CONCEPTO_DIF
                          FROM SGC_TT_DIFERIDOS D,SGC_TP_SERVICIOS S
                          WHERE S.COD_SERVICIO=D.CONCEPTO
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
//        echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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





    public function cuotasdif($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          SELECT DD.NUM_CUOTA,DD.VALOR,DD.VALOR_PAGADO,DD.PERIODO,DD.FECHA_PAGO
                            FROM SGC_TT_DIFERIDOS D,sgc_tt_detalle_diferidos dd
                            WHERE DD.COD_DIFERIDO=D.CODIGO
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
//        echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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