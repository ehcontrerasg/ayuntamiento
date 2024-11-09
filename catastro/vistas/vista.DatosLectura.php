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
<form name="FormDatosLectura" action="vista.DatosLectura.php" method="get">
	<table id="flex2" style="display:none">
    </table>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex2").flexigrid	(
        {

            url: './../datos/datos.datosLectura.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
				{display: 'N&deg;', name: 'rnum', width:10,  align: 'center'},
                {display: 'Periodo', name: 'PERIODO', width:60,  align: 'center'},
                {display: 'Lectura', name: 'LECTURA_ACTUAL', width: 62, sortable: true, align: 'center'},
				{display: 'Consumo', name: 'CONSUMO', width: 62, sortable: true, align: 'center'},
				{display: 'Fecha', name: 'FECLEC', width: 80, sortable: true, align: 'center'},
				{display: 'Observaci\u00F3n', name: 'OBSERVACION', width: 70, sortable: true, align: 'center'},
				{display: 'Lector', name: 'COD_LECTOR', width: 100, sortable: true, align: 'center'}
            ],
			
            sortname: "PERIODO",
            sortorder: "DESC",
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
