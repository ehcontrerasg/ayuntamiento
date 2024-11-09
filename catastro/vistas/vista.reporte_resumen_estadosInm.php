<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script src="../../js/jquery-3.2.1.min.js"></script>


        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../js/repResEstadosInm.js?0"></script>


    </head>

    <h3 class="panel-heading" style=" background-color:#A349A3; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px"
        align="center">Reporte Resumen Estados Inmuebles</h3>
    <div class="flexigrid" style="width:1120px">
        <div class="mDiv">

            <div>Filtros de B&uacute;squeda Resumen Estados Inmuebles</div>
            <div style="background-color:rgb(255,255,255)">
                <form idaction="#" name="report_form" class="form" onsubmit="return false">
                    <table width="100%">
                        <tr>

                            <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br/>
                                <select name="proyecto"  id='selPro' class="input" required=""></select>
                            </td>

                            <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Estado<br/>
                                <select name="estado" id='selEstado' class="input" required>

                                </select>
                            </td>
                            <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Periodo<br/>
                                <select name="periodo" id='selPeriodo' class="input" >

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
        <div class="container-fluid">
            <div class="row">
                <table id="dataTable" width="98%" class="row-border hover stripe">

                </table>
            </div>
        </div>
    </div>

    </article>
    </section>

    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

