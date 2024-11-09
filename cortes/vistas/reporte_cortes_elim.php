<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta  charset="UTF-8" />
        <title>Reporte Asignacion de reconexion diaria</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/repCorElim.js"></script>
        <link href="../../css/general.css?1" rel="stylesheet" type="text/css" />


        </head>
    <body>
    <header class="cabeceraTit fondGerencia">
        Reporte De Cortes Eliminados
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genRepCorElim">

                <div class="subCabecera fondGerencia">Filtros de Búsqueda</div>
                <div class="fieldCol">
                    <div ><select tabindex="2" name="proyecto" required select id="repCorElim" >
                        </select></div>
                    <div ><label>Fecha Inicial:</label><input  id="inpFechaIni" name="fechaIni" required  type="date"   ></div>
                    <div ><label>Fecha Final:</label><input  id="inpFechaFin" name="fechaFin" required  type="date"   ></div>
                    <div ><select id="tipo" name="tipo">
                            <option value="0" selected>Todos</option>
                            <option value="1">Medidos</option>
                            <option value="2">No Medidos</option>
                        </select></div>
                    <div>
                        <label for="pdfOut">PDF<input type="radio" name="output" value="rep" checked></label>
                        <label for="excelOut">Excel<input type="radio" name="output" value="excel"></label>
                    </div>
                    <div>
                        <input type="submit" value="Generar" id="butCortElim" class="botonFormulario botFormCorte" tabindex="4">
                    </div>
                </div>


            </form>

            <div>
                <iframe id="ifHojaCortElim" src="" width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="500" style="background-color: #E0E0E0;"></iframe>
            </div>

        </article>
    </section>

    <script>
        inicio();
    </script>
    <footer>
    </footer>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

