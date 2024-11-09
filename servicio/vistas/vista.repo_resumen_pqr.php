<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Informe Mensual Grandes Clientes</title>
    <!--JQUERY -->
    <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/servicio.css?<?php echo time(); ?> " />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repResPqr.js?<?echo time();?>"></script>
</head>

<body>
	<header>
		<div class="cabecera">
			Reporte Resumen PQRs
		</div>
	</header>
	<section>
		<article>
			<div class="subCabecera">
				Filtros de Búsqueda Reporte Resumen PQRs
			</div>
            <form onSubmit="return false" id="genResPqrForm">
                <div class="divfiltros">
                    <div class="contenedor">
                        <p><label> Acueducto </label></p>
                        <select id="selAcueducto" name="proyecto"></select>
                    </div>
                    <div class="contenedor">
                        <p><label> Zona </label></p>
                        Desde:&nbsp;&nbsp;<input type="text" id="inpZonini" maxlength="3" name="zonini">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="text" id="inpZonfin" maxlength="3" name="zonfin">
                    </div>
                    <div class="contenedor">
                        <p><label> Sector </label></p>
                        Desde:&nbsp;&nbsp;<input  type="number" id="inpSecfin" min="12" max="99"   name="secini">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="number" id="inpSecfin" min="12" max="99" name="secfin">
                    </div>
                    <div class="contenedor">
                        <p><label> Ruta </label></p>
                        Desde:&nbsp;&nbsp;<input type="number" id="inpRutini" min="0" max="99" name="rutini"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="number" id="inpRutfin" min="1" max="99" name="rutfin"/>
                    </div>
                    <div class="contenedor">
                        <p><label> Oficina </label></p>
                        Desde:&nbsp;&nbsp;<input type="number" id="inpOfiini" min="100" max="999" name="ofiini"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="number" id="inpOfifin" min="100" max="999" name="ofifin"/>
                    </div>
                    <div class="contenedor">
                        <p><label> N&deg; Reclamo </label></p>
                        Desde:&nbsp;&nbsp;<input type="number" id="inpRecini" class="inputLargo" name="recini" />
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="number" id="inpRecfin" class="inputLargo" name="recfin" />
                    </div>
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <p><label> Motivo PQR </label></p>
                        <select id="selMotivo" name="motivo"></select>
                    </div>


                    <div class="contenedor">
                        <p><label> Fecha Radicaci&oacute;n </label></p>
                        Desde:&nbsp;&nbsp;<input type="date" id="inpFecinirad" class="inputDate" name="fecinirad" />
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="date" id="inpFecfinrad" class="inputDate" name="fecfinrad"/>
                    </div>
                    <div class="contenedor">
                        <p><label> Fecha Resoluci&oacute;n </label></p>
                        Desde:&nbsp;&nbsp;<input type="date" id="inpFecinires" class="inputDate" name="fecinires" />
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Hasta:&nbsp;&nbsp;<input type="date" id="inpFecfinres" class="inputDate" name="fecfinres" />
                    </div>

                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <p><label> Tipo Resoluci&oacute;n </label></p>
                        <div class="radio">
                            <input type="radio" name="radTipoResol" id="radProcedente"><label for="radProcedente">Procedente</label>
                            <input type="radio" name="radTipoResol" id="radNoprocedente" ><label for="radNoprocedente">No Procedente</label>
                            <input type="radio" name="radTipoResol" id="radTodosres" ><label for="radTodosres">Todos</label>
                        </div>
                    </div>
                    <div class="contenedor">
                        <p><label> Estado </label></p>
                        <div class="radio">
                            <input type="radio" name="tipo_estado" id="radPendientes" value="1"><label for="radPendientes">Pendientes</label>
                            <input type="radio" name="tipo_estado" id="radRealizadas" value="2"><label for="radRealizadas">Realizadas</label>
                            <input type="radio" name="tipo_estado" id="radTodosest" value="3"><label for="radTodosest">Todos</label>
                        </div>
                    </div>
                    <div class="contenedor" style="padding:0px 109px 9px 109px">
                        <button id="butGenResPqr" title="Mostrar En Pantalla" class="butPantalla"><i class="fa fa-television fa-2x"></i></button>
                        <button id="butExlResPqr" title="Descargar en Excel" class="butExcel"><i class="fa fa-file-excel-o fa-2x"></i></button>
                        <!--button id="butPdfResPqr" title="Descargar en PDF" class="butPdf"><i class="fa fa-file-pdf-o fa-2x"></i></button-->
                    </div>
                </div>
                <div class="divfiltros">
                    <div class="contenedor">
                        <table id="flexirespqr" style="display:none">
                        </table>
                    </div>
                </div>

            </form>

		</article>
	</section>
	<footer>
	</footer>

</body>
</html>
<script type="text/javascript">
    repResPqrInicio();
</script>