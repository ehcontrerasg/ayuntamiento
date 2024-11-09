ñ<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require '../clases/class.AnulaPagos.php';
    include '../../destruye_sesion.php';

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>

        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <script type="text/javascript" src="../js/registroDepCon.js?1"></script>
        <link href="../../css/general.css?3" rel="stylesheet" type="text/css" />
        <style>
            table {
                width: 100%;
                margin-bottom: 2em;
            }
            table input {
                width: 90%;
            }
            input[type=date] {
                line-height: inherit;
            }
            td, th {
                padding: 10px 0;
            }

            .saveField {
                font-size: 1.5em;
                color: #FF8000;
                cursor: pointer;
            }
            .updateField {
                font-size: 1.5em;
                color: #FF8000;
                cursor: pointer;
            }
            .updateField2 {
                font-size: 1.5em;
                color: #71cf3c;
                cursor: pointer;
            }
            .readonly {
                background-color: #FFF;
                border: none;
            }
        </style>
    </head>
    <body style="margin-top: 25px" ">
    <div id="content" style="padding:0px; width:100%; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:99%; margin: 0 auto;" align="center">Registro de Depositos por Concepto</h3>
        <section class="contenido">
            <article>
                <form onsubmit="return false" id="ingResInsMedForm">
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
		                <input type="submit" tabindex="5" value="Buscar" class="botonFormulario botFormRec"  id="genRepDepCon">
		            </span>

                </form>
            </article>
            <table id="depositTable">
            </table>
        </section>


    </div>
    </body>
    <script type="text/javascript">
        regDepConInicio();
    </script>

    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

