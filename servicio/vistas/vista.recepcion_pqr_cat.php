<?
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include_once ('../clases/classPqrs.php');
    include_once ('../../destruye_sesion.php');

//pasamos variables por post
    $coduser = $_SESSION['codigo'];
//$cod_inmueble=$_GET['cod_inmueble'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css?0" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css?0" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?0">
        <link href="../../alertas/dialog_box.css?0" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?0"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?0"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js?0"></script>
        <!--script type="text/javascript" src="../../js/facturaspendientes.js?0"></script-->
        <link href="../../font-awesome/css/font-awesome.min.css?0" rel="stylesheet" />
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

        $dt = new DateTime();
        $dt->modify("-6 hour");
        $fecha = $dt->format('d/m/Y H:i:s');
        /*$dt = new DateTime();
        $dt->modify("+15 days");
        $fecha_res = $dt->format('d/m/Y');*/

        if (isset($_REQUEST["procesar"])){
            //$cod_inmueble=$_POST['cod_inmueble'];
            $direccion=$_POST['direccion'];
            //$cod_zon=$_POST['cod_zon'];
            //$uso_inm=$_POST['uso_inm'];
            //$est_inm=$_POST['est_inm'];
            //$act_inm=$_POST['act_inm'];
            //$uni_inm=$_POST['uni_inm'];
            //$cmo_min=$_POST['cmo_min'];
            //$cod_pro=$_POST['cod_pro'];
            //$des_pro=$_POST['des_pro'];
            $nom_cli=$_POST['nom_cli'];
            $ced_cli=$_POST['ced_cli'];
            $tel_cli=$_POST['tel_cli'];
            $cel_cli=$_POST['cel_cli'];
            $mail_cli=$_POST['mail_cli'];
            $medio=$_POST['medio'];
            $tipo=$_POST['tipo'];
            $motivo=$_POST['motivo'];
            $area_res=$_POST['area_res'];
            $fecha_res=$_POST['fecha_res'];
            //$deuda=$_POST['tel_cli'];
            //$facpend=$_POST['facpend'];
            //$facent=$_POST['facent'];
            $fecha=$_POST['fecha'];
            $descripcion=$_POST['descripcion'];
            //$ger_inm=$_POST['ger_inm'];
            $c = new PQRs();
            $bandera = $c->IngresaPqrCat($fecha,$nom_cli,$ced_cli,$direccion,$tel_cli,$cel_cli,$mail_cli,$medio,$tipo,$motivo,$fecha_res,$descripcion,$cod_ent,$id_punto,$num_caja,$coduser,$area_res);
            if($bandera == false){
                $error=$c->getmsgresult();
                $coderror=$c->getcodresult();
                echo"
			<script type='text/javascript'>
				showDialog('Error Registrando Solicitud','C&oacute;digo de error: $coderror <br>Mensaje: $error','error');
			</script>";
            }
            else if($bandera == true){
                $error=$c->getmsgresult();
                echo "
			<script type='text/javascript'>
				showDialog('Solicitud Registrada','Se Registro la Solicitud Catastral','success');
			</script>";
                /* if($tipo<>5){
                     echo "
                 <script type='text/javascript'>
                     window.open('vista.documento_pqr.php?codigo_pqr=$error', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400');
                 </script>";
                 }*/
            }
        }

        $p=new PQRs();
        $stid = $p->obtenerfechares(64);
        while (oci_fetch($stid)) {
            $fecha_res= oci_result($stid, 'FEC_RESOL');
        }oci_free_statement($stid);

        ?>
        <h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px" align="center">Recepci&oacute;n Solicitudes Ubicaci&oacute;n Catastral</h3>
        <form name="pagos" action="vista.recepcion_pqr_cat.php" method="post">
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
                                    Fecha Registro<br />
                                    <input type="text" name="fecha" value="<?php echo $fecha;?>" size="15" readonly class="input" />
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
                                    <input type="text" name="nom_cli" id="nom_cli" value="<?php echo $nom_cli;?>" size="40" class="input" required/>
                                </td>
                                <td width="160">
                                    C&eacute;dula<br />
                                    <input type="text" name="ced_cli" id="ced_cli" value="<?php echo $ced_cli;?>" size="18" class="input"/>
                                </td>
                                <td width="130">
                                    Tel&eacute;fono<br />
                                    <input type="text" name="tel_cli" id="tel_cli" value="<?php echo $tel_cli;?>" size="12" class="input"/>
                                </td>
                                <td width="130">
                                    Celular<br />
                                    <input type="text" id="cel_cli" name="cel_cli" value="<?php echo $cel_cli;?>" size="12" class="input" />
                                </td>
                                <td width="386">
                                    Email<br />
                                    <input type="email" id="mail_cli" name="mail_cli" value="<?php echo $mail_cli;?>" size="40" class="input" />
                                </td>
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
                                <td width="105" colspan="1">
                                    Tipo PQR<br />
                                    <select name='tipo' id="tipo" class='input' required>
                                        <?php
                                        $c = new PQRs();
                                        $resultado = $c->seleccionaTipoPqrCat();
                                        while (oci_fetch($resultado)) {
                                            $cod_tipo = oci_result($resultado, 'ID_TIPO_RECLAMO') ;
                                            $des_tipo = oci_result($resultado, 'DESC_TIPO_RECLAMO') ;
                                            if($cod_tipo == $tipo) echo "<option value='$cod_tipo' selected>$des_tipo</option>\n";
                                            else echo "<option value='$cod_tipo'>$des_tipo</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="220" colspan="1">
                                    Motivo PQR<br />
                                    <select name='motivo' id="motivo" class='input' required>
                                        <?php
                                        $c = new PQRs();
                                        $resultado = $c->seleccionaMotivoPqrCat();
                                        while (oci_fetch($resultado)) {
                                            $cod_motivo = oci_result($resultado, 'ID_MOTIVO_REC') ;
                                            $des_motivo = oci_result($resultado, 'DESC_MOTIVO_REC') ;
                                            if($cod_motivo == $motivo) echo "<option value='$cod_motivo' selected>$des_motivo</option>\n";
                                            else echo "<option value='$cod_motivo'>$des_motivo</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="110" colspan="1">
                                    Departamento<br />
                                    <select name='area_res' id="area_res" class='input' required>
                                        <?php
                                        $c = new PQRs();
                                        $resultado = $c->seleccionaDepartamentoConsultaCat();
                                        while (oci_fetch($resultado)) {
                                            $cod_area = oci_result($resultado, 'ID_AREA') ;
                                            $des_area = oci_result($resultado, 'DESC_AREA') ;
                                            if($cod_area == $area_res) echo "<option value='$cod_area' selected>$des_area</option>\n";
                                            else echo "<option value='$cod_area'>$des_area</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="163">
                                    Fecha Max. Resoluci&oacute;n<br />
                                    <input type="text" name="fecha_res" id="fecha_res" class="input" value="<?php echo $fecha_res;?>" size="15"/>
                                </td>
                                <td width="115" align="right" id="botonpago" style="display:block;" >
                                    <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit"
                                            name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-floppy-o"></i><b>&nbsp;Guardar Solicitud</b>
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

        /*$("#tipo").change(function ()
        {
            $("#tipo option:selected").each(function ()
               {
                   id_tipo = $(this).val();
                $.post("../datos/datos.agregamotivo.php?ger_inm=E", { id_tipo: id_tipo, valor:"motivo" }, function(data)
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

            });
        })*/
    </script>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

