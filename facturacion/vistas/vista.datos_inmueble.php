<?php
session_start();
include '../clases/class.inmuebles.php';
include '../../destruye_sesion.php';
//pasamos variables por post
$coduser     = $_SESSION['codigo'];
$codinmueble = $_GET['codinmueble'];
$proyecto    = $_GET['proyecto'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Consulta General de Inmuebles</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
    <script language="JavaScript" type="text/javascript" src="../js/datInm.js?10 "></script>
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link href="../../css/tabber_facturacion.css" rel="stylesheet" type="text/css">
    <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
     <link rel="stylesheet" type="text/css" href="../css/datpago.css ">
</head>
<body>
    <?php
if ($codinmueble != "") {
    $where     = " AND I.CODIGO_INM = '$codinmueble'";
    $l         = new inmuebles();
    $registros = $l->datosInmueble($where);
    while (oci_fetch($registros)) {
        $zona_inm     = oci_result($registros, 'ID_ZONA');
        $urba_inm     = oci_result($registros, 'DESC_URBANIZACION');
        $dir_inm      = oci_result($registros, 'DIRECCION');
        $fecalta_inm  = oci_result($registros, 'FEC_ALTA');
        $estado_inm   = oci_result($registros, 'ID_ESTADO');
        $catastro_inm = oci_result($registros, 'CATASTRO');
        $proceso_inm  = oci_result($registros, 'ID_PROCESO');
        $cod_cli      = oci_result($registros, 'CODIGO_CLI');
        $con_cli      = oci_result($registros, 'ID_CONTRATO');
        $nom_cli      = oci_result($registros, 'ALIAS');
        $doc_cli      = oci_result($registros, 'DOCUMENTO');
        $tel_cli      = oci_result($registros, 'TELEFONO');
        $serial       = oci_result($registros, 'SERIAL');
        $email        = oci_result($registros, 'EMAIL');
        $facturas     = oci_result($registros, 'FACTURAS');
        $deuda        = oci_result($registros, 'DEUDA');

    }
    ?>
    <form name="datinm" action="vista.datos_inmueble.php" method="get">
        <div>
        <fieldset class="fieldset_fact" style="width:1280px">
        <legend class="legend_fact"><b>Inmueble N&deg;: <?php echo $codinmueble; ?></b></legend>
            <div>
                <div class="divinfo">
                    <label>Zona:</label>
                    <input class="input" type="text" readonly  value="<?php echo $zona_inm ?>"/>
                </div>


                <div class="divinfo">
                <label>Direccion:</label>
                <input class="input" type="text" readonly  value="<?php echo $dir_inm ?>"/>
                </div>


                <div class="divinfo">
                    <label>Urb.:</label>
                    <input class="input" type="text" readonly  value="<?php echo $urba_inm ?>"/>
                </div>

                <div class="divinfo">
                <label>Fecha Alta:</label>
                <input class="input" type="text" readonly value="<?php echo $fecalta_inm ?>"/>
                </div>

                <div class="divinfo">
                <label>Catastro:</label>
                <input class="input" type="text" readonly value="<?php echo $catastro_inm ?>"/>
                </div>

                <div class="divinfo">
                <label>Proceso:</label>
                <input class="input" type="text" readonly value="<?php echo $proceso_inm ?>"/>
                </div>

                <div class="divinfo">
                <label>Estado</label>
                <input class="input" type="text" readonly value="<?php echo $estado_inm ?>"/>
                </div>

                <div class="divinfo">
                <label>Cliente:</label>
                <input class="input" type="text" id="inpDatInmCli" readonly value="<?php echo $cod_cli ?>"/>
                </div>
                <input class="input" type="hidden" id="inpDatInmInm" readonly value="<?php echo $codinmueble ?>"/>
                <div class="divinfo">
                <label>Nombre:</label>
                <input class="input" type="text" readonly value="<?php echo $nom_cli ?>"/>
                </div>

                <div class="divinfo">
                <label>Contrato:</label>
                <input class="input" type="text" readonly value="<?php echo $con_cli ?>"/>
                </div>

                <div class="divinfo">
                <label>Documento:</label>
                <input class="input" type="text" readonly value="<?php echo $doc_cli ?>"/>
                </div>

                <?php
if (trim($tel_cli) != "") {
        ?>
                <div class="divinfo">
                <label>Telefono:</label>
                <input class="input" type="text" readonly value="<?php echo $tel_cli ?>"/>
                </div>
                <?php
}
    if (trim($email) != "") {
        ?>

                <div class="divinfo">
                <label>Email:</label>
                <input class="input" type="text" readonly value="<?php echo $email ?>"/>
                </div>

                <?php
}
    $s2   = new inmuebles();
    $data = $s2->SaldoFavor($codinmueble);
    while (oci_fetch($data)) {
        $saldofavor = oci_result($data, 'SALDO');
    }
    ?>
                <div class="divinfo" >
                    <label>A Favor:</label>
                    <input class="input" style="color:#0000FF"  type="text" readonly value="<?php echo ("RD" . money_format('%.2n', $saldofavor)) ?>"/>
                </div>


                 <?php

    $s2   = new inmuebles();
    $data = $s2->DiferidoDeud($codinmueble);
    while (oci_fetch($data)) {
        $difdeuda = oci_result($data, 'DIFERIDO');
    }
    ?>

                <div class="divinfo" >
                    <label>Dif.:</label>
                    <input class="input" style="color:#FF0000"  type="text" readonly value="<?php echo ("RD" . money_format('%.2n', $difdeuda)) ?>"/>
                </div>

                <div class="divinfo" >
                    <label>#Facturas:</label>
                    <input style="color: #ff0000" class="input" type="text" readonly value="<?php echo $facturas ?>"/>
                </div>

                <div class="divinfo">
                    <label>Deuda:</label>
                    <input style="color: #ff0000" class="input" type="text" readonly value="<?php echo ("RD" . money_format('%.2n', $deuda)) ?>"/>
                </div>

                <?php

    if (trim($estado_inm) == "SS" or trim($estado_inm) == "PC") {
        $s2   = new inmuebles();
        $data = $s2->tarifaRec($codinmueble);
        while (oci_fetch($data)) {
            $reconexion = oci_result($data, 'VALOR_TARIFA');
        }

        ?>
                <div class="divinfo">
                    <label>Reconexion:</label>
                    <input class="input" style="color: #ff0000"  type="text" readonly value="<?php echo ("RD" . money_format('%.2n', $reconexion)) ?>"/>
                </div>
                <?php
}

    ?>


                <div class="divinfo">
                    <input  class="legend_fact" type="button"  id="butActDat" size="15" value="Actualizar datos" />
                </div>
            </div>
        </fieldset>
        </div>
        <div class="tabber" id="tab" style="width:1300px;">
        <?php
if ($serial != "") {
        ?>
            <div class="tabbertab" title="Medidor">
                <?php
include 'vista.DatosMedidor.php';
        ?>
            </div>
            <div class="tabbertab" title="Lecturas">
                <?php
include 'vista.DatosLectura.php';
        ?>
            </div>
            <?php
}
    ?>
            <div class="tabbertab" title="Servicios">
            <?php
include 'vista.DatosServicios.php';
    ?>
            </div>
            <div class="tabbertab" title="Facturas">
            <?php

    include 'vista.facturas.php';
    ?>
            </div>

            <div class="tabbertab" title="Diferidos">
                <?php
include 'vista.diftotal.php';
    ?>
            </div>
            <div class="tabbertab" title="Pagos">
                <?php
include 'vista.Datos_Pagos.php';
    ?>
            </div>

            <div class="tabbertab" title="O. Recaudos">
                <?php
include 'vista.Datos_OtrosRec.php';
    ?>
            </div>

            <div class="tabbertab" title="Corte y Reconexion">
                <?php
include 'vista.Datos_CorteYRec.php';
    ?>
            </div>


            <div class="tabbertab" title="Observaciones">
                <?php
include 'vista.Datos_Obs.php';
    ?>
            </div>

            <div class="tabbertab" title="Deuda Cero">
                <?php
include 'vista.deudcertotal.php';
    ?>
            </div>
            <div class="tabbertab" title="Saldo A Favor">
                <?php
include 'vista.saldofavor.php';
    ?>
            </div>
            <div class="tabbertab" title="Reclamos">
                <?php
include 'vista.hist_reclamos.php';
    ?>
            </div>
                        <?php
$admitUser = array('80095626', '001-1375145-7', '223-0160790-3', '001-0530662-5', '402-0047660-0');

    if (in_array($_SESSION['codigo'], $admitUser)) {
        ?>

            <div class="tabbertab" title="Nueva Observacion">
                <?php
include 'vista.nueva_observacion.php';
        ?>
            </div>
               <?php
}
    ?>

        </div>
        </form>
    <?php
}
?>
<script type="text/javascript">
    inicioInm();
    console.log('<?=$_SESSION['codigo'];?>')
</script>

</body>
</html>