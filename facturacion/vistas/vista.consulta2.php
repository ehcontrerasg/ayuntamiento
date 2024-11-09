<?php
session_start();
require '../clases/class.consultaGeneral.php';
include '../../destruye_sesion.php';
//pasamos variables por post
$coduser = $_SESSION['codigo'];
$proyecto = $_POST['proyecto'];
$codinmueble = $_POST['codinmueble'];
$secini = $_POST['secini'];
$secfin = $_POST['secfin'];
$zonini = $_POST['zonini'];
$zonfin = $_POST['zonfin'];
$procini = $_POST['procini'];
$procfin = $_POST['procfin'];
$urbaniza = $_POST['urbaniza'];
$tipovia = $_POST['tipovia'];
$nomvia = $_POST['nomvia'];
$numcasa = $_POST['numcasa'];
$estado = $_POST['estado'];
$estado_inm = $_POST['estado_inm'];
$codcliente = $_POST['codcliente'];
$nomcliente = $_POST['nomcliente'];
$numdoc = $_POST['numdoc'];
$grupo = $_POST['grupo'];
$tipocli = $_POST['tipocli'];
$numcon = $_POST['numcon'];
$fecinicon = $_POST['fecinicon'];
$fecfincon = $_POST['fecfincon'];
$marca = $_POST['marca'];
$serial = $_POST['serial'];
$emplaza = $_POST['emplaza'];
$metodo = $_POST['metodo'];
$fecinsini = $_POST['fecinsini'];
$fecinsfin = $_POST['fecinsfin'];
$mora = $_POST['mora'];
$totalizador = $_POST['totalizador'];
$concepto = $_POST['concepto'];
$uso = $_POST['uso'];
$actividad = $_POST['actividad'];
$tarifa = $_POST['tarifa'];
$numfac = $_POST['numfac'];
$tipofac = $_POST['tipofac'];
$fecinipag = $_POST['fecinipag'];
$fecfinpag = $_POST['fecfinpag'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Consulta General de Inmuebles</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="../../acordion/style_fac.css" rel="stylesheet" />
    <script src="../../acordion/js/jquery.min.js"></script>
    <script src="../../acordion/js/main.js"></script>
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
    <script src="../../js/ajax.js"></script>
    <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        $(function() {
            $("#secini").autocomplete({
                source: "../datos/datos.sector2.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });

        $(function() {
            $("#secfin").autocomplete({
                source: "../datos/datos.sector2.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });


        $(function() {
            $("#zonini").autocomplete({
                source: "../datos/datos.zona2.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });

        $(function() {
            $("#zonfin").autocomplete({
                source: "../datos/datos.zona2.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });


        $(function() {
            $("#urbaniza").autocomplete({
                source: "../datos/datos.urbaniza.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });

        $(function() {
            $("#nomvia").autocomplete({
                source: "../datos/datos.nomvia.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });

        $(function() {
            $("#numcon").autocomplete({
                source: "../datos/datos.numcon.php",
                minLength: 1,
                html: true,
                open: function(event, ui)
                {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });
    </script>
</head>
<body>
<?php
if (isset($_REQUEST["buscar"])){
    ?>
    <script type="text/javascript">
        window.open('vista.detalle_consulta.php?proyecto=<?php echo $proyecto;?>&codinmueble=<?php echo $codinmueble;?>&secini=<?php echo $secini;?>&secfin=<?php echo $secfin;?>
        &zonini=<?php echo $zonini;?>&zonfin=<?php echo $zonfin;?>&procini=<?php echo $procini;?>&procfin=<?php echo $procfin;?>&urbaniza=<?php echo $urbaniza;?>
        &tipovia=<?php echo $tipovia;?>&nomvia=<?php echo $nomvia;?>&estado=<?php echo $estado;?>&estado_inm=<?php echo $estado_inm;?>&codcliente=<?php echo $codcliente?>
        &nomcliente=<?php echo $nomcliente?>&numdoc=<?php echo $numdoc;?>&grupo=<?php echo $grupo;?>&tipocli=<?php echo $tipocli;?>&numcon=<?php echo $numcon;?>
        &fecinicon=<?php echo $fecinicon;?>&fecfincon=<?php echo $fecfincon;?>&marca=<?php echo $marca;?>&serial=<?php echo $serial;?>&emplaza=<?php echo $emplaza;?>
        &metodo=<?php echo $metodo;?>&fecinsini=<?php echo $fecinsini;?>&fecinsfin=<?php echo $fecinsfin;?>&mora=<?php echo $mora;?>&totalizador=<?php echo $totalizador;?>
        &concepto=<?php echo $concepto;?>&uso=<?php echo $uso;?>&actividad=<?php echo $actividad;?>&tarifa=<?php echo $tarifa;?>&numfac=<?php echo $numfac;?>
        &tipofac=<?php echo $tipofac;?>&fecinipag=<?php echo $fecinipag;?>&fecfinpag=<?php echo $fecfinpag;?>&numcasa=<?php echo $numcasa;?>',	'<?php echo $codinmueble;?>','width=1350, height=660, replace=true');
    </script>
<?php
}
?>
<div id="content" style="margin-top:-25px">
    <form name="consulta" action="vista.consulta.php" method="post" autocomplete="on">
        <h3 class="panel-heading" style="background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Consulta General de Inmuebles</h3>
        <div style="text-align:center">
            <table width="100%">
                <tr>
                    <td align="left" style="font-size:13px"><b>Filtros de B&uacute;squeda</b></td>
                    <td align="right">
                        <button type="submit" name="buscar" id="buscar" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO">
                            <b>Consultar&nbsp;&nbsp;</b><i class="fa fa-search"></i>
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <hr style="border-color:#336699"/>
        <div>
            <ul id="accordion" class="accordion">
                <li>
                    <div class="link"><i class="fa fa-home"></i>B&uacute;squeda Inmueble<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <table width="100%">
                            <tr style="height:10px"></tr>
                            <tr>
                                <td width="19%" align="left" style="font-size:11px"><b>Acueducto</b><br />
                                    <select name='proyecto' id="proyecto" class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Acueducto...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaAcueducto();
                                        while (oci_fetch($stid)) {
                                            $id_proyecto = oci_result($stid, 'ID_PROYECTO') ;
                                            $des_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;
                                            if($id_proyecto == $proyecto) echo "<option value='$id_proyecto' selected>$des_proyecto</option>\n";
                                            else echo "<option value='$id_proyecto'>$des_proyecto</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                                <td width="14%" align="left" style="font-size:11px"><b>C&oacute;digo Sistema</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="codinmueble" id="codinmueble" value="<? echo $codinmueble; ?>"
                                           size="11" maxlength="10" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');">
                                </td>
                                <td width="19%" align="left" style="font-size:11px"><b>Sector</b><br />
                                    Desde&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="secini" id="secini" value="<? echo $secini; ?>"
                                                      size="1" maxlength="2">
                                    &nbsp;&nbsp;&nbsp;Hasta&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="secfin" id="secfin"
                                                                        value="<? echo $secfin; ?>" size="1" maxlength="2">
                                </td>
                                <td width="21%" align="left" style="font-size:11px"><b>Zona</b><br />
                                    Desde&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="zonini" id="zonini" value="<? echo $zonini; ?>" size="2"
                                                      maxlength="3">
                                    &nbsp;&nbsp;&nbsp;Hasta&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="zonfin" id="zonfin"
                                                                        value="<? echo $zonfin; ?>" size="2" maxlength="3">
                                </td>
                                <td width="27%" align="left" style="font-size:11px"><b>Id Proceso</b><br />
                                    Desde&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="procini" id="procini" value="<? echo $procini; ?>"
                                                      size="9" maxlength="11" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');">
                                    &nbsp;&nbsp;&nbsp;Hasta&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="text" name="procfin" id="procfin"
                                                                        value="<? echo $procfin; ?>" size="9" maxlength="11" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');">
                                </td>
                            </tr>
                        </table>
                        <p></p>
                        <table width="100%">
                            <tr>
                                <td width="17%" align="left" style="font-size:11px" ><b>Urbanizaci&oacute;n</b><br />
                                    <input type="text" id="urbaniza" name="urbaniza" value="<? echo $urbaniza?>" class="btn btn-default btn-sm dropdown-toggle">
                                </td>
                                <td width="16%" align="left" style="font-size:11px" ><b>Tipo V&iacute;a</b><br />
                                    <select name='tipovia' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Tipo V&iacute;a...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaTipoVia();
                                        while (oci_fetch($stid)) {
                                            $id_tvia = oci_result($stid, 'ID_TIPO_VIA') ;
                                            $des_dvia = oci_result($stid, 'DESC_TIPO_VIA') ;
                                            if($id_tvia == $tipovia) echo "<option value='$id_tvia' selected>$des_dvia</option>\n";
                                            else echo "<option value='$id_tvia'>$des_dvia</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                                <td width="16%" align="left" style="font-size:11px" ><b>Nombre V&iacute;a</b><br />
                                    <input type="text" id="nomvia" name="nomvia" value="<? echo $nomvia?>" class="btn btn-default btn-sm dropdown-toggle">
                                </td>
                                <td width="8%" align="left" style="font-size:11px" ><b>N&uacute;mero Inm</b><br />
                                    <input type="text" id="numcasa" name="numcasa" value="<? echo $numcasa?>" class="btn btn-default btn-sm dropdown-toggle" style="width:70px">
                                </td>
                                <td width="35%" align="left" style="font-size:11px"><b>Estado Inmueble</b><br />
                                    <input type="radio" name="estado" value="T" <? if ($estado=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todos&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="estado" value="A" <? if ($estado=='A'){ echo "checked";}?>>&nbsp;&nbsp;Activos&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="estado" value="I" <? if ($estado=='I'){ echo "checked";}?>>&nbsp;&nbsp;Inactivos&nbsp;&nbsp;&nbsp;
                                    <select name='estado_inm' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Estado...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaEstado();
                                        while (oci_fetch($stid)) {
                                            $id_estado = oci_result($stid, 'ID_ESTADO') ;
                                            $des_estado = oci_result($stid, 'DESC_ESTADO') ;
                                            if($id_estado == $estado_inm) echo "<option value='$id_estado' selected>$des_estado</option>\n";
                                            else echo "<option value='$id_estado'>$des_estado</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height:10px"></tr>
                        </table>
                    </ul>
                </li>
                <li>
                    <div class="link"><i class="fa fa-user"></i>B&uacute;squeda Cliente<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <table width="100%">
                            <tr style="height:10px"></tr>
                            <tr>
                                <td width="11%" align="left" style="font-size:11px"><b>C&oacute;digo Cliente</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="codcliente" id="codcliente" value="<? echo $codcliente; ?>"
                                           size="11" maxlength="6" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');">
                                </td>
                                <td width="27%" align="left" style="font-size:11px"><b>Nombre Cliente</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="nomcliente" id="nomcliente" value="<? echo $nomcliente; ?>"
                                           size="40" maxlength="40" onKeyUp="this.value=this.value.replace(/[^A-ZñÑ ]/ig, '');">
                                </td>
                                <td width="13%" align="left" style="font-size:11px"><b>C&eacute;dula</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="numdoc" id="numdoc" value="<? echo $numdoc; ?>"
                                           size="13" maxlength="13" onKeyUp="this.value=this.value.replace(/[^0-9-]/ig, '');">
                                </td>
                                <td width="31%" align="left" style="font-size:11px"><b>Grupo de Empresas</b><br />
                                    <select name='grupo' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Grupo...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaGrupo();
                                        while (oci_fetch($stid)) {
                                            $id_grupo = oci_result($stid, 'COD_GRUPO') ;
                                            $des_grupo = oci_result($stid, 'DESC_GRUPO') ;
                                            if($id_grupo == $grupo) echo "<option value='$id_grupo' selected>$des_grupo</option>\n";
                                            else echo "<option value='$id_grupo'>$des_grupo</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                                <td width="18%" align="left" style="font-size:11px"><b>Tipo Cliente</b><br />
                                    <input type="radio" name="tipocli" value="T" <? if ($tipocli=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todos
                                    <input type="radio" name="tipocli" value="G" <? if ($tipocli=='G'){ echo "checked";}?>>&nbsp;&nbsp;Grandes Clientes
                                </td>
                            </tr>
                        </table>
                        <p></p>
                        <table width="100%">
                            <tr>
                                <td width="13%" height="24" align="left" style="font-size:11px"><b>N&deg; Contrato</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="numcon" id="numcon" value="<? echo $numcon; ?>"
                                           size="11" maxlength="11">
                                </td>
                                <td width="46%" align="left" style="font-size:11px"><b>Fecha Inicio de Contrato</b><br />
                                    Desde&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="date" format="dd/mm/yyyy" name="fecinicon" id="fecinicon"
                                                      value="<? echo $fecinicon; ?>" style="width:150px; height:27px">
                                    &nbsp;&nbsp;&nbsp;Hasta &nbsp;<input class="btn btn-default btn-sm dropdown-toggle" type="date" format="dd/mm/yyyy" name="fecfincon"
                                                                         id="fecfincon" value="<? echo $fecfincon; ?>" style="width:150px; height:27px">
                                </td>
                                <td width="41%">
                                </td>
                            </tr>
                            <tr style="height:10px"></tr>
                        </table>
                    </ul>
                </li>
                <li>
                    <div class="link"><i class="fa fa-dashboard"></i>B&uacute;squeda Medidor<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <table width="100%">
                            <tr style="height:10px"></tr>
                            <tr>
                                <td width="19%" height="24" align="left" style="font-size:11px"><b>Marca</b><br />
                                    <select name='marca' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Marca...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaMarcaMed();
                                        while (oci_fetch($stid)) {
                                            $id_marca = oci_result($stid, 'CODIGO_MED') ;
                                            $des_marca = oci_result($stid, 'DESC_MED') ;
                                            if($id_marca == $marca) echo "<option value='$id_marca' selected>$des_marca</option>\n";
                                            else echo "<option value='$id_marca'>$des_marca</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                                <td width="13%" height="24" align="left" style="font-size:11px"><b>Serial</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="serial" id="serial" value="<? echo $serial; ?>"
                                           size="11" maxlength="11">
                                </td>
                                <td width="22%" height="24" align="left" style="font-size:11px"><b>Emplazamiento</b><br />
                                    <select name='emplaza' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Emplazamiento...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaEmplazamiento();
                                        while (oci_fetch($stid)) {
                                            $id_emplaza = oci_result($stid, 'COD_EMPLAZAMIENTO') ;
                                            $des_emplaza = oci_result($stid, 'DESC_EMPLAZAMIENTO') ;
                                            if($id_emplaza == $emplaza) echo "<option value='$id_emplaza' selected>$des_emplaza</option>\n";
                                            else echo "<option value='$id_emplaza'>$des_emplaza</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                                <td width="22%" height="24" align="left" style="font-size:11px"><b>M&eacute;todo Suministro</b><br />
                                    <select name='metodo' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione M&eacute;todo...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaMetodo();
                                        while (oci_fetch($stid)) {
                                            $id_metodo = oci_result($stid, 'COD_SUMINISTRO') ;
                                            $des_metodo = oci_result($stid, 'DESC_SUMINISTRO') ;
                                            if($id_metodo == $metodo) echo "<option value='$id_metodo' selected>$des_metodo</option>\n";
                                            else echo "<option value='$id_metodo'>$des_metodo</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <p></p>
                        <table width="100%">
                            <tr>
                                <td align="left" style="font-size:11px;"><b>Fecha de Instalaci&oacute;n</b><br />
                                    Desde&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="date" format="dd/mm/yyyy" name="fecinsini" id="fecinsini"
                                                      value="<? echo $fecinsini; ?>" style="width:150px; height:27px">
                                    &nbsp;&nbsp;&nbsp;Hasta&nbsp;<input class="btn btn-default btn-sm dropdown-toggle" type="date" format="dd/mm/yyyy" name="fecinsfin"
                                                                        id="fecinsfin" value="<? echo $fecinsfin; ?>" style="width:150px; height:27px">
                                </td>
                            </tr>
                            <tr style="height:10px"></tr>
                        </table>
                    </ul>
                </li>
                <li>
                    <div class="link"><i class="fa fa-qrcode"></i>B&uacute;squeda C&oacute;digos<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <table width="100%">
                            <tr style="height:10px"></tr>
                            <tr>
                                <td width="25%" align="left" style="font-size:11px"><b>Mora</b><br />
                                    <input type="radio" name="mora" value="T" <? if ($mora=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todos&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="mora" value="M" <? if ($mora=='M'){ echo "checked";}?>>&nbsp;&nbsp;Con Mora&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="mora" value="S" <? if ($mora=='S'){ echo "checked";}?>>&nbsp;&nbsp;Sin Mora&nbsp;&nbsp;&nbsp;
                                </td>
                                <td width="75%" align="left" style="font-size:11px"><b>Totalizadores</b><br />
                                    <input type="radio" name="totalizador" value="I" <? if ($totalizador=='I'){ echo "checked";}?> checked>
                                    &nbsp;&nbsp;Todos los Inmuebles&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="totalizador" value="T" <? if ($totalizador=='T'){ echo "checked";}?>>
                                    &nbsp;&nbsp;Totalizadores&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="totalizador" value="D" <? if ($totalizador=='D'){ echo "checked";}?>>
                                    &nbsp;&nbsp;Dependientes&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="totalizador" value="A" <? if ($totalizador=='A'){ echo "checked";}?>>
                                    &nbsp;&nbsp;Totalizadores y Dependientes&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                            <tr style="height:10px"></tr>
                        </table>
                    </ul>
                </li>
                <li>
                    <div class="link"><i class="fa fa-file"></i>B&uacute;squeda Factura<i class="fa fa-chevron-down"></i></div>
                    <ul class="submenu">
                        <table width="100%">
                            <tr style="height:10px"></tr>
                            <tr>
                                <!--td align="left" style="font-size:11px"><b>Concepto</b><br />
									<select name='concepto' class='btn btn-default btn-sm dropdown-toggle'>
										<option value="">Seleccione Concepto...</option>
										<?php
                                /*$c = new Consulta();
                                $stid = $c->seleccionaConcepto();
                                while (oci_fetch($stid)) {
                                    $id_concepto = oci_result($stid, 'COD_SERVICIO') ;
                                    $des_concepto = oci_result($stid, 'DESC_SERVICIO') ;
                                    if($id_concepto == $concepto) echo "<option value='$id_concepto' selected>$des_concepto</option>\n";
                                    else echo "<option value='$id_concepto'>$des_concepto</option>\n";
                                }oci_free_statement($stid);*/
                                ?>
									</select>
								</td-->
                                <td align="left" style="font-size:11px"><b>Uso</b><br />
                                    <select name='uso' class='btn btn-default btn-sm dropdown-toggle' onChange="load(this.value)">
                                        <option value="" selected>Seleccione Uso...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaUso();
                                        while (oci_fetch($stid)) {
                                            $id_uso = oci_result($stid, 'ID_USO') ;
                                            $des_uso = strtoupper(oci_result($stid, 'DESC_USO'));
                                            if($id_uso == $uso) echo "<option value='$id_uso' selected>$des_uso</option>\n";
                                            else echo "<option value='$id_uso'>$des_uso</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                                <td align="left" style="font-size:11px"><b>Actividad</b><br />
                                    <select id="actividad" name='actividad' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="" selected>Seleccione Actividad...</option>
                                    </select>
                                </td>
                                <td align="left" style="font-size:11px"><b>Tarifa</b><br />
                                    <select name='tarifa' class='btn btn-default btn-sm dropdown-toggle'>
                                        <option value="">Seleccione Tarifa...</option>
                                        <?php
                                        $c = new Consulta();
                                        $stid = $c->seleccionaTarifa();
                                        while (oci_fetch($stid)) {
                                            $id_tarifa = oci_result($stid, 'CONSEC_TARIFA');
                                            $des_tarifa = oci_result($stid, 'DESC_TARIFA');
                                            $cod_uso = oci_result($stid, 'COD_USO') ;
                                            if($id_tarifa == $tarifa) echo "<option value='$id_tarifa' selected>$cod_uso - $des_tarifa</option>\n";
                                            else echo "<option value='$id_tarifa'>$cod_uso - $des_tarifa</option>\n";
                                        }oci_free_statement($stid);
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <p></p>
                        <table width="100%">
                            <tr>
                                <td width="12%" height="24" align="left" style="font-size:11px"><b>N&deg; Factura</b><br />
                                    <input class='btn btn-default btn-sm dropdown-toggle' type="text" name="numfac" id="numfac" value="<? echo $numfac; ?>"
                                           size="10" maxlength="10" onKeyUp="this.value=this.value.replace(/[^0-9]/ig, '');">
                                </td>
                                <td width="16%" align="left" style="font-size:11px"><b>Tipo Facturas</b><br />
                                    <input type="radio" name="tipofac" value="T" <? if ($tipofac=='T'){ echo "checked";}?> checked>&nbsp;&nbsp;Todas&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="tipofac" value="V" <? if ($tipofac=='V'){ echo "checked";}?>>&nbsp;&nbsp;Vencidas&nbsp;&nbsp;&nbsp;
                                </td>
                                <td width="46%" align="left" style="font-size:11px"><b>Fecha Ultimo Pago</b><br />
                                    Desde&nbsp;<input class='btn btn-default btn-sm dropdown-toggle' type="date" format="dd/mm/yyyy" name="fecinipag" id="fecinipag"
                                                      value="<? echo $fecinipag; ?>" style="width:150px; height:27px">
                                    &nbsp;&nbsp;&nbsp;Hasta&nbsp;<input class="btn btn-default btn-sm dropdown-toggle" type="date" format="dd/mm/yyyy" name="fecfinpag"
                                                                        id="fecfinpag" value="<? echo $fecfinpag; ?>" style="width:150px; height:27px">
                                </td>
                            </tr>
                        </table>
                    </ul>
                </li>
            </ul>
        </div>
    </form>
</div>
</body>
</html>