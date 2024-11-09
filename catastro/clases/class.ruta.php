<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Ruta extends ConexionClass{
	Private $id_sector;
	Private $id_ruta;
	Private $id_proyecto;
    private $codresult;
    private $msgresult;

	
		
	
	public function __construct()
	{
		parent::__construct();
		$this->id_sector="";
		$this->id_ruta="";
		$this->id_proyecto="";
		
	}

    public function getcodresult(){
        return $this->codresult;
    }
    public function getmsresult(){
            return $this->msgresult;
        }



	public function setidsector($valor){
		$this->id_sector=$valor;
	}
	
	public function setidruta($valor){
		$this->id_ruta=$valor;
	}
	
	public function setidproyecto($valor){
		$this->id_proyecto=$valor;
	}
		
	public function obtenerrutas($proyecto,$sector){

		echo $sql= "SELECT RUT.ID_RUTA
				 FROM SGC_TP_RUTAS RUT 
				WHERE RUT.ID_PROYECTO = '$proyecto' AND RUT.ID_SECTOR='$sector'
				ORDER BY RUT.ID_RUTA";
		$resultado = oci_parse($this->_db,$sql );
		
	
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


    public function crearruta(){
//        funcion que crea una nueva ruta.
//        retorna true si el proceso fue exitoso
//        retorna false si hubo fallo.
        $sql="BEGIN SGC_P_CREA_RUTA($this->id_sector,'$this->id_ruta','$this->id_proyecto',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);

        if($bandera){

            if($this->codresult==0){
                oci_close($this->_db);
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }

        }else{
            oci_close($this->_db);
            return false;
        }




    }


    public function getRangoRutas($proyecto,$rutaIni="",$rutFin="",$sectorIni="",$sectorFin=""){

	    $where="";
	    if(($rutaIni!="" && $rutFin!="")){
            $where = " AND R.ID_RUTA BETWEEN  $rutaIni AND $rutFin";
        }elseif($rutaIni!="" && $rutFin=="" ){
            $where = " AND R.ID_RUTA BETWEEN  $rutaIni AND $rutaIni";
        }elseif($rutaIni=="" && $rutFin!="" ){
            $where = " AND R.ID_RUTA BETWEEN  $rutFin  AND $rutFin";
        }

        if($sectorIni!="" && $sectorFin!=""){
            $where.=" AND R.ID_SECTOR between $sectorIni and $sectorFin ";
        }elseif($sectorIni!="" && $sectorFin==""){
            $where.=" AND R.ID_SECTOR between $sectorIni and $sectorIni ";
        }elseif($sectorIni=="" && $sectorFin!=""){
            $where.=" AND R.ID_SECTOR between $sectorFin and $sectorFin ";
        }



        $sql="SELECT R.ID_RUTA,R.ID_SECTOR
              FROM SGC_TP_RUTAS R
              WHERE R.ID_PROYECTO = '$proyecto'"
              .$where.
              " ORDER BY R.ID_SECTOR, R.ID_RUTA";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

}
