<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require'../clases/classPqrs.php';
    include('../../destruye_sesion_cierra.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_pqr=$_GET['codigo_pqr'];
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
        <script type="text/javascript" src="../../js/facturaspendientes.js"></script>
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
        $resultado = $c->obtieneDatosPqr ($cod_pqr);
        while (oci_fetch($resultado)) {
            $cod_inm = oci_result($resultado, 'COD_INMUEBLE');
            $nom_rec = oci_result($resultado, 'NOM_CLIENTE');
            $dir_rec = oci_result($resultado, 'DIR_CLIENTE');
            $tel_rec = oci_result($resultado, 'TEL_CLIENTE');
            $descripcion = oci_result($resultado, 'DESCRIPCION');
            $area_act = oci_result($resultado, 'AREA_ACTUAL');
            $orden = oci_result($resultado, 'ORDEN');
            $gerencia = oci_result($resultado, 'GERENCIA');
        }oci_free_statement($resultado);

        $dt = new DateTime();
        $dt->modify("-6 hour");
        $fec_cierre = $dt->format('d/m/Y');

        $l=new PQRs();
        $registros=$l->obtieneAreaUsuario ($coduser);
        while (oci_fetch($registros)) {
            $area_user = oci_result($registros, 'ID_AREA') ;
        }oci_free_statement($registros);

        if (isset($_REQUEST["cerrar"])){
            $cod_inm=$_POST['cod_inm'];
            $cod_pqr=$_POST['cod_pqr'];
            $observa=$_POST['observa'];
            $area_act=$_POST['area_act'];
            $orden=$_POST['orden'];
            //$area_res=$_POST['area_res'];
            $tipo_res=$_POST['tipo_res'];
            $diagnostico=$_POST['diagnostico'];
            $c = new PQRs();
            $bandera = $c->CierraPqr($cod_inm,$cod_pqr,$observa,$area_act,$orden,$tipo_res,$diagnostico,$coduser);
            if($bandera == false){
                $error=$c->getmsgresult();
                $coderror=$c->getcodresult();
                ?>
                <script type='text/javascript'>
                    swal({
                        title: "Error Registrando Cierre de PQR",
                        text: "C&oacute;digo de error: <?php echo $coderror?> <br>Mensaje: <?php echo $error?>",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#66CCFF",
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
                <script type='text/javascript'>
                    swal({
                            title: "Cierre de PQR Registrado",
                            text: "Se Registro el cierre de la PQR N&deg; <?php echo $cod_pqr?>",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#66CC00",
                            confirmButtonText: "OK!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            html: true
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                window.opener.location.reload(true);
                                window.close(this);
                            }
                        });

                </script>
                <?php
            }
        }


        ?>
        <h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:760px" align="center">Cierre de PQRs</h3>
        <form name="pagos" action="vista.close_pqr.php" method="post">
            <div class="flexigrid" style="width:760px">
                <div class="mDiv">
                    <div align="left"><b>Datos PQR</b></div>
                </div>
            </div>

            <div class="flexigrid" style="width:760px">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table>
                            <tr>
                                <td width="160">
                                    C&oacute;digo PQR<br />
                                    <input class="input" type="text" value="<?php echo $cod_pqr?>" name="cod_pqr" id="cod_pqr" size="15" readonly/>
                                </td>
                                <td width="160">
                                    C&oacute;digo Sistema<br />
                                    <input class="input" type="text" value="<?php echo $cod_inm?>" name="cod_inm" id="cod_inm" size="15" readonly/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flexigrid" style="width:760px">
                <div class="mDiv">
                    <div align="left"><b>Informaci&oacute;n de la Persona Reclamante</b></div>
                </div>
            </div>
            <div class="flexigrid" style="width:760px">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table>
                            <tr>
                                <td width="248">
                                    Nombre <br />
                                    <input type="text" name="nom_rec" id="nom_rec" value="<?php echo $nom_rec;?>" size="35" readonly class="input" />
                                </td>
                                <td width="248">
                                    Tel&eacute;fono <br />
                                    <input type="text" name="tel_rec" id="tel_rec" value="<?php echo $tel_rec;?>" size="12" readonly class="input" />
                                </td>
                                <td width="248">
                                    Direcci&oacute;n<br />
                                    <input type="text" name="dir_rec" id="dir_rec" value="<?php echo $dir_rec;?>" size="35" readonly class="input" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    Descripci&oacute;n PQR<br />
                                    <textarea rows="4" cols="120" readonly name="descripcion" id="descripcion"><?php echo $descripcion?></textarea>
                                </td>
                            </tr>
                            <?php
                            $cont = 0;
                            $c = new PQRs();
                            $resultado = $c->obtieneResolucionesPqrs ($cod_pqr);
                            while (oci_fetch($resultado)) {
                                $area_com = oci_result($resultado, 'DESC_AREA') ;
                                $desc_com = oci_result($resultado, 'RESPUESTA') ;
                                $cont++;
                                if($desc_com <> ''){
                                    ?>
                                    <tr>
                                        <td colspan="3">
                                            <?php
                                            if($cont == 1){
                                                ?>
                                                Resoluciones y/o Comentarios Anteriores<br />
                                                <?php
                                            }
                                            ?>
                                            <input type="text" size="20" value="<?php echo $area_com?>" readonly />&nbsp;&nbsp;
                                            <input type="text" size="93" value="<?php echo $desc_com?>" readonly/>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }oci_free_statement($resultado);
                            ?>
                            <tr>
                                <td colspan="3">
                                    Observaci&oacute;n Cierre PQR<br />
                                    <textarea rows="4" cols="120" name="observa" id="observa"><?php echo $observa?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td width="380">
                                    Fecha Cierre<br />
                                    <input type="text" name="fec_cierre" id="fec_cierre" value="<?php echo $fec_cierre;?>" size="15"  class="input" />
                                </td>
                                <td width="380">
                                    Usuario Resoluci&oacute;n<br />
                                    <input type="text" name="coduser" id="coduser" value="<?php echo $coduser;?>" size="15"  class="input" />
                                    <input type="hidden" name="area_act" id="area_act" value="<?php echo $area_act?>" />
                                    <input type="hidden" name="orden" id="orden" value="<?php echo $orden?>" />
                                </td>
                            </tr>
                        </table>
                        <?php
                        $c = new PQRs();
                        $resultado = $c->obtieneMotivoResolucionPqrsCodigo($cod_pqr,$gerencia);
                        while (oci_fetch($resultado)) {
                            $motpqr = oci_result($resultado, 'ID_MOTIVO_REC');
                            $desmotpqr = oci_result($resultado, 'DESC_MOTIVO_REC');
                        }oci_free_statement($resultado);

                        ?>
                        <table>
                            <tr>
                                <td width="380" >
                                    Tipo Resoluci&oacute;n<br />
                                    <select name='tipo_res' id="tipo_res" class='input' required>
                                        <option value="<?php echo $motpqr?>" selected><?php echo $motpqr.' - '.$desmotpqr?></option>
                                        <?php
                                        $c = new PQRs();
                                        $resultado = $c->obtieneMotivoResolucionPqrs($gerencia);
                                        while (oci_fetch($resultado)) {
                                            $cod_resol = oci_result($resultado, 'ID_MOTIVO_REC') ;
                                            $des_resol = oci_result($resultado, 'DESC_MOTIVO_REC') ;
                                            if($cod_resol == $tipo_res) echo "<option value='$cod_resol' selected>$cod_resol - $des_resol</option>\n";
                                            else echo "<option value='$cod_resol'>$cod_resol - $des_resol</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="380">
                                    Diagnostico<br />
                                    <select name='diagnostico' id="diagnostico" class='input' required>
                                        <option value="" selected>Seleccione Diagnostico...</option>
                                        <?php
                                        $c = new PQRs();
                                        $resultado = $c->obtieneDiagnosticoPqr();
                                        while (oci_fetch($resultado)) {
                                            $cod_diag = oci_result($resultado, 'ID_DIAGNOSTICO') ;
                                            $des_diag = oci_result($resultado, 'DESC_DIAGNOSTICO') ;
                                            if($cod_diag == $diagnostico) echo "<option value='$cod_diag' selected>$des_diag</option>\n";
                                            else echo "<option value='$cod_diag'>$des_diag</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flexigrid" style="width:760px; border-bottom:1px solid #CCCCCC">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:0px solid #ccc;" align="left">
                        <table>
                            <tr>
                                <td width="760" align="center">
                                    <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit"
                                            name="cerrar" id="cerrar"class="btn btn btn-INFO"><i class="fa fa-envelope-o"></i><b>&nbsp;Cerrar PQR</b>
                                    </button>
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



<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

