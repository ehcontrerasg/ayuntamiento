<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>


    <?php
    session_start();
    include_once ('../../include.php');
    require'../clases/classAsignaFac.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $proyecto=$_POST['proyecto'];
    $zona=$_POST['zona'];
    $periodo=$_POST['periodo'];
    $sector=$_POST['sector'];
    $proc=$_POST['proc'];
    $operario=$_POST['operario'];
    $ruta=$_POST['ruta'];
    $desasignar_loc=$_POST['desasignar_loc'];
    $fecPla=$_POST['fecPla'];
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
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript" src="../../js/combos_asigna_lotes.js"></script>
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

        </style>
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px">
        <?php
        if (isset($_REQUEST["Asignar"])){
            $operario_asignado=$_POST['operario_asignado'];
            if($zona != ""){
                $asignaciones = count($operario);
                $vacios=0;
                for ($j = 0; $j < $asignaciones; $j++){
                    $oper_asig = $operario[$j];
                    if (trim($oper_asig)=="" ){
                        $vacios++;
                    }//cierra if
                }//cierra for
                if($vacios != $asignaciones) {
                    $i = new AsignaLotes();
                    for ($j = 0; $j <$asignaciones; $j++){
                        if($operario[$j] != ''){
                            $operario_asignado = $operario[$j];
                            $bandera = $i->AsignaRuta($coduser,$operario_asignado,$zona,$ruta[$j],$periodo,$fecPla);
                            if($bandera == FALSE){
                                $error=$i->getmsgresult();
                                $coderror=$i->getcodresult();
                                echo"
							<script type='text/javascript'>
								showDialog('Error','Error al asignar la ruta $ruta[$j] CODIGO=$coderror. MENSAJE=$error','error',3);
							</script>";
                            }
                        }
                    }
                    if($bandera == TRUE){
                        echo "
				<script type='text/javascript'>
				showDialog('Transacci&oacute;n Exitosa','Has asignado las rutas correctamente','success',3);
				</script>";
                    }
                }
                else{
                    echo "
				<script type='text/javascript'>
				showDialog('Advertencia','Debe asignar minimo un operario','warning',3);
				</script>";
                }
            }
        }
        /*if (isset($_REQUEST["Asignar"])){
            if($zona != ""){
                $asignaciones = count($operario);
                 $vacios=0;
                   for ($j = 0; $j < $asignaciones; $j++){
                    $oper_asig = $operario[$j];
                    if (trim($oper_asig)=="" ){
                        $vacios++;
                       }//cierra if
                  }//cierra for
                if($vacios >=1) {
                    $i = new AsignaLotes();
                    for ($j = 0; $j <$asignaciones; $j++){
                        $operario_asignado = $operario[$j];
                        $bandera = $i->AsignaRuta($coduser,$operario_asignado,$zona,$ruta[$j],$periodo);
                        if($bandera == FALSE){
                            $error=$i->getmsgresult();
                            $coderror=$i->getcodresult();
                            echo"
                                <script type='text/javascript'>
                                    showDialog('Error','Error al asignar la ruta $ruta[$j] CODIGO=$coderror. MENSAJE=$error','error',3);
                                </script>";
                        }
                    }
                    if($bandera == TRUE){
                        echo "
                        <script type='text/javascript'>
                        showDialog('Transacci&oacute;n Exitosa','Has asignado las rutas correctamente','success',3);
                        </script>";
                    }
                }
                else{
                    echo "
                        <script type='text/javascript'>
                        showDialog('Advertencia','Debe asignar todos los operarios','warning',3);
                        </script>";
                }
            }
        }*/

        if (isset($_REQUEST["Desasignar"])){
            if($zona != ""){
                $desasignaciones = count($desasignar_loc);
                if($desasignaciones == 0){
                    echo "
				<script type='text/javascript'>
				showDialog('Advertencia','Debe seleccionar minimo una ruta para desasignar','warning',3);
				</script>";
                }
                else{
                    for ($j = 0; $j < $desasignaciones; $j++){
                        $itin_desasignada = $desasignar_loc[$j];
                        $i = new AsignaLotes();
                        $bandera = $i->DesasignaRuta($zona,$desasignar_loc[$j],$periodo);
                        if($bandera == FALSE){
                            $error=$i->getmsgresult();
                            $coderror=$i->getcodresult();
                            echo"
					<script type='text/javascript'>
					showDialog('Error','Error al desasignar la ruta $ruta[$j] CODIGO=$coderror. MENSAJE=$error','error',3);
					</script>";
                        }
                    }
                    if($bandera == TRUE){
                        echo "
				<script type='text/javascript'>
				showDialog('Transacci&oacute;n Exitosa','Has desasignado las rutas correctamente','success',3);
				</script>";
                    }
                }
            }
        }
        ?>
        <form name="asignalotlec" action="vista.asignacionfac.php" method="post" >
            <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Asignaci&oacute;n Lote Facturas</h3>
            <div style="text-align:center; width:1120px; margin-left:0px">
                <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
                    <tr>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Acueducto:&nbsp;</td>
                        <td align="left" width="5%" bgcolor="#EBEBEB">
                            <select name='proyecto' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();' >
                                <option value="" selected>Seleccione proyecto...</option>
                                <?php
                                $c = new AsignaLotes();
                                $resultado = $c->seleccionaProyecto($coduser);
                                while (oci_fetch($resultado)) {
                                    $cod_proyecto = oci_result($resultado, 'ID_PROYECTO') ;
                                    $sigla_proyecto = oci_result($resultado, 'SIGLA_PROYECTO') ;
                                    if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                    else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                }oci_free_statement($resultado);
                                ?>
                            </select>
                        </td>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Periodo:&nbsp;</td>
                        <td align="left" width="5%" bgcolor="#EBEBEB">
                            <select name='periodo' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();'>
                                <option value="" selected>Seleccione periodo...</option>
                                <?php
                                $c = new AsignaLotes();
                                $resultado = $c->seleccionaPeriodo($proyecto);
                                while (oci_fetch($resultado)) {
                                    $cod_periodo = oci_result($resultado, 'PERIODO') ;
                                    if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                                    else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                                }oci_free_statement($resultado);
                                ?>
                            </select>
                        </td>
                        <!--td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Sector:&nbsp;</td>
				<td align="left" width="15%" bgcolor="#EBEBEB">
					<select name='sector' id='sector' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();'>
					<option value="" selected>Seleccione sector...</option>
					<?php
                        /*$c = new AsignaLotes();
                        $resultado = $c->seleccionaSector($periodo, $proyecto);
                        while (oci_fetch($resultado)) {
                            $cod_sector = oci_result($resultado, 'ID_SECTOR') ;
                            if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                            else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                        }oci_free_statement($resultado);*/
                        ?>
					</select>
				</td-->
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Zona:&nbsp;</td>
                        <td align="left" width="5%" bgcolor="#EBEBEB">
                            <select name='zona' id='zona' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
                                <option value="" selected>Seleccione zona...</option>
                                <?php
                                $c = new AsignaLotes();
                                $resultado = $c->seleccionaZona($periodo, $proyecto);
                                while (oci_fetch($resultado)) {
                                    $cod_zona = oci_result($resultado, 'ID_ZONA') ;
                                    if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
                                    else echo "<option value='$cod_zona'>$cod_zona</option>\n";
                                }oci_free_statement($resultado);
                                ?>
                            </select>
                        </td>

                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Zona:&nbsp;</td>
                        <td align="left" width="5%" bgcolor="#EBEBEB">
                            <select name='zona' id='zona' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
                                <option value="" selected>Seleccione zona...</option>
                                <?php
                                $c = new AsignaLotes();
                                $resultado = $c->seleccionaZona($periodo, $proyecto);
                                while (oci_fetch($resultado)) {
                                    $cod_zona = oci_result($resultado, 'ID_ZONA') ;
                                    if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
                                    else echo "<option value='$cod_zona'>$cod_zona</option>\n";
                                }oci_free_statement($resultado);
                                ?>
                            </select>
                        </td>

                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="30%">Fech pla:&nbsp;</td>
                        <td align="left" width="5%" bgcolor="#EBEBEB">
                            <input name='fecPla' id='fecPla' value="<? echo $fecPla?>"  type="date" required class='form-control'>

                        </td>

                    </tr>
                </table>
                <?php
                if ($periodo != "" && $zona != "" && $proyecto != ""){
                if($zona != '' ) $des_zona = '- Zona '.$zona;
                ?>
                <p></p>

                <table width="49%" border="1" bordercolor="#CCCCCC" align="left" vspace="12" >
                    <tr>
                        <td colspan="3">
                            <div class="flexigrid" style="width:100%">
                                <div class="mDiv">
                                    <div>Asignaci&oacute;n Facturas - Periodo  <?php echo $periodo?> <?php echo $des_zona;?></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="th">Rutas</td>
                        <td class="th">Cantidades</td>
                        <td class="th">Operarios</td>
                    </tr>
                    <tr>
                        <?php
                        $cantrutas = 0;
                        $cantlect = 0;
                        $c = new AsignaLotes();
                        //if($zona == "") $resultado = $c->seleccionaAsignacionSector($periodo, $proyecto, $sector);
                        //else
                        $resultado = $c->seleccionaAsignacionZona($periodo, $proyecto, $zona);

                        while (oci_fetch($resultado)) {
                        /*if($zona == ""){
                            $cant = oci_result($resultado, 'CANTIDAD');
                            $ruta = oci_result($resultado, 'ID_ZONA');
                        }
                        else{*/
                        $cant = oci_result($resultado, 'CANTIDAD');
                        $ruta = oci_result($resultado, 'ID_RUTA');
                        $sect = oci_result($resultado, 'ID_SECTOR');
                        //}
                        ?>
                        <td class="tda"><?php echo $sect.$ruta;?></td><input type="hidden" name="ruta[]" value="<?php echo $ruta;?>">
                        <td class="tda"><?php echo $cant;?></td><input type="hidden" name="cant[]" value="<?php echo $cant;?>">
                        <td class="tda">
                            <select name="operario[]" size="1" class="select"><option></option>
                                <?php
                                $c = new AsignaLotes();
                                $resultadoop = $c->seleccionaOperario($proyecto);
                                while (oci_fetch($resultadoop)) {
                                    $cod_operario = oci_result($resultadoop, 'ID_USUARIO');
                                    $nom_operario = oci_result($resultadoop, 'NOM_USR');
                                    $ape_operario = oci_result($resultadoop, 'APE_USR') ;
                                    if($cod_operario == $asi_oper[$cont]) echo "<option value='$cod_operario' selected>$cod_operario - $nom_operario $ape_operario</option>\n";
                                    else echo "<option value='$cod_operario'>$cod_operario  - $nom_operario $ape_operario</option>\n";
                                }oci_free_statement($resultadoop);
                                ?>
                            </select></td>
                    </tr>
                    <?php
                    $cantlect += $cant;
                    $cantrutas++;
                    }oci_free_statement($resultado);
                    ?>
                    <tr>
                        <td class="th"><b style="color:#FF0000"><?php echo $cantrutas;?></b></td>
                        <td class="th"><b style="color:#FF0000"><?php echo $cantlect;?></b></td>
                        <td class="th"><b style="color:#FF0000"><?php echo $cantrutas;?></b></td>
                    </tr>
                    <tr>
                        <td colspan="3" bgcolor="EBEBEB" align="center">
                            <input type="submit" value="Asignar" name="Asignar" id="Asignar" class="btn btn-primary btn-lg" style="background-color:#336699;border-color:#336699">
                        </td>
                    </tr>
                </table>
                <table width="49%" border="1" bordercolor="#CCCCCC" align="right" vspace="12">
                    <tr>
                        <td colspan="4">
                            <div class="flexigrid" style="width:100%">
                                <div class="mDiv">
                                    <div>Desasignaci&oacute;n Facturas - Zona <? echo $zona?> - Periodo  <?php echo $periodo?></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="th">Rutas</td>
                        <td class="th">Cantidades</td>
                        <td class="th">Operarios</td>
                        <td class="th"><input type="checkbox" name="checktodos"></td>
                    </tr>
                    <tr>
                        <?php
                        $cantrutasd = 0;
                        $cantlectd = 0;
                        $c = new AsignaLotes();
                        $resultado = $c->seleccionaDesasignacionZona($periodo, $zona);
                        while (oci_fetch($resultado)) {
                        $can_operario_1 = oci_result($resultado, 'CANTIDAD');
                        $sec_operario_1 = oci_result($resultado, 'SECTOR');
                        $rut_operario_1 = oci_result($resultado, 'RUTA');
                        $cod_operario_1 = oci_result($resultado, 'ID_USUARIO');
                        $nom_operario_1 = oci_result($resultado, 'NOM_USR');
                        $ape_operario_1 = oci_result($resultado, 'APE_USR') ;
                        ?>
                        <td class="tda"><? echo $sec_operario_1.$rut_operario_1;?></td>
                        <td class="tda"><? echo $can_operario_1;?></td>
                        <td class="tda"><? echo $nom_operario_1." ".$ape_operario_1;?></td>
                        <td class="tda"><input type="checkbox" name="desasignar_loc[]" value = "<? echo "$rut_operario_1"?>"></td>
                    </tr>
                    <?php
                    $cantlectd += $can_operario_1;
                    $cantrutasd++;
                    }oci_free_statement($resultado);
                    ?>
                    <tr>
                        <td class="th"><b style="color:#FF0000"><?php echo $cantrutasd;?></b></td>
                        <td class="th"><b style="color:#FF0000"><?php echo $cantlectd;?></b></td>
                        <td class="th"><b style="color:#FF0000"><?php echo $cantrutasd;?></b></td>
                        <td class="th"></td>
                    </tr>
                    <tr>
                        <td colspan="4" bgcolor="EBEBEB" align="center">
                            <input type="submit" value="Des-Asignar" name="Desasignar" id="Desasignar" class="btn btn-primary btn-lg" style="background-color:#336699;border-color:#336699">
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
            document.asignalotlec.submit();
        }

        function validaform(){
            if (document.asignalotlec.proyecto.selectedIndex == 0 || document.asignalotlec.periodo.selectedIndex == 0 || document.asignalotlec.zona.selectedIndex == 0) {
                document.asignalotlec.proc.value = 0;
                return false;
            }
            else {
                document.asignalotlec.proc.value = 1;
                return true;
            }
        }

        $(document).ready(function(){
            //Checkbox

            var d = new Date();
            var montMin=(d.getMonth()+1);
            var montMax= (d.getMonth()+1);
            if(montMin<10){
                montMin='0'+montMin
            }

            if(montMax<10){
                montMax='0'+montMax
            }

            var strDate = d.getFullYear() + "-" + montMin + "-" + d.getDate();
            var strDate2 = d.getFullYear() + "-" + montMax + "-" + (d.getDate()+1);
            $("#fecPla").prop('min',strDate);
            $("#fecPla").prop('max',strDate2);

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

