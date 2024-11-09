<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
//session_start();
    require '../clases/classPagos.php';
    $cod_inmueble=$_POST['cod_inmueble'];
    /*$valores = explode(" ", $q);
    $q = $valores[0]; // valores1
    $des_uso = $valores[1]; // valores2
    $cod_pro = $valores[2]; // valores2
    */
    $c = new Pagos();
    $resultado = $c->obtenerDatosCliente($cod_inmueble);
    while (oci_fetch($resultado)) {
        $cod_inm = oci_result($resultado, 'CODIGO_INM');
        $dir_inm = oci_result($resultado, 'DIRECCION');
        $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
        $cod_cli = oci_result($resultado, 'CODIGO_CLI');
        $nom_cli = oci_result($resultado, 'ALIAS');
        $est_inm = oci_result($resultado, 'ID_ESTADO');
        $sec_act = oci_result($resultado, 'DESC_ACTIVIDAD');
        $des_uso = oci_result($resultado, 'ID_USO');
    }oci_free_statement($resultado);
    $direccion = $urb_inm.' '.$dir_inm;

    ?>

    <td width="214">
        Direcci&oacute;n<br />
        <input type="text" name="direccion" id="direccion" value="<?php echo $direccion;?>" size="30" readonly class="input"/>
    </td>
    <td width="418">
        Cliente<br />
        <input type="text" name="cod_cli" value="<?php echo $cod_cli;?>" size="10" readonly class="input" />
        <input type="text" name="nom_cli" value="<?php echo $nom_cli;?>" size="50" readonly class="input"/>
    </td>
    <td width="95">
        Estado<br />
        <input type="text" name="est_inm" value="<?php echo $est_inm;?>" size="10" readonly class="input" />
    </td>
    <td width="322">
        Uso<br />
        <input type="text" name="des_uso" value="<?php echo $des_uso;?>" size="30" readonly class="input"/>
    </td>




<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

