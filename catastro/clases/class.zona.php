<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Zona extends ConexionClass
{
    private $id_zona;
    private $id_sector;
    private $id_ciclo;
    private $id_proyecto;

    public function __construct()
    {
        parent::__construct();
        $this->id_zona     = "";
        $this->id_sector   = "";
        $this->id_ciclo    = "";
        $this->id_proyecto = "";

    }
    public function setidzona($valor)
    {
        $this->id_zona = $valor;
    }

    public function setidsector($valor)
    {
        $this->id_sector = $valor;
    }

    public function setidciclo($valor)
    {
        $this->id_ciclo = $valor;
    }
    public function setidproyecto($valor)
    {
        $this->id_proyecto = $valor;
    }

    public function obtenerzonas($proyecto, $sector)
    {


        if ($proyecto=='SD'){
            $resultado = oci_parse($this->_db, "SELECT ZON.ID_ZONA
        FROM SGC_TP_ZONAS ZON
                WHERE ZON.ID_PROYECTO = '$proyecto' AND ZON.ID_SECTOR='$sector'
                OR ZON.ID_ZONA IN ('52A','52B','52C','52D','52E','52F','52H','60A','60B','60F')
                --OR ZON.ID_ZONA IN ('60A','52A','52B','52C','52D','60B')
                ORDER BY ZON.ID_ZONA");
    }
        if ($proyecto=='BC'){
            $resultado = oci_parse($this->_db, "SELECT ZON.ID_ZONA
        FROM SGC_TP_ZONAS ZON
                WHERE ZON.ID_PROYECTO = '$proyecto' AND ZON.ID_SECTOR='$sector'
                OR ZON.ID_ZONA IN ('64A', '64B', '64C', '23F')
                ORDER BY ZON.ID_ZONA");
    }

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function obtenersector($proyecto)
    {
        $resultado = oci_parse($this->_db, "SELECT ID_SECTOR
				 FROM SGC_TP_SECTORES
				--WHERE ID_PROYECTO = '$proyecto'
				ORDER BY ID_SECTOR");

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function obtenerciclo()
    {
        $resultado = oci_parse($this->_db, "SELECT ID_CICLO
				 FROM SGC_TP_CICLOS
				--WHERE ID_PROYECTO = '$proyecto'
				ORDER BY ID_CICLO");

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function NuevaZona()
    {
        $sql       = " BEGIN SGC_P_INSERTA_ZONA('$this->id_sector','$this->id_proyecto','$this->id_ciclo',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        //echo "BEGIN SGC_P_INSERTA_CALLE('$this->desc_calle','$this->id_proyecto','$this->id_tipovia',:PMSGRESULT,:PCODRESULT)";
        oci_bind_by_name($resultado, ':PCODRESULT', $codresult, "123");
        oci_bind_by_name($resultado, ':PMSGRESULT', $msgresult, "123");
        $bandera = oci_execute($resultado);
        if ($bandera = true && $codresult == 0) {
            oci_close($this->_db);
            return $codresult;
        } else {
            oci_close($this->_db);
            echo "$msgresult: $codresult";
            return $codresult;
        }
    }


    public function obtieneZonas($proyecto)
    {

        $sql="SELECT ID_ZONA CODIGO, ID_ZONA DESCRIPCION
                FROM SGC_TP_ZONAS Z
                WHERE Z.ID_PROYECTO = '$proyecto'
                ORDER BY ID_ZONA";
        $resultado = oci_parse($this->_db,$sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

}
