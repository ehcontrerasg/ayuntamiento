<?php
require_once 'ConexionClass.php';

class FotosMantenimientoClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }


     public function insertarfoto($consecutivo,$codsistema,$fecha,$nombrefoto,$urlfoto,$periodo,$proyecto)
    { 
        
        $resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_FOTOS_MANTENIMIENTO(CONSECUTIVO,ID_INMUEBLE,FECHA,NOMBRE_FOTO,URL_FOTO,ID_PERIODO,ID_PROYECTO) VALUES ('$consecutivo','$codsistema',TO_DATE('$fecha','MM/DD/YYYY'),'$nombrefoto','$urlfoto','$periodo','$proyecto') ");
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
    
    
    
    	$resultado = oci_parse($this->_db,"SELECT COUNT(1) NUMFOTOS FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE CONSECUTIVO='$consecutivo' AND ID_INMUEBLE='$codsistema'AND ID_PERIODO='$periodo'");
    
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

        addslashes($idInmueble);
        $sql = "BEGIN SGC_P_BORRA_FOTOS_MANT($idInmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':PCODRESULT', $codresult, "1000");
        oci_bind_by_name($resultado, ':PMSGRESULT', $msgresult, "1000");
      //  echo $msgresult;
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            return $resultado;
        }
        else
        {
           // echo $msgresult;
            echo "que es lo que pasa!";
            return false;
        }
    }

    public function EliminarFotos($idInmueble)
    {
        $sql = "SELECT FM.URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO FM
                WHERE FM.ID_INMUEBLE=$idInmueble
                AND FM.ELIMINADA='S'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas=oci_execute($resultado);
        if ($banderas==true) {
            while (oci_fetch($resultado)) {
                $nombreFoto = oci_result($resultado, 'URL_FOTO');
                $urlFoto= str_replace('../','../../fotos_sgc/',$nombreFoto);

               // $urlFoto=str_replace("../","../../fotos_sgc/",$urlFoto);

                if (file_exists($urlFoto)) {
                    if (unlink($urlFoto)) {
                       // echo "Foto eliminada con exito:$urlFoto ";
                    } else {
                      //  echo "Error no se pudo borrar la foto";
                    }
                } else {
                 // echo "El archivo no existe:$urlFoto ";
                }
            }
        }
        else
            echo "Error en la consulta";

        oci_free_statement($resultado);
    }


    
    
 
}