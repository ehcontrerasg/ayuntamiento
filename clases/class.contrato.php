<?php
include "class.conexion.php";


class Contrato extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }


    private $mesrror;
    private $coderror;


    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function cambiaUsuContrato($contrato,$documento,$usuario,$alias)
    {
         $sql="BEGIN SGC_P_CAMBIA_CONTRATO('$contrato','$documento','$usuario','$alias',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }


    }

    public function IngresaNuevoContrato($idc, $inm, $acu, $pro, $sec, $rut, $urb, $dir, $cli, $ali, $ema, $doc, $tdo, $tel, $cod, $tot, $cuo, $est, $der, $fia, $cta, $cup, $uni){
        $sql="BEGIN SGC_P_INGNUEVO_CONTRATO('$idc', $inm,'$acu',$pro,$sec,'$rut','$urb','$dir','$cli','$ali','$ema','$doc','$tdo','$tel','$cod','$tot','$cuo','$est',$der,$fia,$cta,$cup,$uni,:PCODRESULT, :PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror, 1000);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera){
            if( $this->coderror > 0){
                return false;
            }else{
                return true;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function cambioNombreContrato($idContrato, $usuarioCrea,$alias,$nombre,$direccion,$telefono,$email,$tipoDocumento,$documento, $grupo, $direccionCorrespondencia, $correspondencia,$contribuyenteDgii,&$codigo, &$mensaje){
        $sql = "BEGIN SGC_P_CAMBIO_NOMBRE_CONTRATO(:ID_CONTRATO_IN,:USUARIO_CREA_IN,:ALIAS_IN,:NOMBRE_IN,:DIRECCION_IN,:TELEFONO_IN,:EMAIL_IN,:TIPODOC_IN,:DOCUMENTO_IN,:GRUPO_IN,:DIR_CORRES_IN,:CORRESPO_IN,:CONTRIBUYENTE_IN,:CODERROR_OUT,:MSERROR_OUT); COMMIT; END;";
        $statement = oci_parse($this->_db, $sql);

        oci_bind_by_name($statement,":ID_CONTRATO_IN",$idContrato);
        oci_bind_by_name($statement,":USUARIO_CREA_IN",$usuarioCrea);
        oci_bind_by_name($statement,":ALIAS_IN",$alias);
        oci_bind_by_name($statement,":NOMBRE_IN",$nombre);
        oci_bind_by_name($statement,":DIRECCION_IN",$direccion);
        oci_bind_by_name($statement,":TELEFONO_IN",$telefono);
        oci_bind_by_name($statement,":EMAIL_IN",$email);
        oci_bind_by_name($statement,":TIPODOC_IN",$tipoDocumento);
        oci_bind_by_name($statement,":DOCUMENTO_IN",$documento);
        oci_bind_by_name($statement,":GRUPO_IN",$grupo);
        oci_bind_by_name($statement,":DIR_CORRES_IN",$direccionCorrespondencia);
        oci_bind_by_name($statement,":CORRESPO_IN",$correspondencia);
        oci_bind_by_name($statement,":CONTRIBUYENTE_IN",$contribuyenteDgii);
        oci_bind_by_name($statement,":CODERROR_OUT",$codigo,5);
        oci_bind_by_name($statement,":MSERROR_OUT",$mensaje,500);
        oci_close($this->_db);

        return (oci_execute($statement)) ? $statement : false;
     }

}
