<?php
include_once 'class.conexion.php';

class FotosFacturaClass extends ConexionClass {
  
	private $consecutivo;
	private $codsistema;
	private $fecha;
	private $nombrefoto;
	private $urlfoto;
	private $periodo;
	private $proyecto;
    public function __construct()
    {
    	$this->consecutivo="1";
    	$this->codsistema="";
    	$this->fecha="";
    	$this->nombrefoto="";
    	$this->urlfoto="";
    	$this->periodo="";
    	$this->proyecto="SD";
         parent::__construct();
         
    }
    
    public function setconsecutivo($valor){$this->consecutivo=$valor;}
    public function setcodsistema($valor){$this->codsistema=$valor;}
    public function setfecha($valor){$this->fecha=$valor;}
    public function setnombre($valor){$this->nombrefoto=$valor;}
    public function seturl($valor){$this->urlfoto=$valor;}
    public function setperiodo($valor){$this->periodo=$valor;}
    public function setproyecto($valor){$this->proyecto=$valor;}
    
    
    
     public function insertarfoto()
    { 
                
        $resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_FOTOS_RECONEXION (CONSECUTIVO,ID_INMUEBLE,FECHA,NOMBRE_FOTO,URL_FOTO,ID_PERIODO,ID_PROYECTO) VALUES ('$this->consecutivo',
        		'$this->codsistema',TO_DATE('$this->fecha','MM/DD/YYYY'),'$this->nombrefoto','$this->urlfoto',
        		'$this->periodo','$this->proyecto')");
       
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
    
    public function existefoto($consecutivo,$codsistema,$periodo)
    {
    
    
    
    	$resultado = oci_parse($this->_db,"SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_RECONEXION WHERE CONSECUTIVO='$consecutivo' AND ID_INMUEBLE='$codsistema'AND ID_PERIODO='$periodo'");
    
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

    public function cambiaEstadoFoto($idInmueble)
    {

        $sql = "BEGIN SGC_P_BORRAR_FOTOS_RECONEXION($idInmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function EliminarFoto($idInmueble)
    {
        $sql = "SELECT FR.URL_FOTO
                FROM SGC_TT_FOTOS_RECONEXION FR
                WHERE
                FR.ID_INMUEBLE = $idInmueble AND
                FR.ELIMINADA='S'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas=oci_execute($resultado);
        if ($banderas==true) {
            while (oci_fetch($resultado)) {
                $urlFoto = oci_result($resultado, 'URL_FOTO');
                $urlFoto=str_replace("../","../../",$urlFoto);
                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                     //   echo "Foto eliminada con exito";
                    } else {
                    //    echo "Error no se pudo borrar la foto";
                    }
                } else {
                //    echo "El archivo no exite";
                }
            }
        }
        else
            echo "Error en la consulta";

        oci_free_statement($resultado);
    }
    
 
}