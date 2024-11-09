<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 11/2/2015
 * Time: 3:47 PM
 */
include_once '../../clases/class.conexion.php';
class Corte extends ConexionClass {
    public function __construct()
    {
        parent::__construct();
    }

    ////////////////// facturas pendientes ///////////////////////

    public function corTotal ($where,$sort,$start,$end)
    {
       $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT RC.ORDEN,RC.FECHA_PLANIFICACION,RC.FECHA_EJE,RC.DESCRIPCION,RC.TIPO_CORTE, US.LOGIN,
						RC.OBS_GENERALES OBS,RC.IMPO_CORTE
						  FROM SGC_TT_REGISTRO_CORTES RC, SGC_TT_USUARIOS US
                        WHERE
                        RC.FECHA_REVERSION IS NULL
                        AND RC.REVERSADO='N'
                        AND US.ID_USUARIO(+)=RC.USR_EJE
                        AND RC.FECHA_PLANIFICACION IS NOT NULL
                        $where
                        $sort
                   )where  rownum<1000
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

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