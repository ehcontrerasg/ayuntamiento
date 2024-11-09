<?php
include_once 'class.conexion.php';
class observacion extends ConexionClass{
	private $fecha;
	private $descripcion;
	private $id_operario;
	
	public function __construct(){
		parent::__construct();
		$this->fecha="";	
	}
	
	public function setfecha($valor)		{$this->fecha=$valor;}
	public function setdescripcion($valor)	{$this->descripcion=$valor;}
	public function setoperario($valor)		{$this->id_operario=$valor;}
	
	public function obtenerActMantCor(){
		$resultado=oci_parse($this->_db,
            "SELECT 
                        M.DESCRIPCION, 
                        M.ID_ACTMANTMED CODIGO 
                     FROM 
                        SGC_TP_ACT_MANTMED_COR M");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener los tipos de corte";
			return false;
		}
		
	}
	public function obtenerActInstMed(){
		$resultado=oci_parse($this->_db,
            "SELECT 
                        M.DESCRIPCION, 
                        M.ID_ACTINSTMED CODIGO 
                     FROM 
                        SGC_TP_ACT_INSTMED M");
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener las actividades";
			return false;
		}

	}

    public function obtenercalibres()
    {
        $resultado=oci_parse($this->_db,"SELECT COD_CALIBRE CODIGO, DESC_CALIBRE DESCRIPCION FROM
				SGC_TP_CALIBRES where ACTIVO='S'
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener los medidores";
            return false;
        }
    }
	public function obtenermedidores()
    {
        $resultado=oci_parse($this->_db,"SELECT M.CODIGO_MED CODIGO, M.DESC_MED DESCRIPCION FROM
				SGC_TP_MEDIDORES M
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener los medidores";
            return false;
        }
    }


	public function obtenerEmplazamiento()
    {
        $resultado=oci_parse($this->_db,"SELECT E.COD_EMPLAZAMIENTO CODIGO, E.DESC_EMPLAZAMIENTO DESCRIPCION FROM
				SGC_TP_EMPLAZAMIENTO E
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener los medidores";
            return false;
        }
    }



    public function obtenerEstMed()
    {
        $resultado=oci_parse($this->_db,"SELECT E.CODIGO CODIGO, E.DESCRIPCION DESCRIPCION FROM
				SGC_TP_ESTMED E
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener los medidores";
            return false;
        }
    }
    public function obtenerObsLect()
    {
        $resultado=oci_parse($this->_db,"SELECT E.CODIGO CODIGO, E.DESCRIPCION DESCRIPCION FROM
				SGC_TP_OBSERVACIONES_LECT E
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener las observaciones";
            return false;
        }
    }
    public function obtenerActPrev()
    {
        $resultado=oci_parse($this->_db,"SELECT AC.DESCRIPCION , AC.ID_ACTMANTMED CODIGO  from SGC_TP_ACT_MANTMED AC
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener las observaciones";
            return false;
        }
    }
    public function obtenerActCorr()
    {
        $resultado=oci_parse($this->_db,"SELECT AC.DESCRIPCION , AC.ID_ACTMANTMED CODIGO  from SGC_TP_ACT_MANTMED_COR AC
				");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else

        {
            oci_close($this->_db);
            echo "Error al obtener las observaciones";
            return false;
        }
    }



}