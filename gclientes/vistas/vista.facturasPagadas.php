<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>FACTURAS PAGADAS</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/facturasPagadas.js?<?echo time();?>"></script>
        <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />

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

    </head>
    <body>
    <header class="cabeceraTit fondGraCli">
        Facturas Pagadas
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="formFactPagadas">
                <span class="datoForm col1">
                    <span class="titDato numCont1">Proyecto:</span>
                    <span class="inpDatp numCont1"><select required name="proyecto" tabindex="4" id="proyecto" ></select></span>
                    <span class="titDato numCont1">Periodo inicial:</span>
                    <span class="inpDatp numCont1"><input required name="periodo_inicial" tabindex="2" id="periodo_inicial" placeholder="Periodo inicial" type="text"></span>
                    <span class="titDato numCont1">Periodo final:</span>
                    <span class="inpDatp numCont1"><input required name="periodo_final" tabindex="2" id="periodo_final" placeholder="Periodo final" type="text"></span>
                    <span class="titDato numCont1">Grupo:</span>
                    <span class="inpDatp numCont2"><select name="grupo" tabindex="4" id="grupo" ></select></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Código Sistema:</span>
                    <span class="inpDatp numCont1"><input name="codigo_sistema" tabindex="3" id="zona" placeholder="Código Sistema" type="text"></span>
                    <span class="titDato numCont1">Zona:</span>
                    <span class="inpDatp numCont1"><input name="zona" tabindex="3" id="zona" placeholder="Zona" type="text"></span>
                    <span class="titDato numCont1">RNC:</span>
                    <span class="inpDatp numCont2"><input name="documento" tabindex="3" id="documento" placeholder="Documento" type="number"></span>
                </span>



                <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormGraCli" tabindex="9" >
            </span>

            </form>

            <div>
                <div class="container-fluid">
                    <div class="row" style="margin-left: auto" width="98%" >
                        <table id="dataTable" width="98%" class="row-border hover stripe" style="margin-top: 2px" ">
                        </table>
                    </div>
                </div>

            </div>

        </article>
    </section>

    <footer>
    </footer>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

