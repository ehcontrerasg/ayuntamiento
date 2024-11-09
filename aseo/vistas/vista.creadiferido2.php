<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
if ($verificarPermisos==true): ?>


    <?php
    session_start();
    include '../../clases/class.deuda_cero.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    if($_GET['codinmueble']){
        $codinmueble = $_GET['codinmueble'];
        $proyecto = $_GET['proyecto'];
        $nom_cli = $_GET['nom_cli'];
    }
    if($_POST['codinmueble']){
        $codinmueble = $_POST['codinmueble'];
        $proyecto = $_POST['proyecto'];
        $nom_cli = $_POST['nom_cli'];
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Proceso de Facturación</title>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../css/tabber_procesofac.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="../js/datInmueblesDif.js?2"></script>
        <style type="text/css">
            .input{
                border:0px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .font{
                float:right;
            }
        </style>
    </head>
    <body style="margin-top:-25px" >
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#A77B24; color:#FFFFFF; font-size:18px; width:1120px" align="center">Generaci&oacute;n de Diferidos</h3>
        <div style="text-align:center; width:1120px;">
            <form id="FMFactPend" name="FMFactPend" method="post">
                <div class="flexigrid" style="width:1120px">
                    <div class="mDiv">
                        <div align="left">Generaci&oacute;n de Diferidos  <font class="font">C&oacute;digo Inmueble:&nbsp;
                                <input class="input" type="number" min="0" value="<?php echo $codinmueble ?>"  required id="codinmueble" name="codinmueble"  style="width: 150px" placeholder="Ingrese el Inmueble"/>&nbsp;<i class="fa fa-search"></i></font>
                            <input type="hidden" id="inpdatfacinm" value="<?php echo $codinmueble;?>">
                        </div>
                    </div>
                </div>
            </form>

            <?php
            $l=new deuda_cero();
            if($codinmueble != ""){
                $registros = $l->datosInmueble($codinmueble);
                while (oci_fetch($registros)) {
                    $zona_inm=oci_result($registros, 'ID_ZONA');
                    $urba_inm=oci_result($registros, 'DESC_URBANIZACION');
                    $dir_inm=oci_result($registros, 'DIRECCION');
                    $fecalta_inm=oci_result($registros, 'FEC_ALTA');
                    $estado_inm=oci_result($registros, 'ID_ESTADO');
                    $catastro_inm=oci_result($registros, 'CATASTRO');
                    $proceso_inm=oci_result($registros, 'ID_PROCESO');
                    $cod_cli=oci_result($registros, 'CODIGO_CLI');
                    $con_cli=oci_result($registros, 'ID_CONTRATO');
                    $nom_cli=oci_result($registros, 'ALIAS');
                    $doc_cli=oci_result($registros, 'DOCUMENTO');
                    $tel_cli=oci_result($registros, 'TELEFONO');
                    $serial=oci_result($registros, 'SERIAL');
                    $proyecto=oci_result($registros, 'ID_PROYECTO');
                }
                if($zona_inm == ""){
                    echo "
			<script type='text/javascript'>
			showDialog('Error de Datos','El inmueble N&deg; $codinmueble no existe.<br>Por Favor Intente Nuevamente','error');
			</script>";
                }
                else{
                    ?>
                    <div style="display: block;float: left">
                        <table id="flexinmueblediferido" style="display:none">
                        </table>
                    </div>
                    <div style="display: block;float: left">
                        <table id="flexdatdiferidos" style="display:none">
                        </table>
                    </div>
                    <?php
                }
            }
            ?>
            <script type="text/javascript">
                inicioInmueblesDiferido();
                flexyInmDif();
                flexydiferidos();
            </script>
        </div>
    </div>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
