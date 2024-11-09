<?php
session_start();

include '../clases/class.reportes_lectura.php';
include '../../destruye_sesion_cierra.php';


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <link rel="stylesheet" href="../../css/tablas_catastro.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--Librería de Bootstrap-->
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <!--Librería Datatable-->
    <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
    <script src="../../archivo/js/datatables/extensions/Scroller/js/dataTables.scroller.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/jszip.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
    <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>
    <!--pagina-->
    <script type="text/javascript" src="../js/diferidosCorteReco.js?<?echo time()?>"></script>
    <style type="text/css">
        .input{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            height:16px;
            font-weight:normal;
        }
    </style>

</head>
<body>
<form id="dif_cortereco" onsubmit="return false">
    <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1220px; margin-top:-5px" align="center">Diferidos de Corte y Reconexi&oacute;n</h3>
    <div class="flexigrid" style="width:1220px">
        <div class="mDiv">
            <div>Filtros de B&uacute;squeda Diferidos Corte y Reconexi&oacute;n</div>
            <div style="background-color:rgb(255,255,255)">
                <table width="100%">
                    <tr>
                        <td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                            <select name="proyecto" class="input" required><option></option>
                                <?php
                                $l=new Reportes();
                                $registros=$l->seleccionaAcueducto();
                                while (oci_fetch($registros)) {
                                    $cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;
                                    $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;
                                    if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                    else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                }oci_free_statement($registros);
                                ?>
                            </select>
                        </td>
                        <td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Fecha Generaci&oacute;n<br />
                            Desde:&nbsp;&nbsp;<input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>" class="input" required />
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            Hasta:&nbsp;&nbsp;<input type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>" class="input"  required/>
                        </td>
                        <td width="22%" style=" border:1px solid #EEEEEE; text-align:center">
                            <input type="submit" value="Generar Reporte" name="Generar" class="btn btn btn-INFO" style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8); border-color:#336699; color:#336699;">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
<div id="divReporte" style="margin-top: 5px"></div>
<div class="flexigrid" style="width: 1220px; margin-top: 5px" >
    <table id="dataTable" width="98%" class="row-border hover stripe"  >
        <thead>
        <tr>
            <th>Código</th>
            <th>Fecha Pago</th>
            <th>Concepto</th>
            <th>Forma de Pago</th>
            <th>Usuario</th>
            <th>Importe</th>
            <th>Inmueble</th>

        </tr>
        </thead>
    </table>
</div>

</body>
</html>