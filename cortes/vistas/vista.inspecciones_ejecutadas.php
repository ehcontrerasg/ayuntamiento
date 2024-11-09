<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 8/15/2018
 * Time: 11:05 AM

 * */
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset=UTF-8" />
        <title>Inspecciones de Cortes</title>


        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--autocompltar -->
        <script src="../../js/jquery-3.2.1.min.js"></script>
        <!--Datatable-->
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.19/dataTable.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.19/ext/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.flash.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.19/ext/jszip.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/pdfmake.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/vfs_fonts.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.html5.min.js"></script>
        <script type="text/javascript" src="../../js/DataTables-1.10.15/ext/buttons.print.min.js"></script>


        <!-- pagina -->
        <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <link href="../../css/general.css?<?php echo time()?>" rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../css/botonesDataTableCortes.css" rel="stylesheet" />
        <script type="text/javascript" src="../js/InspecionesEjecutadas.js?<?php echo time()?>"></script>


    </head>
    <body>
    <header class="cabeceraTit fondCorte">
        Inspeciones ejecutadas
    </header>

    <section class="contenido">
        <article>

            <form onsubmit="return false" id="rendCorForm" >
              <span class="datoForm col1">
                  <span class="titDato numCont1">Acueducto:</span>
                  <span class="inpInspEje numCont1"><select  required name="proyecto" select id="rendCorSelPro" ></select></span>


                  <span class="titDato numCont1">Contratista:</span>
                <span class="inpInspEje numCont1"> <select tabindex="2"  name="contratista"  select id="rendCorSelContr"></select></span>


                  <span class="titDato numCont1">Fecha Ini:</span>
                  <span class="inpInspEje numCont1"><input size="3"  id="rendCorInpFecIni" required name="fecIni" type="date" ></span>


                  <span class="titDato numCont1">Fecha Fin:</span>
                  <span class="inpInspEje numCont1"><input  size="3"  id="rendCorInpFecFin" required name="fecFin" type="date"  ></span>

                             <span class="titDato numCont1">Reconectado:</span>
                <span class="inpInspEje numCont1"> <select tabindex="2"  name="rec"  select id="rec">


                    </select></span>

              </span>
                <span class="datoForm col1">
                  <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormCorte" tabindex="9">
              </span>
            </form>


        </article>



    </section>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
<!--                    <h4 class="modal-title"></h4>
-->                </div>
                <div class="modal-body">
                    <div id="content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <table id="dataTable" width="98%" class="row-border hover stripe">
            </table>
        </div>
    </div>


    <footer>
    </footer>



    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

