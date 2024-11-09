<?php
include_once 'class.conexion.php';
date_default_timezone_set('America/Santo_Domingo');
class personal extends ConexionClass{
    private $codresult;
    private $msgresult;

    public function __construct(){
        parent::__construct();

    }

    public function getcodresult(){return $this->codresult;}
    public function getmsgresult(){return $this->msgresult;}




    public function todosareas ()
    {
        $sql="select A.DESC_AREA, A.ID_AREA from sgc_tp_areas a ORDER BY A.ID_AREA";
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



    public function todoscargos ()
    {
        $sql="SELECT C.ID_CARGO,C.DESC_CARGO, A.DESC_AREA, A.ID_AREA FROM SGC_TP_CARGOS C, SGC_TP_AREAS A
WHERE A.ID_AREA=C.ID_AREA
      ORDER BY ID_AREA ASC, C.ID_CARGO ASC ";
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


    public function actualizaarea($id_area, $tname, $field, $data){
        $sql = "UPDATE $tname SET $field = UPPER('$data') WHERE ID_AREA = $id_area";
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


    public function actualizacargo($id_area, $tname, $field, $data){
        $sql = "UPDATE $tname SET $field = UPPER('$data') WHERE ID_CARGO = $id_area";
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

    public function agregaarea($descripcion,$id){
        echo $sql="BEGIN SGC_P_AREAS ($id, '$descripcion',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }


    public function agregacargo($descripcion,$id,$idcargo){
        echo $sql="BEGIN SGC_P_AGRCARGO ($id,$idcargo, '$descripcion',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function obtenerareas (){
        $resultado = oci_parse($this->_db,"SELECT AR.ID_AREA, AR.DESC_AREA
                            FROM SGC_TP_areas ar ORDER BY(AR.DESC_AREA)");



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