<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/29/2015
 * Time: 3:38 PM
 */
if (is_file("../../clases/class.conexion.php"))
{
    include_once "../../clases/class.conexion.php";
}
elseif  (is_file("../clases/class.conexion.php"))
{
    include_once "../clases/class.conexion.php";
}

class reliquidacion extends ConexionClass{
    private $codresult;
    private $msgresult;

    public function getcodresult(){return $this->codresult;}
    public function getmsgresult(){return $this->msgresult;}

    public function relconcepto($inmueble,$concepto,$perini,$perfin,$valor,$tiponota,$usr,$obs){


        if($valor!=null){
            
            $sql="BEGIN sgc_p_rel_concep_peri($inmueble,$concepto,$perini,$perfin,$valor,'$tiponota','$usr','$obs',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
			//echo $sql;
            $resultado= oci_parse($this->_db,$sql);
            oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
            oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
            $bandera=oci_execute($resultado);
            if($bandera){
                if($this->codresult==0){
                    return true;
                }
                else{

                    return false;
                }
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }else{
            return true;
        }
    }


    public function relconcepto2($inmueble,$concepto,$perini,$perfin,$valor,$tiponota,$usr,$obs){


        if($valor!=null){

            echo $sql="BEGIN sgc_p_nota_fact_concep_peri($inmueble,'$concepto','$valor',$perini,$perfin,'$tiponota','$usr','$obs',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
            //echo $sql;
            $resultado= oci_parse($this->_db,$sql);
            oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
            oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
            $bandera=oci_execute($resultado);
            if($bandera){
                if($this->codresult==0){
                    return true;
                }
                else{

                    return false;
                }
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }else{
            return true;
        }
    }


//////////////// reliquida una factura por metros facturados/////////////////
    public function relpormetros($metros,$factura,$obs,$usr){


        $msresult="";
        $codresult="";
        $sql="BEGIN SGC_P_RELMETROS($metros,$factura,'$obs','$usr',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
	
	
	//////////////// reliquida toda la deuda/////////////////
    public function reltot($inmueble,$obs,$usr){


        $msresult="";
        $codresult="";
        $sql="BEGIN sgc_p_rel_total($inmueble,'$obs','$usr', :PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

	//////////////// reliquida toda la deuda/////////////////
    public function reltot2($inmueble,$obs,$usr){


        $msresult="";
        $codresult="";
        $sql="BEGIN sgc_p_nota_fac_total($inmueble,'0','$obs','$usr', :PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
}