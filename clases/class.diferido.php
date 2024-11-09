<?php
include_once "class.conexion.php";


class Diferido extends ConexionClass{


/*PRUEBA GITLAB*/
//MI COMENTARIO

    public function __construct()
    {
        parent::__construct();
    }

    public function getCuotasDifByDifFlexy($where,$sort,$start,$end)
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
