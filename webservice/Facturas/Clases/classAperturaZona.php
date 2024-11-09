<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/29/2015
 * Time: 3:38 PM
 */
if (is_file("../clases/class.conexion.php"))
{
    include "../clases/class.conexion.php";
}
if (is_file("clases/class.conexion.php"))
{
    include "clases/class.conexion.php";
}

class AperturaZona extends ConexionClass{

    public function AbrePeriodo($zona,$periodo){


        $msresult="";
        $codresult="";
       $sql="BEGIN ACEA.SGC_P_APERTURA_CICL_FACT('$zona',$periodo,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$msresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$codresult,"123");
        $bandera=oci_execute($resultado);
        echo 'CODIGO: '.$msresult.' MENSAJE: '.$codresult;
        if($bandera==TRUE && $msresult==0){
            oci_close($this->_db);
            return $msresult;
        }
        else{
            oci_close($this->_db);

            $vecterr=array('coderror'=>$msresult,'mserror'=>$codresult);
            return $vecterr;
        }
    }

}