<?php
session_start();
require'../../clases/classPqrsAseo.php';
require'../../clases/class.reconexion.php';
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$cod_inmueble=$_GET['cod_inmueble'];
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
    <!--script type="text/javascript" src="../../js/facturaspendientes.js"></script-->
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>

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
            margin-top:-10px;
            margin-left:-1px;
            border-color: #666666;
            border:0px solid #ccc;
            display:block;
            width: 1121px;
            height: 265px;
            float:left;
        }
    </style>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>

</head>
<body style="margin-top:-25px" onload="document.getElementById('cod_inmueble').focus();">
<div id="content" style="padding:0px; width:1120px; margin-left:0px">
    <?php
    $c = new PQRs();
    $resultado = $c->seleccionaUser($coduser);
    while (oci_fetch($resultado)) {
        $id_caja = oci_result($resultado, 'ID_CAJA');
        $num_caja = oci_result($resultado, 'NUM_CAJA');
        $id_punto = oci_result($resultado, 'ID_PUNTO_PAGO');
        $des_punto = oci_result($resultado, 'DESCRIPCION');
        $cod_ent = oci_result($resultado, 'COD_ENTIDAD');
        $des_ent = oci_result($resultado, 'DESC_ENTIDAD');
        $nom_usu = oci_result($resultado, 'NOMBRE');
    }oci_free_statement($resultado);

    $c = new PQRs();
    $resultado = $c->obtenerDatosCliente($cod_inmueble);
    while (oci_fetch($resultado)) {
        $cod_inm = oci_result($resultado, 'CODIGO_INM');
        $dir_inm = oci_result($resultado, 'DIRECCION');
        $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
        $cod_cli = oci_result($resultado, 'CODIGO_CLI');
        $nom_cli = oci_result($resultado, 'ALIAS');
        $cod_pro = oci_result($resultado, 'ID_PROYECTO');
        $des_pro = oci_result($resultado, 'DESC_PROYECTO');
        $est_inm = oci_result($resultado, 'ID_ESTADO');
        $uso_inm = oci_result($resultado, 'ID_USO');
        $act_inm = oci_result($resultado, 'DESC_ACTIVIDAD');
        $cod_zon = oci_result($resultado, 'ID_ZONA');
        $uni_inm = oci_result($resultado, 'UNIDADES_HAB');
        $cmo_min = oci_result($resultado, 'CONSUMO_MINIMO');
        $ced_cli = oci_result($resultado, 'DOCUMENTO');
        $tel_cli = oci_result($resultado, 'TELEFONO');
        $mail_cli = oci_result($resultado, 'EMAIL');
        $ger_inm = oci_result($resultado, 'ID_GERENCIA');
        $cat_inm = oci_result($resultado, 'CATASTRO');
        $pro_inm = oci_result($resultado, 'ID_PROCESO');
        $ali_cli = oci_result($resultado, 'NOMBRE_CLI');
    }oci_free_statement($resultado);
    $direccion = $dir_inm.' '.$urb_inm;

    if($nom_cli == '') $nom_cli = $ali_cli;

    $c = new PQRs();
    $resultado = $c->totalfacven($cod_inmueble);
    while (oci_fetch($resultado)) {
        $deuda = oci_result($resultado, 'DEUDA') ;
        $facpend = oci_result($resultado, 'FACPEND') ;
    }oci_free_statement($resultado);

    $c = new PQRs();
    $resultado = $c->totalfacent($cod_inmueble);
    while (oci_fetch($resultado)) {
        $facent = oci_result($resultado, 'FACENT') ;
    }oci_free_statement($resultado);

    $c = new PQRs();
    $resultado = $c->reclamosAnteriores($cod_inmueble);
    while (oci_fetch($resultado)) {
        $cantrec = oci_result($resultado, 'CANTREC') ;
    }oci_free_statement($resultado);

    $dt = new DateTime();
    $dt->modify("-6 hour");
    $fecha = $dt->format('d/m/Y H:i:s');

    if (isset($_REQUEST["procesar"])){
        $cod_inmueble=$_POST['cod_inmueble'];
        $direccion=$_POST['direccion'];
        $cod_zon=$_POST['cod_zon'];
        $uso_inm=$_POST['uso_inm'];
        $est_inm=$_POST['est_inm'];
        $act_inm=$_POST['act_inm'];
        $uni_inm=$_POST['uni_inm'];
        $cmo_min=$_POST['cmo_min'];
        $cod_pro=$_POST['cod_pro'];
        $des_pro=$_POST['des_pro'];
        $nom_cli=$_POST['nom_cli'];
        $ced_cli=$_POST['ced_cli'];
        $tel_cli=$_POST['tel_cli'];
        $mail_cli=$_POST['mail_cli'];
        $medio=$_POST['medio'];
        $tipo=$_POST['tipo'];
        $motivo=$_POST['motivo'];
        $area_res=$_POST['area_res'];
        $fecha_res=$_POST['fecha_res'];
        $deuda=$_POST['tel_cli'];
        $facpend=$_POST['facpend'];
        $facent=$_POST['facent'];
        $fecha=$_POST['fecha'];
        $descripcion=$_POST['descripcion'];
        $ger_inm=$_POST['ger_inm'];
        $tel_cli_nuevo=$_POST['tel_cli_nuevo'];
        $mail_cli_nuevo=$_POST['mail_cli_nuevo'];

        $c = new PQRs();
        $bandera = $c->IngresaPqr($fecha,$cod_inmueble,$nom_cli,$ced_cli,$direccion,$tel_cli,$mail_cli,$medio,$tipo,$motivo,$fecha_res,$descripcion,$cod_ent,$id_punto,$num_caja,$coduser,$ger_inm,$area_res,$tel_cli_nuevo,$mail_cli_nuevo);
        if($bandera == false){
            $error=$c->getmsgresult();
            $coderror=$c->getcodresult();
            echo"
			<script type='text/javascript'>
				showDialog('Error Registrando PQR','C&oacute;digo de error: $coderror <br>Mensaje: $error','error');
			</script>";
        }
        else if($bandera == true){
            $error=$c->getmsgresult();
            echo "
			<script type='text/javascript'>
				showDialog('PQR Registrada','Se Registro la PQR para el Inmueble $cod_inmueble, con el codigo pqr $error','success');
			</script>
			";
            if($tipo<>5){
                echo "
			<script type='text/javascript'>
				window.open('../../facturacion/vistas/vista.documento_pqr_aseo.php?codigo_pqr=$error', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400');
			</script>";
            }
        }
    }


    ?>
    <h3 class="panel-heading" style=" background-color:#A77B25; color:#FFFFFF; font-size:18px; width:1120px" align="center">Recepci&oacute;n de PQRs</h3>
    <form name="pagos" action="vista.recepcion_pqr2.php" method="post">
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div align="left"><b>Datos Punto de Atenci&oacute;n</b></div>
            </div>
        </div>
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                    <table>
                        <tr>
                            <td width="150">
                                Fecha Inicio<br />
                                <input type="text" name="fecha" value="<?php echo $fecha;?>" size="15" readonly class="input" />
                            </td>
                            <td width="150">
                                Ayuntamiento<br />
                                <input type="text" id="cod_pro" name="cod_pro" value="<?php echo $cod_pro;?>" size="2" readonly class="input" />
                                <input type="text" name="des_pro" value="<?php echo $des_pro;?>" size="10" readonly class="input" />
                            </td>
                            <td width="220">
                                Entidad<br />
                                <input type="text" name="cod_ent" value="<?php echo $cod_ent;?>" size="2" readonly class="input" />
                                <input type="text" name="des_ent" value="<?php echo $des_ent;?>" size="20" readonly class="input" />
                            </td>
                            <td width="220">
                                Punto<br />
                                <input type="text" name="id_punto" value="<?php echo $id_punto;?>" size="2" readonly class="input" />
                                <input type="text" name="des_punto" value="<?php echo $des_punto;?>" size="20" readonly class="input" />
                            </td>
                            <td width="330">
                                Caja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atendido Por<br />
                                <input type="text" name="num_caja" value="<?php echo $num_caja;?>" size="2" class="input" readonly />
                                <input type="text" name="nom_cajero" value="<?php echo $nom_usu;?>" size="40" class="input" readonly />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div align="left"><b>Datos Inmueble</b></div>
            </div>
        </div>

        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">

                    <table>
                        <tr>
                            <td width="160">
                                C&oacute;digo Sistema<br />
                                <input class="input" type="text" value="<?php echo $cod_inmueble ?>" name="cod_inmueble" id="cod_inmueble" size="15" readonly/>
                                <!--a href="JAVASCRIPT:fotosperiodo(<?php echo $cod_inmueble?>);">
                                    <i class="fa fa-camera fa-lg" style="color:#000000"></i></a-->
                            </td>
                            <td width="285">
                                Direcci&oacute;n<br />
                                <input type="text" name="direccion" id="direccion" value="<?php echo $direccion;?>" size="40" readonly class="input"/>
                            </td>
                            <td width="70">
                                Zona<br />
                                <input type="text" name="cod_zon" id="cod_zon" value="<?php echo $cod_zon;?>" size="4" readonly class="input"/>
                            </td>
                            <td width="70">
                                Estado<br />
                                <input type="text" id="est_inm" name="est_inm" value="<?php echo $est_inm;?>" size="4" readonly class="input" />
                            </td>
                            <td width="70">
                                Uso<br />
                                <input type="text" id="uso_inm" name="uso_inm" value="<?php echo $uso_inm;?>" size="3" readonly class="input" />
                            </td>
                            <td width="201">
                                Actividad<br />
                                <input type="text" id="act_inm" name="act_inm" value="<?php echo $act_inm;?>" size="25" readonly class="input" />
                            </td>
                            <td width="77">
                                Unidades<br />
                                <input type="text" id="uni_inm" name="uni_inm" value="<?php echo $uni_inm;?>" size="4" readonly class="input" />
                            </td>
                            <!--td width="151">
                                Cmo Minimo <font color="#FF0000">(mts&sup3;)</font><br />
                                <input type="text" id="cmo_min" name="cmo_min" value="<?php echo $cmo_min;?>" size="4" readonly class="input" />
                            </td-->
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td width="168">
                                Gerencia <br />
                                <input type="text" name="ger_inm" id="ger_inm" value="<?php echo $ger_inm;?>" size="2"  class="input" />
                            </td>
                            <td width="168">
                                Total Deuda <font color="#FF0000">(RD$)</font><br />
                                <input type="text" name="deuda" id="deuda" value="<?php echo number_format($deuda,0,',','.');?>" size="10" maxlength="9" class="input" style="color:#FF0000"/>
                            </td>

                            <td width="172">
                                Catastro<br />
                                <input type="text" value="<?php echo $cat_inm;?>" size="20" maxlength="20" readonly class="input"/>
                            </td>

                            <td width="172">
                                Proceso<br />
                                <input type="text"  value="<?php echo $pro_inm;?>" size="20" maxlength="20" readonly class="input"/>
                            </td>

                        </tr>
                    </table>

                </div>
            </div>
        </div>
        <?php
        /*$c = new PQRs();
        $resultado = $c->obtenerDatosMedidor($cod_inmueble);
        while (oci_fetch($resultado)) {
            $marca_med = oci_result($resultado, 'DESC_MED');
            $empla_med= oci_result($resultado, 'DESC_EMPLAZAMIENTO');
            $diame_med = oci_result($resultado, 'DESC_CALIBRE');
            $seria_med = oci_result($resultado, 'SERIAL');
            $descr_med = oci_result($resultado, 'DESCRIPCION');
            $insta_med = oci_result($resultado, 'FEC_INSTAL');
        }oci_free_statement($resultado);
        if($marca_med != ''){*/
            ?>
            <!--div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div align="left"><b>Datos Medidor</b></div>
                </div>
            </div>
            <div-- class="flexigrid" style="width:100%; display:block" id="divdatosformapago">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table>
                            <tr class="person">
                                <td width="170">
                                    Marca<br />
                                    <input type="text" name="marca_med" id="marca_med" value="<?php echo $marca_med;?>" size="20" readonly class="input"/>
                                </td>
                                <td width="170">
                                    Emplazamiento<br />
                                    <input type="text" name="empla_med" id="empla_med" value="<?php echo $empla_med;?>" size="20" readonly class="input"/>
                                </td>
                                <td width="80">
                                    Calibre<br />
                                    <input type="text" id="diame_med" name="diame_med" value="<?php echo $diame_med;?>" size="5" readonly class="input" />
                                </td>
                                <td width="140">
                                    Serial<br />
                                    <input type="text" id="seria_med" name="seria_med" value="<?php echo $seria_med;?>" size="15" readonly class="input" />
                                </td>
                                <td width="170">
                                    Estado<br />
                                    <input type="text" id="descr_med" name="descr_med" value="<?php echo $descr_med;?>" size="20" readonly class="input" />
                                </td>
                                <td width="206">
                                    Fecha Instalaci&oacute;n<br />
                                    <input type="text" id="insta_med" name="insta_med" value="<?php echo $insta_med;?>" size="10" readonly class="input" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div-->
            <?php
        //}
        ?>
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div align="left"><b>Datos Cliente Interpone PQR</b></div>
            </div>
        </div>
        <div class="flexigrid" style="width:100%; display:block" id="divdatosformapago">
            <div class="mDiv">
                <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                    <table>
                        <tr class="person">
                            <td width="290">
                                Nombre Cliente<br />
                                <input type="text" name="nom_cli" id="nom_cli" value="<?php echo $nom_cli;?>" size="40" class="input"/>
                            </td>
                            <td width="160">
                                C&eacute;dula<br />
                                <input type="text" name="ced_cli" id="ced_cli" value="<?php echo $ced_cli;?>" size="18" class="input"/>
                            </td>
                            <td width="130">
                                Tel&eacute;fono<br />
                                <input type="text" name="tel_cli_nuevo" id="tel_cli_nuevo" value="<?php echo $tel_cli;?>" size="12" class="input"/>
                            </td>
                            <td width="130">
                                Celular<br />
                                <input type="text" id="cel_cli_nuevo" name="cel_cli_nuevo" value="<?php echo $cel_cli;?>" size="12" class="input" />
                            </td>
                            <td width="386">
                                Email<br />
                                <input type="email" id="mail_cli_nuevo" name="mail_cli_nuevo" value="<?php echo $mail_cli;?>" size="40" class="input" />
                            </td>
                            </td>
                            <td width="130">
                                <input type="hidden" name="tel_cli" id="tel_cli" value="<?php echo $tel_cli;?>" size="12" class="input"/>
                            </td>
                            <td width="130">
                                <input type="hidden" id="cel_cli" name="cel_cli" value="<?php echo $cel_cli;?>" size="12" class="input" />
                            </td>
                            <td width="386">
                                <input type="hidden" id="mail_cli" name="mail_cli" value="<?php echo $mail_cli;?>" size="40" class="input" />

                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="flexigrid" style="width:100%; display:block" id="divformapago">
            <div class="mDiv">
                <div align="left"><b>Datos PQR</b></div>
            </div>
        </div>
        <div class="flexigrid" style="width:100%; display:block" id="divdatosformapago">
            <div class="mDiv">
                <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                    <table>
                        <tr class="person">
                            <td width="139" >
                                Medio de Recepci&oacute;n<br />
                                <select name='medio' id="medio" class='input' required>
                                    <option value="" selected>Seleccione medio...</option>
                                    <?php
                                    $c = new PQRs();
                                    $resultado = $c->seleccionaMedioRecep();
                                    while (oci_fetch($resultado)) {
                                        $cod_medio = oci_result($resultado, 'ID_MEDIO_REC') ;
                                        $des_medio = oci_result($resultado, 'DESC_MEDIO_REC') ;
                                        if($cod_medio == $medio) echo "<option value='$cod_medio' selected>$des_medio</option>\n";
                                        else echo "<option value='$cod_medio'>$des_medio</option>\n";
                                    }oci_free_statement($resultado);
                                    ?>
                                </select>
                            </td>
                            <td width="155" colspan="1">
                                Tipo PQR<br />
                                <select name='tipo' id="tipo" class='input' required>
                                    <option value="" selected>Seleccione Tipo PQR...</option>
                                    <?php
                                    $c = new PQRs();
                                    $resultado = $c->seleccionaTipoPqr();
                                    while (oci_fetch($resultado)) {
                                        $cod_tipo = oci_result($resultado, 'ID_TIPO_RECLAMO') ;
                                        $des_tipo = oci_result($resultado, 'DESC_TIPO_RECLAMO') ;
                                        if($cod_tipo == $tipo) echo "<option value='$cod_tipo' selected>$des_tipo</option>\n";
                                        else echo "<option value='$cod_tipo'>$des_tipo</option>\n";
                                    }oci_free_statement($resultado);
                                    ?>
                                </select>
                            </td>
                            <td width="346" colspan="1">
                                Motivo PQR<br />
                                <select name='motivo' id="motivo" class='input' required>
                                    <option value="" selected>Seleccione Motivo PQR...</option>
                                </select>
                            </td>
                            <td width="174" colspan="1">
                                Departamento<br />
                                <select name='area_res' id="area_res" class='input' required>

                                </select>
                            </td>
                            <td width="163">
                                Fecha Max. Resoluci&oacute;n<br />
                                <select name='fecha_res' id="fecha_res" class='input'>

                                </select>
                            </td>
                            <td width="115" align="right" id="botonpago" style="display:block;" >
                                <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#A77B25; color:#A77B25; display:block" type="submit"
                                        name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-floppy-o"></i><b>&nbsp;Guardar PQR</b>
                                </button>
                            </td>
                        </tr>
                        <tr class="person">
                            <td colspan="5">
                                Descripci&oacute;n<br />
                                <textarea rows="5" cols="150" required name="descripcion" id="descripcion" maxlength="400"><?php echo $descripcion?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>

    <iframe id="ifdetfactpend" style="display:none; border-left:0px solid #ccc; border-right:0px solid #ccc; margin-top:-1px" class="iframe"></iframe><br /></div>
</body>
</html>
<script type="text/javascript">

    $("#tipo").change(function ()
    {
        $("#tipo option:selected").each(function ()
        {
            id_tipo = $(this).val();
            $.post("../datos/datos.agregamotivo.php?ger_inm=<?php echo $ger_inm?>", { id_tipo: id_tipo, valor:"motivo" }, function(data)
            {
                $("#motivo").html(data);
            });
        });
    })


    $("#motivo").change(function ()
    {
        $("#motivo option:selected").each(function ()
        {
            id_motivo = $(this).val();
            $.post("../datos/datos.agregafecha_res.php", { id_motivo: id_motivo, valor:"fecha_res" }, function(data)
            {
                $("#fecha_res").html(data);
            });

            $.post("../datos/datos.agregaarea_res.php?ger_inm=<?php echo $ger_inm;?>&cod_pro=<?php echo $cod_pro;?>", { id_motivo: id_motivo, valor:"area_res" }, function(data)
            {
                $("#area_res").html(data);
            });
        });
    })



    var popped = null;

    function popup(uri, awid, ahei, scrollbar) {
        var params;
        if (uri != "") {
            if (popped && !popped.closed) {
                popped.location.href = uri;
                popped.focus();
            }
            else {
                params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                popped = window.open(uri, "popup", params);
            }
        }
    }

    function hisfac(inm) { // Traer la fila seleccionada
        popup("vista.hist_fac.php?inmueble="+inm,500,310,'yes','pop3');
    }

    function observaciones(inm) { // Traer la fila seleccionada
        popup("vista.observa.php?inmueble="+inm,1000,500,'yes','pop3');
    }


    function totalizador(inm) { // Traer la fila seleccionada
        popup("vista.DatosTot.php?inmueble="+inm,357,250,'yes','pop3');
    }

    function hisfacent(inm) { // Traer la fila seleccionada
        popup("vista.hist_fac_ent.php?inmueble="+inm,700,310,'yes','pop3');
    }

    function hiscanrec(inm) { // Traer la fila seleccionada
        popup("vista.hist_reclamos.php?inmueble="+inm,1170,400,'yes','pop3');
    }

    function fotosperiodo(id2) { // Traer la fila seleccionada
        popup("vista.fotos_inm.php?cod_sistema="+id2,750,600,'yes','pop1');
    }
</script>