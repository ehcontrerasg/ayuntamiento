<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 3/7/2018
 * Time: 13:58
 */

include "../../clases/class.listaPagos.php";

 if ($_GET["tipo"]=="report")
 {
     $fec_in  = $_GET['fechaDesde'];
     $fec_fin = $_GET['fechaHasta'];

     $listaPagos = new ListaPagos();

     $reporte = $listaPagos->getListaPagos($fec_in,$fec_fin);
     $data = array();
     while(oci_fetch($reporte))
     {
         $idFactura  = oci_result($reporte,"ID_FACTURA");
         $monto      = oci_result($reporte,"MONTO");
         $fechaPago  = oci_result($reporte,"FECHA_PAGO");
         $inmueble   = oci_result($reporte,"INMUEBLE");
         $proyecto   = oci_result($reporte,"ACUEDUCTO");
         $origen     = oci_result($reporte,"ORIGEN");

         /*$control = "";

         if($origen == "PAGO_RECURRENTE_CARDNET"){*/
             $control  = "<button class='btn btn-primary btnAnular'>Anular</button>";
         /*}*/

         $arr = array(
             $idFactura,
             $monto,
             $fechaPago,
             $inmueble,
             $proyecto,
             $origen,
             $control
         );
         array_push($data,$arr);
     }
     oci_free_statement($reporte);
     echo json_encode($data);



 }