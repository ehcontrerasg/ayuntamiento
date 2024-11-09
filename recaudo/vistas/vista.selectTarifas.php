<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
//session_start();
    require '../clases/classPagos.php';
    $q=$_POST['q'];
    $valores = explode(" ", $q);
    $q = $valores[0]; // valores1
    $des_uso = $valores[1]; // valores2
    $cod_pro = $valores[2]; // valores2
    $h = new Pagos();
    $resultado = $h->seleccionaTarifa($q, $des_uso, $cod_pro);
    echo "Tarifa<br /> 
	<select name='tarifa' id='tarifa' class='input' >
	<option value='0' selected>Seleccione tarifa...</option>";

    while (oci_fetch($resultado)) {
        $cod_tarifa = oci_result($resultado, 'CONSEC_TARIFA') ;
        $des_tarifa = oci_result($resultado, 'DESC_TARIFA') ;
        if($cod_tarifa == $tarifa) echo "<option value='$cod_tarifa' selected>$des_tarifa</option>\n";
        else echo "<option value='$cod_tarifa'>$des_tarifa</option>\n";
    }oci_free_statement($resultado);
    echo "</select>";
    ?>




<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

