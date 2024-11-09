<?php
include_once "class.conexion.php";


class Sector extends ConexionClass{

    public function getSecMantCorByPro($proyecto){
        $sql="SELECT
                INM.ID_SECTOR CODIGO,
                INM.ID_SECTOR DESCRIPCION
              FROM
                SGC_TT_MANT_CORRMED MC,
                SGC_TT_INMUEBLES INM
              WHERE
                INM.CODIGO_INM=MC.CODIGO_INM AND
                MC.FECHA_REEALIZACION IS NULL AND
                MC.FECH_ANU IS NULL AND
                INM.ID_PROYECTO='$proyecto' AND
                MC.ANULADO='N'
              GROUP BY
                INM.ID_SECTOR";

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
    public function getSecByPro($proyecto){
        $proyecto=addslashes($proyecto);
        $sql="SELECT
                S.ID_SECTOR CODIGO,
                S.ID_SECTOR DESCRIPCION
              FROM
                SGC_TP_SECTORES S
              WHERE
                S.ID_PROYECTO='$proyecto'";

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



    public function getSecMantPreByPro($proyecto){
        $sql="SELECT
                INM.ID_SECTOR CODIGO,
                INM.ID_SECTOR DESCRIPCION
              FROM
                SGC_TT_MANT_MED MC,
                SGC_TT_INMUEBLES INM
              WHERE
                INM.CODIGO_INM=MC.CODIGO_INM AND
                MC.FECHA_REEALIZACION IS NULL AND
                INM.ID_PROYECTO='$proyecto' AND
                MC.ESTADO='A'
              GROUP BY
                INM.ID_SECTOR";

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


    public function getSecCorByProy($proy){
        $sql="SELECT I.ID_SECTOR CODIGO, I.ID_SECTOR DESCRIPCION FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND R.REVERSADO='N'
              and R.PERVENC='N'
			  AND R.FECHA_ACUERDO IS NULL
              AND I.ID_PROYECTO='$proy'
              GROUP BY (I.ID_SECTOR)";

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

    public function getSectorInsByFech($fechaIni,$fechaFin){
        $sql="SELECT INM.id_SECTOR SECTOR FROM SGC_tT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM
      WHERE INM.CODIGO_INM=IC.CODIGO_INM AND IC.FECHA_PLANIFICACION
      BETWEEN TO_DATE('$fechaIni','YYYY-MM-DD') AND to_date('$fechaFin','YYYY-MM-DD')
      group by (id_sector)";

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

    public function getSecHojRecByPro($proy){
        $sql="SELECT I.ID_SECTOR FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND I.ID_PROYECTO='$proy'
              GROUP BY (I.ID_SECTOR)";

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

    public function getSecHojCorByPro($proy){
        $sql="SELECT I.ID_SECTOR FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND I.ID_PROYECTO='$proy'
              GROUP BY (I.ID_SECTOR)";

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


    public function getSecHojInsByPro($proy){
        $sql="SELECT I.ID_SECTOR FROM SGC_TT_INSPECCIONES_CORTES R, SGC_TT_INMUEBLES I
              WHERE I.CODIGO_INM=R.CODIGO_INM
              AND R.USR_ASIG IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND I.ID_PROYECTO='$proy'
              GROUP BY (I.ID_SECTOR)";

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




    public  function getSecCantRecAsigByFecha($fecha,$proyecto,$gerencia,$contratista){
        $where="";

        $where="";
        if(trim($gerencia)<>""){
            $where=" AND S.ID_GERENCIA='$gerencia'";
        }

        $sql="select  I.ID_SECTOR, COUNT(1) CANTIDAD
              from SGC_tT_REGISTRO_RECONEXION rc, SGC_tT_INMUEBLES I,
              SGC_TP_SECTORES S, SGC_TT_USUARIOS u
              where
              I.CODIGO_INM=RC.ID_INMUEBLE AND
              I.ID_PROYECTO='$proyecto'
              AND
              S.ID_SECTOR=I.ID_SECTOR
              AND
              RC.USR_EJE is not null
              and RC.USUARIO_ASIGNACION is not null
              AND RC.USR_EJE=U.ID_USUARIO
              AND U.CONTRATISTA='$contratista'
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              $where
              group by (I.ID_SECTOR) ";
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
