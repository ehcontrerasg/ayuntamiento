<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <?php
    session_start();
    include '../clases/class.admin_pagos.php';
    include '../../destruye_sesion.php';

    /*$cod_inmueble=$_POST['cod_inmueble'];
    $sortname = $_POST['sortname'];
    $sortorder = $_POST['sortorder'];

    if (!$sortname) $sortname = "COD_PARAMETRO";
    if (!$sortorder) $sortorder = "DESC";
    $sort = "ORDER BY $sortname $sortorder";
    $fname="COD_PARAMETRO";
    $tname="SGC_TP_PARAMETROS";*/
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <style type="text/css">
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
                height:16px;
            }
            .table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            .th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:1px solid #ccc;
                border-left:0px solid #ccc;
                border-bottom:0px solid #ccc;
                border-top:0px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
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
            .font{
                float:right;
            }
            .select{
                border:0px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .btn-info{
                color:#fff;
                background-color:#5bc0de;
                border-color:#46b8da;
            }
            .btn{
                display:inline-block;
                padding:6px 12px;
                margin-bottom:0;
                font-size:14px;
                font-weight:400;
                line-height:1.42857143;
                text-align:center;
                white-space:nowrap;
                vertical-align:middle;
                cursor:pointer;
                -webkit-user-select:none;
                -moz-user-select:none;
                -ms-user-select:none;
                user-select:none;
                background-image:none;
                border:1px solid transparent;
                border-radius:4px
            }
        </style>
    </head>
    <body style="margin-top:-25px" >
    <div id="content" style="padding:0px; width:400px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:400px" align="center">Lista de Cajas</h3>
    </div>
    <table id="flex1" style="display:block;">
        <thead>
        <tr>
            <th align="center" class="th" style="width:112px; color:#000000">Id Caja</th>
            <th align="center" class="th" style="width:400px; color:#000000">Entidad</th>
            <th align="center" class="th" style="width:400px; color:#000000">Punto</th>
            <th align="center" class="th" style="width:112px; color:#000000">Caja</th>
        </tr>
        </thead>
        <tbody>
        <tr >
            <?php
            //$cont = 0;
            $l=new AdminstraPagos();
            $registros=$l->listadoCajas();
            while (oci_fetch($registros)) {
            //$cont++;
            $id_caja = oci_result($registros, 'ID_CAJA');
            $id_entidad = oci_result($registros, 'ID_ENTIDAD');
            $desc_entidad = oci_result($registros, 'DESC_ENTIDAD');
            $id_punto= oci_result($registros, 'COD_VIEJO');
            $desc_punto = oci_result($registros, 'DESC_PUNTO');
            $caja = oci_result($registros, 'DESC_CAJA');
            ?>
            <td class="tda" style="width:112px; text-align:center; color:#000000"><b><?php echo $id_caja?></b></td>
            <td class="tda" style="width:400px; text-align:center; color:#000000"><?php echo $id_entidad.' - '.$desc_entidad?></td>
            <td class="tda" style="width:400px; text-align:center; color:#000000"><?php echo $id_punto.' - '.$desc_punto?></td>
            <td class="tda" style="width:112px; text-align:center; color:#000000"><?php echo $caja?></td>
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

