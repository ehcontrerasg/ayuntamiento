<?
include_once('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!--autocompltar -->
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <!--Datatable-->
    <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
    <!--pagina-->
    <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
    <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <script type="text/javascript" src="../js/repInmDeudaAcumulada.js?1"></script>
    <style type="text/css">
        .buttons-print {
            background-color: #000040;

        }
        .buttons-excel {
            background-color: #000040;

        }   .buttons-pdf {
            background-color: #000040;

        }
        .input{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            height:16px;
            font-weight:normal;
        }
        .inputn{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            height:16px;
            width:40px;
            font-weight:normal;
        }
        .inputm{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            height:16px;
            width:80px;
            font-weight:normal;
        }
    </style>
</head>
<body>

<form id='repdeuAcuInm' onsubmit="return false">
    <h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px; margin-top: 20px" align="center">Reporte Deuda acumulada</h3>
    <div class="flexigrid" style="width:1120px">
        <div class="mDiv">
            <div>Filtro de búsqueda deuda acumulada</div>
            <div style="background-color:rgb(255,255,255)">
                <table width="100%">
                    <tr>
                        <td style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Codigo de inmuble<br />
                           &nbsp;<input type="text" name="codInmuble" id="codInmuble"  required class="input"/>
                        </td>
                        <td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                            <select name="proyecto" id='selPro' class="input" required=""><option></option>
                        </td>
                        <td width="19%" style=" border:1px solid #EEEEEE" rowspan="2" align="center">
                            <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit"
                                    name="Buscar" id="Buscar"class="btn btn btn-INFO"><i class="fa fa-search"></i><b>&nbsp;Consultar</b>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row" style="margin-left: auto" width="98%" >
                <table id="dataTable" width="98%" class="row-border hover stripe" style="margin-top: 2px" ">
                </table>
            </div>
        </div>

    </div>
</form>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

