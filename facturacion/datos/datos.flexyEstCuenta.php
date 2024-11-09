<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../clases/class.pagos.php';
include '../clases/class.otrosRecaudos.php';
include '../clases/class.facturas.php';
$inmueble=$_GET['inmueble'];


function countRec() {
    return 100;
}

if (!$page) $page = 1;
if (!$rp) $rp = 10000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$l=new facturas();
$registros=$l->todasFacturas($inmueble);

$o=new Pago();
$registros2=$o->todosPagos($inmueble);


$p=new OtrosRec();
$registros3=$p->todosOtrosRec($inmueble);


$total =countRec();
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;



$bandPago=false;
$bandOtrosR=false;
$bandFact=false;
$entrowhile=false;
$noentrowhile=true;

$datospago=true;
$datosotro=true;
$datosfac=true;

$saldo=0;
while($bandPago||$bandOtrosR||$bandFact||$noentrowhile){

    if($noentrowhile==false){
        if($fechcomp<=$fechcomp2 && $fechcomp<=$fechcomp3){
            $saldo+=$total;
            if ($rc) $json .= ",";
            $json .= "\n{";
            $json .= "id:'".$desc."',";
            $json .= "cell:['" .$fecha."'";
            $json .= ",'".addslashes($desc)."'";
            $json .= ",'".addslashes("$".$total)."'";
            $json .= ",'".addslashes("$"."0")."'";
            $json .= ",'".addslashes("$".$saldo)."'";
            $json .= "]}";
            $rc = true;
            $bandFact=false;

        }else if($fechcomp2<=$fechcomp && $fechcomp2<=$fechcomp3){
            $saldo-=$total2;
            if ($rc) $json .= ",";
            $json .= "\n{";
            $json .= "id:'".$desc2."',";
            $json .= "cell:['" .$fecha2."'";
            $json .= ",'".addslashes($desc2)."'";
            $json .= ",'".addslashes("$"."0")."'";
            $json .= ",'".addslashes("$".$total2)."'";
            $json .= ",'".addslashes("$".$saldo)."'";
            $json .= "]}";
            $rc = true;
            $bandPago=false;

        }else{
            $saldo-=$tota3;
            if ($rc) $json .= ",";
            $json .= "\n{";
            $json .= "id:'".$desc3."',";
            $json .= "cell:['" .$fecha3."'";
            $json .= ",'".addslashes($desc3)."'";
            $json .= ",'".addslashes("$"."0")."'";
            $json .= ",'".addslashes("$".$tota3)."'";
            $json .= ",'".addslashes("$".$saldo)."'";
            $json .= "]}";
            $rc = true;
            $bandOtrosR=false;


        }
    }



    $noentrowhile=false;
    if($bandFact==false && $datosfac){
        if(oci_fetch($registros)){
            $fecha=oci_result($registros, 'FEC_EXPEDICION');
            $desc = oci_result($registros, 'DESCRIPCION');
            $total = oci_result($registros, 'TOTAL');
            $fechcomp = oci_result($registros, 'FECHCOMP');
            $bandFact=true;



        }else{

            $fechcomp=99999999;
            $bandFact=false;
            $datosfac=false;
        }
    }



    if($bandPago==false && $datospago){
        if(oci_fetch($registros2)){
            $fecha2=oci_result($registros2, 'FECHA_PAGO');
            $desc2 = oci_result($registros2, 'DESCRIPCION');
            $total2 = oci_result($registros2, 'IMPORTE');
            $fechcomp2 = oci_result($registros2, 'FECHCOMP');
            $bandPago=true;
        }else{


            $fechcomp2=99999999;
            $bandPago=false;
            $datospago=false;
        }
    }


    if($bandOtrosR==false && $datosotro){
        if(oci_fetch($registros3)){
            $fecha3=oci_result($registros3, 'FECHA');
            $desc3 = oci_result($registros3, 'DESCRIPCION');
            $total3 = oci_result($registros3, 'IMPORTE');
            $fechcomp3 = oci_result($registros3, 'FECHCOMP');
            $bandOtrosR=true;

        }else{

            $fechcomp3=99999999;
            $bandOtrosR=false;
            $datosotro=false;
        }
    }




}




$json .= "]\n";
$json .= "}";
echo $json;
?>