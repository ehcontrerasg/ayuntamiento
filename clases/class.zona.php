<?php
include_once "class.conexion.php";


class Zona extends ConexionClass{

    public function getZonByPro($proyecto,$parcial){
        $sql="SELECT
                ID_ZONA CODIGO
              FROM
                SGC_TP_ZONAS
		      WHERE
		        ID_PROYECTO = '$proyecto' AND
		        ID_ZONA LIKE '%$parcial%'";

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



    public function obtenerZonHojRec($sector){
        $sql="SELECT I.ID_ZONA FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND I.ID_SECTOR='$sector'
              GROUP BY (I.ID_ZONA)";

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

    public function getZonCorBySec($sec){
        $sql="SELECT I.ID_ZONA CODIGO, I.ID_ZONA DESCRIPCION FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND R.REVERSADO='N'
			  AND R.FECHA_ACUERDO IS NULL
              AND R.PERVENC='N'
              AND I.ID_SECTOR='$sec'
              GROUP BY (I.ID_ZONA)";

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




    public function getZonCortByProPer($pro,$per)
    {
        $pro=addslashes($pro);
        $per=addslashes($per);
        $where="";

        if(trim($per)!=""){
            $where= " AND C.ID_PERIODO='$per' ";
        }



         $sql="SELECT
                I.ID_ZONA CODIGO,
                I.ID_ZONA DESCRIPCION
              FROM
                SGC_TT_REGISTRO_CORTES C,
                SGC_tT_INMUEBLES I
              WHERE
                I.CODIGO_INM=C.ID_INMUEBLE and
                C.FECHA_ACUERDO IS NULL AND
                C.FECHA_REVERSION IS NULL AND
                C.FECHA_EJE IS NULL AND
                C.PERVENC='N' AND
                I.ID_PROYECTO= '$pro'
                $where
              GROUP BY
                I.ID_ZONA
              ORDER BY I.ID_ZONA ASC";

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


    public function getZonaInsByPro($proyecto){
        /*$sql = "SELECT I.ID_ZONA
        FROM SGC_TT_INSPECCIONES_CORTES c, sgc_Tt_inmuebles i, SGC_TT_REGISTRO_CORTES RC
        WHERE

         I.CODIGO_INM=RC.ID_INMUEBLE
        and RC.ORDEN=C.ORDEN_CORTE
        AND C.USR_ASIG IS NULL
        AND I.ID_PROYECTO='$proyecto'
        GROUP BY I.ID_ZONA ORDER BY 1 ASC";*/
        $sql="SELECT I.ID_ZONA
              FROM  SGC_TT_INSPECCIONES_CORTES IC,SGC_TT_INMUEBLES I
              WHERE
                  IC.USR_ASIG IS NULL AND
                  I.CODIGO_INM = IC.CODIGO_INM AND
                  I.ID_PROYECTO = '$proyecto'
              GROUP BY  I.ID_ZONA
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


    public function getZonInsAbiBySec($sec){
        $sql="SELECT I.ID_ZONA FROM SGC_TT_INSPECCIONES_CORTES R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.CODIGO_INM
              AND R.USR_ASIG IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND I.ID_SECTOR='$sec'
              GROUP BY (I.ID_ZONA)";

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

    public function getZonAsiLecByProPer($proyecto,$periodo){

        $sql="SELECT 
                L.ID_ZONA DESCRIPCION, 
                Z.ID_ZONA ZONA
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

}
