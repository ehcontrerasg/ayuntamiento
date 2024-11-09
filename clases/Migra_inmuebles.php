<?php
include_once "class.conexion.php";

class Migracion extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function obtieneInmueblesMigración($proyecto, $sector, $ruta, $manzana, $inmueble, $sector2, $ruta2, $manzana2, $ciclo)
    {

        if($inmueble != '') $where .= " AND I.CODIGO_INM = '$inmueble'";
        if($manzana != '' ) $where .= " AND SUBSTR(I.CATASTRO,3,3) = $manzana";

        $sql = "SELECT I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO,
                       CONCAT('$sector2','$ciclo') NUEVA_ZONA,
                       CONCAT('$sector2$ruta2',SUBSTR(I.ID_PROCESO,5))NUEVO_PROCESO, '$sector2'||NVL('$manzana2',SUBSTR(I.CATASTRO,3,3)) ||SUBSTR(I.CATASTRO,6) NUEVO_CATASTRO
                FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U
                WHERE I.CONSEC_URB = U.CONSEC_URB
                  AND I.ID_PROYECTO = '$proyecto' 
                  AND I.ID_SECTOR = '$sector' 
                  AND I.ID_RUTA = '$ruta'
                  $where 
                ORDER BY I.ID_PROCESO ASC";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);

        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    function verificaProceso($inmueble, $proceso){
        $sql = "SELECT COUNT(ID_PROCESO) CANTIDAD
		FROM SGC_TT_INMUEBLES 
		WHERE ID_PROCESO = '$proceso'
		AND CODIGO_INM <> $inmueble";
        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
        //echo $sql;
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }
    function validaProceso($inmueble, $proceso){
        $sql = "SELECT COUNT(ID_PROCESO) CANTIDAD
		FROM SGC_TT_INMUEBLES 
		WHERE ID_PROCESO = '$proceso'
		AND CODIGO_INM <> $inmueble";
        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
        //echo $sql;
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function verificaCatastro($inmueble, $catastro){
        $sql = "SELECT COUNT(CATASTRO) CANTIDAD 
		FROM SGC_TT_INMUEBLES 
		WHERE CATASTRO = '$catastro'
		AND CODIGO_INM <> '$inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $bandera=oci_execute($resultado);

        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function validaCatastro($inmueble, $catastro){
        $sql = "SELECT COUNT(CATASTRO) CANTIDAD 
		FROM SGC_TT_INMUEBLES 
		WHERE CATASTRO = '$catastro'
		AND CODIGO_INM <> '$inmueble'";
        $resultado = oci_parse($this->_db, $sql);
        $bandera=oci_execute($resultado);

        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function guardaMigracion($inm, $zon, $pro, $cat, $usu)
    {
        $sql = "BEGIN SGC_P_GUARDA_MIG('$inm','$zon','$pro','$cat','$usu',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 1000);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror, 1000);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }
}
