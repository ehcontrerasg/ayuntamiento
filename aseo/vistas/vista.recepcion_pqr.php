<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require'../../clases/classPqrsAseo.php';
    include('../../destruye_sesion.php');
    //pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_GET['cod_inmueble'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <!-- <link href="../../css/bootstrap.min.css?1" rel="stylesheet"> -->
        <link href="../../font-awesome/css/font-awesome.min.css?1" rel="stylesheet" />
        <link rel="stylesheet" href="../../flexigrid/style.css?1" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../alertas/dialog_box.css?1" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.min.css">
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
            /** Allendy Valdez Pillier {{ */
            #solCerradas{
                color: #000040;
                padding: 5px;
                border-radius: 5px 5px 5px 5px;
                -moz-border-radius: 5px 5px 5px 5px;
                -webkit-border-radius: 5px 5px 5px 5px;
                border: 1px solid black;

            }
            #solCerradas:hover{
                cursor:pointer;
                cursor: hand;
                background-color: #dedede;
            }
            #tblRecpPqr thead{
                color: white;
                background-color: #0a0a3e;
                border: 1px solid white;
            }
            #tblRecpPqr{
                background-color: #e2e2e2;
                font-size: 12px;
            }
            /** Allendy Valdez Pillier }}*/
        </style>
    </head>
    <body style="margin-top:-25px" onload="document.getElementById('cod_inmueble').focus();">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <?php
        if($cod_inmueble != ''){
            $c = new PQRs();
            $resultado = $c->obtenerDatosCliente2($cod_inmueble);
            while (oci_fetch($resultado)) {
                $cod_inm = oci_result($resultado, 'CODIGO_INM');
                $est_inm = oci_result($resultado, 'ID_ESTADO');
            }oci_free_statement($resultado);
            $c = new PQRs();
            $resultado = $c->totalfacven ($cod_inmueble);
            while (oci_fetch($resultado)) {
                $deuda = oci_result($resultado, 'DEUDA');
            }oci_free_statement($resultado);

            if(isset($cod_inm)){
                echo "<script>window.location='vista.recepcion_pqr2.php?cod_inmueble=$cod_inm';</script>";
                /*if($deuda > 0) echo "<script>window.location='vista.pagos.php?cod_inmueble=$cod_inm';</script>";*/

            }
            else{
                echo "<script>showDialog('Error Cargando Datos','El Inmueble N&deg; $cod_inmueble No Existe.<br><br>Por Favor Verifique.','error',3);</script>";
            }
        }
        ?>
        <h3 class="panel-heading" style=" background-color:#A77B25; color:#FFFFFF; font-size:18px; width:1120px" align="center">Recepcion de Reclamos, Solicitudes y Consultas</h3>
        <form name="recepcion" action="vista.recepcion_pqr.php" method="get">
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table width="1231">
                            <tr>
                                <td width="207">
                                    C&oacute;digo Sistema<br />
                                    <input class="input" type="text" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" id="cod_inmueble" placeholder="Ingrese el Inmueble"   onchange="recarga();"/>
                                </td>
                                <!--td width="115" align="right" id="botonpago" style="display:block;" >
                                    <a href="vista.recepcion_pqr_cat.php" style="font-size:13px; vertical-align:middle">
                                        <i class="fa fa-floppy-o fa-2x"></i>&nbsp;&nbsp;Crear Solicitud Ubicaci&oacute;n Catastral
                                    </a>
                                </td>
                                <td>
                                    <span id="btnSolCerradas" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-down"></span>Solicitudes Cerradas</span>
                                </td-->
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <br>
    </div>
    <!--div id="conSolCerradas" class="container-fluid">
        <div style="width: 100%" id="paginacion">
        </div>
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?1"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?1"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js?1"></script>
        <script type="text/javascript" src="../js/con_recp_pqr.js?<?//php echo time()?>"></script>
        <script type="text/javascript" src="../../js/spin.min.js"></script>
    </div-->
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

