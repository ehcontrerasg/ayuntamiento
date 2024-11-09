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
	<table id="flex4" style="display:none">
    </table>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex4").flexigrid	(
        {

           url: './../datos/datos.facturas.php?inmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:15,  align: 'center'},
                {display: 'Consec Factura', name: 'CONSEC_FACTURA', width: 77, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'Periodo', width: 47, sortable: true, align: 'center'},
                {display: 'Fec Lectura', name: 'FEC_LECT', width: 47, sortable: true, align: 'center'},
                {display: 'Consumo Fact', name: 'LECTURA', width: 47, sortable: true, align: 'center'},
                {display: 'Expedicion', name: 'FEC_EXPEDICION', width: 60, sortable: true, align: 'center'},
                {display: 'NFC', name: 'NCF', width: 127, sortable: true, align: 'center'},
                {display: 'Valor', name: 'TOTAL', width: 39, sortable: true, align: 'center'},
                {display: 'Pagado', name: 'TOTAL_PAGADO', width: 39, sortable: true, align: 'center'},
                {display: 'fac anteriores', name: 'ANTERIORES', width: 39, sortable: true, align: 'center'}

            ],

            sortname: "PERIODO",
            sortorder: "desc",
            usepager: false,
            //title: 'facturas',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            //width: 686,
            height: 180
        }
    );
</script>
</body>
</html>
