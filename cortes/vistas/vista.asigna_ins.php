<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include_once ('../../include.php');
    include_once '../../clases/class.inspeccionCorte.php';
    include_once '../../clases/class.usuario.php';
    include_once '../../clases/class.proyecto.php';
    include_once '../../clases/class.zona.php';
    include_once '../../clases/class.uso.php';
    include_once '../../clases/class.calibre.php';
    include_once('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $proyecto=$_POST['proyecto'];
    $zona=$_POST['zona'];
    $calibre=$_POST['calibre'];
    $periodo=$_POST['periodo'];
    $proc=$_POST['proc'];
    $operario=$_POST['operario'];
    $ruta=$_POST['ruta'];
    $fecPla=$_POST['fecPla'];



    if($_POST['facini']){
        $facini=$_POST['facini'];
        $facfin= $_POST['facfin'];
    }else{
        $facini=1;
        $facfin=99999;
    }

    $medido=$_POST['medido'];
    $desasignar_loc=$_POST['desasignar_loc'];
    $usoc=$_POST['usoc'];
    $catc=$_POST['catc'];
    echo $desasignar=$_POST['Desasignar'];
     echo $asignar=$_POST['Asignar'];
     echo $procesar=$_POST['Procesar'];

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link rel="stylesheet" href="../../flexigrid/style.css?1" />
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

            .contenedor {
                display: flex;
                flex-wrap: wrap;
                background-color: #EBEBEB;
                width: 100%;
                padding-top: 1em;
            }

            .form-group{
                width: 25%;
            }

            .form-group select {
                padding: 6px 12px;
                width: 90%;
            }
            .form-group input {
                padding: 4px 12px;
                width: 90%;
            }
            .form-group input[type=date]{
                line-height: inherit;
            }
        </style>
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px">
        <?php
        if (isset($_REQUEST["Asignar"])){
            $inm = trim($_POST['inm']);
            //if($zona != ""){
            $asignaciones = count($operario);
            $vacios=0;
            for ($j = 0; $j < $asignaciones; $j++){
                $oper_asig = $operario[$j];
                if (trim($oper_asig)=="" ){
                    $vacios++;
                }//cierra if
            }//cierra for
            //if($vacios==0) {

            for ($j = 0; $j <$asignaciones; $j++){
                if($operario[$j]<>""){
                    $i = new InspeccionCorte();
                    $operario_asignado = $operario[$j];
                    $bandera = $i->setAsignaIns($zona,$ruta[$j],$operario_asignado,$coduser,$usoc,$medido[$j],$facini,$facfin,$fecPla,$inm,$catc);

                    if($bandera == FALSE){
                        echo $bandera;
                        // $error=$i->errorms();
                        // $coderror=$i->errorcod();
                        /*echo "
                            <script type='text/javascript'>
                                showDialog('Error','Error al asignar la ruta $ruta[$j] CODIGO=$coderror. MENSAJE=$error','error',3);
                            </script>";*/
                    }
                }
            }
            if($bandera == TRUE){
                if (is_null($inm)) {

                    echo "
                <script type='text/javascript'>
                showDialog('Transacci&oacute;n Exitosa','Has asignado las rutas correctamente','success',3);
                </script>";
                } else {
                    echo "
                <script type='text/javascript'>
                showDialog('Transacci&oacute;n Exitosa','Has asignado el inmueble $inm éxitosamente!','success',3);
                </script>";
                }
            }
            //}
            /*else{
                echo "
                    <script type='text/javascript'>
                    showDialog('Advertencia','Debe asignar todos los operarios','warning',3);
                    </script>";
            }*/
            //}
        }

        if (isset($_REQUEST["Desasignar"])){
            $inm = trim($_POST['inm']);
            //if($zona != ""){
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
                    $i = new InspeccionCorte();
                    $bandera = $i->setDesAsigInsByZon($zona,$desasignar_loc[$j], $inm);
                    if($bandera == FALSE){
                        $error=$i->errorms();
                        $coderror=$i->errorcod();
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
            // }
        }
        ?>
        <form name="asignains" action="vista.asigna_ins.php" method="post" >
            <h3 class="panel-heading" style="background-color:#88A247; color:#FFFFFF; font-size:18px; width:100%" align="center">Aignaci&oacute;n de Inspecciones</h3>
            <div style="text-align:center; margin-left:0px">
                <!--  <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
               <tr>
                   <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Acueducto:&nbsp;</td>
                   <td align="left" width="15%" bgcolor="#EBEBEB">
                       <select name='proyecto' class='btn btn-default btn-sm dropdown-toggle' required onChange='recarga();' >
                           <option value="" disabled selected>Seleccione proyecto...</option>
                           <?php
                $c = new Proyecto();
                $resultado = $c->obtenerProyecto($coduser);
                while (oci_fetch($resultado)) {
                    $cod_proyecto = oci_result($resultado, 'CODIGO') ;
                    $sigla_proyecto = oci_result($resultado, 'DESCRIPCION') ;
                    if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                    else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                }oci_free_statement($resultado);
                ?>
                       </select>
                   </td>
                   <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Zona:&nbsp;</td>
                   <td align="left" width="10%" bgcolor="#EBEBEB">
                       <select name='zona' id='zona' class='btn btn-default btn-sm dropdown-toggle' required >
                           <option value="" disabled selected>Seleccione zona...</option>
                           <?php
                $c = new Zona();
                $resultado = $c->getZonaInsByPro($proyecto);
                while (oci_fetch($resultado)) {
                    $cod_zona = oci_result($resultado, 'ID_ZONA') ;
                    if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
                    else echo "<option value='$cod_zona'>$cod_zona</option>\n";
                }oci_free_statement($resultado);
                ?>
                       </select>
                   </td>
                   <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Uso:&nbsp;</td>
                   <td align="left" width="10%" bgcolor="#EBEBEB">
                       <select name='usoc' id='usoc' class='btn btn-default btn-sm dropdown-toggle''>
                       <option value=""  selected>Seleccione Uso...</option>
                       <?php
                $c = new Uso();
                $resultado = $c->getUsoCorte();
                while (oci_fetch($resultado)) {
                    $cod_uso = oci_result($resultado, 'ID_USO') ;
                    $desc_uso = oci_result($resultado, 'DESC_USO') ;
                    if($cod_uso == $usoc) echo "<option value='$cod_uso' selected>$desc_uso</option>\n";
                    else echo "<option value='$cod_uso'>$desc_uso</option>\n";
                }oci_free_statement($resultado);
                ?>
                       </select>
                   </td>

                   <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">No Fac Ini:&nbsp;</td>
                   <td align="left" width="1%" bgcolor="#EBEBEB">
                       <input type="number" name="facini" value="<?echo $facini?>" size="1" />
                   </td>


                   <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">No Fac Fin:&nbsp;</td>
                   <td align="left" width="1%" bgcolor="#EBEBEB">
                       <input type="number" name="facfin" value="<?echo $facfin?>"   size="1"/>
                   </td>


                   <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Fecha planificacion:&nbsp;</td>
                   <td align="left" width="1%" bgcolor="#EBEBEB">
                       <input type="date" name="fecPla" id="fecPla" required value="<? echo $fecPla?>" class="form-control" maxlength="10" size="10" />
                   </td>

               </tr>

           </table> -->

                <div class="contenedor">
                    <div class="form-group">
                        <select name='proyecto' class='' required onChange='recarga();' >
                            <option value="" disabled selected>Seleccione proyecto...</option>
                            <?php
                            $c = new Proyecto();
                            $resultado = $c->obtenerProyecto($coduser);
                            while (oci_fetch($resultado)) {
                                $cod_proyecto = oci_result($resultado, 'CODIGO') ;
                                $sigla_proyecto = oci_result($resultado, 'DESCRIPCION') ;
                                if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                            }oci_free_statement($resultado);
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="inmueble" placeholder="Inmueble" />
                    </div>
                    <div class="form-group">
                        <select name='zona' id='zona' class='' onChange='recarga();' >
                            <option value="" disabled selected>Seleccione zona...</option>
                            <?php
                            $c = new Zona();
                            $resultado = $c->getZonaInsByPro($proyecto);
                            while (oci_fetch($resultado)) {
                                $cod_zona = oci_result($resultado, 'ID_ZONA') ;
                                if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
                                else echo "<option value='$cod_zona'>$cod_zona</option>\n";
                            }oci_free_statement($resultado);
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name='usoc' id='usoc' class=''  onChange='recarga();' >
                            <option value=""  selected>Seleccione Uso...</option>
                            <?php
                            $c = new Uso();
                            $resultado = $c->getUsoCorte();
                            while (oci_fetch($resultado)) {
                                $cod_uso = oci_result($resultado, 'ID_USO') ;
                                $desc_uso = oci_result($resultado, 'DESC_USO') ;
                                if($cod_uso == $usoc) echo "<option value='$cod_uso' selected>$desc_uso</option>\n";
                                else echo "<option value='$cod_uso'>$desc_uso</option>\n";
                            }oci_free_statement($resultado);
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>&nbsp;Categoria</label>
                        <select name='catc' id='catc' class=''  onChange='recarga();' >
                            <option value=""  selected>Seleccione Categoria...</option>
                            <?php
                            $c = new Uso();
                            $resultado = $c->getCategoriaByUso($usoc,$proyecto);
                            while (oci_fetch($resultado)) {
                                $cod_cat = oci_result($resultado, 'CODIGO') ;
                                $desc_cat = oci_result($resultado, 'DESCRIPCION') ;
                                if($cod_cat == $catc) echo "<option value='$cod_cat' selected>$desc_cat</option>\n";
                                else echo "<option value='$cod_cat'>$desc_cat</option>\n";
                            }oci_free_statement($resultado);
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>&nbsp;Diámetros</label>
                        <select name='calibre' id='calibre' class=''>
                            <option value=""  selected>Seleccione diámetro....</option>
                            <?php
                            $c = new Calibre();
                             $resultado = $c->getCalibresByZonProyAsigInsp($zona,$proyecto,$usoc);
                            while (oci_fetch($resultado)) {
                                    $cod_calibre = oci_result($resultado, 'CODIGO') ;
                                $desc_calibre = oci_result($resultado, 'DESCRIPCION');
                                if($cod_calibre == $calibre) echo "<option value='$cod_calibre' selected>$desc_calibre</option>\n";
                                else echo "<option value='$cod_calibre'>$desc_calibre</option>\n";
                            }oci_free_statement($resultado);

                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Factura Inicial</label>
                        <input type="number" name="facini" value="<?echo $facini?>"  />
                    </div>
                    <div class="form-group">
                        <label>Factura Final</label>
                        <input type="number" name="facfin" value="<?echo $facfin?>"  />
                    </div>
                    <div class="form-group">
                        <label>Fecha Planificación</label>
                        <input type="date" name="fecPla" id="fecPla" required value="<? echo $fecPla?>" />
                    </div>

                </div>
                <p>
                    <input type="submit" onclick="desabilita();" value="Procesar" name="Procesar" id="Procesar" class="btn btn-primary btn-lg" style="background-color:#88A247;border-color:#88A247; margin-top: 1em;"></center></p>
                <?php
                if (isset($_REQUEST["Procesar"])){
                ?>
                <p></p>
                <form name="asignains2" action="vista.asigna_ins.php" method="post" >
                    <table width="49%" border="1" bordercolor="#CCCCCC" align="left" vspace="15" >
                        <tr>
                            <td colspan="5">
                                <div class="flexigrid" style="width:100%">
                                    <div class="mDiv">
                                        <div>Asignaci&oacute;n Rutas - Zona <? echo $zona?> - Periodo  <?php echo $periodo?></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="th">Medido</td>
                            <td class="th">Rutas</td>
                            <td class="th">Categoria</td>
                            <td class="th">Cantidades</td>
                            <td class="th">Operarios</td>
                        </tr>
                        <tr>
                            <?php
                            $cantrutas = 0;
                            $cantlect = 0;
                            $inm = $_POST['inmueble'];
                            $c = new InspeccionCorte();
                            $resultado = $c->getCantInsRutMedByZonUsoFac($zona,$usoc,$facini,$facfin,$inm,$calibre,$catc);
                            while (oci_fetch($resultado)) {
                            $medido = oci_result($resultado, 'MEDIDO');
                            $cant = oci_result($resultado, 'CANTIDAD');
                            $ruta = oci_result($resultado, 'RUTA');
                            $categoria = oci_result($resultado, 'CATEGORIA');
                            ?>
                            <td class="tda"><?php echo $medido;?></td><input type="hidden" name="medido[]" value="<?php echo $medido;?>">
                            <td class="tda"><?php echo $ruta;?></td><input type="hidden" name="ruta[]" value="<?php echo $ruta;?>">
                            <td class="tda"><?php echo $categoria;?></td><input type="hidden" name="cat[]" value="<?php echo $categoria;?>">
                            <td class="tda"><?php echo $cant;?></td><input type="hidden" name="cant[]" value="<?php echo $cant;?>">
                            <input type="hidden" name="inm" value="<?php echo $inm;?>">

                            <td class="tda">
                                <select name="operario[]" size="1" class="select"><option></option>
                                    <?php
                                    $c = new Usuario();
                                    $resultadoop = $c->getOperariosCorteByPro($proyecto);
                                    while (oci_fetch($resultadoop)) {
                                        $cod_operario = oci_result($resultadoop, 'CODIGO');
                                        $nom_operario = oci_result($resultadoop, 'NOMBRE');
                                        $ape_operario = oci_result($resultadoop, 'APELLIDO') ;
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
                            <td class="th"><b style="color:#FF0000"></b></td>
                            <td class="th"><b style="color:#FF0000"><?php echo $cantrutas;?></b></td>
                            <td class="th"><b style="color:#FF0000"></b></td>
                            <td class="th"><b style="color:#FF0000"><?php echo $cantlect;?></b></td>
                            <td class="th"><b style="color:#FF0000"><?php echo $cantrutas;?></b></td>

                        </tr>
                        <tr>
                            <td colspan="5" bgcolor="EBEBEB" align="center">
                                <input type="submit" value="Asignar" name="Asignar" id="Asignar" class="btn btn-primary btn-lg" style="background-color:#336699;border-color:#336699">
                            </td>
                        </tr>
                    </table>
                    <table width="49%" border="1" bordercolor="#CCCCCC" align="right" vspace="12">
                        <tr>
                            <td colspan="4">
                                <div class="flexigrid" style="width:100%">
                                    <div class="mDiv">
                                        <div>Desasignaci&oacute;n Rutas - Zona <? echo $zona?> - Periodo  <?php echo $periodo?></div>
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
                            $c = new InspeccionCorte();
                            $resultado = $c->setDesAsignacionIns($zona,$facini,$facfin, $inm,$calibre);
                            while (oci_fetch($resultado)) {
                            $can_operario_1 = oci_result($resultado, 'CANTIDAD');
                            $rut_operario_1 = oci_result($resultado, 'RUTA');
                            $cod_operario_1 = oci_result($resultado, 'ID_USUARIO');
                            $nom_operario_1 = oci_result($resultado, 'NOM_USR');
                            $ape_operario_1 = oci_result($resultado, 'APE_USR') ;
                            ?>
                            <td class="tda"><? echo $rut_operario_1;?></td>
                            <td class="tda"><? echo $can_operario_1;?></td>
                            <td class="tda"><? echo $nom_operario_1." ".$ape_operario_1;?></td>
                            <td class="tda"><input type="checkbox" name="desasignar_loc[]" value = "<? echo "$rut_operario_1"?>"></td>
                            <input type="hidden" name="inm" value="<?php echo $inm;?>">
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
                                <input type='submit'  value='Des-Asignar' name='Desasignar' id='Desasignar' class='btn btn-primary btn-lg' style='background-color:#336699;border-color:#336699'>
                            </td>
                        </tr>
                    </table>
                </form>
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
            document.asignains.submit();
        }

        function validaform(){
            if (document.asignains.proyecto.selectedIndex == 0 || document.asignains.periodo.selectedIndex == 0 || document.asignains.zona.selectedIndex == 0) {
                document.asignains.proc.value = 0;
                return false;
            }
            else {
                document.asignains.proc.value = 1;
                return true;
            }
        }

        $(document).ready(function(){


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


