<?php
include_once "class.conexion.php";


class Medidor extends ConexionClass
{


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

    public function __construct()
    {
        parent::__construct();
    }




    public function IngresaOrdMantCorMed($usr,$orden,$medio,$fecMan,$med,$cal,$ser,$lect,$emp,$obMan,$obGen,$long,$lat,$inm,$act){
        $usr=addslashes($usr);
        $orden=addslashes($orden);
        $medio=addslashes($medio);
        $fecMan=addslashes($fecMan);
        $med=addslashes($med);
        $cal=addslashes($cal);
        $ser=addslashes($ser);
        $lect=addslashes($lect);
        $emp=addslashes($emp);
        $obMan=addslashes($obMan);
        $obGen=addslashes($obGen);
        $long=addslashes($long);
        $lat=addslashes($lat);
        $inm=addslashes($inm);
        $act=addslashes($act);
        $usr=addslashes($usr);

        $sql="BEGIN SGC_P_INGMANTCORR('$usr',$orden,'$medio',$lect,'$fecMan','$med','$cal','$ser','$emp','$obMan','$obGen','$long','$lat','$inm','$act',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $nombrelog=date('Y-m-d');
        $file = fopen("logs/log-$nombrelog-MedCorrAntes.txt", "a");
        fwrite($file, date('Ymd H:i:s') . PHP_EOL);
        fwrite($file, $sql . PHP_EOL);
        fclose($file);
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medcorrFail.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql .$this->mesrror . PHP_EOL);
                fclose($file);
                return false;
            }else{
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medcorrOk.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
                return true;
            }
        }
        else{
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-medcorrFail.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            return false;

        }
    }

    public function IngresaOrdMantActMedCor($act,$orden){
        $act=addslashes($act);
        $orden=addslashes($orden);

        $sql="BEGIN SGC_P_INGMACTMED_CORR('$act',$orden,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

    public function IngresaOrdCambioMed($usr,$orden,$medio,$lecRet,$obsLec,$motImp,$fecIns,$med,$cal,$ser,$lect,$emp,$entUsr,$obins,$oblecins,$long,$lat,$inm,$fact){


        $sql="BEGIN SGC_P_INGCAMBMED_MOV('$usr',$orden,'$medio','$lecRet','$obsLec','$motImp','$fecIns','$med','$cal','$ser',$lect,'$emp','$entUsr','$obins','$oblecins','$long','$lat','$inm','$fact',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $nombrelog=date('Y-m-d');
        $file = fopen("logs/log-$nombrelog-MedInsCambAntes.txt", "a");
        fwrite($file, date('Ymd H:i:s') . PHP_EOL);
        fwrite($file,  $sql.PHP_EOL);
        fclose($file);
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medInsCamFail.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql .$this->mesrror . PHP_EOL);
                fclose($file);
                echo  $this->mesrror;
            }else{
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medInsCamOk.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
                return true;
            }
        }
        else{
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-medInsCamFail.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
           echo  $this->mesrror;
        }
    }





    public function IngresaMedManPre($usr,$orden,$medio,$fecIns,$med,$cal,$ser,$lect,$emp,$obins,$long,$lat,$inm){


         $sql="BEGIN SGC_P_INGMANTMED_MOV('$usr',$orden,'$medio',$lect,'$fecIns','$med','$cal','$ser','$emp','$obins','','$long','$lat','$inm',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $nombrelog=date('Y-m-d');
        $file = fopen("logs/log-$nombrelog-MedPreAntes.txt", "a");
        fwrite($file, date('Ymd H:i:s') . PHP_EOL);
        fwrite($file,  $sql.PHP_EOL);
        fclose($file);
         $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medPreFail.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql .$this->mesrror . PHP_EOL);
                fclose($file);
                return false;
            }else{
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medPreOk.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
                return true;
            }
        }
        else{
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-medPreFail.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            return false;
        }
    }

    public function IngresaMedManCorr($usr,$orden,$medio,$fecIns,$med,$cal,$ser,$lect,$emp,$obins,$long,$lat,$inm){


         $sql="BEGIN SGC_P_INGMANTMED_CORR_MOV('$usr',$orden,'$medio',$lect,'$fecIns','$med','$cal','$ser','$emp','$obins','','$long','$lat','$inm',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $nombrelog=date('Y-m-d');
        $file = fopen("logs/log-$nombrelog-MedCorrAntes.txt", "a");
        fwrite($file, date('Ymd H:i:s') . PHP_EOL);
        fwrite($file,  $sql.PHP_EOL);
        fclose($file);
         $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medCorrFail.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql .$this->mesrror . PHP_EOL);
                fclose($file);
                echo "error prod";
                return false;
            }else{
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medCorrOk.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
                return true;
            }
        }
        else{
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-medCorrFail.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            echo "bandera false";
            return false;
        }
    }




    public function IngresaActManPre($act,$ord){


        $sql="BEGIN SGC_P_INGMACTMED_MOV('$act',$ord,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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
    public function IngresaActManCorr($act,$ord){


        $sql="BEGIN SGC_P_INGMACTMEDCORR_MOV('$act',$ord,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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



    public function IngresaInsEstMed($orden,$fecIns,$obins,$long,$lat){


        $sql="BEGIN SGC_P_INGRESA_INSPECCION_EST('$orden','$fecIns','$obins','$lat','$long',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                echo  $this->mesrror;
            }else{
                return true;
            }
        }
        else{
            echo  $this->mesrror;
        }
    }

    public function IngresaOrdMantMed($usr,$orden,$medio,$fecMan,$med,$cal,$ser,$lect,$emp,$obMan,$obGen,$long,$lat,$inm,$lisAct){



        $sql="BEGIN SGC_P_INGMANTMED('$usr',$orden,'$medio',$lect,'$fecMan','$med','$cal','$ser','$emp','$obMan','$obGen','$long','$lat','$inm','$lisAct',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $nombrelog=date('Y-m-d');
        $file = fopen("logs/log-$nombrelog-MedPreAntes.txt", "a");
        fwrite($file, date('Ymd H:i:s') . PHP_EOL);
        fwrite($file,  $sql.PHP_EOL);
        fclose($file);
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medPreFail.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql .$this->mesrror . PHP_EOL);
                fclose($file);
                return false;
            }else{
                $nombrelog=date('Y-m-d');
                $file = fopen("logs/log-$nombrelog-medPreOk.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
                return true;
            }
        }
        else{
            $nombrelog=date('Y-m-d');
            $file = fopen("logs/log-$nombrelog-medPreFail.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            return false;
        }
    }

    public function IngresaOrdMantACTMed($act,$orden){

        $sql="BEGIN SGC_P_INGMACTMED('$act',$orden,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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


}