<?php
require_once 'class.comando.php';
class Caja extends Comando {
    public function __construct(){
        parent::__construct();
    }
    public function GetCajasAtencion(){

        $sql = "SELECT CP.ID_CAJA, CP.DESCRIPCION NUMERO_POSICION, (U.NOM_USR||' '||U.APE_USR) USUARIO, CP.ID_PUNTO_PAGO CODIGO_PUNTO, PP.DESCRIPCION, EP.DESC_ENTIDAD
                FROM SGC_TP_CAJAS_PAGO CP, SGC_TT_USUARIOS U,SGC_TP_PUNTO_PAGO PP,SGC_TP_ENTIDAD_PAGO EP
                WHERE U.ID_USUARIO(+) = CP.ID_USUARIO
                  AND PP.ID_PUNTO_PAGO = CP.ID_PUNTO_PAGO
                  AND EP.COD_ENTIDAD = PP.ENTIDAD_COD
                  AND (U.ID_CARGO IN (300,301) OR CP.DESCRIPCION LIKE '%AT%')
                ORDER BY CP.ID_CAJA";

        $resultado = $this->EjecutarConsulta($sql);
        return $resultado;
    }
    public function AsignarPosicion($idUsuario,$idCaja){
         $sql = "BEGIN SGC_P_ASIGNA_CAJA(:ID_USUARIO_IN,
                                  :ID_CAJA_IN,
                                  :CODERROR_OUT,
                                  :MSJERROR_OUT); COMMIT; END;";

         $resultado = oci_parse($this->_db,$sql);
         oci_bind_by_name($resultado,":ID_USUARIO_IN",$idUsuario);
         oci_bind_by_name($resultado,":ID_CAJA_IN",$idCaja);
         oci_bind_by_name($resultado,":CODERROR_OUT",$coderror,5);
         oci_bind_by_name($resultado,":MSJERROR_OUT",$msjerror,500);

         $bandera = oci_execute($resultado);
         if($bandera == true){
             return [
                     "Code"   => $coderror,
                     "Status" => $msjerror
                    ];
         }else{
             return [
                 "Code"   => 03,
                 "Status" => oci_error($resultado)
             ];
         }
         /*$parametros_in = [["nombreParametro"=>"ID_USUARIO_IN","valorParametro"=>$idUsuario],
                          ["nombreParametro"=>"ID_CAJA_IN"   ,"valorParametro"=>$idCaja]/*,
                          "CODERROR_OUT",
                          "MSJERROR_OUT"]*/
        //return $this->EjecutarProcedimiento($sql,$parametros_in);
    }
    public function DesasignarPosicion($idCaja){
         $sql = "BEGIN SGC_P_DESASIGNA_CAJA(:ID_CAJA_IN,
                                            :CODERROR_OUT,
                                            :MSJERROR_OUT); COMMIT; END;";

         $resultado = oci_parse($this->_db,$sql);
         oci_bind_by_name($resultado,":ID_CAJA_IN",$idCaja);
         oci_bind_by_name($resultado,":CODERROR_OUT",$coderror,5);
         oci_bind_by_name($resultado,":MSJERROR_OUT",$msjerror,500);

         $bandera = oci_execute($resultado);
         if($bandera == true){
             return [
                     "Code"   => $coderror,
                     "Status" => $msjerror
                    ];
         }else{
             return [
                 "Code"   => 03,
                 "Status" => oci_error($resultado)
             ];
         }
    }
}