<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require'../clases/class.IngresaPagos.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_GET['cod_inmueble'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <style type="text/css">
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
        </style>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>

    </head>
    <body style="margin-top:-25px" onload="document.getElementById('cod_inmueble').focus();">
    <div style="padding:0px; width:1120px; margin-left:0px">
        <?php
        if($cod_inmueble != ''){
            $c = new IngresaPagos();
            $resultado = $c->datosDeudaInmueble($cod_inmueble);
            while (oci_fetch($resultado)) {
                $cod_inm = oci_result($resultado, 'CODIGO_INM');
                $est_inm = oci_result($resultado, 'ID_ESTADO');
                $deuda = oci_result($resultado, 'DEUDA');
            }oci_free_statement($resultado);
            if(isset($cod_inm)){
            if($est_inm == 'CC' || $est_inm == 'CB' || $est_inm == 'CT'){
                ?>
                <script>
                    swal({
                            title: "Inmueble Inactivo!",
                            text: "El Inmueble <?php echo $cod_inmueble?>  Se Encuentra Inactivo.\nPor Favor Contacte a Catastro!!!",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            html: true
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                window.location='vista.inc_fianzas.php';
                            }
                        });

                </script>
            <?php
            }
            else if($est_inm == 'CK'){
            ?>
                <script>
                    swal({
                            title: "Cuenta Control",
                            text: "El Inmueble <?php echo $cod_inmueble?>  Se Encuentra en Estado Cuenta Control.\nPor Favor Contacte a Catastro Para su Activaci&oacute;n!!!",
                            type: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            html: true
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                window.location='vista.inc_fianzas.php';
                            }
                        });
                </script>
            <?php
            }
            else if($est_inm <> 'CC' || $est_inm <> 'CB' || $est_inm == 'CT' || $est_inm <> 'CK'){
            ?>
                <script>
                    window.location='vista.otrosrecaudos2.php?cod_inmueble=<?php echo $cod_inm?>';
                </script>
            <?php
            }
            else{}
            }
            else{
            ?>
                <script>
                    swal("Inmueble Inexistente!", "El Inmueble <?php echo $cod_inmueble?>  No Existe.\nPor Favor Verifique", "error");
                </script>
                <?php
            }
        }


        ?>

        <h3 class="panel-heading" style=" background-color:#FF8000;color:#FFFFFF; font-size:18px; width:1120px" align="center">Ingreso de Incorporaci&oacute;n, Fianzas y Otros Recaudos</h3>
        <form name="tipopagos" action="vista.inc_fianzas.php" method="get">
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table width="1231">
                            <tr>
                                <td width="207">
                                    C&oacute;digo Sistema<br />
                                    <input class="input" type="text" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" id="cod_inmueble" placeholder="Ingrese el Inmueble"   onchange="recarga();" maxlength="7"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </body>
    </html>
    <script type="text/javascript">
        function recarga(){
            //document.tipopagos.submit();
        }
    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
