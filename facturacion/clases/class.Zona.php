<?php
include_once '../../clases/class.conexion.php';
class Zona extends ConexionClass{



	public function __construct()
	{
		parent::__construct();


	}

    public function obtieneZonAuto($proyecto,$parcial){

        $sql="SELECT
                ID_ZONA
              FROM
                SGC_TP_ZONAS
		      WHERE
		        ID_PROYECTO = '$proyecto' AND
		        ID_ZONA LIKE '$parcial%'";
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

    public function getZonAsiLecByProPer($proyecto,$periodo){

        $sql="SELECT 
                L.ID_ZONA, Z.ID_ZONA
              FROM 
                SGC_TT_REGISTRO_LECTURAS L, 
                SGC_TP_PERIODO_ZONA Z
              WHERE 
                L.ID_ZONA = Z.ID_ZONA 
                AND Z.PERIODO = '$periodo' 
                AND Z.CODIGO_PROYECTO = '$proyecto'
                AND Z.FEC_DIFE IS NULL
                AND L.FECHA_LECTURA IS NULL
              GROUP BY L.ID_ZONA, Z.ID_ZONA
              ORDER BY 1 ASC";
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


    public function getZonByPro($proyecto){

        $sql="
        select z.ID_ZONA from SGC_TP_ZONAS z
            where z.ID_PROYECTO='$proyecto'";
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

    public function getZonSupFacByPro($proyecto){

	    $sql="
        select DISTINCT z.ID_ZONA from SGC_TP_ZONAS z, SGC_TT_REGISTRO_ENTREGA_FAC RE
            where z.ID_PROYECTO='$proyecto' 
            AND RE.ID_ZONA=Z.ID_ZONA AND 
                RE.FECHA_EJECUCION IS NOT NULL AND
                RE.SUPERVISADO='N'  
            ";
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



    public function getPerSupByZona($zona){

         $sql="Select max(rl.PERIODO) PERIODO from 
                                    SGC_TT_REGISTRO_ENTREGA_FAC rl
where rl.ID_ZONA='$zona' AND
      rl.FECHA_EJECUCION IS NOT NULL and
      rl.SUPERVISADO='N'";
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
