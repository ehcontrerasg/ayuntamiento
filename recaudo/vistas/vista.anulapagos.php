<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
/*    error_reporting(E_ALL);
    ini_set('display_errors', '1');*/
    session_start();
    require'../clases/class.AnulaPagos.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_POST['cod_inmueble'];
    $direccion=$_POST['direccion'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?1" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?1"></script>

        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css?1" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?1">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?1"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?1"></script>
        <link href="../../font-awesome/css/font-awesome.min.css?1" rel="stylesheet" />
        <style type="text/css">
            .font{
                font-style:normal;
                font:bold;
            }
            .table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            .th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
                text-align:center;
            }
            .tda{

                height:24px;
                border:1px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .iframe{
                /*margin-top:-10px;*/
                margin-left:-1px;
                border-color: #666666;
                border:0px solid #ccc;
                display:none;
                width: 1121px;
                height: 230px;
                float:left;
            }
        </style>
    </head>
    <body style="margin-top:-25px" onload="document.getElementById('cod_inmueble').focus();">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <?php
        $c = new AnulaPagos();
        $resultado = $c->obtenerDatosCliente($cod_inmueble);
        while (oci_fetch($resultado)) {
            $cod_inm = oci_result($resultado, 'CODIGO_INM');
            $dir_inm = oci_result($resultado, 'DIRECCION');
            $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
            $cod_cli = oci_result($resultado, 'CODIGO_CLI');
            $nom_cli = oci_result($resultado, 'ALIAS');
            $cod_pro = oci_result($resultado, 'ID_PROYECTO');
            $des_pro = oci_result($resultado, 'DESC_PROYECTO');
            $cliente = oci_result($resultado, 'NOMBRE_CLI');
        }oci_free_statement($resultado);
        $direccion = $urb_inm.' '.$dir_inm;
        if($nom_cli == '') $nom_cli = $cliente;
        if(!isset($cod_inm) && $cod_inmueble != ''){
            ?>
            <script>
                swal("Inmueble Inexistente!", "El Inmueble <?php echo $cod_inmueble?>  No Existe.\nPor Favor Verifique", "error");
            </script>
            <?php
        }

        if (isset($_REQUEST["procesar"])){
            $cod_pago = $_POST['cod_pago'];
            $observacion=$_POST['observacion'];
            $observacion = strtoupper($observacion);
            $d = new AnulaPagos();
            $bandera = $d->ReversaPagos($cod_pago, $observacion, $coduser);
            if($bandera == false){
                $error=$d->getmsgresult();
                $coderror=$d->getcodresult();
                ?>
                <script>
                    swal({
                        title: "Error",
                        text: "No fue posible anular el pago N&deg; <?php echo $cod_pago.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK!",
                        cancelButtonText: "No!",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        html: true
                    });
                </script>
            <?php
            }
            else if($bandera == true){
            ?>
                <script>
                    swal("Pago Anulado!", "Se anul\u00f3 el pago No. <?php echo $cod_pago?>", "success");
                </script>
                <?php
            }
        }
        ?>
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Anulaci&oacute;n de Pagos</h3>
        <form name="anulapagos" action="vista.anulapagos.php" method="post">
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div align="left"><b>Datos Inmueble</b></div>
                </div>
            </div>
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table width="100%">
                            <tr>
                                <td width="180">
                                    C&oacute;digo Sistema<br />
                                    <input class="input" type="text" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" id="cod_inmueble" placeholder="Ingrese el Inmueble" onchange="recarga()" onfocus="habilitadivpagos()"/>
                                </td>
                                <td width="160">
                                    Acueducto<br />
                                    <input type="text" name="cod_pro" value="<?php echo $cod_pro;?>" size="2" readonly class="input" />
                                    <input type="text" name="des_pro" value="<?php echo $des_pro;?>" size="10" readonly class="input" />
                                </td>
                                <td width="290">
                                    Direcci&oacute;n<br />
                                    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion;?>" size="40" readonly class="input"/>
                                </td>
                                <td width="300">
                                    Cliente<br />
                                    <input type="text" name="cod_cli" value="<?php echo $cod_cli;?>" size="10" readonly class="input" />
                                    <input type="text" name="nom_cli" value="<?php echo $nom_cli;?>" size="50" readonly class="input"/>
                                </td>
                            </tr>
                        </table >

                    </div>
                </div>

                <div style="display:none; float: left" id="divpagos">
                    <table id="flex1" style="display:block;float: left">
                    </table>
                </div>
            </div>
        </form>
        <script type="text/javascript">
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {

                    url: './../datos/datos.PagosInmueble.php?inmueble=<?php echo $cod_inmueble;?>',

                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width:70,  align: 'center'},
                        {display: 'Id Pago', name: 'id_pago', width:70,  sortable: true, align: 'center'},
                        {display: 'Fecha<br/>Pago', name: 'FECHA_PAGO', width: 120, sortable: true, align: 'center'},
                        {display: 'Valor<br/>Pagado', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                        {display: 'Cajero', name: 'ID_USUARIO', width: 180, sortable: true, align: 'center'},
                        {display: 'Entidad Pago', name: 'ID_USUARIO', width: 200, sortable: true, align: 'center'},
                        {display: 'Punto Pago', name: 'ID_USUARIO', width: 180, sortable: true, align: 'center'},
                        {display: 'Caja', name: 'ID_USUARIO', width: 80, sortable: true, align: 'center'},
                        {display: 'M&eacute;todo<br/>Pago', name: 'mediopago', width: 150, sortable: true, align: 'center'},
                        {display: 'Estado', name: 'estado', width: 150, sortable: true, align: 'center'},
                        {display: 'Motivo<br/>Aulaci&oacute;n', name: 'motivo_rev', width: 150, sortable: true, align: 'center'},
                        {display: 'Usuario<br/>Anulaci&oacute;n', name: 'usr_rev', width: 150, sortable: true, align: 'center'},
                        {display: 'Fecha<br/>Anulaci&oacute;n', name: 'fecha_rev', width: 150, sortable: true, align: 'center'}
                    ],

                    sortname: "ID_PAGO",
                    sortorder: "DESC",
                    usepager: false,
                    title: 'Listado Pagos Realizados Inmueble <?php echo $cod_inmueble?>',
                    useRp: false,
                    rp: 1000,
                    page: 1,
                    showTableToggleBtn: false,
                    width: 1120,
                    height: 100
                }
            );
        </script>
        <iframe id="ifdetfacpagos" style="border-left:0px solid #ccc; border-right:0px solid #ccc;" class="iframe"></iframe><br />
    </body>
    </html>
    <script type="text/javascript">

        function recarga(){
            document.anulapagos.submit();
        }

        function habilitadivpagos(){
            if(document.getElementById('cod_inmueble').value == '')	{
                document.getElementById('divpagos').style.display = 'none';
            }
            else{
                document.getElementById('divpagos').style.display = 'table-cell';
                //document.getElementById("ifdetpagos").contentDocument.location.reload(true);
                //document.getElementById('ifdetpagos').src = 'vista.det_pagos.php?inmueble='+$('#cod_inmueble').attr('value');
            }
        }

    </script>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

