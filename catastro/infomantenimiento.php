<?php
session_start();

$coduser = $_SESSION['codigo'];
$cod = ($_GET['cod_sis']);
$per = ($_GET['periodo']);
$per1 = $_POST['per'];
$cod1 = $_POST['cod'];
$proc = $_POST['proc'];
$cod_inm = $_POST['cod_inm'];
$cod_pro = $_POST['cod_pro'];
$cod_cat = $_POST['cod_cat'];
$cod_per = $_POST['cod_per'];
$cod_cli = $_POST['cod_cli'];
$dir_act1 = $_POST['dir_act'];
$dir_nue1 = $_POST['dir_nue'];
$uso_act1 = $_POST['uso_act'];
$uso_nue1 = $_POST['uso_nue'];
$act_act1 = $_POST['act_act'];
$cup_nue = $_POST['cup_nue'];

$tar_act1 = $_POST['tar_act'];
$tar_act_alc = $_POST['tar_act_alc'];
$tar_act1_med = $_POST['tar_act_med'];

$act_nue1 = $_POST['act_nue'];
$uni_act1 = $_POST['uni_act'];
$uni_nue1 = $_POST['uni_nue'];
$unh_act1 = $_POST['unh_act'];
$unh_nue1 = $_POST['uni_hab'];
$und_act1 = $_POST['und_act'];
$und_nue1 = $_POST['uni_des'];
$est_act1 = $_POST['est_act'];
$est_nue1 = $_POST['est_nue'];
$nom_act1 = $_POST['nom_act'];
$nom_nue1 = $_POST['nom_nue'];
$tid_act1 = $_POST['tid_act'];
$tid_nue1 = $_POST['tid_nue'];
$doc_act1 = $_POST['doc_act'];
$doc_nue1 = $_POST['doc_nue'];
$tel_act1 = $_POST['tel_act'];
$tel_nue1 = $_POST['tel_nue'];
$observa1 = $_POST['observa'];

$tarifa = $_POST['selTarifa'];
$tarifa_alc = $_POST['selTarifaAlc'];
$tarifa_med = $_POST['selTarifaMed'];

$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/Static_Tabstrip.css">
<link rel="stylesheet" href="../flexigrid/style.css" />
<link rel="stylesheet" type="text/css" href="../flexigrid/css/flexigrid/flexigrid.css">
<script type="text/javascript" src="../flexigrid/lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../flexigrid/flexigrid.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$(".botonExcel").click(function(event) {
			$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
			$("#FormularioExportacion").submit();
	});
	});
</script>
</head>
<body bgcolor="#E0E0E0" onLoad="cierra_ventana();">
<div id="content">
<?php
if ($cod == ''){
    $cod = $cod_inm;
    $per = $cod_per;
}
 $sql = "SELECT I.ID_PROYECTO, M.ID_INMUEBLE, I.ID_PROCESO, I.CATASTRO, ASI.ID_PERIODO, C.CODIGO_CLI, I.DIRECCION DIRACT, M.DIRECCION DIRNUE, A.ID_USO USOACT, M.ID_USO USONUE,
A.ID_ACTIVIDAD ACTACT, M.ID_ACTIVIDAD ACTNUE, I.TOTAL_UNIDADES, M.UNIDADES, I.UNIDADES_HAB, M.UNIDADES_H, I.UNIDADES_DES, M.UNIDADES_D,
I.ID_ESTADO ESTACT, M.ID_ESTADO ESTNUE, O.ALIAS NOMBRE_CLI, M.CLIENTE, C.TIPO_DOC, M.ID_TIPO_DOC, C.DOCUMENTO DOCACT, M.DOCUMENTO DOCNUE,
C.TELEFONO, M.TELEFONO_CLI, M.OBSERVACIONES, SI.CONSEC_TARIFA, TA.DESC_TARIFA,
(SELECT TA2.DESC_TARIFA FROM SGC_TT_SERVICIOS_INMUEBLES SI2, SGC_TP_TARIFAS TA2
WHERE TA2.CONSEC_TARIFA=SI2.CONSEC_TARIFA
AND SI2.COD_SERVICIO IN (2,4)
AND SI2.COD_INMUEBLE=M.ID_INMUEBLE) DESC_TAR_ALC,
(SELECT TA2.CONSEC_TARIFA FROM SGC_TT_SERVICIOS_INMUEBLES SI2, SGC_TP_TARIFAS TA2
WHERE TA2.CONSEC_TARIFA=SI2.CONSEC_TARIFA
AND SI2.COD_SERVICIO IN (2,4)
AND SI2.COD_INMUEBLE=M.ID_INMUEBLE) CONSEC_TAR_ALC,

(SELECT TA2.DESC_TARIFA FROM SGC_TT_SERVICIOS_INMUEBLES SI2, SGC_TP_TARIFAS TA2
WHERE TA2.CONSEC_TARIFA=SI2.CONSEC_TARIFA
AND SI2.COD_SERVICIO IN (11)
AND SI2.COD_INMUEBLE=M.ID_INMUEBLE) DESC_TAR_MED,
(SELECT TA2.CONSEC_TARIFA FROM SGC_TT_SERVICIOS_INMUEBLES SI2, SGC_TP_TARIFAS TA2
WHERE TA2.CONSEC_TARIFA=SI2.CONSEC_TARIFA
AND SI2.COD_SERVICIO IN (11)
AND SI2.COD_INMUEBLE=M.ID_INMUEBLE) CONSEC_TAR_MED
,
NVL(RC.LIMITE_MIN,0) LIMMIN ,
NVL(RC.LIMITE_MAX,9999999) LIMMAX,
SI.CUPO_BASICO

FROM SGC_TT_MANTENIMIENTO M, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_CLIENTES C, SGC_TT_CONTRATOS O, SGC_TT_ASIGNACION ASI,
sgc_Tt_servicios_inmuebles SI, SGC_TP_TARIFAS TA, SGC_TP_RANGOS_CATEGORIA RC
WHERE M.ID_INMUEBLE = I.CODIGO_INM AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND C.CODIGO_CLI(+) = O.CODIGO_CLI
AND O.CODIGO_INM(+) = I.CODIGO_INM AND M.ID_INMUEBLE = $cod AND ASI.ID_PERIODO = $per AND ASI.ID_TIPO_RUTA='1' AND ASI.ID_INMUEBLE=I.CODIGO_INM
AND O.FECHA_FIN (+) IS NULL
AND SI.COD_INMUEBLE(+)=I.CODIGO_INM
AND SI.COD_SERVICIO (+) IN (1,3)
AND TA.CONSEC_TARIFA(+)=SI.CONSEC_TARIFA
AND TA.CATEGORIA=RC.DESCRIPCION(+)
AND RC.PROYECTO=I.ID_PROYECTO
AND RC.CONCEPTO=SI.COD_SERVICIO
AND M.ACTUALIZADO='N'
";
//echo $sql;
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
while (oci_fetch($stid)) {
    $cod_inm = oci_result($stid, 'ID_INMUEBLE');
    $proyecto = oci_result($stid, 'ID_PROYECTO');
    $cod_pro = oci_result($stid, 'ID_PROCESO');
    $cod_cat = oci_result($stid, 'CATASTRO');
    $cod_per = oci_result($stid, 'ID_PERIODO');
    $cod_cli = oci_result($stid, 'CODIGO_CLI');
    $dir_act = oci_result($stid, 'DIRACT');
    $dir_nue = oci_result($stid, 'DIRNUE');
    $uso_act = oci_result($stid, 'USOACT');
    $uso_nue = oci_result($stid, 'USONUE');
    $act_act = oci_result($stid, 'ACTACT');
    $act_nue = oci_result($stid, 'ACTNUE');
    $uni_act = oci_result($stid, 'TOTAL_UNIDADES');
    $uni_nue = oci_result($stid, 'UNIDADES');
    $unh_act = oci_result($stid, 'UNIDADES_HAB');
    $unh_nue = oci_result($stid, 'UNIDADES_H');
    $und_act = oci_result($stid, 'UNIDADES_DES');
    $und_nue = oci_result($stid, 'UNIDADES_D');
    $est_act = oci_result($stid, 'ESTACT');
    $est_nue = oci_result($stid, 'ESTNUE');
    $nom_act = oci_result($stid, 'NOMBRE_CLI');
    $nom_nue = oci_result($stid, 'CLIENTE');
    $tid_act = oci_result($stid, 'TIPO_DOC');
    $tid_nue = oci_result($stid, 'ID_TIPO_DOC');
    $doc_act = oci_result($stid, 'DOCACT');
    $doc_nue = oci_result($stid, 'DOCNUE');
    $tel_act = oci_result($stid, 'TELEFONO');
    $tel_nue = oci_result($stid, 'TELEFONO_CLI');
    $observa = oci_result($stid, 'OBSERVACIONES');
    $act_cod_tar = oci_result($stid, 'CONSEC_TARIFA');
    $act_des_tar = oci_result($stid, 'DESC_TARIFA');
    $act_cod_tar_alc = oci_result($stid, 'CONSEC_TAR_ALC');
    $act_des_tar_alc = oci_result($stid, 'DESC_TAR_ALC');
    $act_cod_tar_med = oci_result($stid, 'CONSEC_TAR_MED');
    $act_des_tar_med = oci_result($stid, 'DESC_TAR_MED');
    $cupo_basico_act = oci_result($stid, 'CUPO_BASICO');
    $limite_min_act = oci_result($stid, 'LIMMIN');
    $limite_max_act = oci_result($stid, 'LIMMAX');

}oci_free_statement($stid);
$ope_uni = str_replace('-','/',$ope);





if($tarifa<>''){
      $query = " SELECT NVL(RC.LIMITE_MIN,0) LIMITEMIN, NVL(RC.LIMITE_MAX,9999999) LIMITEMAX FROM SGC_TP_RANGOS_CATEGORIA RC,SGC_TP_TARIFAS TA
 WHERE TA.CONSEC_TARIFA=$tarifa AND TA.CATEGORIA=RC.DESCRIPCION
 AND TA.COD_SERVICIO=RC.CONCEPTO
 AND TA.COD_PROYECTO=RC.PROYECTO";
    $stid = oci_parse($link, $query);
    oci_execute($stid, OCI_DEFAULT);
    while (oci_fetch($stid)) {
        $limite_max_act = oci_result($stid, 'LIMITEMAX');
        $limite_min_act = oci_result($stid, 'LIMITEMIN');
    }
    oci_free_statement($stid);
}



?>
<!--<form name="mantenimiento" action="infomantenimiento.php?cod=<?//php echo $cod;?>&ope=<?//php echo $ope_uni;?>&per=<?//php echo $per;?>" method="post" onSubmit="return infomante();">-->
<form name="mantenimiento" action="infomantenimiento.php?cod=<?php echo $cod;?>&per=<?php echo $per;?>" method="post" onSubmit="return infomante();">
<?php
if($proc == 1){
    $sql="SELECT NVL(SUM(SALDO),0) SALDO FROM(
        SELECT SUM(O.IMPORTE-O.APLICADO)SALDO
        FROM SGC_TT_OTROS_RECAUDOS O
        WHERE ESTADO = 'A'
        AND O.CONCEPTO=1
        AND O.INMUEBLE = '$cod_inm'
        union
        SELECT NVL(SUM(IMPORTE-VALOR_APLICADO),0) SALDO
        FROM SGC_TT_SALDO_FAVOR F
        WHERE ESTADO = 'A'
        AND INM_CODIGO = '$cod_inm')";
    $stid = oci_parse($link, $sql);
    oci_execute($stid, OCI_DEFAULT);
    while (oci_fetch($stid)) {
        $saldo = oci_result($stid, 'SALDO');
    }oci_free_statement($stid);

    if($saldo <= 30){

        $fecha=date("D M j G:i:s T Y");
        $query = "UPDATE SGC_TT_MANTENIMIENTO SET ACTUALIZADO = 'S'";
        if($dir_nue1 != ''){ $query .= ", DIRECCION = '$dir_nue1'" ; }
        if($uso_nue1 != ''){ $query .= ", ID_USO = '$uso_nue1'" ; }
        if($act_nue1 != ''){ $query .= ", ID_ACTIVIDAD = '$act_nue1'" ; }
        if($uni_nue1 != ''){ $query .= ", UNIDADES = '$uni_nue1'" ; }
        if($unh_nue1 != ''){ $query .= ", UNIDADES_H = '$unh_nue1'" ; }
        if($und_nue1 != ''){ $query .= ", UNIDADES_D = '$und_nue1'" ; }
        if($est_nue1 != ''){ $query .= ", ID_ESTADO = '$est_nue1'" ; }
        if($nom_nue1 != ''){ $query .= ", CLIENTE = '$nom_nue1'" ; }
        if($tid_nue1 != ''){ $query .= ", ID_TIPO_DOC = '$tid_nue1'" ; }
        if($doc_nue1 != ''){ $query .= ", DOCUMENTO = '$doc_nue1'" ; }
        if($tel_nue1 != ''){ $query .= ", TELEFONO_CLI = '$tel_nue1'" ; }
        if($observa1 != ''){ $query .= ", OBSERVACIONES = '$observa1 actualizado por $coduser el $fecha' "; }
        $query .= "WHERE ID_PERIODO = $cod_per AND ID_INMUEBLE = '$cod_inm'";
        $stid = oci_parse($link, $query);
        $result = oci_execute($stid);
        oci_free_statement($stid);
        //echo $query."<br>";

        $query = "SELECT SEC_ACTIVIDAD FROM SGC_TP_ACTIVIDADES WHERE ID_USO ='$uso_nue1' AND ID_ACTIVIDAD = '$act_nue1'";
        $stid = oci_parse($link, $query);
        oci_execute($stid, OCI_DEFAULT);
        while (oci_fetch($stid)) {
            $sec_act = oci_result($stid, 'SEC_ACTIVIDAD');
        }oci_free_statement($stid);

       //COMIENZO CODIGO QUE DEBE SER BORRADO UNA VEZ EL SISTEMA NIXON DEJE DE FUNCIONAR


        $unidesdesh=$uni_nue1-$unh_nue1;

        $unidesdesh=$uni_nue1-$unh_nue1;
        $query = "UPDATE SGC_TT_INMUEBLES SET USUARIO_MODIFICACION='$coduser',FECHA_MODIFICACION=SYSDATE";
        if($dir_nue1 != ''){ $query .= " ,DIRECCION = '$dir_nue1'" ; }
        if($uso_nue1 != '' || $act_nue != ''){ $query .= " ,SEC_ACTIVIDAD = '$sec_act'" ; }
        if($uni_nue1 != ''){ $query .= " ,TOTAL_UNIDADES = '$uni_nue1'" ; }
        if($est_nue1 != ''){ $query .= " ,ID_ESTADO = '$est_nue1'" ; }
        if($unh_nue1 != ''){ $query .= ",UNIDADES_HAB  = '$unh_nue1'".",UNIDADES_DES= '$unidesdesh'" ; }

        $query .= " WHERE CODIGO_INM = '$cod_inm'";
        $stid = oci_parse($link, $query);
        $result = oci_execute($stid);
        oci_free_statement($stid);

        $query = "INSERT INTO SGC_TT_OBSERVACIONES_INM(CONSECUTIVO,ASUNTO,CODIGO_OBS,DESCRIPCION,FECHA,USR_OBSERVACION,INM_CODIGO)
                    VALUES (SGC_S_INMOBS.NEXTVAL,'Validacion Web','MCA','$observa1',SYSDATE,'$coduser', $cod_inm)";

        $stid = oci_parse($link, $query);
        $result = oci_execute($stid);
        oci_free_statement($stid);



        $query = "UPDATE SGC_TT_SERVICIOS_INMUEBLES SET USR_MODIFICACION='$coduser'";
        if($uni_nue1 != ''){ $query .= " ,UNIDADES_TOT = '$uni_nue1', CONSUMO_MINIMO=(CUPO_BASICO*$unh_nue1) " ; }
        if($unh_nue1 != ''){ $query .= ",UNIDADES_HAB  = '$unh_nue1'".",UNIDADES_DESH= '$unidesdesh'" ; }

        $query .= " WHERE COD_INMUEBLE = '$cod_inm' AND COD_SERVICIO IN (1,2,3,4)";
        $stid = oci_parse($link, $query);
        $result = oci_execute($stid);
        oci_free_statement($stid);






      //  echo $query."<br>";




        if($tarifa<>''){
            $query = "UPDATE SGC_TT_SERVICIOS_INMUEBLES SI SET SI.CONSEC_TARIFA='$tarifa',
                      USR_MODIFICACION='$coduser'
                    WHERE SI.COD_INMUEBLE='$cod_inm' AND SI.COD_SERVICIO IN (1,3)";
            $stid = oci_parse($link, $query);
            $result = oci_execute($stid);
            oci_free_statement($stid);

        }




        if($tarifa_alc<>''){
             $query = "UPDATE SGC_TT_SERVICIOS_INMUEBLES SI SET SI.CONSEC_TARIFA='$tarifa_alc',
                      USR_MODIFICACION='$coduser'
                    WHERE SI.COD_INMUEBLE='$cod_inm' AND SI.COD_SERVICIO IN (2,4)";
            $stid = oci_parse($link, $query);
            $result = oci_execute($stid);
            oci_free_statement($stid);

        }

        if($tarifa_med<>''){
          echo  $query = "UPDATE SGC_TT_SERVICIOS_INMUEBLES SI SET SI.CONSEC_TARIFA='$tarifa_med',
                      USR_MODIFICACION='$coduser'
                    WHERE SI.COD_INMUEBLE='$cod_inm' AND SI.COD_SERVICIO IN (11)";
            $stid = oci_parse($link, $query);
            $result = oci_execute($stid);
            oci_free_statement($stid);

        }

        $query = "UPDATE  SGC_TT_SERVICIOS_INMUEBLES SI
                SET SI.CUPO_BASICO=$cup_nue,
                SI.CONSUMO_MINIMO=SI.UNIDADES_HAB*$cup_nue,
                SI.USR_MODIFICACION='$coduser'
                WHERE SI.COD_INMUEBLE='$cod_inm'
                AND SI.COD_SERVICIO IN (1,2,3,4)";
                    $stid = oci_parse($link, $query);
                    $result = oci_execute($stid);
                    oci_free_statement($stid);


    }
    else{
        echo "
            <script type='text/javascript'>
            if(confirm('ERROR: El inmueble tiene un saldo a favor activo. No se pueden modificar los datos')){
                window.close();
            }
            </script>";
    }

}
?>
<div class="flexigrid" style="width:1700px">
	<div class="mDiv">
    	<div>
        	Verificaci&oacute;n Datos Inmueble <?php echo $cod;?>
        </div>
        <div style="background-color:rgb(255,255,255)">
            <table width="100%">
            	<tr>
       				<td class="cabecera2" colspan="11">&nbsp;&nbsp;&raquo;&nbsp;Datos del Predio</td>
        		</tr>
    			<tr>
                	<td style="text-align:right">C&oacute;digo Sistema:</td>  
					<td style="color:rgb(255,0,0); font-size:14px"><?php echo $cod;?></td>
                    <td style="text-align:right">Id Proceso:</td>  
                    <td style="color:rgb(255,0,0); font-size:14px"><?php echo $cod_pro;?></td>
                    <td style="text-align:right">Catastro:</td>  
                    <td style="color:rgb(255,0,0); font-size:14px"><?php echo $cod_cat;?></td>
                </tr>
                <tr>
            		<td style="text-align:right">Direcci&oacute;n Actual:</td>
                    <td class="textlabel"><?php echo $dir_act;?></td>
                    <td style="text-align:right">Nueva Direcci&oacute;n:</td>
                    <td><input type="text" class="textbox" value="<?php echo strtoupper($dir_nue);?>" size="15" name="dir_nue"></td>
                    <td style="text-align:right">Uso Actual:</td>
                    <td class="textlabel"><?php echo $uso_act;?></td>
                    <td style="text-align:right">Nuevo Uso:</td>
                    <td><input type="text" class="textbox" onblur="recarga();" value="<?php if($uso_nue1<>''){echo strtoupper($uso_nue1);}else{echo strtoupper($uso_nue);}  ?>" size="15" name="uso_nue"></td>
                </tr>
                <tr>
                    <td style="text-align:right">Actividad Actual:</td>
                    <td class="textlabel"><?php echo $act_act;?></td>
                    <td style="text-align:right">Nueva Actividad:</td>
                    <td><input type="text" class="textbox" value="<?php echo strtoupper($act_nue);?>" size="15" name="act_nue"></td>
                    <td style="text-align:right">Unidades Actual:</td>
                    <td class="textlabel"><?php echo $uni_act;?></td>
                    <td style="text-align:right">Nueva Unidad (T - H - D):</td>
                    <td>
                        <input type="text" class="textbox" value="<?php echo strtoupper($uni_nue);?>" size="2" name="uni_nue">
                        <input type="text" class="textbox" value="<?php echo strtoupper($unh_nue);?>" size="2" name="uni_hab">
                        <input type="text" class="textbox" value="<?php echo strtoupper($und_nue);?>" size="1" name="uni_des">
                    </td>
                </tr>
                <tr>
                 	<td style="text-align:right">Estado Actual:</td>
                    <td class="textlabel"><?php echo $est_act;?></td>
                    <td style="text-align:right">Nueva Estado:</td>
                    <td><input type="text" class="textbox" value="<? echo strtoupper($est_nue);?>" size="15" name="est_nue"></td>
				
					<td style="text-align:right">Tarifa Actual Agua:</td>
                    <td class="textlabel"><?php echo $act_cod_tar.'-'.$act_des_tar;?></td>
                    <td style="text-align:right">Nueva tarifa:</td>
                    <td><select name="selTarifa" class="textbox" onchange="recarga();" ><option></option>
                            <?php
                            if($uso_nue1<>''){
                                $uso=$uso_nue1;
                            }else{
                                $uso=$uso_nue;
                            }
                            $sql = "SELECT T.DESC_TARIFA, T.CONSEC_TARIFA FROM SGC_TP_TARIFAS T
                                    WHERE T.COD_PROYECTO='$proyecto'
                                    AND T.COD_SERVICIO = ( SELECT SI.COD_SERVICIO
                                    FROM SGC_TT_SERVICIOS_INMUEBLES SI WHERE SI.COD_INMUEBLE=$cod_inm AND T.COD_SERVICIO=SI.COD_SERVICIO
                                    AND SI.COD_SERVICIO IN (1,3)  )
                                    AND T.COD_USO='$uso'
                                    AND T.VISIBLE='S'";
                            $stid = oci_parse($link, $sql);
                            oci_execute($stid, OCI_DEFAULT);
                            while (oci_fetch($stid)) {
                                $cod_tarifa = oci_result($stid, 'CONSEC_TARIFA') ;
                                $desc_tarifa= oci_result($stid, 'DESC_TARIFA') ;
                                if($cod_tarifa == $tarifa) echo "<option value='$cod_tarifa' selected>$desc_tarifa</option>\n";
                                else echo "<option value='$cod_tarifa'>$desc_tarifa</option>\n";
                            }oci_free_statement($stid);
                            ?>
                        </select></td>


                </tr>


                <tr>




                    <td style="text-align:right">Tarifa Actual Alcantarillado:</td>
                    <td class="textlabel"><?php echo $act_cod_tar_alc.'-'.$act_des_tar_alc;?></td>
                    <td style="text-align:right">Nueva tarifa Alcantarillado:</td>
                    <td><select name="selTarifaAlc" class="textbox" ><option></option>
                            <?php
                            if($uso_nue1<>''){
                                $uso=$uso_nue1;
                            }else{
                                $uso=$uso_nue;
                            }
                            $sql = "SELECT T.DESC_TARIFA, T.CONSEC_TARIFA FROM SGC_TP_TARIFAS T
                                    WHERE T.COD_PROYECTO='$proyecto'
                                    AND T.COD_SERVICIO = ( SELECT SI.COD_SERVICIO
                                    FROM SGC_TT_SERVICIOS_INMUEBLES SI WHERE SI.COD_INMUEBLE=$cod_inm AND T.COD_SERVICIO=SI.COD_SERVICIO
                                    AND SI.COD_SERVICIO IN (2,4)  )
                                    AND T.COD_USO='$uso'
                                    AND T.VISIBLE='S'";
                            $stid = oci_parse($link, $sql);
                            oci_execute($stid, OCI_DEFAULT);
                            while (oci_fetch($stid)) {
                                $cod_tarifa_alc = oci_result($stid, 'CONSEC_TARIFA') ;
                                $desc_tarifa_alc= oci_result($stid, 'DESC_TARIFA') ;
                                if($cod_tarifa_alc == $tarifa_alc) echo "<option value='$cod_tarifa_alc' selected>$desc_tarifa_alc</option>\n";
                                else echo "<option value='$cod_tarifa_alc'>$desc_tarifa_alc</option>\n";
                            }oci_free_statement($stid);
                            ?>
                        </select></td>


                    <td style="text-align:right">Tarifa Actual Medidor:</td>
                    <td class="textlabel"><?php echo $act_cod_tar_med.'-'.$act_des_tar_med;?></td>
                    <td style="text-align:right">Nueva tarifa Medidor:</td>
                    <td><select name="selTarifaMed" class="textbox" ><option></option>
                            <?php
                            if($uso_nue1<>''){
                                $uso=$uso_nue1;
                            }else{
                                $uso=$uso_nue;
                            }
                            ECHO $sql = "SELECT T.DESC_TARIFA, T.CONSEC_TARIFA FROM SGC_TP_TARIFAS T
                                    WHERE T.COD_PROYECTO='$proyecto'
                                    AND T.COD_SERVICIO = ( SELECT SI.COD_SERVICIO
                                    FROM SGC_TT_SERVICIOS_INMUEBLES SI WHERE SI.COD_INMUEBLE=$cod_inm AND T.COD_SERVICIO=SI.COD_SERVICIO
                                    AND SI.COD_SERVICIO IN (11)  )
                                    AND T.COD_USO='$uso'
                                    AND T.VISIBLE='S'";
                            $stid = oci_parse($link, $sql);
                            oci_execute($stid, OCI_DEFAULT);
                            while (oci_fetch($stid)) {
                                $cod_tarifa_med = oci_result($stid, 'CONSEC_TARIFA') ;
                                $desc_tarifa_med= oci_result($stid, 'DESC_TARIFA') ;
                                if($cod_tarifa_med == $tarifa_med) echo "<option value='$cod_tarifa_med' selected>$desc_tarifa_med</option>\n";
                                else echo "<option value='$cod_tarifa_med'>$desc_tarifa_med</option>\n";
                            }oci_free_statement($stid);
                            ?>
                        </select></td>

                </tr>

                <?if($act_cod_tar<>""){ ?>
                <tr>
                    <td style="text-align:right">Cupo basico Act:</td>F
                    <td class="textlabel"><?php echo $cupo_basico_act;?></td>
                    <td style="text-align:right">Nuevo Cupo Basico:</td>
                    <td><input type="number" class="textbox" step="any"  min="<?php echo $limite_min_act;?>" max="<?php echo $limite_max_act;?>" value="<?php echo strtoupper(cup_nue);?>" size="15" name="cup_nue"></td>
                    <td style="text-align:right"></td>
                    <td class="textlabel"></td>
                    <td style="text-align:right"></td>
                    <td></td>
                </tr>
                <?}?>
                <tr>



                <tr>
       				<td class="cabecera2" colspan="19">&nbsp;&nbsp;&raquo;&nbsp;Datos del Cliente</td>
        		</tr>
                <tr>
                 	<td style="text-align:right">Cliente Actual:</td>
                    <td class="textlabel"><?php echo $nom_act;?></td>
                    <td style="text-align:right">Nuevo Cliente:</td>
                    <td><input type="text" class="textbox" value="<? echo strtoupper($nom_nue);?>" size="15" name="nom_nue"></td>
                    <td style="text-align:right">Tipo Doc Actual:</td>
                    <td class="textlabel"><?php echo $tid_act;?></td>
                    <td style="text-align:right">Nuevo Tipo Doc:</td>
                    <td><input type="text" class="textbox" value="<? echo strtoupper($tid_nue);?>" size="15" name="tid_nue"></td>
                </tr>
                <tr>
                    <td style="text-align:right">Documento Actual:</td>
                    <td class="textlabel"><?php echo $doc_act;?></td>
                    <td style="text-align:right">Nuevo Documento:</td>
                    <td><input type="text" class="textbox" value="<? echo strtoupper($doc_nue);?>" size="15" name="doc_nue"></td>
                    <td style="text-align:right">Tel&eacute;fono Actual:</td>
                    <td class="textlabel"><?php echo $tel_act;?></td>
                    <td style="text-align:right">Nuevo Tel&eacute;fono:</td>
                    <td><input type="text" class="textbox" value="<? echo $tel_nue;?>" size="15" name="tel_nue"></td>
                </tr>
                <tr>
                    <td style="text-align:right">Observaciones:</td>
                    <td colspan="9"><textarea class="textarea" name="observa" cols="100" rows="5"><?php echo strtoupper($observa)?></textarea></td>
                </tr>
                 <tr>
                    <td class="cabecera2" colspan="19">&nbsp;&nbsp;&raquo;&nbsp;Fotograf&iacute;as</td>
                </tr>
                <?php
                $sql = "SELECT COUNT(URL_FOTO) CANTIDAD FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod AND ID_PERIODO = 
                (select MAX(ID_PERIODO) FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod)";
                $stid = oci_parse($link, $sql);
                oci_execute($stid, OCI_DEFAULT);
                while (oci_fetch($stid)) {
                    $can_foto = oci_result($stid, 'CANTIDAD');
                }oci_free_statement($stid);

                if($can_foto > 0){
                    $sql = "SELECT URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod AND ID_PERIODO = (select MAX(ID_PERIODO) FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod)";
                    $stid = oci_parse($link, $sql);
                    oci_execute($stid, OCI_DEFAULT);
                    ?>
                    <tr>
                    <?php    
                    while (oci_fetch($stid)) {
                        $url_foto = oci_result($stid, 'URL_FOTO');
                        $url_foto = substr($url_foto, 3); 
                        ?>
                        <tr class="titulo" bgcolor="#DCDCDA">
                        <td align="center" height="200" >
                            <img width="200" heigth="200"  align="center" onClick="rotate(90,<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>,'<? echo '../../../webservice/fotos_sgc/'.$url_foto;?>');"   id="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" name="<? echo str_replace('-','',str_replace('/','',str_replace('.','',$url_foto))); ?>" src="../../../webservice/fotos_sgc/<? echo $url_foto; ?>">

                        </td>
<!--                            <td colspan="2"><img src="../../webservice/fotos_sgc/--><?// echo $url_foto; ?><!--" width="200" heigth="200"></td>-->
                        <?php
                    }
                    ?>
                    </tr>
                <?php    
                }
                else{
                    ?>
                    <tr>
                        <td class="textlabel" colspan="10">No se encontraron fotografias para el inmueble <?php echo $cod; ?></td>
                    </tr>
                <?php   
                }
                ?>
                <tr>
                 	<td colspan="8" align="center">
						<input type="submit" value="Actualizar" name="Actualizar" class="boton">
                    	<input type="hidden" name="proc" value="<?php echo $proc;?>">
                        <input type="hidden" name="cod_inm" value="<?php echo $cod_inm;?>">
                        <input type="hidden" name="cod_per" value="<?php echo $cod_per;?>">
                        <input type="hidden" name="cod_cli" value="<?php echo $cod_cli;?>">
                        <input type="hidden" name="dir_act1" value="<?php echo $dir_act1;?>">
                        <input type="hidden" name="dir_nue1" value="<?php echo $dir_nue1;?>">
                        <input type="hidden" name="uso_act1" value="<?php echo $uso_act1;?>">
                        <input type="hidden" name="uso_nue1" value="<?php echo $uso_nue1;?>">
                        <input type="hidden" name="act_act1" value="<?php echo $act_act1;?>">
                        <input type="hidden" name="act_nue1" value="<?php echo $act_nue1;?>">
                        <input type="hidden" name="uni_act1" value="<?php echo $uni_act1;?>">
                        <input type="hidden" name="uni_nue1" value="<?php echo $uni_nue1;?>">
                        <input type="hidden" name="unh_act1" value="<?php echo $unh_act1;?>">
                        <input type="hidden" name="unh_nue1" value="<?php echo $unh_nue1;?>">
                        <input type="hidden" name="und_act1" value="<?php echo $und_act1;?>">
                        <input type="hidden" name="und_nue1" value="<?php echo $und_nue1;?>">
                        <input type="hidden" name="est_act1" value="<?php echo $est_act1;?>">
                        <input type="hidden" name="est_nue1" value="<?php echo $est_nue1;?>">
                        <input type="hidden" name="nom_act1" value="<?php echo $nom_act1;?>">
                        <input type="hidden" name="nom_nue1" value="<?php echo $nom_nue1;?>">
                        <input type="hidden" name="tid_act1" value="<?php echo $tid_act1;?>">
                        <input type="hidden" name="tid_nue1" value="<?php echo $tid_nue1;?>">
                        <input type="hidden" name="doc_act1" value="<?php echo $doc_act1;?>">
                        <input type="hidden" name="doc_nue1" value="<?php echo $doc_nue1;?>">
                        <input type="hidden" name="tel_act1" value="<?php echo $tel_act1;?>">
                        <input type="hidden" name="tel_nue1" value="<?php echo $tel_nue1;?>">
                        <input type="hidden" name="observa1" value="<?php echo $observa1;?>">
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
<script language="javascript">	

function infomante(){
		document.mantenimiento.proc.value = 1;
		return true;
}

function recarga() {
    //document.asignacion_mant.operario.selectedIndex = 0;
    document.mantenimiento.submit();
}

function cierra_ventana(){
	<?
	if( $proc == 1){
		?>
		setTimeout("window.opener.document.location.reload();self.close()",1000);
		<?
	}
	?>
}

var angulotot=0;
function rotate(angle,ob,url){
    angle=angle+angulotot;
    angle=angle%360;
    angulotot=angle;
    var rotation = Math.PI * angle / 180;
    var costheta = Math.cos(rotation);
    var sintheta = Math.sin(rotation);
    if(!window.ActiveXObject){
        ob.style.webkitTransform ='rotate('+angle+'deg)';
        ob.style.khtmlTransform ='rotate('+angle+'deg)';
        ob.style.MozTransform='rotate('+angle+'deg)';
        ob.style.OTransform='rotate('+angle+'deg)';
        ob.style.transform='rotate('+angle+'deg)';
    }else{
        ob.style.filter="progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand')";
        ob.filters.item(0).M11 = costheta;
        ob.filters.item(0).M12 = -sintheta;
        ob.filters.item(0).M21 = sintheta;
        ob.filters.item(0).M22 = costheta;
        ob.style.top= ((this.parentNode.offsetHeight/2)-(this.offsetHeight/2))+'px';
        ob.style.left=  ((this.parentNode.offsetWidth/2)-(this.offsetWidth/2))+'px';
    }

}




</script>