<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

class Documento extends ConexionClass{
    Private $cod;
    Private $descripcion;
    Private $fecha_creacion;


    public function __construct()
    {
        parent::__construct();
        $this->cod="";
        $this->descripcion="";
        $this->fecha_creacion="";

    }
    public function setcodigo($valor){
        $this->cod=$valor;
    }
    public function  getcodigo(){
        return  $this->cod;
    }

    public function setdescripcion($valor){
        $this->descripcion=$valor;
    }
    public function  getdescripcion(){
        return  $this->descripcion;
    }
    public function setfecha($valor){
        $this->fecha_creacion=$valor;
    }
    public function  getfecha(){
        return $this->fecha_creacion;
    }




    public function NuevoDocumento(){

        $resultado = oci_parse($this->_db," INSERT INTO SGC_TP_TIPODOC VALUES($this->cod,$this->descripcion,$this->fecha_creacion) ");
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


    public function Todos (){
        $resultado = oci_parse($this->_db,"SELECT ID_TIPO_DOC, DESCRIPCION_TIPO_DOC FROM SGC_TP_TIPODOC");
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

