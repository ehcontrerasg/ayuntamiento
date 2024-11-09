<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <head>
        <meta  charset="UTF-8" />
        <title>Reporte pago por concepto </title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <link href="../css/repPagConc.css" rel="stylesheet" />
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript" src="../js/repRecConc.js"></script>

        </head>
    <body>

    <header>
        <div class="cabecera">
            Reporte Pagos Por Concepto y Fecha
        </div>
    </header>

    <section>
        <article>

            <div class="subCabecera">
                Filtros de Búsqueda
            </div>

            <div class="contenedorBusqVal">
                <div class="titFiltro">Acueducto</div>
                <select id="selRepPagConcAcu"></select>
            </div>

            <div class="contenedorBusqVal">
                <div class="titFiltro">Concepto</div>
                <select id="selRepPagConcConc"></select>
            </div>

            <div class="contenedorBusqRang">
                <div class="titFiltro">Entidad</div>
                <label>desde:</label>
                <input type="number" min="1" max="999" id="inpRepPagConcIniEnt"/>
                <label>hasta:</label>
                <input type="number" min="1" max="999" id="inpRepPagConcFinEnt"/>
            </div>

            <div class="contenedorBusqRang">
                <div class="titFiltro">Punto</div>
                <label>desde:</label>
                <input type="number" min="1" max="999" id="inpRepPagConcIniPun"/>
                <label>hasta:</label>
                <input type="number" min="1" max="999" id="inpRepPagConcFinPun"/>
            </div>

            <div class="contenedorBusqRang">
                <div class="titFiltro">Caja</div>
                <label>desde:</label>
                <input type="number" min="1" max="999" id="inpRepPagConcIniCaj"/>
                <label>hasta:</label>
                <input type="number" min="1" max="999" id="inpRepPagConcFinCaj"/>
            </div>

            <div class="contenedorBusqFech">
                <div class="titFiltro">Fecha</div>
                <label>desde:</label>
                <input class="inputFecha" type="date" id="inpRepPagConcIniFec"/>
                <label>hasta:</label>
                <input class="inputFecha" type="date" id="inpRepPagConcFinFec"/>
            </div>

            <div class="contenedorBut">
                <button class="buttonRep" id="butRepPagConcFinGenRep">Generar Reporte</button>
            </div>

            <div  >
                <object id="divRepPagConcRep" class="conPdf" type="application/pdf" width="100%" height="100%"></object>
            </div>

        </article>
    </section>

    <footer>

    </footer>

    <script type="text/javascript">
        repRecConInicio();
    </script>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

