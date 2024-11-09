<?php
include_once '../../clases/class.conexion.php';
class Observaciones extends ConexionClass{
    Private $cod_inmueble;
    private $codresult;
    private $msgresult;

    public function __construct()
    {
        parent::__construct();
        $this->cod_inmueble="";
        $this->codresult="";
        $this->msgresult="";

    }
    public function setcodinmueble($valor){
        $this->cod_inmueble=$valor;
    }

    public function getMsError(){
        return $this->msgresult;
    }

    public function getCodRes(){
        return $this->codresult;
    }



    public function Todos ($where,$sort,$start,$end,$inmueble){
        $resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM
				(SELECT replace(replace(OBS.ASUNTO,chr(10),''),chr(13),'') ASUNTO,  OBS.CODIGO_OBS,
				replace(replace(OBS.DESCRIPCION,chr(10),''),chr(13),'') DESCRIPCION
				 ,TO_CHAR(OBS.FECHA,'YYYY/MM/DD HH24:MI:SS') FECHA,
				USR.LOGIN, OBS.CONSECUTIVO
				FROM SGC_TT_OBSERVACIONES_INM OBS, SGC_TT_USUARIOS USR
				WHERE OBS.INM_CODIGO='$inmueble' AND OBS.USR_OBSERVACION=USR.ID_USUARIO $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");
        
       
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

    public function CantidadRegistros ($fname,$tname,$where,$sort){

        $resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE OBS.USR_OBSERVACION=USR.ID_USUARIO   $where $sort");
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


    public function seltiposObs (){
        $sql="SELECT TOB.CODIGO, TOB.DESCRIPCION FROM SGC_TP_TIPOS_OBS TOB
            WHERE TOB.VISIBLE='S'
            ORDER BY DESCRIPCION ";

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


    public function NuevaObs($usrobs,$asunto,$descripcion,$codigo,$inmueble){


        $sql= "BEGIN AGC_P_ING_OBS('$asunto','$codigo','$descripcion','$usrobs',$inmueble,:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera)
        {
            if ($this->codresult>0)
            {
                oci_close($this->_db);
                return $this->msgresult." ".$this->codresult;
            }
            else{
                oci_close($this->_db);
                return "true";
            }
        }else{
            oci_close($this->_db);
            return "false";
        }
    }


}
