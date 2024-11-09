<?php
require_once 'ConexionClass.php';

class MantenimientoClass extends ConexionClass {
  
    public function __construct()
    {
         parent::__construct();
         
    }

    public function actualizardatos($periodo,$inmueble,$Unidades,$unidadesd,$unidadesh, $estado,$usos,$actividad,$cliente,$telefono,
    		$tipodoc,$direccion,$fueraruta,$documento,$observaciones,$diasagua,$condServ){
			$observaciones=str_replace("'","",$observaciones);
             $sql="INSERT INTO
                SGC_TT_MANTENIMIENTO
              VALUES
                ('$periodo','$inmueble','$Unidades','$unidadesh','$unidadesd','$estado','$usos',
                '$actividad',"."'".str_replace("'"," ",$cliente)."','$telefono','$tipodoc','$direccion','N','$fueraruta','$documento','$observaciones','$diasagua','$condServ')";
       
//echo $sql;
      $resultados=oci_parse($this->_db,$sql );


        $nombrelog=date('Y-m-d');
        $file = fopen("Logs/log-$nombrelog-catastroAntes.txt", "a");
        fwrite($file, date('Ymd H:i:s') . PHP_EOL);
        fwrite($file, $sql . PHP_EOL);
        fclose($file);
   
         
        if(oci_execute($resultados))
        {
            $nombrelog=date('Y-m-d');
            $file = fopen("Logs/log-$nombrelog-catastroOk.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
           
            return $resultados;

        }
        else
        {
            $nombrelog=date('Y-m-d');
            $file = fopen("Logs/log-$nombrelog-catastroFail.txt", "a");
            fwrite($file, date('Ymd H:i:s') . PHP_EOL);
            fwrite($file, $sql . PHP_EOL);
            fclose($file);
            echo "false";
     
            return false;
        }
      
    }
	
	
	public function existe_mant($inm,$per){
		$sql=" select nvl(count(1),0) cant from SGC_TT_MANTENIMIENTO where id_periodo=$per and id_inmueble=$inm";
		$resultados=oci_parse($this->_db,$sql );

        if(oci_execute($resultados))
        {   
            oci_close($this->_db);
			if(oci_fetch($resultados)){
				$cant=oci_result($resultados,"CANT");
				if($cant==0){
					return false;
				}else{
					return true;
				}
				
			}
			else
			{
				return false;
			}
			
            

        }
        else
        {
            echo "false";
            oci_close($this->_db);
            return false;
        }
	}


    public function actualizarasignacion($fecha,$latitud,$longitud,$idtiporuta,$inmueble,$periodo){
        $sql="UPDATE
                SGC_TT_ASIGNACION
              SET
                FECHA_FIN=TO_DATE('$fecha','MM/DD/YYYY HH24:MI:SS'),
                LATITUD='$latitud',
                LONGITUD='$longitud'
              WHERE
                ID_TIPO_RUTA='$idtiporuta' AND
                ID_INMUEBLE='$inmueble' AND
                ID_PERIODO='$periodo'";
       

      $resultados=oci_parse($this->_db,$sql );


 
        if(oci_execute($resultados))
        {   
            oci_close($this->_db);
            return $resultados;

        }
        else
        {
            echo "false";
            oci_close($this->_db);
            return false;
        }
      
    }



}
