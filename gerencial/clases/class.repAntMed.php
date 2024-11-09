<?php
include_once 'class.conexion.php';
class ReportesAntMed extends ConexionClass{

    public function __construct()
    {
        parent::__construct();
    }

    public function getcodresult(){
        return $this->codresult;
    }

    public function getmsgresult(){
        return $this->msgresult;
    }

    //CONSULTAS ANALISIS DE CONSUMOS MEDIDOS POR SECTOR

    public function antiguedadMedidores($proyecto, $sector, $ruta, $cliente, $diametro){

        if($ruta <> '') $sqlruta = " AND I.ID_RUTA = $ruta ";
        if($cliente <> '') $uso = " AND A.ID_USO = '$cliente' ";
        if($diametro <> '') $calibre = " AND C.COD_CALIBRE = $diametro ";

        $sql = "SELECT I.ID_SECTOR, ID_RUTA, I.CODIGO_INM, C.DESC_CALIBRE, ME.DESC_MED, M.SERIAL, 
        TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA_INSTAL, TO_CHAR(M.FECHA_BAJA,'DD/MM/YYYY') FECHA_BAJA
        FROM SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_CALIBRES C, SGC_TP_MEDIDORES ME
        WHERE I.CODIGO_INM = M.COD_INMUEBLE
        AND M.COD_CALIBRE = C.COD_CALIBRE
        AND M.COD_MEDIDOR = ME.CODIGO_MED
        AND I.ID_PROYECTO = '$proyecto'
        AND I.ID_SECTOR = $sector
        $sqlruta 
        $uso 
        $calibre";
        //echo 'res = '.$sql;
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
?>