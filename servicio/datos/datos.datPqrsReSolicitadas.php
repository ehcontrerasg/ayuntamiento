<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
///ini_set('MAX_EXECUTION_TIME', '-1');

error_reporting(E_ALL);//mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include '../../clases/classPqrs.php';
session_start();
$cod = $_SESSION['codigo'];

if ($tipo == 'motiv') {
    $l         = new PQRs();
    $tipo_pqr      = $_POST['group'];
    $registros = $l->seleccionaMotivoPqrporTipo($tipo_pqr);
    $arr       = array();

    oci_fetch_all($registros, $motivo);
    echo json_encode($motivo);

}

if ($tipo == 'PqrReSol') {


    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];
    $proyecto = $_POST['proyecto'];
    $tipo_pqr = $_POST['tipo_pqr'];
    $motivo_pqr = $_POST['motivo_pqr'];


    //Insertamos los datos
    $pqr = new PQRs();
    $fila = 4;
    $registros = $pqr->GetInmPqrsReSolicitadas($proyecto, $tipo_pqr, $motivo_pqr, $fecini, $fecfin);
    $data=array();
    $record = array();
    $name = array();
    $value = array();
    $dat = array();
//for ($i=0;$i<=count($registros);$i++){}
    while (oci_fetch($registros)) {

        $inm = oci_result($registros, 'COD_INMUEBLE');
        $motivo = oci_result($registros, 'MOTIVO_PQR');

        $registros2 = $pqr->GetFechPqrsReSol($inm, $motivo, $fecini, $fecfin);

        while (oci_fetch($registros2)) {
            $fechaPqr = oci_result($registros2, 'FECHA_REGISTRO');
            $inm = oci_result($registros2, 'COD_INMUEBLE');
            $motivo = oci_result($registros2, 'MOTIVO_PQR');

           // $pqr2 = new PQRs();
          //  $registros3 = $pqr->GetPqrsDifDias($inm, $motivo, $fechaPqr);
           $registros3 = $pqr->GetPqrsReSolValidas($inm, $motivo, $fechaPqr);
// echo var_dump($registros3);
            while (oci_fetch($registros3)) {
               // for ($i=0,$i2=1;$i<=count($registros3);$i++,$i2++){

                 $cod_pqr = oci_result($registros3, 'CODIGO_PQR');
                 $dias_res = oci_result($registros3, 'DIAS_RESOLUCION');
                 $fechaPqr = oci_result($registros3, 'FECHA_REGISTRO');
                $inm = oci_result($registros3, 'COD_INMUEBLE');
                $motivo = oci_result($registros3, 'MOTIVO_PQR');
                $dias = oci_result($registros3, 'DIAS');


                if ($dias >= 1) {
                    $arr = array( $inm ,$motivo, $dias_res,$cod_pqr,$fechaPqr);
                  // $arr = array('', "Inmueble: " . $inm . " Motivo " . $motivo, '');
                    //$arr2 = array('', $cod_pqr, $fechaPqr);
                   //array_push($data, $arr);

                        array_push($data,$arr );



                  //  foreach($data as $key=>$value){


                //   array_push($data, $arr2);
               //    print_r($arr);

                }

            }


        }

    }

      for ($i = 0; $i < count($data); $i++) {
          //print_r($data[$i][2]);

          if (!in_array($data[$i], $record)) {
              //   print_r($data[$i][2]);
              //      echo "no encontrado: ".$data[$i];
              array_push($record, $data[$i]);

              //    echo "encontrado: ".$data[$i];

          }
      //    print_r($record[$i]);

      }

    echo json_encode($record);

}
?>
