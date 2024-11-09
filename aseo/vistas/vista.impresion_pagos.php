<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require'../clases/classPagos.php';
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
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>

        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
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
                border-color: #666666;
                border:0px solid #ccc;
                display:block;
                width: 1120px;
                height: 260px;
                float:left;
            }
        </style>

    </head>
    <body style="margin-top:-25px" onload="document.getElementById('cod_inmueble').focus();">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <?php
        $c = new Pagos();
        $resultado = $c->obtenerDatosCliente($cod_inmueble);
        while (oci_fetch($resultado)) {
            $cod_inm = oci_result($resultado, 'CODIGO_INM');
            $dir_inm = oci_result($resultado, 'DIRECCION');
            $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
            $cod_cli = oci_result($resultado, 'CODIGO_CLI');
            $nom_cli = oci_result($resultado, 'ALIAS');
            $cod_pro = oci_result($resultado, 'ID_PROYECTO');
            $des_pro = oci_result($resultado, 'DESC_PROYECTO');
        }oci_free_statement($resultado);
        $direccion = $urb_inm.' '.$dir_inm;
        if(!isset($cod_inm) && $cod_inmueble != ''){
            //else{
            echo "<script>showDialog('Error Cargando Datos','El Inmueble N&deg; $cod_inmueble No Existe.<br><br>Por Favor Verifique.','error',3);</script>";
        }
        ?>
        <h3 class="panel-heading" style=" background-color:#A77B25; color:#FFFFFF; font-size:18px; width:100%" align="center">Impresi&oacute;n Recibos de Pagos</h3>
        <form name="imprimerecibos" action="vista.impresion_pagos.php" method="post">
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
                                    <input class="input" type="number" value="<?php echo $cod_inmueble ?>" size= "10" name="cod_inmueble" id="cod_inmueble"  onchange="recarga()" onfocus="habilitadivpagos()"/>
                                </td>
                                <td width="160">
                                    Ayuntamiento<br />
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
                                    <input type="text" name="nom_cli" value="<?php echo $nom_cli;?>" size="30" readonly class="input"/>
                                </td>
                            </tr>
                        </table>

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

                    url: './../datos/datos.RecibosPagos.php?cod_inmueble=<?php echo $cod_inmueble;?>',

                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width:40,  align: 'center'},
                        {display: 'Id Pago', name: 'id_pago', width:70,  sortable: true, align: 'center'},
                        {display: 'Fecha<br/>Pago', name: 'FECHA_PAGO', width: 120, sortable: true, align: 'center'},
                        {display: 'Valor<br/>Pagado', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                        {display: 'Cajero', name: 'ID_USUARIO', width: 180, sortable: true, align: 'center'},
                        {display: 'Periodo', name: 'PERIODO', width: 80, sortable: true, align: 'center'},
                        {display: 'Tipo', name: 'TIPO', width: 80, sortable: true, align: 'center'}
                        //{display: 'Enviar<br/>Comprobante', name: 'enviar', width: 120, sortable: true, align: 'center'}

                    ],

                    sortname: "FECHA_REGISTRO",
                    sortorder: "DESC",
                    usepager: false,
                    title: 'Listado Pagos Realizados Inmueble <?php echo $cod_inmueble?>',
                    useRp: false,
                    rp: 1000,
                    page: 1,
                    showTableToggleBtn: false,
                    width: 1120,
                    height: 80
                }
            );
        </script>
        <iframe id="ifreciboimprime" style="display:none;" class="iframe"></iframe>
    </body>
    </html>
    <script type="text/javascript">

        function recarga(){
            document.imprimerecibos.submit();
        }

        function habilitadivpagos(){
            if(document.getElementById('cod_inmueble').value == '')	{
                document.getElementById('divpagos').style.display = 'none';
            }
            else{
                document.getElementById('divpagos').style.display = 'block';
                //document.getElementById("ifdetpagos").contentDocument.location.reload(true);
                //document.getElementById('ifdetpagos').src = 'vista.det_pagos.php?inmueble='+$('#cod_inmueble').attr('value');
            }
        }
        function EnvioEmail(cod_inmueble, id_pago) {
            window.open("../clases/class.enviopagos.php?inmueble="+cod_inmueble+"&idpago="+id_pago, "Envio Comprobante", "width=700,height=450,scrollbars=NO" );
        }

    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

