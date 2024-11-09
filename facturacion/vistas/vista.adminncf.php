<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css " />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!--autocompltar -->
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <link href="../../css/css.css " rel="stylesheet" type="text/css" />
    <link href="../../css/general.css" rel="stylesheet" type="text/css" />
    <!--Datatables-->
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
    <script type="text/javascript" src="../js/adminncf.js?5"></script>

</head>
<body >
<header class="cabeceraTit fondFac">
    Administrar NCF
</header>

<div class="container-fluid">
<div class="row" style="margin-left: auto" width="98%" >
    <table id="dataTable" width="98%" class="row-border hover stripe" style="margin-top: 2px" ">
    </table>
</div>
</div>

<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content" onsubmit="return false" id="actCampos">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Editar campos</h4>
            </div>
            <div class="modal-body">
                <center>
                    <TABLE >
                        <TR>
                            <TD> <label for="codigo-sistema" class="col-2 col-form-label">CONCECUTIVO</label> </TD>
                            <TD> <label for="codigo-archivo" class="col-2 col-form-label">LIMITE</label> </TD>
                        </TR>
                        <TR>
                            <TD>
                                <div class="">
                                    <div class="col-10">
                                        <input class="form-control" type="text" id="consecutivo" name="consecutivo" required>
                                    </div>
                                </div>
                            </TD>
                            <TD>
                                <div class="">
                                    <div class="col-10">
                                        <input class="form-control" type="text" id="limite" name="limite" required>
                                    </div>
                                </div>
                            </TD>
                        </TR>
                    </TABLE>
                </center>
            </div>

            <div class="modal-footer">
                <button type="submit" id="modificar" class="btn btn-primary">Guardar cambios</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
            </div>
        </form>
        </div>
    </div>

</div>
</body>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
