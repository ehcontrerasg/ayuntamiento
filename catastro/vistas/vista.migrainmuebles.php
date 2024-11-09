<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();

if ($verificarPermisos==true): ?>

    <?php
    session_start();
//include_once ('../../include.php');
    require'../clases/class.Migracion.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_POST['cod_inmueble'];
    $proyecto=$_POST['proyecto'];
    $sector=$_POST['sector'];
    $sector2=$_POST['sector2'];
    $ruta=$_POST['ruta'];
    $ruta2=$_POST['ruta2'];
    $manzana=$_POST['manzana'];
    $manzana2=$_POST['manzana2'];
    $ciclo=$_POST['ciclo'];
    $count=$_POST['count'];
    $cod_inm=$_POST['cod_inm'];
    $cod_nzo=$_POST['cod_nzo'];
    $cod_npr=$_POST['cod_npr'];
    $cod_nca=$_POST['cod_nca'];
    $cod_nzo2=$_POST['cod_nzo2'];
    $cod_npr2=$_POST['cod_npr2'];
    $cod_nca2=$_POST['cod_nca2'];
    $migrar_inm=$_POST['migrar_inm'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript" src="../../js/combos_asigna_lotes.js"></script>
        <script src="../../js/ajax.migra_sector.js"></script>
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link href="../../css/css.css" rel="stylesheet" type="text/css" />
        <style type="text/css">

            .table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            .th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:26px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
                text-align:center;
            }
            .tda{

                height:26px;
                border:1px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }

        </style>
    </head>


    <body style="margin-top:-25px">
    <div id="content" style="padding:0px">
        <?php
        if (isset($_POST["migrar"])){
            //echo "entro";
            echo "entro";
            $migraciones = count($migrar_inm);
            if($migraciones == 0){
                echo "
			<script type='text/javascript'>
			showDialog('Advertencia','Debe seleccionar minimo un inmueble para migrar','warning');
			</script>";
            }
            else{
                for ($j = 0; $j < $migraciones; $j++){

                    $pieces = explode(" ", $migrar_inm[$j]);
                    $inm_migrado = $pieces[0];
                    $contador = $pieces[1];

                    $nueva_zon = $cod_nzo2[$contador-1];
                    $nuevo_pro = $cod_npr2[$contador-1];
                    $nuevo_cat = $cod_nca2[$contador-1];

                    $i = new Migracion();
                    $bandera = $i->migraInmuebles($inm_migrado, $proyecto, $nueva_zon, $nuevo_pro, $nuevo_cat, $coduser, $sector2, $ruta2, $ciclo);
                    if($bandera == FALSE){
                        $error=$i->getmsgresult();
                        $coderror=$i->getcodresult();
                        echo"
				<script type='text/javascript'>
				showDialog('Error','Error al migrar el inmueble $migrar_inm[$j] CODIGO=$coderror. MENSAJE=$error','error');
				</script>";
                    }
                }
                if($bandera == TRUE){
                    echo "
			<script type='text/javascript'>
			showDialog('Transacci&oacute;n Exitosa','Has migrado los inmuebles correctamente','success');
			</script>";
                }
            }
            //unset ($sector, $ruta);
        }
        ?>
        <form name="migrainmuebles" action="vista.migrainmuebles.php" method="post" >
            <h3 class="panel-heading" style=" background-color:rgb(163,73,163); color:#FFFFFF; font-size:18px; width:1120px" align="center">Migraci&oacute;n Inmuebles</h3>
            <div style="text-align:center; width:1120px; margin-left:0px">
                <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
                    <tr>
                        <td width="8%" align="right" bgcolor="#EBEBEB" style="font-size:13px"><b>Acueducto:&nbsp;</b></td>
                        <td align="left" width="17%" bgcolor="#EBEBEB">
                            <select name='proyecto' id="proyecto" class='btn btn-default btn-sm dropdown-toggle' required onChange="load1(this.value); load3(this.value)">
                                <option value="" disabled selected>Seleccione acueducto...</option>
                                <?php
                                $c = new Migracion();
                                $resultado = $c->seleccionaAcueducto();
                                while (oci_fetch($resultado)) {
                                    $cod_proyecto = oci_result($resultado, 'ID_PROYECTO') ;
                                    $sigla_proyecto = oci_result($resultado, 'SIGLA_PROYECTO') ;
                                    if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                    else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                }oci_free_statement($resultado);
                                ?>
                            </select>
                        </td>
                        <td width="8%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>Sector:&nbsp;</b></td>
                        <td align="left" width="17%" bgcolor="#EBEBEB">
                            <div id="divsector">
                                <select name='sector' id="sector" class='btn btn-default btn-sm dropdown-toggle' required onChange="load2(this.value)">
                                    <?php
                                    if($sector != ''){
                                        ?>
                                        <option value="<?php echo $sector;?>" selected><?php echo $sector;?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="" disabled selected>Seleccione sector...</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td width="8%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>Ruta:&nbsp;</b></td>
                        <td align="left" width="17%" bgcolor="#EBEBEB">
                            <div id="divruta">
                                <select name='ruta' id="ruta" class='btn btn-default btn-sm dropdown-toggle' required>
                                    <?php
                                    if($ruta != ''){
                                        ?>
                                        <option value="<?php echo $ruta;?>" selected><?php echo $ruta;?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="" disabled selected>Seleccione ruta...</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td width="11%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>C&oacute;digo Sistema:&nbsp;</b></td>
                        <td align="left" width="14%" bgcolor="#EBEBEB">
                            <input type="text" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" placeholder="Ingrese el Inmueble" class='btn btn-default btn-sm dropdown-toggle'/>
                        </td>

                        <td width="11%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>Manzana:&nbsp;</b></td>
                        <td align="left" width="14%" bgcolor="#EBEBEB">
                            <input type="text" value="<?php echo $manzana ?>"  name="manzana" placeholder="manzana" class='btn btn-default btn-sm dropdown-toggle'/>
                        </td>

                        <td width="11%" colspan="1" align="right" bgcolor="#EBEBEB" style="font-size:13px" ></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" bgcolor="#EBEBEB" style="font-size:13px"><b>Migrar a:&nbsp;</b></td>
                        <td align="right" bgcolor="#EBEBEB" style="font-size:13px"><b>Ciclo:&nbsp;</b></td>
                        <td align="left" width="17%" bgcolor="#EBEBEB">
                            <select name='ciclo' id="ciclo" class='btn btn-default btn-sm dropdown-toggle' required>
                                <option value="" disabled selected>Seleccione ciclo...</option>
                                <?php
                                $c = new Migracion();
                                $resultado = $c->seleccionaCiclo();
                                while (oci_fetch($resultado)) {
                                    $cod_ciclo= oci_result($resultado, 'ID_CICLO') ;
                                    if($cod_ciclo == $ciclo) echo "<option value='$cod_ciclo' selected>$cod_ciclo</option>\n";
                                    else echo "<option value='$cod_ciclo'>$cod_ciclo</option>\n";
                                }oci_free_statement($resultado);
                                ?>
                            </select>
                        </td>
                        <td width="8%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>Sector:&nbsp;</b></td>
                        <td align="left" width="17%" bgcolor="#EBEBEB">
                            <div id="divsector2">
                                <select name='sector2' id="sector2" class='btn btn-default btn-sm dropdown-toggle' required onChange="load4(this.value)">
                                    <?php
                                    if($sector2 != ''){
                                        ?>
                                        <option value="<?php echo $sector2;?>" selected><?php echo $sector2;?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="" disabled selected>Seleccione sector...</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td width="8%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>Ruta:&nbsp;</b></td>
                        <td align="left" width="17%" bgcolor="#EBEBEB">
                            <div id="divruta2">
                                <select name='ruta2' id="ruta2" class='btn btn-default btn-sm dropdown-toggle' required>
                                    <?php
                                    if($ruta2 != ''){
                                        ?>
                                        <option value="<?php echo $ruta2;?>" selected><?php echo $ruta2;?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="" disabled selected>Seleccione ruta...</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td width="11%" align="right" bgcolor="#EBEBEB" style="font-size:13px" ><b>Manzana:&nbsp;</b></td>
                        <td align="left" width="14%" bgcolor="#EBEBEB">
                            <input type="text" value="<?php echo $manzana2 ?>"  name="manzana2" placeholder="manzana" class='btn btn-default btn-sm dropdown-toggle'/>
                        </td>

                        <td align="left" width="14%" bgcolor="#EBEBEB">
                            <input type="button" value="procesar" onclick="recarga();" class='btn btn-default btn-sm dropdown-toggle'/>
                        </td>
                    </tr>
                </table>
                <?php
                if (($proyecto != '' && $sector != '' && $ruta != '') || $cod_inmueble != ''){

                ?>
                <p></p>

                <table width="100%" border="1" bordercolor="#CCCCCC" align="left" vspace="12" >
                    <tr>
                        <td colspan="12">
                            <div class="flexigrid" style="width:100%">
                                <div class="mDiv">
                                    <div>Inmuebles a Migrar al Sector - Ruta <? echo $sector2.$ruta2?></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="th">N&deg;</td>
                        <td class="th">C&oacute;digo<br />Inmueble</td>
                        <td class="th">Urbanizaci&oacute;n</td>
                        <td class="th">Direcci&oacute;n</td>
                        <td class="th">Estado</td>
                        <td class="th">Zona<br />Actual</td>
                        <td class="th">Proceso<br />Actual</td>
                        <td class="th">Catastro<br />Actual</td>
                        <td class="th">Nueva<br />Zona</td>
                        <td class="th">Nuevo<br />Proceso</td>
                        <td class="th">Nuevo<br />Catastro</td>
                        <td class="th"><input type="checkbox" name="checktodos" checked></td>
                    </tr>
                    <tr>
                        <?php
                        $count = 1;
                        if($cod_inmueble != '') $where .= " AND I.CODIGO_INM = '$cod_inmueble'";
                        if($proyecto != '' && $sector != '' && $ruta != '') $where .= " AND I.ID_PROYECTO = '$proyecto' AND I.ID_SECTOR = '$sector' AND I.ID_RUTA = '$ruta'";
                        if($manzana != '' ) $where .= " AND SUBSTR(I.CATASTRO,3,3)=$manzana";
                        $c = new Migracion();
                        $resultado = $c->obtenerInmuebles ($proyecto, $sector, $ruta, $sector2, $ruta2, $ciclo, $manzana2,$where);
                        while (oci_fetch($resultado)) {
                        $cod_inm = oci_result($resultado, 'CODIGO_INM');
                        $cod_urb = oci_result($resultado, 'DESC_URBANIZACION');
                        $cod_dir = oci_result($resultado, 'DIRECCION');
                        $cod_est = oci_result($resultado, 'ID_ESTADO');
                        $cod_zon = oci_result($resultado, 'ID_ZONA');
                        $cod_pro = oci_result($resultado, 'ID_PROCESO');
                        $cod_cat = oci_result($resultado, 'CATASTRO');
                        $cod_nzo = oci_result($resultado, 'NUEVA_ZONA');
                        $cod_npr = oci_result($resultado, 'NUEVO_PROCESO');
                        $cod_nca = oci_result($resultado, 'NUEVO_CATASTRO');
                        $d = new Migracion();
                        $valores = $d->verificaProceso($cod_npr,$cod_inm);
                        while (oci_fetch($valores)) {
                            $existepro = oci_result($valores, 'ID_PROCESO');
                        }oci_free_statement($valores);
                        $d = new Migracion();
                        $valores = $d->verificaCatastro($cod_nca,$cod_inm);
                        while (oci_fetch($valores)) {
                            $existecat = oci_result($valores, 'CATASTRO');
                        }oci_free_statement($valores);

                        ?>
                        <td class="tda"><?php echo $count?></td>
                        <td class="tda"><?php echo $cod_inm;?></td><input type="hidden" name="cod_inm[]" value="<?php echo $cod_inm;?>">
                        <td class="tda"><?php echo $cod_urb;?></td>
                        <td class="tda"><?php echo $cod_dir;?></td>
                        <td class="tda"><?php echo $cod_est;?></td>
                        <td class="tda"><?php echo $cod_zon;?></td>
                        <td class="tda"><?php echo $cod_pro;?></td>
                        <td class="tda"><?php echo $cod_cat;?></td>
                        <td class="tda">
                            <input type="text" size="3" name="cod_nzo2[]" value="<?php echo $cod_nzo;?>" style="text-align:center; border:none">
                        </td>
                        <?php
                        if($existepro == ''){
                            ?>
                            <td class="tda">
                                <div id="divnpr1">
                                    <input type="text" name="cod_npr2[]" value="<?php echo $cod_npr;?>" style="text-align:center; border:none;" size="13">
                                </div>
                            </td>
                            <?php
                        }
                        else{
                            ?>
                            <td class="tda" style="background-color:#990000">
                                <div id="divnpr1">
                                    <input type="text" name="cod_npr2[]" value="<?php echo $cod_npr;?>" style="background-color:#990000; border:none; color:#FFFFFF; text-align:center" size="13">
                                </div>
                            </td>
                            <?php
                        }
                        if($existecat == ''){
                            ?>
                            <td class="tda">
                                <div id="divnca1">
                                    <input type="text" name="cod_nca2[]" value="<?php echo $cod_nca;?>" style="text-align:center; border:none" size="16">
                                </div>
                            </td>
                            <?php
                        }
                        else{
                            ?>
                            <td class="tda" style="background-color:#990000">
                                <div id="divnca1">
                                    <input type="text" name="cod_nca2[]" value="<?php echo $cod_nca;?>" style="background-color:#990000; border:none; color:#FFFFFF; text-align:center" size="16">
                                </div>
                            </td>
                            <?php
                        }
                        ?>
                        <td class="tda"><input type="checkbox" name="migrar_inm[]" value = "<?php echo $cod_inm.' '.$count?>" checked></td>
                    </tr>
                    <?php
                    $count++;
                    //unset($existepro, $existecat);
                    unset($existepro, $existecat);
                    }oci_free_statement($resultado);
                    ?>
                    <tr>
                        <td colspan="12" bgcolor="EBEBEB" align="center">
                            <input type="submit" value="Migrar"  name="migrar" id="migrar"  class="btn btn-primary btn-lg" style="background-color:rgb(163,73,163);border-color:rgb(163,73,163);">
                        </td>
                    </tr>
                </table>
            </div>
            <?php
            }
            ?>
        </form>
    </div>
    </body>
    </html>
    <script>
        function recarga() {
            document.migrainmuebles.submit();
        }
        $(document).ready(function(){
            //Checkbox
            $("input[name=checktodos]").change(function(){
                $('input[type=checkbox]').each( function() {
                    if($("input[name=checktodos]:checked").length == 1){
                        this.checked = true;
                    } else {
                        this.checked = false;
                    }
                });
            });

        });
    </script>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

