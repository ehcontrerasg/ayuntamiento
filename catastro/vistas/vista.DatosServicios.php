<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title></title>	
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
</head>
<body>
<form name="FormDatosServicios" action="vista.DatosServicios.php" method="get">
	<table id="flex3" style="display:none">
    </table>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex3").flexigrid	(
        {

            url: './../datos/datos.datosServicios.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
				{display: 'C\u00F3digo;', name: 'COD_SERVICIO', width:30, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'DESC_SERVICIO', width:100,  align: 'center'},
                {display: 'Tarifa', name: 'CONSEC_TARIFA', width: 180, sortable: true, align: 'center'},
				{display: 'Unidades', name: 'UNIDADES_TOT', width: 62, sortable: true, align: 'center'},
				{display: 'Habitadas', name: 'UNIDADES_HAB', width: 80, sortable: true, align: 'center'},
				{display: 'Deshabitadas', name: 'UNIDADES_DESH', width: 70, sortable: true, align: 'center'},
				{display: 'Cupo B\u00E1sico', name: 'CUPO_BASICO', width: 100, sortable: true, align: 'center'},
				{display: 'Promedio', name: 'PROMEDIO', width: 100, sortable: true, align: 'center'},
				{display: 'Consumo M\u00EDnimo', name: 'CONSUMO_MINIMO', width: 100, sortable: true, align: 'center'},
				{display: 'Calculo', name: 'DESC_CALCULO', width: 100, sortable: true, align: 'center'}
            ],
			
            sortname: "COD_SERVICIO",
            sortorder: "ASC",
            usepager: false,
            //title: 'Datos Medidor',
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            //width: 1000,
            height: 180,
			pagestat: 'Mostrando del {from} al {to} de {total} registros',
			procmsg: 'Procesando, un momento por favor ...',
			nomsg: 'No existen registros para su consulta'
        }
    );
</script>
</body>
</html>
