
<!doctype html>
<html>
    <head>
        <title>Resumen de facturación</title>
        <!--Bootstrap-->
        <link type="text/css" rel="stylesheet" href="../css/bootstrap/css/bootstrap.css"/>
        <!--Estilos-->
        <link type="text/css" rel="stylesheet" href="../css/resumenFacturacion.css?<? echo time();?>"/>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    </head>
    <body>
        <section id="content">
            <div id="formHeader"><h4>RESUMEN GENERAL DE FACTURACIÓN</h4></div>
            <article id="inputs">
                <form action="#" method="POST" enctype="application/x-www-form-urlencoded" onSubmit="return false" id="frmResumenFacturacion">
                    <div>
                        <label for="slcAcueducto">Acueducto</label>
                        <select id="slcAcueducto" name="proyecto" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div id='dvZonas'>
                        <label>Zonas</label>
                        <div>
                            <label for="txtZonaDesde">Desde</label>
                            <input type="text" id="txtZonaDesde" name="zonaDesde" required/>
                            <label for="txtZonaHasta">Hasta</label>
                            <input type="text" id="txtZonaHasta" name="zonaHasta" required/>
                        </div>
                    </div>
                    <div id='dvPeriodos'>
                        <label>Periodos</label>
                        <div>
                            <label for="txtPeriodoDesde">Desde</label>
                            <input type="number" id="txtPeriodoDesde" name="periodoDesde" required/>
                            <label for="txtPeriodoHasta">Hasta</label>
                            <input type="number" id="txtPeriodoHasta" name="periodoHasta" required/>
                        </div>
                    </div>
                    <div>
                        <input type="submit" value="Generar" class="btn btn-success"/>
                        <button class="btn btn-warning" id="btnLimpiar">Limpiar</button>
                    </div>
                </form>
            </article>
            <article id="articleExportacion">
                <div id="dvPdf" title='Exportar a excel'>
                    <img src="../../recursos/logos/excel.png"/>
                </div>
            </article>
            <article id="articleReporte">
                <div id="dvResumenGeneral"></div>
                <div id="dvResumenConceptoUsoTarifa"></div>
            </article>
        </section>
        <!--Jquery-->
        <script type="text/javascript" rel="javascript" src="../../js/jquery-3.3.1.min.js"></script>
        <!--Bootstrap-->
        <script type="text/javascript" rel="javascript" src="../../css/bootstrap/js/bootstrap.js"></script>
        <!--Alertas-->
        <script type="text/javascript" rel="javascript" src="../../js/sweetalert.min.js "></script>
        <!--Sesion-->
        <script type="text/javascript" rel="javascript" src="../../js/session.js"></script>
        <!--Exportar a EXCEL-->
        <script src="../../js/tableToExcel/customTableToExcel.js"></script>
        <!--Resumen de facturación-->
        <script type="text/javascript" rel="javascript" src="../js/resumenFacturacion.js?<?echo time();?>"></script>
    </body>
</html>