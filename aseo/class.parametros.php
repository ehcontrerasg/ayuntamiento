<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}
elseif  (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}

class parametros extends ConexionClass{
    private $numero_apto;
    private $letra_apto;

    public function __construct()
    {
        $this->letra_apto="";
        $this->numero_apto="";
        parent::__construct();

    }

    public function setletra($valor){$this->letra_apto=$valor;}
    public function setmun($valor){$this->numero_apto=$valor;}


    public function obtenerestados (){
        $resultado = oci_parse($this->_db,"SELECT ID_ESTADO, DESC_ESTADO
				 FROM SGC_TP_ESTADOS_INMUEBLES");

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



    public function obtenererTipoCliente (){
        $resultado = oci_parse($this->_db,"SELECT  ID_TIPO_CLIENTE CODIGO, DESC_TIPO_CLIENTE DESCRIPCION
				 FROM SGC_TP_TIPO_CLIENTE");

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



    public function obtenereDiametros (){
        $resultado = oci_parse($this->_db,"SELECT COD_CALIBRE, DESC_CALIBRE FROM SGC_TP_CALIBRES");

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


    public function obtenersubnumeros (){
        $sql="SELECT ID_SUBNUM, DESCRIPCION
				 FROM SGC_TP_SUBNUMEROS";
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



    public function NuevoApto(){

        $resultado=oci_parse($this->_db," BEGIN ACEA.SGC_P_INSERTA_APARTAMENTO($this->numero_apto,'$this->letra_apto',:PMSGRESULT,:PCODRESULT);COMMIT;END;");
        echo " BEGIN ACEA.SGC_P_INSERTA_CALLE('$this->desc_nvia','$this->id_proyecto',$this->id_tvia,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        oci_bind_by_name($resultado,':PCODRESULT',$codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera=TRUE && $codresult==0 ){
            oci_close($this->_db);
            return $codresult;
        }else{
            oci_close($this->_db);
            echo "$msgresult: $codresult";
            return $codresult;
        }
    }

    public  function ObtenerApto($termino){
        $resultado = oci_parse($this->_db,"SELECT CONCAT(NUMERO_APARTAMENTO,LETRA_APARTAMENTO) APTO
				 FROM SGC_TP_APARTAMENTOS WHERE CONCAT(NUMERO_APARTAMENTO,LETRA_APARTAMENTO) LIKE '%$termino%'");
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






    public  function ObtenerFianza($sector,$ruta,$uso){
        $resultado = oci_parse($this->_db,"SELECT FIANZA FROM SGC_TP_VALOR_CONTRATOS WHERE ID_SECTOR='$sector' AND ID_RUTA='$ruta'
				AND ID_USO='$uso'");


        $banderas=oci_execute($resultado);
        oci_fetch($resultado);
        $fianza = oci_result($resultado, 'FIANZA');

        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $fianza;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }



    public  function ObtenerDesTipovia($proyecto,$tipvia){
        $resultado = oci_parse($this->_db,"SELECT DESC_TIPO_VIA FROM SGC_TP_TIPO_VIA WHERE ID_TIPO_VIA='$tipvia' AND ID_PROYECTO='$proyecto'
				");


        $banderas=oci_execute($resultado);
        oci_fetch($resultado);
        $tipvia = oci_result($resultado, 'DESC_TIPO_VIA');

        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $tipvia;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }



    public  function ObtenerDescsubnum($subnum){
        $resultado = oci_parse($this->_db,"SELECT DESCRIPCION FROM SGC_TP_SUBNUMEROS WHERE ID_SUBNUM=$subnum
				");


        $banderas=oci_execute($resultado);
        oci_fetch($resultado);
        $subnumero = oci_result($resultado, 'DESCRIPCION');

        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $subnumero;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }


    public  function ObtenerDesNomvia($consecvia){
        $resultado = oci_parse($this->_db,"SELECT DESC_NOM_VIA FROM SGC_TP_NOMBRE_VIA WHERE CONSEC_NOM_VIA='$consecvia' 
				");


        $banderas=oci_execute($resultado);
        oci_fetch($resultado);
        $tipvia = oci_result($resultado, 'DESC_NOM_VIA');

        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $tipvia;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }



    public  function ObtenerCli($termino){
        $resultado = oci_parse($this->_db,"SELECT DOCUMENTO
				FROM SGC_TT_CLIENTES WHERE DOCUMENTO LIKE '%$termino%' AND ROWNUM<100");
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



    public  function ObtenerCargo($codusr){

        $sql="select USR.ID_CARGO from sgc_tt_usuarios usr
				where USR.ID_USUARIO='$codusr'";
        $resultado = oci_parse($this->_db,$sql);


        $banderas=oci_execute($resultado);
        oci_fetch($resultado);
        $cargo = oci_result($resultado, 'ID_CARGO');

        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $cargo;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

}


