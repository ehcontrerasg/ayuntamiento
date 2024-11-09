<?php
include_once "../../clases/class.conexion.php";
class AsignaLotes extends ConexionClass{
    private $coduser;
    private $operario_asignado;
    private $operario_desasignado;
    private $zona;
    private $ruta;
    private $periodo;
    private $codresult;
    private $msgresult;

    public function __construct(){
        parent::__construct();
        $this->coduser="";
        $this->operario_asignado="";
        $this->operario_desasignado="";
        $this->zona="";
        $this->ruta="";
        $this->periodo="";
    }

    public function getcodresult(){
        return $this->codresult;
    }

    public function getmsgresult(){
        return $this->msgresult;
    }


    public function seleccionaProyecto($coduser){
        $sql = "SELECT PR.ID_PROYECTO, PR.DESC_PROYECTO 
		FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
		WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
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

    public function seleccionaPeriodo($proyecto){
        $sql = "SELECT PERIODO 
		FROM SGC_TT_REGISTRO_ENT_FAC_ASEO F, SGC_TP_ZONAS Z
		WHERE F.ID_ZONA = Z.ID_ZONA AND F.FECHA_EJECUCION IS NULL AND Z.ID_PROYECTO = '$proyecto'
		GROUP BY PERIODO ORDER BY PERIODO DESC";
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

    public function seleccionaSector($periodo, $proyecto){
        $sql = "SELECT S.ID_SECTOR
        FROM SGC_TT_REGISTRO_ENTREGA_FAC F, SGC_TP_SECTORES S
        WHERE SUBSTR(F.ID_ZONA,0,2) = S.ID_SECTOR
        AND PERIODO = '$periodo' AND S.ID_PROYECTO = '$proyecto'  AND USR_EJE IS NULL
        GROUP BY S.ID_SECTOR
        ORDER BY S.ID_SECTOR ASC";
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

    public function seleccionaZona($periodo, $proyecto){
        $sql = "SELECT F.ID_ZONA
		FROM SGC_TT_REGISTRO_ENT_FAC_ASEO F, SGC_TP_ZONAS Z
		WHERE F.ID_ZONA = Z.ID_ZONA AND PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' --AND F.ID_ZONA = '$sector' 
		GROUP BY F.ID_ZONA ORDER BY 1 ASC";
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

    public function seleccionaOperario($proyecto){
        $sql = "SELECT ID_USUARIO, NOM_USR, APE_USR
		FROM SGC_TT_USUARIOS
		WHERE REPARTO = 'S' 
		--AND ID_PROYECTO = '$proyecto'
		AND FEC_FIN IS NULL";
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

    /*public function seleccionaAsignacionSector($periodo, $proyecto, $sector){
        $sql = "SELECT COUNT(F.ID_ZONA)CANTIDAD, F.ID_ZONA
        FROM SGC_TT_REGISTRO_ENTREGA_FAC F, SGC_TP_ZONAS Z
        WHERE F.ID_ZONA = Z.ID_ZONA AND PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' AND SUBSTR(F.ID_ZONA,0,2) = '$sector'
        GROUP BY F.ID_ZONA ORDER BY 1 ASC";
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

    }*/

    public function seleccionaAsignacionZona($periodo, $proyecto, $zona){
        $sql = "SELECT COUNT(F.ID_ZONA)CANTIDAD, I.ID_SECTOR, I.ID_RUTA
        FROM SGC_TT_REGISTRO_ENT_FAC_ASEO F, SGC_TP_ZONAS Z, SGC_TT_INMUEBLES I
        WHERE F.COD_INMUEBLE = I.CODIGO_INM AND Z.ID_ZONA = F.ID_ZONA AND PERIODO = '$periodo' AND Z.ID_PROYECTO = '$proyecto' AND F.ID_ZONA = '$zona'
		AND FECHA_ASIGNA IS NULL
        GROUP BY I.ID_SECTOR, I.ID_RUTA ORDER BY  I.ID_RUTA ASC";
        //echo $sql;
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


    public function seleccionaDesasignacionZona($periodo, $zona){
        $sql = "SELECT COUNT(F.COD_INMUEBLE)CANTIDAD, SUBSTR(F.ID_ZONA,0,2) SECTOR, F.RUTA, ID_USUARIO, NOM_USR, APE_USR
        FROM SGC_TT_REGISTRO_ENT_FAC_ASEO F, SGC_TT_USUARIOS U
        WHERE U.ID_USUARIO = F.USR_EJE AND ID_ZONA='$zona' AND PERIODO = '$periodo'
        AND USR_EJE IS NOT NULL AND FECHA_EJECUCION IS NULL
        GROUP BY F.ID_ZONA,  RUTA, ID_USUARIO, NOM_USR, APE_USR ORDER BY RUTA";
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


    public function AsignaRuta($coduser,$operario_asignado,$zona,$ruta,$periodo,$fecPla){
        $sql="BEGIN SGC_P_ASIGNA_FACTURAS_ASEO('$coduser','$operario_asignado','$zona','$ruta',$periodo,'$fecPla',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);

        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function DesasignaRuta($zona,$desasignar_loc,$periodo){
        $sql="BEGIN SGC_P_DESASIGNA_FACTURAS_ASEO('$zona','$desasignar_loc',$periodo,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);

        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
}