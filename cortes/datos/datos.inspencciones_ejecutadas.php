<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 8/15/2018
 * Time: 11:26 AM
 */
$tipo=$_POST['tip'];

if ($tipo=="report") {

    include '../../clases/class.inspeccionCorte.php';

    $proyecto = $_POST['proyecto'];
    $fechaIn = $_POST['fechaIn'];
    $fechaFn = $_POST['fechaFn'];
    $contratista=$_POST['contratista'];
    $reconect=$_POST['reconectado'];

    $l=new InspeccionCorte();
    $registros=$l->getInspEjecutadas($proyecto,$fechaIn,$fechaFn,$contratista,$reconect);
    $data = array();
    $total_fotos=null;

    while (oci_fetch($registros)) {
      //  unset($total_fotos);
        //$cont++;
        $codigoInm = oci_result($registros, 'CODIGO_INM');
        $fechaEje  = oci_result($registros, 'FECHA_EJE');
        $tipoCorte  = oci_result($registros, 'TIPO_CORTE_ENCONTRADO_TERRENO');
        $tipoCorteEje = oci_result($registros, 'TIPO_CORTE_EJECUTADO');
        $reconectado = oci_result($registros, 'RECONECTADO');
        $fechaAsig = oci_result($registros, 'FECHA_ASIG');
        $login = oci_result($registros, 'LOGIN');
        $operario = oci_result($registros, 'USR_ASIG');


        if($reconectado=='S') {
            $I = new InspeccionCorte();
            $total_fotos = $I->existefotoins($codigoInm, $fechaIn, $fechaFn, $operario);
            if ($total_fotos == true)
                $arr = array($codigoInm, $fechaEje, $tipoCorte, $tipoCorteEje, $reconectado, $fechaAsig, $login, "S <b>" . "<img src=\"../../images/camara.ico\" style='cursor:pointer;' data-toggle=\"modal\" data-target=\"#myModal\" onclick=\"fotos(" . $codigoInm . ",'" . $fechaIn . "','" . $fechaFn . "')\" width='15' height='15\'/>" . "</b>");
            else
                $arr = array($codigoInm,$fechaEje,$tipoCorte,$tipoCorteEje,$reconectado,$fechaAsig,$login,"N");

        }else{
            $arr = array($codigoInm,$fechaEje,$tipoCorte,$tipoCorteEje,$reconectado,$fechaAsig,$login,"N");
        }


        array_push($data,$arr);
    }

//echo print_r($data);

  oci_free_statement($total_fotos);
  oci_free_statement($registros);
   echo json_encode($data);


}
