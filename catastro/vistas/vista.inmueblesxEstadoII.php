<!doctype html>
<html>
    <head>
        <!--ESTILOS-->
        <link rel="stylesheet" type="text/css" href="../css/inmueblesPorEstado.css?<?echo time();?>">
        <!--Librería JQUERY-->
        <script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>
        <!--alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?<?echo time();?>" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?<?echo time();?>"></script>
        <!--Librería de Bootstrap-->
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
        <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
        <!--Librería Datatable-->
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <script src="../../archivo/js/datatables/extensions/Scroller/js/dataTables.scroller.js"></script>


    </head>
    <body>
        <div id="cabecera">
            <h4>Reporte de inmuebles por estado</h4>
        </div>
        <div id="parametros" class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <form id="frmReporteInmueblePorEstado" method="post" onsubmit="return false">
                        <div class="form-group col-sm-2" id="divAcueducto">
                            <label>Acueducto:</label><br/>
                            <p></p>
                            <select id="selectProyecto" name="proyecto" class="form-control"></select>
                        </div>
                        <div class="form-group col-sm-1" id="divProceso">
                            <label>Procesos</label><br/>
                            <label> Inicial:</label>
                            <input type="text" id="txtProcesoInicial" name="proceso_inicial" class="form-control"/><br/>
                            <label> Final:</label>
                            <input type="text" id="txtProcesoFinal" name="proceso_final" class="form-control"/>
                        </div>
                        <div class="form-group col-sm-1" id="divManzana">
                            <label>Manzana</label><br/>
                            <label>Inicial:</label>
                            <input type="text" id="txtManzanaInicial" name="manzana_inicial" class="form-control"/>
                            <label>Final:</label>
                            <input type="text" id="txtManzanaFinal" name="manzana_final" class="form-control"/>
                        </div>
                        <div class="form-group col-sm-1" id="divEstado">
                            <label>Estado</label><br/>
                            <label>Inicial:</label>
                            <input type="text" id="txtEstadoInicial" name="estado_inicial" class="form-control"/>
                            <label>Final:</label>
                            <input type="text" id="txtEstadoFinal" name="estado_final" class="form-control"/>
                        </div>
                        <div class="form-group col-sm-1" id="divUsoActividad">
                            <label>Uso</label><br/>
                            <select id="selectUso" name="uso" class="form-control"></select>
                            <label>Actividad</label><br/>
                            <select  id="selectActividad" name="actividad" class="form-control">
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group col-sm-1" id="divServicioZona">
                            <label>Servicio</label><br/>
                            <select name="servicio" id="selectServicio" class="form-control"></select>
                            <label>Zona</label><br/>
                            <select id="selectZona" name="idzona" class="form-control"></select>

                        </div>
                        <div class="form-group col-sm-1" id="divTipoCliente">
                            <label>Tipo Cliente</label><br/>
                            <select id="selectCliente" name="tipcliente" class="form-control"></select>
                            <input type ="submit" id="btnGenerarReporte" class="btn btn-success" value="Generar reporte">
                        </div>
                    </form>
                </div>
                <div class="col-sm-12" style="margin-top: 10px; width: 100%">
                    <button id="btnExportarExcel" class="btn btn-primary">Excel</button>
                    <table id="dataTable" width="100%" class="cell-border display">
                        <thead>
                        <tr>
                            <th>SECTOR</th>
                            <th>RUTA</th>
                            <th>ZONA</th>
                            <th>INMUEBLE</th>
                            <th>URBANIZACION</th>
                            <th>DIRECCION</th>
                            <th style="width:300px">ALIAS</th>
                            <th>PROCESO</th>
                            <th>CATASTRO</th>
                            <th>MEDIDOR</th>
                            <th>SERIAL</th>
                            <th>EMPLAZAMIENTO</th>
                            <th>CALIBRE</th>
                            <th>SUMINISTRO</th>
                            <th>USO</th>
                            <th>ACTIVIDAD</th>
                            <th>UNIDADES</th>
                            <!--<th>CONTRATO</th>-->
                            <th>ESTADO</th>
                            <th>TARIFA</th>
                            <th>FACTURAS PENDIENTES</th>
                            <th>DEUDA</th>
                            <th>CUPO BASICO</th>
                            <th>CONSUMO MINIMO</th>
                            <!-- <th>SERVICIO</th>-->
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!--style="overflow-x: scroll"-->
            <div id="tablaExportar" class="col-sm-12"></div>
        </div>
        <!--style="margin-top: 20px;height:400px;"-->

        <!--<div class="row">-->

        <!--</div>-->



    </body>
    <!--Lógica de la página-->
    <script type="text/javascript" src="../js/inmueblesPorEstado.js?<?echo time();?>"></script>
</html>