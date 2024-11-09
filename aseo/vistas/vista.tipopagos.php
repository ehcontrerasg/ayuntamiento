<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
/*    error_reporting(E_ALL);
    ini_set('display_errors', '1');*/
    session_start();
    require'../../clases/class.IngresaPagos.php';
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
                font-size:14px;
                font-weight:normal;
                height:20px;
                width:150px;
            }
        </style>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>

    </head>
    <body style="margin-top:-25px">
    <div style="padding:0px; width:1120px; margin-left:0px">
        <?php
        if($cod_inmueble != ''){
            $c = new IngresaPagos();
            $resultado = $c->datosDeudaInmueble($cod_inmueble);
            $cod_inm = "";
            while (oci_fetch($resultado)) {
                $cod_inm = oci_result($resultado, 'CODIGO_INM');
                $est_inm = oci_result($resultado, 'ID_ESTADO');
                $deuda = oci_result($resultado, 'DEUDA');
                $excluido = oci_result($resultado, 'EXCLUIDO');
                $credito = oci_result($resultado, 'ESTADO_CREDITO');
            }oci_free_statement($resultado);


            if(isset($cod_inm)){
                if($credito == '2'){
                ?>
                <script>
                    swal({
                            title: "Gesti\u00f3n Legal",
                            text: "El Inmueble <?php echo $cod_inmueble?> se encuentra en proceso de Gesti\u00f3n Legal.\nNo es posible registrar el pago!!!",
                            type: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "OK!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                window.location='vista.tipopagos.php';
                            }
                        });
                </script>
            <?php

                }else{

            ?>
                <script>
                    window.location='vista.pagos.php?cod_inmueble=<?php echo $cod_inm?>';
                </script>
            <?php
                }

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

        <h3 class="panel-heading" style=" background-color:#a77b25; color:#FFFFFF; font-size:18px; width:1120px" align="center">Ingreso de Pagos y Otros Recaudos</h3>
        <form name="tipopagos" action="vista.tipopagos.php" method="get">
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table width="1231">
                            <tr>
                                <td width="207">
                                    C&oacute;digo Sistema<br />
                                    <input class="input" type="number" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" id="cod_inmueble" placeholder="Ingrese el Inmueble"   onchange="recarga();" maxlength="7"/>
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
            document.tipopagos.submit();
        }
    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

