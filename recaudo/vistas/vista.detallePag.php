<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!-- Created by PhpStorm.-->
    <!-- User: Ehcontrerasg-->
    <!-- Date: 7/14/2016-->
    <!-- Time: 9:45 AM-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta  charset="UTF-8" />
        <title>Listado Detallado de Pagos</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <!--estilo pag    -->
        <link rel="stylesheet" type="text/css" href="../css/recaudo.css" />
        <!--logica pag    -->
        <script type="text/javascript" src="../js/detallePag.js?<?echo time();?>"></script>
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
        </html>
    </head>
    <body class="formulario">
    <header>
        <div class="cabeceraTit">
            Lista de pagos detallado
        </div>
    </header>

    <section>
        <article>
            <div class="contenedor-tabla" style="height: 600px">
                <form action="../../funciones/ficheroExcel.php?nomrepo=detalle_pagos_por_fecha" method="post" target="_blank"  id="FormularioExportacion">
                    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                    <div class="flexigrid" style="width:100px">

                        <div style="background-color:rgb(255,255,255)">
                            Exportar a:<img src="../../images/excel/Excel.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                        </div>

                    </div>

                    <div class="tituloTabla">
                        Reporte Pagos por caja
                    </div>
                </form>
                <table class="cuerpo-tabla" id="Exportar_a_Excel">
                    <thead>
                    <tr>
                        <th colspan="9" style="border:none; border-right:1px solid #DEDEDE; text-align:center; display: none">
                            Lista de Pagos Detallado -.- Impreso el :<?php echo date('d/m/Y') ?>
                        </th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Inmueble</th>
                        <th>Id pago</th>
                        <th>Cliente</th>
                        <th>Fecha Pago</th>
                        <th>Importe</th>
                        <th>Facturado</th>
                        <th>Usuario</th>
                        <th>Forma Pago</th>
                    </tr>
                    </thead>
                    <tbody id="tBodyDetallePag">
                    </tbody>
                </table>
            </div>

        </article>
    </section>

    <footer>

    </footer>
    <script type="text/javascript">
        detallePagInicio();
    </script>

    </body>
    </html>



<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

