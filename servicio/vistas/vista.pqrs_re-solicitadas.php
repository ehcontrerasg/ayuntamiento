<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/classPqrs.php';
    include '../../destruye_sesion.php';

    $tipo_pqr   = $_POST['tipo_pqr'];
    $motivo_pqr = $_POST['motivo_pqr'];
    $fecini     = $_POST['fecini'];
    $fecfin     = $_POST['fecfin'];
    $proyecto   = $_POST['proyecto'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Reporte PQRs Re-Solicitadas</title>
        <!--JQUERY-->
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
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
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!-- iconos botones   -->
        <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
        <!--estilo pag    -->
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="../css/servicio.css?2" />
        <link href="../css/botonesDataTableServicios.css" rel="stylesheet" />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/pqrs_re-solicitadas.js?<?echo time();?>"></script>
    </head>
    <body>
    <header>
        <div class="cabecera">
            Reporte PQRs Re-Solicitadas dentro de 30 días
        </div>
    </header>
    <section>
        <article>
            <form onSubmit="return false" id="genRepPqrReSolForm">
                <div class="subCabecera">
                    Filtros de Búsqueda
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <select id="selProyEstPqr" name="proyecto" >
                            <option>Proyecto</option>
                            <?php
                            $l         = new PQRs();
                            $registros = $l->seleccionaAcueducto();
                            while (oci_fetch($registros)) {
                                $cod_proyecto   = oci_result($registros, 'ID_PROYECTO');
                                $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO');
                                if ($cod_proyecto == $proyecto) {
                                    echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                } else {
                                    echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                }

                            }
                            oci_free_statement($registros);
                            ?>
                        </select>
                    </div>
                    <div class="contenedor">
                        <select name="tipo_pqr" id="inpTipoPqr" >
                            <option>Tipo PQR:</option>
                            <?php
                            $l         = new PQRs();
                            $registros = $l->seleccionaTipoPqr();
                            while (oci_fetch($registros)) {
                                $cod_tipo = oci_result($registros, 'ID_TIPO_RECLAMO');
                                $des_tipo = oci_result($registros, 'DESC_TIPO_RECLAMO');
                                if ($cod_tipo == $tipo_pqr) {
                                    echo "<option value='$cod_tipo' selected>$des_tipo</option>\n";
                                } else {
                                    echo "<option value='$cod_tipo'>$des_tipo</option>\n";
                                }

                            }
                            oci_free_statement($registros);
                            ?>
                        </select>
                    </div>
                    <div class="contenedor">
                        <select name="motivo_pqr" id="inpMotivoPqr">
                            <option value="0">Motivo PQR:</option>
                        </select>
                    </div>
                    <div class="contenedor">
                        <label>Fecha Inicial: </label>
                        <input type="date" id="inpFecIniEstPqr" class="inputDate" name="fecini" >
                    </div>
                    <div class="contenedor">
                        <label>Fecha Final: </label>
                        <input type="date" id="inpFecFinEstPqr" class="inputDate" name="fecfin" >
                    </div>
                    <div class="contenedor">
                        <button id="butGenEstPqr">Generar Reporte</button>
                    </div>
                </div>
                <div>
                    <input type="hidden" id="inpHidValRes">
                </div>
            </form>
        </article>
        <div class="container-fluid" style="margin-top: 10px">
            <div class="row">
                <table id="dataTable" width="98%" class="row-border hover stripe">
                </table>
            </div>
        </div>
    </section>
    <footer>
    </footer>

  <!--  <script type="text/javascript">
        repEstPqrInicio();
    </script>-->
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

