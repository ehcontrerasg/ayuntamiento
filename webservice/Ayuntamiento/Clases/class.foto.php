<?php
include_once 'class.conexion.php';

class FotosAyuntamientoClass extends ConexionClass {
  
	private $consecutivo;
	private $cliente;
	private $fecha;
	private $nombrefoto;
	private $urlfoto;
	private $agno;
	private $proyecto;
    public function __construct()
    {
    	$this->consecutivo="";
    	$this->cliente="";
    	$this->fecha="";
    	$this->nombrefoto="";
    	$this->urlfoto="";
    	$this->agno="";
    	$this->proyecto="BC";
         parent::__construct();
         
    }
    
    public function setConsecutivo($valor){$this->consecutivo=$valor;}
    public function setCliente($valor){$this->cliente=$valor;}
    public function setFecha($valor){$this->fecha=$valor;}
    public function setNombre($valor){$this->nombrefoto=$valor;}
    public function setUrl($valor){$this->urlfoto=$valor;}
    public function setAgno($valor){$this->agno=$valor;}
    public function setProyecto($valor){$this->proyecto=$valor;}
    
    
    
     public function insertarfoto()
    {
         $sql="
         INSERT INTO SGC_TT_FOTOS_AYUNTAMIENTO(CONSECUTIVO, CLIENTE, FECHA, NOMBRE_FOTO, URL_FOTO, AGNO, ID_PROYECTO) 
        VALUES($this->consecutivo,'$this->cliente',TO_DATE('$this->fecha', 'YYYYMMDD_HH24MISS'),'$this->nombrefoto','$this->urlfoto',$this->agno,'$this->proyecto' )";

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

    public function cambiaEstadoFotos($idInmueble)
    {

        $sql = "BEGIN SGC_P_BORRAR_FOTOS_LECTURA ($idInmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function EliminarFotos($idInmueble)
    {
        $sql = "SELECT FC.URL_FOTO
                FROM SGC_TT_FOTOS_LECTURA FC
                WHERE
                FC.ID_INMUEBLE = $idInmueble AND
                FC.ELIMINADA='S'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas=oci_execute($resultado);
        if ($banderas==true) {
            while (oci_fetch($resultado)) {
                $urlFoto = oci_result($resultado, 'URL_FOTO');
                $urlFoto=str_replace("../","../../",$urlFoto);
                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                      //  echo "Foto eliminada con exito\n";

                    } else {
                  //     echo "Error no se pudo borrar la foto";

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