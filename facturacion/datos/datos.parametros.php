<?php
     include "../../clases/class.parametros.php";

    $peticion = $_POST["peticion"];


     if ($peticion=="reporte")
     {
         $parametros = new Parametro();
         $reporte =  $parametros->getParametros();
         $data=array();
         while (oci_fetch($reporte))
         {
            $codParametro = oci_result($reporte,"COD_PARAMETRO");
            $nomParametro = oci_result($reporte,"NOM_PARAMETRO");
            $valParametro = oci_result($reporte,"VAL_PARAMETRO");
            $descParametro = oci_result($reporte,"DES_PARAMETRO");

            $arr = array(
                $codParametro,
                $nomParametro,
                $valParametro,
                $descParametro,
            );

            array_push($data,$arr);

         }
         oci_free_statement($reporte);
         echo json_encode($data);
     }

     if($peticion=="actualiza")
     {
         $id = $_POST["id"];
         $val = $_POST["val"];

         $parametros1 = new Parametro();
         $resultado=$parametros1->actualizarParametro($id,$val);


     }