<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
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
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>

        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>

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
            $cod_rec = $_POST['cod_rec'];
            $observacion=$_POST['observacion'];
            $observacion = strtoupper($observacion);
            $d = new AnulaPagos();
            $bandera = $d->ReversaRecaudo($cod_rec, $observacion, $coduser);
            if($bandera == false){
                $error=$d->getmsgresult();
                $coderror=$d->getcodresult();
                ?>
                <script>
                    swal({
                        title: "Error",
                        text: "No fue posible anular el recaudo N&deg; <?php echo $cod_rec.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                    swal("Recaudo Anulado!", "Se anul\u00f3 el recaudo No. <?php echo $cod_rec?>", "success");
                </script>
                <?php
            }

        }
        ?>
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Anulaci&oacute;n de Otros Recaudos</h3>
        <form name="anularecaudos" action="vista.anularecaudos.php" method="post">
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
                        </table>

                    </div>
                </div>

                <div style="display:none; float: left" id="divpagos">
                    <table id="flex1" style="display:block;float: left">
                    </table>
                    <!--table  width="100%">
					<tr>
						<td><b>Observaci&oacute;n</b><br />
						<textarea id="observacion" cols="155" class="input" required><?php //echo $observacion?></textarea></td>
						<td align="right" style="display:block;" id="botonpago" >
							<button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#FF8000; color:#FF8000; display:block; margin-top:17px; margin-left:10px" type="submit"
								name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Anular Recaudo</b>
							</button>
						</td>
					</tr>
				</table-->
                </div></div>
        </form>
        <script type="text/javascript">
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {

                    url: './../datos/datos.RecaudosInmueble.php?inmueble=<?php echo $cod_inmueble;?>',

                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width:50,  align: 'center'},
                        {display: 'Id Recaudo', name: 'codigo', width:70,  sortable: true, align: 'center'},
                        {display: 'Fecha<br/>Pago', name: 'FECHA', width: 80, sortable: true, align: 'center'},
                        {display: 'Valor<br/>Pagado', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                        {display: 'Cajero', name: 'ID_USUARIO', width: 180, sortable: true, align: 'center'},
                        {display: 'Concepto', name: 'CONCEPTO', width: 80, sortable: true, align: 'center'},
                        {display: 'Descripci&oacute;n', name: 'DESC_SERVICIO', width: 130, sortable: true, align: 'center'},
                        {display: 'Estado', name: 'estado', width: 150, sortable: true, align: 'center'},
                        {display: 'Motivo<br/>Aulaci&oacute;n', name: 'motivo_rev', width: 150, sortable: true, align: 'center'},
                        {display: 'Usuario<br/>Anulaci&oacute;n', name: 'usr_rev', width: 150, sortable: true, align: 'center'},
                        {display: 'Fecha<br/>Anulaci&oacute;n', name: 'fecha_rev', width: 150, sortable: true, align: 'center'}
                    ],

                    sortname: "CODIGO",
                    sortorder: "DESC",
                    usepager: false,
                    title: 'Listado Recaudos Realizados Inmueble <?php echo $cod_inmueble?>',
                    useRp: false,
                    rp: 1000,
                    page: 1,
                    showTableToggleBtn: false,
                    width: 1120,
                    height: 195
                }
            );
        </script>
        <iframe id="ifdetfacrecaudos" style="border-left:0px solid #ccc; border-right:0px solid #ccc;" class="iframe"></iframe><br />
        <!--iframe id="ifdetfacrecaudos" style="border-left:0px solid #ccc; border-right:0px solid #ccc; margin-left:559px; margin-top:-320px" class="iframe"></iframe><br /-->
    </body>
    </html>
    <script type="text/javascript">

        function recarga(){
            document.anularecaudos.submit();
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

