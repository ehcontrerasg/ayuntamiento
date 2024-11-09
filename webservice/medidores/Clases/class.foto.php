<?php
include_once 'class.conexion.php';

class Fotos extends ConexionClass {
  
	private $consecutivo;
	private $codsistema;
	private $fecha;
	private $nombrefoto;
	private $urlfoto;
	private $periodo;
	private $orden;
    public function __construct()
    {
    	$this->consecutivo="1";
    	$this->codsistema="";
    	$this->fecha="";
    	$this->nombrefoto="";
    	$this->urlfoto="";
    	$this->periodo="";
    	$this->ordeno="";
         parent::__construct();
         
    }
    
    public function setconsecutivo($valor){$this->consecutivo=$valor;}
    public function setcodsistema($valor){$this->codsistema=$valor;}
    public function setfecha($valor){$this->fecha=$valor;}
    public function setnombre($valor){$this->nombrefoto=$valor;}
    public function seturl($valor){$this->urlfoto=$valor;}
    public function setperiodo($valor){$this->periodo=$valor;}
    public function setorden($valor){$this->orden=$valor;}

    
    
    
     public function insertarfoto($orden)
    {
         $sql="INSERT INTO SGC_TT_FOTOS_CAMBINS_MED VALUES ('$this->consecutivo',
        		'$this->codsistema',TO_DATE('$this->fecha','MM/DD/YYYY'),'$this->nombrefoto','$this->urlfoto'
        		,$orden) ";

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

    public function insertarfotoManPre()
    {

        $sql="INSERT INTO SGC_TT_FOTOS_MED_PRE (CONSECUTIVO, ID_INMUEBLE, FECHA, NOMBRE_FOTO, URL_FOTO, ORDEN) VALUES ('$this->consecutivo',
        		'$this->codsistema',TO_DATE('$this->fecha','MM/DD/YYYY hh24_mi_ss'),'$this->nombrefoto','$this->urlfoto',
        		'$this->orden') ";
        //echo $sql;
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
    public function insertarfotoManCorr()
    {

        $resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_FOTOS_MED_CORR(CONSECUTIVO, ID_INMUEBLE, FECHA, NOMBRE_FOTO, URL_FOTO, ORDEN) VALUES ('$this->consecutivo',
        		'$this->codsistema',TO_DATE('$this->fecha','MM/DD/YYYY'),'$this->nombrefoto','$this->urlfoto',
        		'$this->orden') ");

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

     public function insertarfotoest()
    {

        $sql="INSERT INTO SGC_TT_FOTOS_INSEST_MED VALUES ('$this->consecutivo',
        		'$this->codsistema',TO_DATE('$this->fecha','MM/DD/YYYY'),'$this->nombrefoto','$this->urlfoto',
        		'$this->periodo') ";


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
    
    public function existefoto($consecutivo,$orden)
    {
    
    
    
    	$resultado = oci_parse($this->_db,"SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_CAMBINS_MED WHERE CONSECUTIVO='$consecutivo' AND ID_ORDEN='$orden'");
    
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
    public function existefotoManCorr($consecutivo,$orden)
    {

    	$resultado = oci_parse($this->_db,"SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_MED_CORR WHERE CONSECUTIVO='$consecutivo' AND ORDEN='$orden'");

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
    public function existefotoManPre($consecutivo,$orden,$fecha)
    {
        $sql="SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_MED_PRE WHERE CONSECUTIVO='$consecutivo' AND ORDEN='$orden' AND FECHA=TO_DATE('$fecha','MM/DD/YYYY HH24_MI_SS') ";
    	//echo $sql;
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
    public function existefotoEst($consecutivo,$codsistema,$periodo)
    {



    	$resultado = oci_parse($this->_db,"SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_INSEST_MED WHERE CONSECUTIVO='$consecutivo' AND ID_INMUEBLE='$codsistema'AND ID_PERIODO='$periodo'");

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
    public function CambiarEstadoFotoManCorr($idInmueble)
    {
        $sql = "BEGIN SGC_P_BORRA_FOTOS_MED_CORR($idInmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function EliminarFotoManCorr($idInmueble)
    {
        $sql = "SELECT FMC.URL_FOTO FROM SGC_TT_FOTOS_MED_CORR FMC
                WHERE FMC.ID_INMUEBLE=$idInmueble
                AND FMC.ELIMINADA='S'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas=oci_execute($resultado);
        if ($banderas==true) {
            while (oci_fetch($resultado)) {
                $urlFoto = oci_result($resultado, 'URL_FOTO');
                $urlFoto=str_replace("../","../../",$urlFoto);

                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                       // echo "Foto eliminada con exito";
                    } else {
                      //  echo "Error no se pudo borrar la foto";
                    }
                } else {
                 //   echo "El archivo no existe";
                }
            }
        }
        else
            echo "Error en la consulta";

        oci_free_statement($resultado);

    }
    public function CambiarEstadoFotoManPre($idInmueble)
    {
        $sql = "BEGIN SGC_P_BORRAR_FOTOS_MED_PRE($idInmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function EliminarFotoManPre($idInmueble)
    {
        $sql = "SELECT FMP.URL_FOTO FROM SGC_TT_FOTOS_MED_PRE FMP
                WHERE FMP.ID_INMUEBLE=$idInmueble
                AND FMP.ELIMINADA='S'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas=oci_execute($resultado);
        if ($banderas==true) {
            while (oci_fetch($resultado)) {
                $urlFoto = oci_result($resultado, 'URL_FOTO');
                $urlFoto=str_replace("../","../../",$urlFoto);

                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                      //  echo "Foto eliminada con exito";
                    } else {
                     //   echo "Error no se pudo borrar la foto";
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