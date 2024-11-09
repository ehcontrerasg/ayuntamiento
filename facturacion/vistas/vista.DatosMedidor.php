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
<form name="FormDatosMedidor" action="vista.DatosMedidor.php" method="get">
	<table id="flex1" style="display:none">
    </table>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {

            url: './../datos/datos.datosMedidor.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
				{display: 'N&deg;', name: 'rnum', width:15,  align: 'center'},
                {display: 'Serial', name: 'SERIAL', width:80,  align: 'center'},
                {display: 'Marca', name: 'DESC_MED', width: 80, sortable: true, align: 'center'},
                {display: 'Calibre', name: 'DESC_CALIBRE', width: 50, sortable: true, align: 'center'},
                {display: 'Emplazamiento', name: 'DESC_EMPLAZAMIENTO', width: 90, sortable: true, align: 'center'},
                {display: 'Fecha Instalaci\u00F3n', name: 'FECHA', width: 130, sortable: true, align: 'center'},
				{display: 'Estado Medidor', name: 'DESCRIPCION', width: 140, sortable: true, align: 'center'},
				{display: 'M\u00E9todo Suministro', name: 'DESC_SUMINISTRO', width: 120, sortable: true, align: 'center'},
				{display: 'Lectura Instalaci\u00F3n', name: 'LECTURA_INSTALACION', width: 100, sortable: true, align: 'center'}
            ],
			
            sortname: "FECHA_INSTALACION",
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
