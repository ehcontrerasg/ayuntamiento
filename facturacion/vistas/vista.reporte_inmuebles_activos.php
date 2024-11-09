<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Reporte Inmuebles Activos</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
        <!-- pagina -->
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/general_ImnAct.css?<?PHP echo time()?>" rel="stylesheet" />

        <script type="text/javascript" src="../js/repInmueblesActivos.js?<?PHP echo time()?>"></script>


    </head>
<body>
    <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:100%; margin-top:10px"
        align="center">Reporte Inmuebles Activos
    </h3>
<!--    <div class="flexigrid" style="width:1120px">
        <div class="mDiv">

            <div>Filtros de B&uacute;squeda Resumen Estados Inmuebles</div>
            <div style="background-color:rgb(255,255,255)">
                <form idaction="#" name="report_form" class="form" onsubmit="return false">
                    <table width="100%">
                        <tr>

                            <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br/>
                                <select name="proyecto"  id='selPro' required class="input" ></select>
                            </td>

                            <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Estado<br/>
                                <select name="estado" id='selEstado' required  class="input" required>

                                </select>
                            </td>
                            <td width="52%" style=" border:1px solid #EEEEEE; text-align:center">
                                <input type="submit" value="Generar Reporte" name="Generar"  id="report-btn" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#A349A3; color:#A349A3;">
                            </td>

                        </tr>
                    </table>
                </form>
            </div>

        </div>
    </div>-->
    <div class="container-fluid" style="margin-top: 5px">
        <div class="row">
            <table id="dataTable" width="100%" class="row-border hover stripe">
            </table>
        </div>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

