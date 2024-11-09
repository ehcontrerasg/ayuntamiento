<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset="UTF-8" />
        <title>Entrada de reportes por Concepto</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/repPagCon.js"></script>
        <link href="../../css/general.css?3" rel="stylesheet" type="text/css" />
        <style>
            input[type=date] {
                line-height: inherit;
            }
            td, th {
                padding: 10px 0;
            }
        </style>
    </head>
    <body>
    <div id="content" style="padding:0px; width:100%; margin-left:0px">
        <header class="cabeceraTit fondRec">
            Reporte de Valores Depositados por Concepto
        </header>

        <section class="contenido">
            <article>
                <form onsubmit="return false" id="ingResInsMedForm">

                    <div class="subCabecera fondRec"> Datos del Reporte</div>
                    <span class="datoForm col1">
                        <span class="inpDatp numCont2"><select id="proyecto" >
                        </select> </span>
                        <span class="inpDatp numCont2"> <select id="concepto" >
                            <option selected="false">Concepto</option>
                            <option value="11">Mant. Medidor</option>
                            <option value="20">Corte y Reconx. Serv</option>
                        </select> </span>
                        <span class="inpDatp numCont2"><input  id="periodo" placeholder="Periodo" type="text"></span>
                    </span>


                    <span class="datoForm col1">
                        <input type="submit" tabindex="5" value="Generar" class="botonFormulario botFormRec"  id="genRepDepCon">
                    </span>

                </form>
            </article>
        </section>
    </div>
    <footer>
    </footer>

    <script type="text/javascript">
        repPagConInicio();
    </script>

    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

