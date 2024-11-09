<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 11/2/2015
 * Time: 3:47 PM
 */
include_once '../../clases/class.conexion.php';
class Reconexion extends ConexionClass {
    public function __construct()
    {
        parent::__construct();
    }

    ////////////////// facturas pendientes ///////////////////////

    public function recTotal ($where,$sort,$start,$end)
    {
       $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT RR.FECHA_PLANIFICACION,FECHA_EJE,RR.TIPO_RECONEXION,RR.OBS_GENERALES,RR.FECHA_ACUERDO,U.LOGIN FROM SGC_tT_REGISTRO_RECONEXION RR,
                        SGC_TT_USUARIOS U
                        WHERE U.ID_USUARIO(+)=RR.USR_EJE
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