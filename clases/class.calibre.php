<?php
include_once "class.conexion.php";


class Calibre extends ConexionClass
{


    private $mesrror;
    private $coderror;


    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }



    public function getCalibres (){
        $sql="SELECT
                CAL.COD_CALIBRE CODIGO,
                CAL.DESC_CALIBRE DESCRIPCION
              FROM
                SGC_TP_CALIBRES CAL
              WHERE
                CAL.ACTIVO='S'
              ORDER BY 2";
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

    public function getCalibresAll ($pro){

        $where="";

  

        if(trim($pro)!=""){
            $where.=" AND  I.ID_PROYECTO='$pro' ";
        }

        $sql="SELECT distinct
                CAL.COD_CALIBRE CODIGO,
                CAL.DESC_CALIBRE DESCRIPCION
              FROM
                SGC_TP_CALIBRES CAL,SGC_TT_INMUEBLES I
              WHERE CAL.COD_CALIBRE= I.COD_DIAMETRO
              $where
              ORDER BY 2";


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

    public function getCalibresByZonProy ($zon,$pro,$uso){

        $pro=addslashes($pro);
        $zon=addslashes($zon);
        $uso=addslashes($uso);

        $where="";

        if(trim($zon)!=""){
            $where.=" AND I.ID_ZONA='$zon' ";
        }
        if(trim($uso)!=""){
            $where.=" AND A.ID_USO='$uso' ";
        }
        if(trim($pro)!=""){
            $where.="  AND  I.ID_PROYECTO='$pro'";
        }

        $sql="SELECT distinct
  CAL.COD_CALIBRE CODIGO,
  CAL.DESC_CALIBRE DESCRIPCION
FROM
  SGC_TP_CALIBRES CAL,SGC_TT_INMUEBLES I, SGC_TT_REGISTRO_CORTES RC, SGC_TT_MEDIDOR_INMUEBLE MI, ACEASOFT.SGC_TP_ACTIVIDADES A
WHERE CAL.COD_CALIBRE= I.COD_DIAMETRO AND
      I.CODIGO_INM=MI.COD_INMUEBLE(+)
      AND I.SEC_ACTIVIDAD=A.SEC_ACTIVIDAD
      AND RC.FECHA_ACUERDO IS NULL
      AND RC.ID_INMUEBLE=I.CODIGO_INM
      AND RC.FECHA_REVERSION IS NULL
      AND RC.FECHA_EJE IS NULL
      AND MI.FECHA_BAJA(+) IS NULL
      AND RC.PERVENC='N'
              $where
              ORDER BY 2";
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


    public function getCalibresByZonProyAsigInsp ($zon,$pro,$uso){

        $pro=addslashes($pro);
        $zon=addslashes($zon);
        $uso=addslashes($uso);

        $where="";

        if(trim($uso)!=""){
            $where.=" AND A.ID_USO='$uso' ";
        }

        /*    $where="";

          //  if(trim($zon)!=""){
                $where.=" AND I.ID_ZONA='$zon' ";
           // }
           //  if(trim($uso)!=""){
                $where.=" AND A.ID_USO='$uso' ";
          //  }
       // if(trim($pro)!=""){
                $where.="  AND  I.ID_PROYECTO='$pro'";
           // }*/

        $sql="SELECT distinct
  CAL.COD_CALIBRE CODIGO,
  CAL.DESC_CALIBRE DESCRIPCION
FROM
  SGC_TP_CALIBRES CAL,SGC_TT_INMUEBLES I, SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_MEDIDOR_INMUEBLE MI, ACEASOFT.SGC_TP_ACTIVIDADES A
WHERE CAL.COD_CALIBRE= I.COD_DIAMETRO AND
      I.CODIGO_INM=MI.COD_INMUEBLE(+)
      AND I.SEC_ACTIVIDAD=A.SEC_ACTIVIDAD
      AND IC.CODIGO_INM=I.CODIGO_INM
      AND MI.FECHA_BAJA  IS NULL
      AND IC.USR_ASIG IS NULL
    AND I.ID_ZONA='$zon'
     AND  I.ID_PROYECTO='$pro'
     $where
              ORDER BY 2";
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
}