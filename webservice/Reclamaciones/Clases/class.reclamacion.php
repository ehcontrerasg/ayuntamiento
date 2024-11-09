<?php
/**
 * Created by PhpStorm.
 * User: soporte
 * Date: 9/5/2018
 * Time: 3:50 PM
 */

include_once 'class.conexion.php';

class Reclamacion extends ConexionClass{

    private $id;
    private $nombre;
    private $telefono;
    private $direccion;
    private $url_foto;
    private $observacion;
    private $latitud;
    private $longitud;
    private $fecha;
    private $id_reclamo;
    private $idReclamacion;
    private $fechaformato;
    private $urlfoto;
    private $email;
    private $consecutivofoto;
    private $proyecto;

    public function __construct()
    {

        parent::__construct();
        $this->id = "";
        $this->nombre = "";
        $this->telefono = "";
        $this->direccion = "";
        $this->url_foto = "";
        $this->observacion = "";
        $this->latitud = "";
        $this->longitud = "";
        $this->fecha = "";
        $this->id_reclamo = "";
        $this->idReclamacion = "";
        $this->fechaformato = "";
        $this->urlfoto = "";
        $this->email = "";
        $this->consecutivofoto = "";
        $this->proyecto="";
    }

    public function setid		    ($valor){$this->id=$valor;}
    public function setemail		 ($valor){$this->email=$valor;}
    public function setnombre		($valor){$this->nombre=$valor;}
    public function settelefono		($valor){$this->telefono=$valor;}
    public function setdireccion	($valor){$this->direccion=$valor;}
    public function setobservacion	($valor){$this->observacion=$valor;}
    public function setlatitud		($valor){$this->latitud=$valor;}
    public function setlongitud		($valor){$this->longitud=$valor;}
    public function setfecha		($valor){$this->fecha=$valor;}
    public function setid_reclamo	($valor){$this->id_reclamo=$valor;}
    public function setidReclamo	($valor){$this->idReclamacion=$valor;}
    public function setfechaFormato	($valor){$this->fechaformato=$valor;}
    public function seturl         	($valor){$this->urlfoto=$valor;}
    public function setConsecutivo  ($valor){$this->consecutivofoto=$valor;}
    public function setproyecto    ($valor){$this->proyecto=$valor;}



    public function insertarfoto()
    {

        $sql="INSERT INTO SGC_TT_FOTOS_INCIDENCIAS
        VALUES ($this->consecutivofoto,'$this->idReclamacion','$this->urlfoto',
        TO_DATE('$this->fechaformato','YYYYMMDD'))";

        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            return $resultado;
        }
        else
        {
            echo  $sql ;
           // echo "false2";
            return false;
        }

    }

    public function guardarlecturanuevo()
    {

        $id=addslashes($this->id);
        $nombre=addslashes($this->nombre);
        $telefono=addslashes($this->telefono);
        $direccion=addslashes($this->direccion);
        $observacion=addslashes($this->observacion);
        $id_reclamo=addslashes($this->id_reclamo);
        $latitud=addslashes($this->latitud);
        $longitud=addslashes($this->longitud);
        $fecha=addslashes($this->fecha);
        $email=addslashes($this->email);
        $proyecto=addslashes($this->proyecto);

        //$sql = "BEGIN SGC_P_INGRESA_RECLAMACION('$this->id','$this->nombre','$this->telefono','$this->direccion','$this->observacion','$this->id_reclamo','$this->latitud','$this->longitud','$this->fecha','$this->email','$this->proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $sql = "BEGIN SGC_P_INGRESA_INCIDENCIA('$id','$nombre','$telefono','$direccion','$observacion','$id_reclamo','$latitud','$longitud','$fecha','$email','$proyecto',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':PCODRESULT', $codresult, 10000);
        oci_bind_by_name($resultado, ':PMSGRESULT', $msgresult, 10000);
        $bandera = oci_execute($resultado);


        if ($bandera == TRUE) {
            $nombrelog = date('Y-m-d');
            $file = fopen("Logs/log-$nombrelog-ReclamacionOK-$this->id.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            oci_close($this->_db);
            return true;
        } else {

            oci_close($this->_db);
            $nombrelog = date('Y-m-d');
            $file = fopen("Logs/log-$nombrelog-ReclamacionFail-$this->id.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fwrite($file, "error: " . $msgresult);
            fclose($file);
            echo "Error al guardar la lectura $msgresult  $this->id";
            return false;
        }
    }


        public function getTipoRecl(){
        $resultado=oci_parse($this->_db,"SELECT ID, DESCRIPCION,ESTADO
				FROM SGC_TP_TIPOS_INCIDENCIAS  ");
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            echo "Error al obtener las observaciones de factura	 ";
            return false;
        }

    }

}