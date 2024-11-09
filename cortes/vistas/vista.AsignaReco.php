<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

<?php
session_start();
include_once ('../../include.php');
require'../../clases/class.reconexion.php';
include('../../destruye_sesion.php');
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];
$sector=$_POST['sector'];
$tipo=$_POST['tipo'];
$operario=$_POST['operario'];
$ruta=$_POST['ruta'];
$desasignar_loc=$_POST['desasignar_loc'];
$proc=$_POST['proc'];
$fecPla=$_POST['fecPla'];
$contratista = $_POST['selCon'];
//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../js/combos_asigna_lotes.js"></script>
    <script type="text/javascript" src="../js/AsignaReco.js?<?echo time();?>"></script>
</head>
<body>
<div id="content">
    <?php
    if (isset($_REQUEST["Asignar"])){
        $proc = 1;
        if($fecini != "" || $sector != ""){
            $asignaciones = count($operario);
            $vacios=0;
            for ($j = 0; $j < $asignaciones; $j++){
                $oper_asig = $operario[$j];
                if (trim($oper_asig)=="" ){
                    $vacios++;
                }//cierra if
            }//cierra for
            if($vacios == $asignaciones){
                echo "
				<script type='text/javascript'>
				showDialog('Advertencia','Debe seleccionar minimo un operario para asignar','warning',2);
				</script>";
            }
            else{
                $i = new Reconexion();
                for ($j = 0; $j <$asignaciones; $j++){
                    $operario_asignado = $operario[$j];
                    if(trim($operario_asignado)<>""){
                        $bandera = $i->setAsignaReco($coduser,$operario_asignado,$sector,$ruta[$j],$fecini,$fecfin,$tipo,$fecPla);
                        if($bandera == FALSE){
                            $error=$i->getMesrror();
                            $coderror=$i->getCoderror();
                            echo"
						<script type='text/javascript'>
						showDialog('Error','Error al asignar la ruta $ruta[$j] CODIGO=$coderror. MENSAJE=$error','error',2);
						</script>";
                        }
                    }

                }
                if($bandera == TRUE){
                    echo "
				<script type='text/javascript'>
				showDialog('Transacci&oacute;n Exitosa','Has asignado las rutas correctamente','success',2);
				</script>";
                }
            }
        }
    }

    if (isset($_REQUEST["Desasignar"])){
        $proc = 1;
        if($fecini != "" || $sector != ""){
            $desasignaciones = count($desasignar_loc);
            if($desasignaciones == 0){
                echo "
				<script type='text/javascript'>
				showDialog('Advertencia','Debe seleccionar minimo una ruta para desasignar','warning',2);
				</script>";
            }
            else{
                for ($j = 0; $j < $desasignaciones; $j++){
                    $itin_desasignada = $desasignar_loc[$j];
                    $i = new Reconexion();
                    $bandera = $i->setDesasignaReco($sector,$fecini,$fecfin,$tipo,$desasignar_loc[$j]);
                    if($bandera == FALSE){
                        $error=$i->getMesrror();
                        $coderror=$i->getCoderror();
                        echo"
					<script type='text/javascript'>
					showDialog('Error','Error al desasignar la ruta $desasignar_loc[$j] CODIGO=$coderror. MENSAJE=$error','error',2);
					</script>";
                    }
                }
                if($bandera == TRUE){
                    echo "
				<script type='text/javascript'>
				showDialog('Transacci&oacute;n Exitosa','Has desasignado las rutas correctamente','success',2);
				</script>";
                }
            }
        }
    }

    ?>

    <form name="asignareco" action="vista.AsignaReco.php" method="post" onsubmit="return validaform();">
        <h3 class="panel-heading" style="background-color:#88A247; font-size:15px; color:#FFFFFF" align="center"><b>Asignaci&oacute;n Reconexiones</b></h3>
        <!--<div style="text-align:center">-->
        <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
            <tr>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b>Acueducto:&nbsp;</b></td>
                <td align="left" bgcolor="#EBEBEB">
                    <select name='proyecto' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();' >
                        <option value="" disabled selected>Seleccione acueducto...</option>
                        <?php
                        $sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO 
					FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
					WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
                        $stid = oci_parse($link, $sql);
                        oci_execute($stid, OCI_DEFAULT);
                        while (oci_fetch($stid)) {
                            $cod_proyecto = oci_result($stid, 'ID_PROYECTO') ;
                            $sigla_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;
                            if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                            else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                        }oci_free_statement($stid);
                        ?>
                    </select>
                </td>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b>Fecha Inicial:&nbsp;</b></td>
                <td><input type="date" name="fecini" value="<? echo $fecini?>" class="form-control" maxlength="10" size="10"/>
                </td>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b>Fecha Final:&nbsp;</b></td>
                <td><input type="date" name="fecfin" value="<? echo $fecfin?>" class="form-control" maxlength="10" size="10" />
                </td>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b> Contratista:&nbsp;</b></td>
                <td><select  name="selCon"  id="selCon"></select>
                </td>
            </tr>
            <tr>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b>Sector:&nbsp;</b></td>
                <td align="left" bgcolor="#EBEBEB">
                    <select name='sector' id='sector' class='btn btn-default btn-sm dropdown-toggle'>
                        <option value="" disabled selected>Seleccione sector...</option>
                        <?php
                        $sql = "SELECT ID_SECTOR
					FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I
					WHERE R.ID_INMUEBLE = I.CODIGO_INM AND I.ID_PROYECTO = '$proyecto'
					GROUP BY ID_SECTOR ORDER BY ID_SECTOR ASC";
                        $stid = oci_parse($link, $sql);
                        oci_execute($stid, OCI_DEFAULT);
                        while (oci_fetch($stid)) {
                            $cod_sector = oci_result($stid, 'ID_SECTOR') ;
                            if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                            else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                        }oci_free_statement($stid);
                        ?>
                    </select>
                </td>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b>Tipo:&nbsp;</b></td>
                <td align="left" bgcolor="#EBEBEB" style="font-size:12px">
                    Todos&nbsp;<input type="radio" name="tipo" value="T" <? if ($tipo=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;
                    Medidos&nbsp;<input type="radio" name="tipo" value="M" <? if ($tipo=='M'){ echo "checked";}?>>&nbsp;&nbsp;
                    No Medidos&nbsp;<input type="radio" name="tipo" value="N" <? if ($tipo=='N'){ echo "checked";}?>>
                </td>
                <td style="font-size:13px" bgcolor="#EBEBEB" align="right"><b>Fecha Planiificacion:&nbsp;</b></td>
                <td><input type="date" name="fecPla" id="fecPla" required value="<? echo $fecPla?>" class="form-control" maxlength="10" size="10" />
                <td colspan="2" align="center">
                    <input type="submit" value="Consultar" name="consultar" id="consultar" style="background-color:#88A247;border-color:#88A247; color:#FFFFFF;">
                </td>
                <input type="hidden" name="proc" value="" />
            </tr>
        </table>
        <!--</div>-->
        <!--<input type="hidden" name="proc" value="<?php// echo $proc;?>">-->
        <?php
        if (isset($_REQUEST['consultar']) || $proc == 1){
            $proc = 1;
            ?>
            <p></p>
            <!--<div style="text-align:center">-->
            <table width="49%" border="1" bordercolor="#CCCCCC" align="left" vspace="12">
                <tr>
                    <td colspan="3" style="font-size:13px; color:#FFFFFF" bgcolor="#88A247" align="center" width="15%">
                        <b>Asignaci&oacute;n Reconexiones</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b>Rutas</b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b>Cantidades</b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b>Operarios</b></td>
                </tr>
                <tr>
                    <?php
                    $from = '';
                    $where_contratista= '';
                    $groupby = '';
                    $select = '';
                    if($contratista!=''){
                        $from = ', SGC_TT_USUARIOS U ';
                        $where_contratista = "  AND NVL(rc.USR_ULT_CORTE,RC.USR_EJE)=U.ID_USUARIO AND U.CONTRATISTA= '$contratista'";
                        $groupby = ' ,CONTRATISTA';
                        $select = ' ,CONTRATISTA';
                    }


                    $cantrutas = 0;
                    $cantlect = 0;
                    $query = "SELECT COUNT(*)CANTIDAD, CONCAT(I.ID_SECTOR,I.ID_RUTA) RUTA".$select."
			FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I,SGC_TT_REGISTRO_CORTES RC ".$from."
			WHERE R.ID_INMUEBLE = I.CODIGO_INM AND  R.USR_EJE  IS NULL AND R.FECHA_EJE IS NULL AND I.ID_PROYECTO = '$proyecto' AND R.ORDEN=RC.ORDEN ".$where_contratista;
                  //if($contratista!='') $query .="   AND NVL(rc.USR_ULT_CORTE,RC.USR_EJE)=U.ID_USUARIO AND U.CONTRATISTA= '$contratista'";
                 //   if($contratista != '') $query .=" AND R.USR_EJE=U.ID_USUARIO AND U.CONTRATISTA = '$contratista'";
                    if($sector != '') $query .= " AND I.ID_SECTOR = '$sector'";
                    if($fecini != '' && $fecfin != '') $query .= " AND R.FECHA_ACUERDO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ANULADO='N'";
                    if($tipo == 'M') $query .= " AND R.SERIAL_MED IS NOT NULL";
                    if($tipo == 'N') $query .= " AND R.SERIAL_MED IS NULL";
                    $query .= " GROUP BY CONCAT(I.ID_SECTOR,I.ID_RUTA)".$groupby." ORDER BY CONCAT(I.ID_SECTOR,I.ID_RUTA) ASC";
//echo $query;
                    $stid = oci_parse($link, $query);
                    oci_execute($stid, OCI_DEFAULT);
                    while (oci_fetch($stid)) {
                    $cant = oci_result($stid, 'CANTIDAD');
                    $ruta = oci_result($stid, 'RUTA');
                    ?>
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="15%"><?php echo $ruta;?></td><input type="hidden" name="ruta[]" value="<?php echo $ruta;?>">
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="15%"><?php echo $cant;?></td><input type="hidden" name="cant[]" value="<?php echo $cant;?>">
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="15%">
                        <select name="operario[]" size="1" class="btn btn-default btn-sm dropdown-toggle"><option></option>
                            <?php
                            /*$queryop = "SELECT ID_USUARIO, NOM_USR, APE_USR
				FROM SGC_TT_USUARIOS U
				WHERE FEC_FIN IS NULL AND RECONEXIONES = 'S' AND U.ID_PROYECTO = '$proyecto'";
                           */
                            $queryop = "SELECT ID_USUARIO, NOM_USR, APE_USR
				FROM SGC_TT_USUARIOS U
				WHERE FEC_FIN IS NULL AND RECONEXIONES = 'S'";
                            if($contratista!='') $queryop .=" AND CONTRATISTA= '$contratista' ";
                            $queryop .=" ORDER BY NOM_USR";
                            $stid2 = oci_parse($link, $queryop);
                            oci_execute($stid2, OCI_DEFAULT);
                            while (oci_fetch($stid2)) {
                                $cod_operario = oci_result($stid2, 'ID_USUARIO');
                                $nom_operario = oci_result($stid2, 'NOM_USR');
                                $ape_operario = oci_result($stid2, 'APE_USR') ;
                                if($cod_operario == $asi_oper[$cont]) echo "<option value='$cod_operario' selected>$cod_operario - $nom_operario $ape_operario</option>\n";
                                else echo "<option value='$cod_operario'>$cod_operario  - $nom_operario $ape_operario</option>\n";
                            }oci_free_statement($stid2);
                            ?>
                        </select></td>
                </tr>
                <?php
                $cantlect += $cant;
                $cantrutas++;
                }oci_free_statement($stid);
                ?>
                <tr>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b><?php echo $cantrutas;?></b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b><?php echo $cantlect;?></b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b><?php echo $cantrutas;?></b></td>
                </tr>
                <tr>
                    <td colspan="3" bgcolor="EBEBEB" align="center">
                        <input type="submit" value="Asignar" name="Asignar" id="Asignar" class="btn btn-primary btn-lg" style="background-color:#88A247;border-color:#88A247">
                    </td>
                </tr>
            </table>
            <table width="49%" border="1" bordercolor="#CCCCCC" align="right" vspace="12">
                <tr>
                    <td colspan="4" style="font-size:13px; color:#FFFFFF" bgcolor="#88A247" align="center" width="15%">
                        <b>Desasignaci&oacute;n Reconexiones</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b>Rutas</b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b>Cantidades</b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b>Operarios</b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><input type="checkbox" name="checktodos"></td>
                </tr>
                <tr>
                    <?php
                    $cantrutasd = 0;
                    $cantlectd = 0;
                     $queryop = "SELECT COUNT(*)CANTIDAD, I.ID_SECTOR, I.ID_RUTA, ID_USUARIO, NOM_USR, APE_USR
            FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U
            WHERE R.ID_INMUEBLE = I.CODIGO_INM AND U.ID_USUARIO = R.USR_EJE AND I.ID_PROYECTO = '$proyecto'";
                    if($contratista!='') $queryop .="   AND U.CONTRATISTA = '$contratista'";
                    if($sector != '') $queryop .= " AND I.ID_SECTOR = '$sector'";
                    if($fecini != '' && $fecfin != '') $queryop .= " AND R.FECHA_ACUERDO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
                    if($tipo == 'M') $queryop .= " AND R.SERIAL_MED IS NOT NULL";
                    if($tipo == 'N') $queryop .= " AND R.SERIAL_MED IS NULL";
                    $queryop .= " AND R.USR_EJE IS NOT NULL AND R.FECHA_EJE IS NULL
            GROUP BY  I.ID_SECTOR, I.ID_RUTA, ID_USUARIO, NOM_USR, APE_USR ORDER BY I.ID_SECTOR, I.ID_RUTA";
                    $stid = oci_parse($link, $queryop);
                    //echo $queryop;
                    oci_execute($stid, OCI_DEFAULT);
                    while (oci_fetch($stid)) {
                    $can_operario_1 = oci_result($stid, 'CANTIDAD');
                    $sec_operario_1 = oci_result($stid, 'ID_SECTOR');
                    $rut_operario_1 = oci_result($stid, 'ID_RUTA');
                    $cod_operario_1 = oci_result($stid, 'ID_USUARIO');
                    $nom_operario_1 = oci_result($stid, 'NOM_USR');
                    $ape_operario_1 = oci_result($stid, 'APE_USR') ;
                    $rut_ope_1 = $sec_operario_1.$rut_operario_1;
                    ?>
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="5%"><? echo $rut_ope_1;?></td>
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="5%"><? echo $can_operario_1;?></td>
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="45%"><? echo $nom_operario_1." ".$ape_operario_1;?></td>
                    <td style="font-size:13px" bgcolor="#FFFFFF" align="center" width="5%"><input type="checkbox" name="desasignar_loc[]" value = "<? echo "$rut_ope_1"?>"></td>
                </tr>
                <?php
                $cantlectd += $can_operario_1;
                $cantrutasd++;
                }oci_free_statement($stid);
                ?>
                <tr>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b><?php echo $cantrutasd;?></b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b><?php echo $cantlectd;?></b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"><b><?php echo $cantrutasd;?></b></td>
                    <td style="font-size:13px" bgcolor="#EBEBEB" align="center" width="15%"></td>
                </tr>
                <tr>
                    <td colspan="4" bgcolor="EBEBEB" align="center">
                        <input type="submit" value="Des-Asignar" name="Desasignar" id="Desasignar" class="btn btn-primary btn-lg" style="background-color:#88A247;border-color:#88A247">
                    </td>
                </tr>
            </table>
            <!--</div>-->
            <?php
        }
        ?>
    </form>
</div>
</body>
</html>
<script language="javascript">
    function recarga() {
        document.asignareco.submit();
    }

    function select(){
        selecionar("<?php echo $contratista;?>");
    }

    function validaform(){
        if (document.asignareco.proyecto.selectedIndex <= 0) {
            showDialog('Advertencia','Debe seleccionar el acueducto','warning',2);
            return false;
        }
        if ((document.asignareco.fecini.value == '' && document.asignareco.fecfin.value == '') && document.asignareco.sector.selectedIndex <= 0) {
            showDialog('Advertencia','Debe seleccionar un sector o un rango de fechas','warning',2);
            return false;
        }
        if (document.asignareco.fecini.value == '' && document.asignareco.fecfin.value != ''){
            showDialog('Advertencia','Debe seleccionar una fecha inicial','warning',2);
            return false;
        }
        if (document.asignareco.fecini.value != '' && document.asignareco.fecfin.value == ''){
            showDialog('Advertencia','Debe seleccionar una fecha final','warning',2);
            return false;
        }
        if (document.asignareco.fecini.value > document.asignareco.fecfin.value){
            showDialog('Advertencia','La fecha inicial debe ser menor a la fecha final','warning',2);
            return false;
        }
        else {
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

    <?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>


</script>