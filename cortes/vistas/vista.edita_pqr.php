<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require_once '../../clases/class.usuario.php';
    require_once '../../clases/classPqrs.php';
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
        $resultado = $c->obtieneDatosPqr($cod_pqr);
        while (oci_fetch($resultado)) {
            $cod_inm = oci_result($resultado, 'COD_INMUEBLE');
            $nom_rec = oci_result($resultado, 'NOM_CLIENTE');
            $dir_rec = oci_result($resultado, 'DIR_CLIENTE');
            $tel_rec = oci_result($resultado, 'TEL_CLIENTE');
            $descripcion = oci_result($resultado, 'DESCRIPCION');
            $area_act = oci_result($resultado, 'AREA_ACTUAL');
            $orden = oci_result($resultado, 'ORDEN');
        }oci_free_statement($resultado);

        $dt = new DateTime();
        $dt->modify("-6 hour");
        $fec_res = $dt->format('d/m/Y');

        $l=new Usuario();
        $registros=$l->getAreaUsuByUsu($coduser);
        while (oci_fetch($registros)) {
            $area_user = oci_result($registros, 'ID_AREA') ;
        }oci_free_statement($registros);

        if (isset($_REQUEST["reasignar"])){
            $cod_inm=$_POST['cod_inm'];
            $cod_pqr=$_POST['cod_pqr'];
            $resolucion=$_POST['resolucion'];
            $area_act=$_POST['area_act'];
            $orden=$_POST['orden'];
            $area_res=$_POST['area_res'];
            $c = new Pqrs();
            $bandera = $c->IngresaFlujoPqr($cod_inm,$cod_pqr,$resolucion,$area_act,$orden,$coduser,$area_res);
            if($bandera == false){
                $error=$c->getmsgresult();
                $coderror=$c->getcodresult();?>
                <script type='text/javascript'>
                    swal({
                        title: "Error Registrando Respuesta a PQR",
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
            <?php }
            else if($bandera == true){?>
                <script type='text/javascript'>
                    swal({
                            title: "PQR Registrada",
                            text: "Se Registro la Respuesta a la PQR N&deg; <?php echo $cod_pqr?>",
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
                                window.close(this);
                                window.reload();
                            }
                        });
                </script>
            <?php }
        } ?>
        <h3 class="panel-heading" style=" background-color:#88a247; color:#FFFFFF; font-size:18px; width:760px" align="center">Resoluci&oacute;n de PQRs</h3>
        <form name="pagos" action="vista.edita_pqr.php" method="post">
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
                            $c = new Pqrs();
                            $resultado = $c->obtieneResolucionesPqrs($cod_pqr);
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
                                    Resoluci&oacute;n PQR<br />
                                    <textarea rows="4" cols="120" required name="resolucion" id="resolucion"><?php echo $resolucion?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td width="248">
                                    Fecha Resoluci&oacute;n<br />
                                    <input type="text" name="fec_res" id="fec_res" value="<?php echo $fec_res;?>" size="15"  class="input" readonly />
                                </td>
                                <td width="248" colspan="1">
                                    Asignar al Departamento<br />
                                    <select name='area_res' id="area_res" class='input' required>
                                        <option value="" selected>Seleccione departamento...</option>
                                        <?php
                                        $c = new PQRs();
                                        $resultado = $c->seleccionaDepartamentoPorUsuario($coduser);
                                        while (oci_fetch($resultado)) {
                                            $cod_area = oci_result($resultado, 'ID_AREA') ;
                                            $des_area = oci_result($resultado, 'DESC_AREA') ;
                                            if($cod_area == $area_res) echo "<option value='$cod_area' selected>$des_area</option>\n";
                                            else echo "<option value='$cod_area'>$des_area</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="248">
                                    Usuario Resoluci&oacute;n<br />
                                    <input type="text" name="coduser" id="coduser" value="<?php echo $coduser;?>" size="15"  class="input" readonly />
                                    <input type="hidden" name="area_act" id="area_act" value="<?php echo $area_act?>" />
                                    <input type="hidden" name="orden" id="orden" value="<?php echo $orden?>" />
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
                                <!--td width="380" align="center">
                                <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit"
                                    name="resuelto" id="resuelto"class="btn btn btn-INFO"><i class="fa fa-floppy-o"></i><b>&nbsp;Dar Por Resuelto</b></button>
                              </td-->
                                <td width="760" align="center">
                                    <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#88a247; color:#88a247; display:block" type="submit"
                                            name="reasignar" id="reasignar"class="btn btn btn-INFO"><i class="fa fa-share"></i><b>&nbsp;Procesar</b>
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
    <script src="../../js/editaPqrs.js?<?php echo time(); ?>"></script>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>