<?php
include "class.conexion.php";


class DeudaCero extends ConexionClass{
    private $mesrror;
    private $coderror;

    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getDeudaCerByInmFlexy ($where,$sort,$start,$end)
    {
          $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select DC.ID_DEUDA_CERO, DC.PERIODO_INI, DC.TOTAL_CUOTAS, DC.FECH_ULTPAGO, DC.ACTIVA, DC.TOTAL_CUOTAS_PAG, DC.TOTAL_DIFERIDO, DC.TOTAL_MORA,
                          CL.NOMBRE_CLI, DC.PERIODO_CARGA_REV PERREV from sgc_tt_deuda_cero dc, SGC_TT_CLIENTES CL
                          WHERE CL.CODIGO_CLI(+)=DC.CLIENTE_ACUERDO
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





}
