<?php
include_once 'class.conexion.php';

class FotosFacturaClass extends ConexionClass {
  
	private $consecutivo;
	private $codInspeccion;
	private $fecha;
	private $nombrefoto;
	private $urlfoto;
	private $periodo;
	private $proyecto;
    public function __construct()
    {
    	$this->consecutivo="1";
    	$this->codInspeccion="";
    	$this->fecha="";
    	$this->nombrefoto="";
    	$this->urlfoto="";
    	$this->periodo="";
    	$this->proyecto="SD";
         parent::__construct();
         
    }
    
    public function setconsecutivo($valor){$this->consecutivo=$valor;}
    public function setcocodInspeccion($valor){$this->codInspeccion=$valor;}
    public function setfecha($valor){$this->fecha=$valor;}
    public function setnombre($valor){$this->nombrefoto=$valor;}
    public function seturl($valor){$this->urlfoto=$valor;}
    public function setperiodo($valor){$this->periodo=$valor;}
    public function setproyecto($valor){$this->proyecto=$valor;}
    
    
    
     public function insertarfoto()
    { 
	 $sql="INSERT INTO SGC_TT_FOTOS_inspeccion(CONSECUTIVO, ID_INMUEBLE, FECHA, NOMBRE_FOTO, URL_FOTO, ID_PERIODO, ID_PROYECTO) VALUES ('$this->consecutivo',
        		'$this->codInspeccion',TO_DATE('$this->fecha','YYYYMMDD'),'$this->nombrefoto','$this->urlfoto',
        		'$this->periodo','$this->proyecto') ";
                
        $resultado = oci_parse($this->_db,$sql);
       
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {    
            return $resultado;
        }
        else
        {
            echo "false";
            return false;
        }
       
    }
    
    public function CambiarEstadoFotos($idInmueble)
    {
        $sql = "BEGIN SGC_P_BORRAR_FOTOS_INSP($idInmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        /* echo $sql;*/
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':PCODRESULT', $codresult, "1000");
        oci_bind_by_name($resultado, ':PMSGRESULT', $msgresult, "1000");
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            return $resultado;
        }
        else
        {
            echo "false";
            return false;
        }

    }
    public function existefoto($consecutivo,$codsistema,$nombrefoto)
    {
    	$resultado = oci_parse($this->_db,"SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_inspeccion WHERE CONSECUTIVO='$consecutivo' AND ID_INMUEBLE='$codsistema'AND 
    			NOMBRE_FOTO='$nombrefoto'");
    	$banderas=oci_execute($resultado);
    	if($banderas==TRUE)
    	{
    		return $resultado;
    	}
    	else
    	{
    		echo "false";
    		return false;
    	}
    }

    public function CambiarEstadoFotoManPre($idInmueble)
    {
        $sql = "BEGIN SGC_P_BORRAR_FOTOS_MED_PRE($idInmueble:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        /* echo $sql;*/
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':PCODRESULT', $codresult, "1000");
        oci_bind_by_name($resultado, ':PMSGRESULT', $msgresult, "1000");
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            return $resultado;
        }
        else
        {
            echo "false";
            return false;
        }

    }

    public function EliminarFotosInspecciones($idInmueble)
    {
        $sql = "SELECT FI.URL_FOTO FROM SGC_TT_FOTOS_INSPECCION FI
                WHERE FI.ID_INMUEBLE=$idInmueble
                AND FI.ELIMINADA='S'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas=oci_execute($resultado);
        if ($banderas==true) {
            while (oci_fetch($resultado)) {
                $nombreFoto = oci_result($resultado, 'URL_FOTO');
             //   $urlFoto= "../".$nombreFoto;
                $urlFoto= str_replace('../','../../',$nombreFoto);

                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                       // echo "Foto eliminada con exito";
                    } else {
                      //  echo "Error no se pudo borrar la foto";
                    }
                } else {
                  //  echo "El archivo no existe";
                }
            }
        }
        else
            echo "Error en la consulta";

        oci_free_statement($resultado);


    }



}