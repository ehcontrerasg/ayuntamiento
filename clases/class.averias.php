<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 9/21/2018
 * Time: 8:52 AM
 */

include_once "class.conexion.php";

class Averias extends  ConexionClass{

    private $mesrror;
    private $coderror;


    public function __construct(){

        parent::__construct();

    }


    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function obtenerMotivo()
    {

        $sql="SELECT
                TR.DESCRIPCION DESCRIPCION,
                TR.ID CODIGO
              FROM
                SGC_TP_TIPOS_INCIDENCIAS TR
              WHERE
                TR.ESTADO='A'";

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

    public function obtenerEstado()
    {

        $sql="SELECT DISTINCT 
                TR.DESCRIPCION DESCRIPCION,
                TR.ESTADO CODIGO
              FROM
                SGC_TP_ESTADOS_INCIDENCIAS TR
             ";

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

    public function obtenerAverias($motivo,$fechaIn,$fechaFn,$id,$estado)
    {
        $where = "";
        if($id!="")
            $where .= "  TR.ID='$id' AND ";
        if($estado!="")
            $where .= "  TR.ESTADO='$estado' AND ";
        if ($motivo != "")
            $where .= "  TR.ID_TIPO_RECLAMO='$motivo' AND ";
        if ($fechaIn != "")
            $where .= "  TR.FECHA BETWEEN TO_DATE('$fechaIn 00:00:00','YYYY-MM-DD HH24:MI:SS')
      AND TO_DATE('$fechaFn 23:59:59','YYYY-MM-DD HH24:MI:SS') AND ";

        $sql = "SELECT
  TR.CODIGO,
  TR.ID,
  TR.OBSERVACION,
  TR.NOMBRE,
  TR.TELEFONO,
  TR.DIRECCION,
  TR.LATITUD,
  TR.LONGITUD,
  TR.FECHA,
  TR.EMAIL,
  E.DESCRIPCION ESTADO,
  TRM.DESCRIPCION
FROM  SGC_TT_INCIDENCIAS TR,SGC_TP_TIPOS_INCIDENCIAS TRM,SGC_TP_ESTADOS_INCIDENCIAS E
WHERE $where TR.ID_TIPO_INCIDENCIA=TRM.ID AND TR.ESTADO=E.ESTADO
ORDER BY TR.FECHA DESC
";

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

        public function obtenerfOTOS($idReclamo){

        $sql="SELECT
                FR.URL_FOTO
              FROM  SGC_TT_INCIDENCIAS TR, SGC_TT_FOTOS_INCIDENCIAS FR
              WHERE TR.ID=FR.ID_RECLAMO
              AND FR.ID_RECLAMO='$idReclamo'        
               ";

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

    public function actEstadoAveria($id,$codUser)
    {
        $id = addslashes($id);
        $codUser = addslashes($codUser);

         $sql       = "BEGIN SGC_P_ACT_ESTADO_INCIDENCIA('$id','$codUser',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);

        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return true;
        } else {

            oci_close($this->_db);
            return false;

        }

    }

}