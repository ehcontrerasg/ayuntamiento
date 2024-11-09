<?php
session_start();
include '../../destruye_sesion.php';
//pasamos variables por post
$coduser = $_SESSION['codigo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Reportes Facturaci&oacute;n</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
 	<script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
	<link href="../../css/Static_Tabstrip.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="../../acordion/style_fac.css" rel="stylesheet" />
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script src="../../acordion/js/jquery.min.js"></script>
	<script src="../../acordion/js/main.js"></script>
</head>
<body style="margin-top:-25px">
<div id="content">
	<form name="repofac" action="vista.reportes_gerencia.php" method="post">
		<h3 class="panel-heading" style=" background-color:#008B8E; color:#FFFFFF; font-size:18px;" align="center">Reportes Gerenciales</h3>
		<div style="text-align:center">
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						<button type="submit" name="relpagos" id="relpagos" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-list fa-lg"></i></p>Relaci&oacute;n de Pagos
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="historicofac" id="historicofac" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-history fa-lg"></i></p>Hist&oacute;rico de Facturaci&oacute;n
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="historicorec" id="historicorec" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-history fa-lg"></i></p>Hist&oacute;rico de Recaudaci&oacute;n
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="totuniusoperusuact" id="totuniusoperusuact" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-user-plus fa-lg"></i></p>Total Unidades Por Uso y<br /> Periodo Usuarios Activos
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="metcubmesant" id="metcubmesant" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-line-chart fa-lg"></i></p>Comparativo M&sup3;<br /> Mes Anterior
						</button>
					</td>
				</tr>
			</table>
			<p></p>
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						<button type="submit" name="metcubanoant" id="metcubanoant" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-bar-chart fa-lg"></i></p>Comparativo M&sup3;<br /> A&ntilde;o Anterior
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="deudaoficiales" id="deudaoficiales" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-dollar fa-lg"></i></p>Deuda Actual Oficiales
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="unigerusoconper" id="unigerusoconper" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-user-plus fa-lg"></i></p>Unidades Por Gerencia, Uso<br /> Concepto y Periodo
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="usualcgerusoconper" id="usualcgerusoconper" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-group fa-lg"></i></p>Usuarios Alcantarillado Por<br />Gerencia, Uso, Concepto<br /> y Periodo
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="infograndesclientes" id="infograndesclientes" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-industry fa-lg"></i></p>Informe Mensual<br /> Grandes Clientes
						</button>
					</td>

				</tr>
			</table>
			<p></p>
			<table width="100%">
				<tr>
					<td align="center" width="20%">
						<button type="submit" name="facrecdetuso" id="facrecdetuso" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-credit-card fa-lg"></i></p>Facturaci&oacute;n y Recaudo<br /> Detallado Por Uso
						</button>
					</td>
					<td align="center" width="20%">
						<button type="submit" name="recporent" id="recporent" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
						<p><i class="fa fa-money fa-lg"></i></p>Recaudo Por Entidad
						</button>
					</td>
					<td align="center" width="20%">
                        <button type="submit" name="pagosxinmueble" id="pagosxinmueble" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-indent fa-lg"></i></p>Comparativo Pagos<br /> X Inmueble
                        </button>
					</td>
                    <td align="center" width="20%">
                        <button type="submit" name="consolidado" id="consolidado" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-archive  fa-lg"></i></p>Reporte Consolidado
                        </button>
                    </td>
                    <td align="center" width="20%">
                        <button type="submit" name="resumen_gerencial" id="resumen_gerencial" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-line-chart fa-lg"></i></p> Resumen gerencial
                        </button>
                    </td>
				</tr>

			</table>
            <p></p>
            <table width="80%">
                <tr>
                    <td align="center" width="20%">
                        <button type="submit" name="cuentasPorCobrar" id="cuentas_por_cobrar" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-dollar fa-lg"></i></p> Cuentas por cobrar
                        </button>
                    </td>
                    <td align="center" width="20%">
                        <button type="submit" name="seguimientoInmuebles" id="seguimiento_inmuebles" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="glyphicon glyphicon-eye-open"></i></p> <p>Seguimiento de inmuebles</p><p> Agosto 2018</p>
                        </button>
                    </td>

                    <td align="center" width="20%">
                        <button type="submit" name="factCaasd" id="fact_caasd" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-archive  fa-lg"></i></p><p>Histórico facturación <p>detallado</p> </p>
                        </button>
                    </td>

                    <td align="center" width="20%">
                        <button type="submit" name="recCaasd" id="rec_caasd" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-archive  fa-lg"></i></p><p>Histórico recaudo <p>detallado</p> </p>
                        </button>
                    </td>


                </tr>
            </table>
		</div>
	</form>

	<div class="modal fade" id="consultar-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 45%; ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-close="1"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Reportes Gerenciales</h4>
      </div>
      <div class="modal-body">
        <iframe  frameborder="0" width="100%" class="modal-consulta-body" name="modal-consulta" style="min-height: 350px"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-close="1">Cerrar</button>
      </div>
    </div>
  </div>
</div>

	<script type="text/javascript">
	$(document).ready(function(){
		$('.btn-INFO').on('click',function(e){
			//e.stopPropagation();

			$('form[name="repofac"]').on('submit', function(e) {
				e.preventDefault();
			});

			var btn = e.target,
				id = $(btn).attr('id'),
				text = $(btn).text(),
				form = $('form[name="repofac"]'),
				url;

			$('#myModalLabel').text($.trim(text));

			switch (id) {
				case 'relpagos':
					url = 'vista.reporte_relpagos.php';
					break;
				case 'historicofac':
					url = 'vista.reporte_hisfac.php';
					break;
				case 'historicorec':
					url = 'vista.reporte_hisrec.php';
					break;
				case 'totuniusoperusuact':
					url = 'vista.total_unidades_uso_periodo.php';
					break;
				case 'metcubmesant':
					url = 'vista.metros_mes_anterior.php';
					break;
				case 'metcubanoant':
					url = 'vista.metros_ano_anterior.php';
					break;
				case 'deudaoficiales':
					url = 'vista.deuda_oficiales.php';
					break;
				case 'unigerusoconper':
					url = 'vista.unidades_ger_uso_con_per.php';
					break;
				case 'usualcgerusoconper':
					url = 'vista.usuariosalc_ger_uso_con_per.php';
					break;
				case 'infograndesclientes':
					url = 'vista.info_grandes_clientes.php';
					break;
				case 'facrecdetuso':
					url = 'vista.fac_rec_detallado_uso.php';
					break;
				case 'recporent':
					url = 'vista.recaudo_entidad.php';
					break;
				case 'activosxuso':
					url = 'vista.activos_x_uso.php';
					break;
				case 'pagosxinmueble':
					url = 'vista.pagos_x_inmueble.php';
					break;
				case 'consolidado':
					url = 'vista.rep_consolidado.php';
					break;
                case 'resumen_gerencial':
                    url = 'vista.informe_resumen_gerencial.php';
                    break;
                case 'cuentas_por_cobrar':
					url = 'vista.cuentasPorCobrar.php';
					break;

                case 'seguimiento_inmuebles':
                    url = 'vista.SeguimientoInmuebles.php';
                    break;

                case 'fact_caasd':

                    url = 'vista.HistoricoFacDeta.php';
                    break;

                case 'rec_caasd':
                    url = 'vista.HistoricoRecDeta.php';
                    break;


			}

			$('.modal-consulta-body').attr('src', url);


		})
	})
	</script>

</div>
</body>
</html>
