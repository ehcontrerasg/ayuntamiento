<?php
class FechaVencimiento{

    /*public function fechaExpiracionActualizado($fecha_expiracion){


        $fecha_expiracion = DateTime::createFromFormat('m/y',$fecha_expiracion);
        $fecha_actual     = DateTime::createFromFormat('m/y',date('m/y'));

        $diferencia_meses = date_diff($fecha_actual,$fecha_expiracion);

        $cantidad_meses   = $diferencia_meses->format("%m");
        $cantidad_agnos   = $diferencia_meses->format("%y");

        var_dump($cantidad_agnos);
        var_dump($cantidad_meses);

        //if($cantidad_agnos == 0){
            if(($cantidad_meses>0 && $cantidad_meses<=2)){
                echo "false";
                return false;
            }
        //}



        echo "true";
        return true;
    }*/


    public function fechaExpiracionActualizado($fecha_expiracion){

        //$fecha_expiracion = '05/23';
        $fecha_expiracion = DateTime::createFromFormat('m/y',$fecha_expiracion);
        $fecha_actual     = DateTime::createFromFormat('m/y',date('m/y'));

        $diferencia_meses = date_diff($fecha_expiracion,$fecha_actual);

        $cantidad_meses   = $diferencia_meses->format("%m");
        $cantidad_agnos   = $diferencia_meses->format("%y");

        print_r(['CANTIDAD_MESES'=>$cantidad_meses,'CANTIDAD_AGNOS'=>$cantidad_agnos]);
        if($cantidad_agnos == 0){
            if(($cantidad_meses>0 && $cantidad_meses<=2)){
                echo "false";
                return false;
            }
        }

        /*
        if(/*$cantidad_agnos == 0 && ($cantidad_meses>0 && $cantidad_meses<=2)){
            return false;
        }*/
        echo "TRUE";
        return true;
    }
}

$instance = new FechaVencimiento();

 $instance->fechaExpiracionActualizado("05/23");

