<?php
include "class.conexion.php";


class Observacion extends ConexionClass{
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


    public function setObsInm($asunto,$codObs,$obs,$usrIn,$inm){

        $sql="BEGIN AGC_P_ING_OBS('$asunto','$codObs','$obs','$usrIn',$inm,:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function getObsByInmFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT OI.CODIGO_OBS,REPLACE(REPLACE(REPLACE(ASUNTO,CHR(10),' ') ,CHR(13),' ') ,'  ',' ') ASUNTO,REPLACE(REPLACE(REPLACE(DESCRIPCION,CHR(10),' ') ,CHR(13),' ') ,'  ',' ') DESCRIPCION,TO_CHAR(OI.FECHA,'DD/MM/YYYY HH24:MM')  FECHA ,U.LOGIN  
						FROM SGC_TT_OBSERVACIONES_INM OI, SGC_TT_USUARIOS U
                        WHERE U.ID_USUARIO=OI.USR_OBSERVACION
                        $where
                        $sort
                   )where  rownum<1000
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1
					";
      // echo $sql;
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
